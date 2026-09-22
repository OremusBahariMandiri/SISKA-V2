<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Data\DataKaryawan;
use App\Models\Data\DataKontrak;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\KontrakKerja;
use App\Models\DataMaster\Perusahaan;
use App\Models\DataMaster\WilayahKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingKontrakController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ================================================================
    // Urutan hierarki kontrak berdasarkan kode numerik ascending
    // Semakin kecil kode = semakin rendah jenjang (SPKK=103, PKWT=102, PKWTT=101)
    // ================================================================
    private function getKontrakOrder(): array
    {
        return KontrakKerja::orderByRaw('CAST(kode_ktr AS UNSIGNED) ASC')
            ->pluck('kode_ktr', 'id')
            ->toArray();
    }

    private function getArahPerubahan(?string $kodeAwal, ?string $kodeBaru, array $urutan): string
    {
        if (!$kodeAwal || !$kodeBaru) return 'baru';

        $urutanValues = array_values($urutan);
        $posAwal = array_search($kodeAwal, $urutanValues);
        $posBaru = array_search($kodeBaru, $urutanValues);

        if ($posAwal === false || $posBaru === false) return 'perubahan';
        if ($posBaru < $posAwal)  return 'upgrade';
        if ($posBaru > $posAwal)  return 'downgrade';
        return 'perpanjangan';
    }

    // ================================================================
    // Base query karyawan
    // ================================================================
    private function baseQuery(Request $request)
    {
        $q = DataKaryawan::query()
            ->whereHas('allContracts');

        if ($request->filter_perusahaan) $q->where('perusahaan', $request->filter_perusahaan);
        if ($request->filter_wilker)     $q->where('wilker', $request->filter_wilker);
        if ($request->filter_area)       $q->where('unit_krj', $request->filter_area);
        if ($request->filter_status)     $q->where('sts_kry', $request->filter_status);

        if ($request->filter_departemen) {
            $depIds = Departemen::where('nama_dep', $request->filter_departemen)->pluck('id');
            $q->whereIn('departemen', $depIds);
        }

        if ($request->filter_kontrak_aktif) {
            $q->where('sts_ktr', $request->filter_kontrak_aktif);
        }

        // Filter: karyawan yang punya transisi dari kontrak A ke kontrak B
        // filter_dari_kontrak = id kontrak awal
        // filter_ke_kontrak   = id kontrak tujuan
        if ($request->filter_dari_kontrak || $request->filter_ke_kontrak) {
            $dariId = $request->filter_dari_kontrak;
            $keId   = $request->filter_ke_kontrak;

            $q->whereHas('allContracts', function ($sq) use ($dariId) {
                if ($dariId) $sq->where('id_ktr', $dariId);
            });

            if ($keId) {
                $q->whereHas('allContracts', function ($sq) use ($keId) {
                    $sq->where('id_ktr', $keId);
                });
            }
        }

        if ($request->filter_tgl_dari || $request->filter_tgl_sampai) {
            $q->whereHas('allContracts', function ($sq) use ($request) {
                if ($request->filter_tgl_dari) {
                    $sq->whereDate('tgl_awl_ktr', '>=', $request->filter_tgl_dari);
                }
                if ($request->filter_tgl_sampai) {
                    $sq->whereDate('tgl_awl_ktr', '<=', $request->filter_tgl_sampai);
                }
            });
        }

        // Exclude TOP MANAJEMENT
        if ($request->input('filter_include_top_mgmt', '0') !== '1') {
            $topIds = Departemen::where('nama_dep', 'TOP MANAJEMENT')->pluck('id');
            if ($topIds->isNotEmpty()) {
                $q->whereNotIn('departemen', $topIds);
            }
        }

        return $q;
    }

    // ================================================================
    // INDEX
    // ================================================================
    public function index(Request $request)
    {
        $kontrakOrder    = $this->getKontrakOrder();
        $kontrakMaster   = KontrakKerja::orderByRaw('CAST(kode_ktr AS UNSIGNED) ASC')->get();
        $kontrakKodeById = KontrakKerja::pluck('kode_ktr', 'id')->toArray();
        $kontrakNamaById = KontrakKerja::pluck('singkatan_ktr', 'id')->toArray();

        // ── Statistik global transisi ──
        $statUpgrade    = 0;
        $statDowngrade  = 0;
        $statPerpanjang = 0;
        $statBaru       = 0;
        $transitionCount = [];

        $allTransitions = DataKontrak::select('id_data_kry', 'id_ktr', 'tgl_awl_ktr')
            ->whereNotNull('id_ktr')
            ->orderBy('id_data_kry')
            ->orderBy('tgl_awl_ktr')
            ->get()
            ->groupBy('id_data_kry');

        foreach ($allTransitions as $kontrakList) {
            $sorted = $kontrakList->sortBy('tgl_awl_ktr')->values();
            for ($i = 1; $i < $sorted->count(); $i++) {
                $prev     = $sorted[$i - 1];
                $curr     = $sorted[$i];
                $kodeAwal = $kontrakKodeById[$prev->id_ktr] ?? null;
                $kodeBaru = $kontrakKodeById[$curr->id_ktr] ?? null;
                $namaAwal = $kontrakNamaById[$prev->id_ktr] ?? $kodeAwal;
                $namaBaru = $kontrakNamaById[$curr->id_ktr] ?? $kodeBaru;
                $arah     = $this->getArahPerubahan($kodeAwal, $kodeBaru, $kontrakOrder);

                if ($arah === 'upgrade')       $statUpgrade++;
                elseif ($arah === 'downgrade') $statDowngrade++;
                elseif ($arah === 'perpanjangan') $statPerpanjang++;

                if ($namaAwal && $namaBaru && $namaAwal !== $namaBaru) {
                    $key = $namaAwal . ' → ' . $namaBaru;
                    $transitionCount[$key] = ($transitionCount[$key] ?? 0) + 1;
                }
            }
            if ($sorted->count() === 1) $statBaru++;
        }

        arsort($transitionCount);
        $topTransisi = collect($transitionCount)
            ->map(fn($v, $k) => ['label' => $k, 'jumlah' => $v])
            ->values()->take(10);

        // ── Distribusi kontrak aktif ──
        $perKontrakAktif = DataKaryawan::select('sts_ktr', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('sts_ktr')
            ->groupBy('sts_ktr')
            ->get()
            ->map(function ($r) {
                $ktr = KontrakKerja::find($r->sts_ktr);
                return [
                    'label'  => $ktr?->singkatan_ktr ?? $ktr?->kode_ktr ?? '-',
                    'nama'   => $ktr?->nama_ktr ?? '-',
                    'jumlah' => (int) $r->jumlah,
                ];
            })
            ->sortByDesc('jumlah')->values();

        // ── Paginate + transform via ->through() ──
        $perPage = $request->input('per_page', 20);
        $search  = $request->input('search', '');

        $karyawanQuery = $this->baseQuery($request)
            ->with(['allContracts.kontrakKerja', 'allContracts.perusahaan',
                    'perusahaanRelation', 'departemenRelation',
                    'unitKerjaRelation', 'kontrakRelation']);

        if ($search) {
            $karyawanQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nrk',  'like', '%' . $search . '%');
            });
        }

        $filterDariKontrak = $request->filter_dari_kontrak;
        $filterKeKontrak   = $request->filter_ke_kontrak;

        $karyawans = $karyawanQuery->orderBy('nama')
            ->paginate($perPage)
            ->withQueryString()
            ->through(function ($k) use (
                $kontrakOrder, $kontrakKodeById, $kontrakNamaById,
                $filterDariKontrak, $filterKeKontrak
            ) {
                $dep = Departemen::find($k->departemen);
                $uk  = WilayahKerja::find($k->unit_krj);

                $kontrakList = $k->allContracts->sortBy('tgl_awl_ktr')->values();

                $timeline = $kontrakList->map(function ($ktr, $i) use (
                    $kontrakList, $kontrakOrder, $kontrakKodeById, $kontrakNamaById
                ) {
                    $prev     = $i > 0 ? $kontrakList[$i - 1] : null;
                    $kodeAwal = $prev ? ($kontrakKodeById[$prev->id_ktr] ?? null) : null;
                    $kodeBaru = $kontrakKodeById[$ktr->id_ktr] ?? null;
                    $arah     = $prev
                        ? $this->getArahPerubahan($kodeAwal, $kodeBaru, $kontrakOrder)
                        : 'baru';

                    return [
                        'id'        => $ktr->id,
                        'no_surat'  => $ktr->no_srt_ktr ?? '-',
                        'tgl_surat' => $ktr->tgl_srt_ktr
                            ? Carbon::parse($ktr->tgl_srt_ktr)->format('d/m/Y') : '-',
                        'id_ktr'    => $ktr->id_ktr,
                        'nama_ktr'  => $ktr->kontrakKerja?->nama_ktr ?? '-',
                        'singkatan' => $ktr->kontrakKerja?->singkatan_ktr ?? '-',
                        'kode_ktr'  => $ktr->kontrakKerja?->kode_ktr ?? '-',
                        'tgl_mulai' => $ktr->tgl_awl_ktr
                            ? Carbon::parse($ktr->tgl_awl_ktr)->format('d/m/Y') : '-',
                        'tgl_akhir' => $ktr->tgl_akhir_ktr
                            ? Carbon::parse($ktr->tgl_akhir_ktr)->format('d/m/Y') : '-',
                        'durasi'    => $ktr->durasi_ktr ? $ktr->durasi_ktr . ' bln' : '-',
                        'status'    => $ktr->sts_srt_ktr ?? '-',
                        'arah'      => $arah,
                        'perusahaan'=> $ktr->perusahaan?->nama_prs2
                                    ?? $ktr->perusahaan?->nama_prs1 ?? '-',
                    ];
                });

                // Jika ada filter dari→ke, tandai node yang relevan
                if ($filterDariKontrak || $filterKeKontrak) {
                    $timeline = $timeline->map(function ($t, $i) use (
                        $timeline, $filterDariKontrak, $filterKeKontrak
                    ) {
                        $prev = $i > 0 ? $timeline[$i - 1] : null;
                        $t['highlighted'] = false;

                        if ($prev && $filterDariKontrak && $filterKeKontrak) {
                            $t['highlighted'] = (
                                $prev['id_ktr'] == $filterDariKontrak &&
                                $t['id_ktr']    == $filterKeKontrak
                            );
                        } elseif ($filterDariKontrak && !$filterKeKontrak) {
                            $t['highlighted'] = $t['id_ktr'] == $filterDariKontrak;
                        } elseif ($filterKeKontrak && !$filterDariKontrak) {
                            $t['highlighted'] = $t['id_ktr'] == $filterKeKontrak;
                        }
                        return $t;
                    });
                }

                $jumlahUpgrade    = $timeline->where('arah', 'upgrade')->count();
                $jumlahDowngrade  = $timeline->where('arah', 'downgrade')->count();
                $jumlahPerpanjang = $timeline->where('arah', 'perpanjangan')->count();

                return [
                    'id'                => $k->id,
                    'nrk'               => $k->nrk ?? '-',
                    'nama'              => $k->nama,
                    'perusahaan'        => $k->perusahaanRelation?->nama_prs2
                                        ?? $k->perusahaanRelation?->nama_prs1 ?? '-',
                    'departemen'        => $dep?->nama_dep ?? '-',
                    'jabatan'           => $dep?->nama_jbt ?? '-',
                    'unit_kerja'        => $uk?->area_krj ?? '-',
                    'kontrak_aktif'     => $k->kontrakRelation?->singkatan_ktr ?? '-',
                    'sts_kry'           => $k->sts_kry ?? '-',
                    'tgl_masuk'         => $k->tgl_masuk?->format('d/m/Y') ?? '-',
                    'total_kontrak'     => $kontrakList->count(),
                    'jumlah_upgrade'    => $jumlahUpgrade,
                    'jumlah_downgrade'  => $jumlahDowngrade,
                    'jumlah_perpanjang' => $jumlahPerpanjang,
                    'timeline'          => $timeline,
                ];
            });

        // ── Master filter ──
        $perusahaans         = Perusahaan::orderBy('nama_prs1')->get();
        $kontrakOptions      = KontrakKerja::orderByRaw('CAST(kode_ktr AS UNSIGNED) ASC')->get();
        $wilayahKerjaOptions = WilayahKerja::select('wilayah_krj')
            ->groupBy('wilayah_krj')->orderBy('wilayah_krj')->get();
        $areaKerjaOptions    = WilayahKerja::orderBy('area_krj')
            ->when($request->filter_wilker, fn($q) => $q->where('wilayah_krj', $request->filter_wilker))
            ->get();
        $departemenOptions   = Departemen::select('nama_dep', 'singkatan_dep',
                DB::raw('MIN(CAST(kode_dep AS UNSIGNED)) as min_kode'))
            ->groupBy('nama_dep', 'singkatan_dep')
            ->orderBy('min_kode')->get();

        $currentFilters = [
            'perusahaan'        => $request->filter_perusahaan     ?? '',
            'wilker'            => $request->filter_wilker         ?? '',
            'area'              => $request->filter_area           ?? '',
            'status'            => $request->filter_status         ?? '',
            'departemen'        => $request->filter_departemen     ?? '',
            'kontrak_aktif'     => $request->filter_kontrak_aktif  ?? '',
            'dari_kontrak'      => $request->filter_dari_kontrak   ?? '',
            'ke_kontrak'        => $request->filter_ke_kontrak     ?? '',
            'tgl_dari'          => $request->filter_tgl_dari       ?? '',
            'tgl_sampai'        => $request->filter_tgl_sampai     ?? '',
            'include_top_mgmt'  => $request->input('filter_include_top_mgmt', '0'),
            'per_page'          => $perPage,
            'search'            => $search,
        ];

        return view('reports.tracking-kontrak.index', compact(
            'karyawans',
            'statUpgrade',
            'statDowngrade',
            'statPerpanjang',
            'statBaru',
            'topTransisi',
            'perKontrakAktif',
            'kontrakMaster',
            'kontrakOptions',
            'perusahaans',
            'wilayahKerjaOptions',
            'areaKerjaOptions',
            'departemenOptions',
            'currentFilters'
        ));
    }

    // ================================================================
    // AJAX: Area kerja dropdown
    // ================================================================
    public function areaByWilker(Request $request)
    {
        return response()->json(
            WilayahKerja::when($request->wilker, fn($q) => $q->where('wilayah_krj', $request->wilker))
                ->orderBy('area_krj')
                ->get(['id', 'area_krj', 'singkatan_wk', 'wilayah_krj'])
        );
    }

    // ================================================================
    // AJAX: Detail timeline satu karyawan
    // ================================================================
    public function detail(Request $request, $id)
    {
        $k = DataKaryawan::with(['allContracts.kontrakKerja', 'allContracts.perusahaan',
                                 'perusahaanRelation'])
            ->findOrFail($id);

        $kontrakOrder    = $this->getKontrakOrder();
        $kontrakKodeById = KontrakKerja::pluck('kode_ktr', 'id')->toArray();
        $kontrakNamaById = KontrakKerja::pluck('singkatan_ktr', 'id')->toArray();

        $kontrakList = $k->allContracts->sortBy('tgl_awl_ktr')->values();

        $timeline = $kontrakList->map(function ($ktr, $i) use (
            $kontrakList, $kontrakOrder, $kontrakKodeById, $kontrakNamaById
        ) {
            $prev     = $i > 0 ? $kontrakList[$i - 1] : null;
            $kodeAwal = $prev ? ($kontrakKodeById[$prev->id_ktr] ?? null) : null;
            $kodeBaru = $kontrakKodeById[$ktr->id_ktr] ?? null;
            $arah     = $prev
                ? $this->getArahPerubahan($kodeAwal, $kodeBaru, $kontrakOrder)
                : 'baru';

            $sisaBulan = null;
            if ($ktr->tgl_akhir_ktr && Carbon::parse($ktr->tgl_akhir_ktr)->isFuture()) {
                $sisaBulan = (int) Carbon::now()->diffInMonths(Carbon::parse($ktr->tgl_akhir_ktr));
            }

            return [
                'id'           => $ktr->id,
                'no_surat'     => $ktr->no_srt_ktr ?? '-',
                'tgl_surat'    => $ktr->tgl_srt_ktr
                    ? Carbon::parse($ktr->tgl_srt_ktr)->format('d/m/Y') : '-',
                'id_ktr'       => $ktr->id_ktr,
                'nama_ktr'     => $ktr->kontrakKerja?->nama_ktr ?? '-',
                'singkatan'    => $ktr->kontrakKerja?->singkatan_ktr ?? '-',
                'kode_ktr'     => $ktr->kontrakKerja?->kode_ktr ?? '-',
                'tgl_mulai'    => $ktr->tgl_awl_ktr
                    ? Carbon::parse($ktr->tgl_awl_ktr)->format('d/m/Y') : '-',
                'tgl_akhir'    => $ktr->tgl_akhir_ktr
                    ? Carbon::parse($ktr->tgl_akhir_ktr)->format('d/m/Y') : '-',
                'durasi'       => $ktr->durasi_ktr ? $ktr->durasi_ktr . ' bulan' : '-',
                'status'       => $ktr->sts_srt_ktr ?? '-',
                'arah'         => $arah,
                'sisa_bulan'   => $sisaBulan,
                'perusahaan'   => $ktr->perusahaan?->nama_prs2
                               ?? $ktr->perusahaan?->nama_prs1 ?? '-',
            ];
        });

        $dep = Departemen::find($k->departemen);
        $uk  = WilayahKerja::find($k->unit_krj);

        return response()->json([
            'karyawan' => [
                'nrk'        => $k->nrk ?? '-',
                'nama'       => $k->nama,
                'perusahaan' => $k->perusahaanRelation?->nama_prs2
                             ?? $k->perusahaanRelation?->nama_prs1 ?? '-',
                'departemen' => $dep?->nama_dep ?? '-',
                'jabatan'    => $dep?->nama_jbt ?? '-',
                'unit_kerja' => $uk?->area_krj ?? '-',
                'tgl_masuk'  => $k->tgl_masuk?->format('d/m/Y') ?? '-',
                'sts_kry'    => $k->sts_kry ?? '-',
            ],
            'timeline' => $timeline,
        ]);
    }
}