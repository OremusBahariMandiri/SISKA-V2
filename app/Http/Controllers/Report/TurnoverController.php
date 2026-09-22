<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Data\DataKaryawan;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\KontrakKerja;
use App\Models\DataMaster\Perusahaan;
use App\Models\DataMaster\WilayahKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TurnoverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ================================================================
    // BASE QUERY — karyawan yang RESIGN/PHK dalam periode
    // ================================================================
    private function baseResignQuery(Request $request)
    {
        // Default periode: tahun berjalan
        $dari    = $request->filter_tgl_dari
            ? Carbon::parse($request->filter_tgl_dari)->startOfDay()
            : Carbon::now()->startOfYear();
        $sampai  = $request->filter_tgl_sampai
            ? Carbon::parse($request->filter_tgl_sampai)->endOfDay()
            : Carbon::now()->endOfDay();

        $q = DataKaryawan::query()
            ->whereNotNull('tgl_phk')
            ->whereBetween('tgl_phk', [$dari, $sampai]);

        // Filter tambahan
        if ($request->filter_perusahaan) $q->where('perusahaan', $request->filter_perusahaan);
        if ($request->filter_kontrak)    $q->where('sts_ktr', $request->filter_kontrak);
        if ($request->filter_wilker)     $q->where('wilker', $request->filter_wilker);
        if ($request->filter_area)       $q->where('unit_krj', $request->filter_area);

        if ($request->filter_departemen) {
            $depIds = Departemen::where('nama_dep', $request->filter_departemen)->pluck('id');
            $q->whereIn('departemen', $depIds);
        }

        // Exclude TOP MANAJEMENT jika tidak dicentang (default exclude)
        if ($request->input('filter_include_top_mgmt', '0') !== '1') {
            $topIds = Departemen::where('nama_dep', 'TOP MANAJEMENT')->pluck('id');
            if ($topIds->isNotEmpty()) {
                $q->whereNotIn('departemen', $topIds);
            }
        }

        return $q;
    }

    // ================================================================
    // Jumlah karyawan aktif pada suatu tanggal (untuk rata-rata)
    // ================================================================
    private function aktifPadaTanggal(Request $request, Carbon $tanggal): int
    {
        $q = DataKaryawan::query()
            ->where('tgl_masuk', '<=', $tanggal)
            ->where(function ($q) use ($tanggal) {
                $q->whereNull('tgl_phk')
                  ->orWhere('tgl_phk', '>', $tanggal);
            });

        if ($request->filter_perusahaan) $q->where('perusahaan', $request->filter_perusahaan);
        if ($request->filter_kontrak)    $q->where('sts_ktr', $request->filter_kontrak);
        if ($request->filter_wilker)     $q->where('wilker', $request->filter_wilker);
        if ($request->filter_area)       $q->where('unit_krj', $request->filter_area);

        if ($request->filter_departemen) {
            $depIds = Departemen::where('nama_dep', $request->filter_departemen)->pluck('id');
            $q->whereIn('departemen', $depIds);
        }

        if ($request->input('filter_include_top_mgmt', '0') !== '1') {
            $topIds = Departemen::where('nama_dep', 'TOP MANAJEMENT')->pluck('id');
            if ($topIds->isNotEmpty()) {
                $q->whereNotIn('departemen', $topIds);
            }
        }

        return $q->count();
    }

    // ================================================================
    // INDEX
    // ================================================================
    public function index(Request $request)
    {
        // ── Periode ──
        $dari   = $request->filter_tgl_dari
            ? Carbon::parse($request->filter_tgl_dari)
            : Carbon::now()->startOfYear();
        $sampai = $request->filter_tgl_sampai
            ? Carbon::parse($request->filter_tgl_sampai)
            : Carbon::now();

        // ── Jumlah resign dalam periode ──
        $totalResign = $this->baseResignQuery($request)->count();

        // ── Rata-rata karyawan aktif ──
        $aktifAwal   = $this->aktifPadaTanggal($request, $dari->copy()->startOfDay());
        $aktifAkhir  = $this->aktifPadaTanggal($request, $sampai->copy()->endOfDay());
        $rataAktif   = ($aktifAwal + $aktifAkhir) / 2;

        // ── Turnover Rate ──
        $turnoverRate = $rataAktif > 0
            ? round(($totalResign / $rataAktif) * 100, 2)
            : 0;

        // ── Karyawan masuk periode yang sama (untuk net turnover) ──
        $totalMasuk = DataKaryawan::query()
            ->whereBetween('tgl_masuk', [
                $dari->copy()->startOfDay(),
                $sampai->copy()->endOfDay(),
            ])
            ->when($request->filter_perusahaan, fn($q) => $q->where('perusahaan', $request->filter_perusahaan))
            ->when($request->filter_wilker,     fn($q) => $q->where('wilker', $request->filter_wilker))
            ->when($request->filter_area,       fn($q) => $q->where('unit_krj', $request->filter_area))
            ->when($request->filter_kontrak,    fn($q) => $q->where('sts_ktr', $request->filter_kontrak))
            ->when($request->filter_departemen, function ($q) use ($request) {
                $depIds = Departemen::where('nama_dep', $request->filter_departemen)->pluck('id');
                return $q->whereIn('departemen', $depIds);
            })
            ->when($request->input('filter_include_top_mgmt', '0') !== '1', function ($q) {
                $topIds = Departemen::where('nama_dep', 'TOP MANAJEMENT')->pluck('id');
                if ($topIds->isNotEmpty()) $q->whereNotIn('departemen', $topIds);
            })
            ->count();

        // ── Resign per bulan (untuk grafik tren) ──
        $resignPerBulan = $this->baseResignQuery($request)
            ->select(
                DB::raw('YEAR(tgl_phk) as tahun'),
                DB::raw('MONTH(tgl_phk) as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')->orderBy('bulan')
            ->get()
            ->map(fn($r) => [
                'label'  => Carbon::createFromDate($r->tahun, $r->bulan, 1)->translatedFormat('M Y'),
                'tahun'  => $r->tahun,
                'bulan'  => $r->bulan,
                'jumlah' => (int) $r->jumlah,
            ]);

        // ── Resign per Departemen ──
        $resignPerDep = $this->baseResignQuery($request)
            ->select('departemen', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('departemen')
            ->groupBy('departemen')
            ->get()
            ->map(function ($r) {
                $dep = Departemen::find($r->departemen);
                return [
                    'id'        => (int) $r->departemen,
                    'label'     => $dep?->nama_dep ?? 'Tidak Diketahui',
                    'singkatan' => $dep?->singkatan_dep ?? '-',
                    'jumlah'    => (int) $r->jumlah,
                ];
            })
            ->groupBy('label')
            ->map(fn($g) => [
                'id'        => $g->first()['id'],
                'label'     => $g->first()['label'],
                'singkatan' => $g->first()['singkatan'],
                'jumlah'    => $g->sum('jumlah'),
            ])
            ->sortByDesc('jumlah')
            ->values();

        // ── Resign per PT ──
        $resignPerPT = $this->baseResignQuery($request)
            ->select('perusahaan', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('perusahaan')
            ->groupBy('perusahaan')
            ->get()
            ->map(function ($r) {
                $p = Perusahaan::find($r->perusahaan);
                return [
                    'id'        => (int) $r->perusahaan,
                    'label'     => $p?->nama_prs1 ?? 'Tidak Diketahui',
                    'singkatan' => $p?->nama_prs2 ?? '-',
                    'jumlah'    => (int) $r->jumlah,
                ];
            })
            ->sortByDesc('jumlah')
            ->values();

        // ── Resign per Wilayah ──
        $resignPerWilayah = $this->baseResignQuery($request)
            ->select('wilker', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('wilker')
            ->groupBy('wilker')
            ->get()
            ->map(fn($r) => [
                'label'  => $r->wilker,
                'jumlah' => (int) $r->jumlah,
            ])
            ->sortByDesc('jumlah')
            ->values();

        // ── Resign per Kontrak ──
        $resignPerKontrak = $this->baseResignQuery($request)
            ->select('sts_ktr', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('sts_ktr')
            ->groupBy('sts_ktr')
            ->get()
            ->map(function ($r) {
                $ktr = KontrakKerja::find($r->sts_ktr);
                return [
                    'id'     => (int) $r->sts_ktr,
                    'label'  => $ktr?->nama_ktr ?? 'Tidak Diketahui',
                    'kode'   => $ktr?->singkatan_ktr ?? ($ktr?->kode_ktr ?? '-'),
                    'jumlah' => (int) $r->jumlah,
                ];
            })
            ->sortByDesc('jumlah')
            ->values();

        // ── Resign per Jenis Kelamin ──
        $resignPerGender = $this->baseResignQuery($request)
            ->select('sex', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('sex')
            ->groupBy('sex')
            ->get()
            ->map(fn($r) => ['label' => $r->sex, 'jumlah' => (int) $r->jumlah])
            ->values();

        // ── Alasan PHK (ket_phk) ──
        $resignPerAlasan = $this->baseResignQuery($request)
            ->select('ket_phk', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('ket_phk')
            ->where('ket_phk', '!=', '')
            ->groupBy('ket_phk')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get()
            ->map(fn($r) => ['label' => $r->ket_phk, 'jumlah' => (int) $r->jumlah])
            ->values();

        // ── Masa Kerja saat resign (bucket) ──
        $masaKerjaBucket = $this->baseResignQuery($request)
            ->select('tgl_masuk', 'tgl_phk')
            ->whereNotNull('tgl_masuk')
            ->get()
            ->groupBy(function ($k) {
                $bulan = Carbon::parse($k->tgl_masuk)->diffInMonths(Carbon::parse($k->tgl_phk));
                if ($bulan < 3)   return '< 3 Bulan';
                if ($bulan < 6)   return '3–6 Bulan';
                if ($bulan < 12)  return '6–12 Bulan';
                if ($bulan < 24)  return '1–2 Tahun';
                if ($bulan < 60)  return '2–5 Tahun';
                return '> 5 Tahun';
            })
            ->map(fn($g, $key) => ['label' => $key, 'jumlah' => $g->count()])
            ->values()
            ->sortByDesc('jumlah')
            ->values();

        // ── Master filter ──
        $perusahaans         = Perusahaan::orderBy('nama_prs1')->get();
        $kontrakOptions      = KontrakKerja::orderBy('kode_ktr')->get();
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
            'tgl_dari'         => $request->filter_tgl_dari         ?? $dari->format('Y-m-d'),
            'tgl_sampai'       => $request->filter_tgl_sampai        ?? $sampai->format('Y-m-d'),
            'perusahaan'       => $request->filter_perusahaan        ?? '',
            'wilker'           => $request->filter_wilker            ?? '',
            'area'             => $request->filter_area              ?? '',
            'kontrak'          => $request->filter_kontrak           ?? '',
            'departemen'       => $request->filter_departemen        ?? '',
            'include_top_mgmt' => $request->input('filter_include_top_mgmt', '0'),
        ];

        return view('reports.turnover.index', compact(
            'totalResign',
            'totalMasuk',
            'aktifAwal',
            'aktifAkhir',
            'rataAktif',
            'turnoverRate',
            'dari',
            'sampai',
            'resignPerBulan',
            'resignPerDep',
            'resignPerPT',
            'resignPerWilayah',
            'resignPerKontrak',
            'resignPerGender',
            'resignPerAlasan',
            'masaKerjaBucket',
            'perusahaans',
            'kontrakOptions',
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
        $areas = WilayahKerja::when($request->wilker, fn($q) => $q->where('wilayah_krj', $request->wilker))
            ->orderBy('area_krj')
            ->get(['id', 'area_krj', 'singkatan_wk', 'wilayah_krj']);

        return response()->json($areas);
    }

    // ================================================================
    // AJAX: Detail karyawan resign (drill-down modal)
    // ================================================================
    public function detail(Request $request)
    {
        $type  = $request->type;
        $value = $request->value;

        $q = $this->baseResignQuery($request);

        switch ($type) {
            case 'bulan':
                [$tahun, $bulan] = explode('-', $value);
                $q->whereYear('tgl_phk', $tahun)->whereMonth('tgl_phk', $bulan);
                $label = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');
                break;
            case 'departemen':
                $depIds = Departemen::where('nama_dep', $value)->pluck('id');
                $q->whereIn('departemen', $depIds);
                $label = 'Departemen: ' . $value;
                break;
            case 'pt':
                $q->where('perusahaan', $value);
                $label = Perusahaan::find($value)?->nama_prs1 ?? $value;
                break;
            case 'wilayah':
                $q->where('wilker', $value);
                $label = 'Wilayah: ' . $value;
                break;
            case 'kontrak':
                $q->where('sts_ktr', $value);
                $label = 'Kontrak: ' . (KontrakKerja::find($value)?->nama_ktr ?? $value);
                break;
            case 'gender':
                $q->where('sex', $value);
                $label = $value === 'LAKI-LAKI' ? 'Laki-laki' : 'Perempuan';
                break;
            case 'masa_kerja':
                // filter by bucket label
                $label = 'Masa Kerja: ' . $value;
                break;
            default:
                $label = 'Semua Resign';
        }

        $data = $q->orderBy('tgl_phk', 'desc')->get()->map(function ($k) {
            $dep = Departemen::find($k->departemen);
            $uk  = WilayahKerja::find($k->unit_krj);
            $masaKerja = $k->tgl_masuk && $k->tgl_phk
                ? Carbon::parse($k->tgl_masuk)->diff(Carbon::parse($k->tgl_phk))
                : null;
            $masaKerjaStr = $masaKerja
                ? ($masaKerja->y > 0 ? $masaKerja->y . 'th ' : '') .
                  ($masaKerja->m > 0 ? $masaKerja->m . 'bl' : ($masaKerja->y === 0 ? '< 1bl' : ''))
                : '-';
            return [
                'nrk'        => $k->nrk ?? '-',
                'nama'       => $k->nama,
                'sex'        => $k->sex ?? '-',
                'perusahaan' => $k->perusahaanRelation?->nama_prs2 ?? $k->perusahaanRelation?->nama_prs1 ?? '-',
                'departemen' => $dep?->nama_dep ?? '-',
                'jabatan'    => $dep?->nama_jbt ?? '-',
                'unit_kerja' => $uk?->area_krj ?? '-',
                'kontrak'    => $k->kontrakRelation?->singkatan_ktr ?? '-',
                'tgl_masuk'  => $k->tgl_masuk?->format('d/m/Y') ?? '-',
                'tgl_phk'    => $k->tgl_phk?->format('d/m/Y') ?? '-',
                'masa_kerja' => $masaKerjaStr,
                'alasan'     => $k->ket_phk ?? '-',
            ];
        });

        return response()->json([
            'label' => $label,
            'total' => $data->count(),
            'data'  => $data,
        ]);
    }

    // ================================================================
    // AJAX: Data chart bulanan untuk refresh dinamis
    // ================================================================
    public function chartBulanan(Request $request)
    {
        $data = $this->baseResignQuery($request)
            ->select(
                DB::raw('YEAR(tgl_phk) as tahun'),
                DB::raw('MONTH(tgl_phk) as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')->orderBy('bulan')
            ->get()
            ->map(fn($r) => [
                'label'  => Carbon::createFromDate($r->tahun, $r->bulan, 1)->translatedFormat('M Y'),
                'tahun'  => $r->tahun,
                'bulan'  => $r->bulan,
                'jumlah' => (int) $r->jumlah,
            ]);

        return response()->json($data);
    }
}