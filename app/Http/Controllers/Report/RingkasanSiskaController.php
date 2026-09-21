<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Data\DataKaryawan;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\KontrakKerja;
use App\Models\DataMaster\Perusahaan;
use App\Models\DataMaster\WilayahKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RingkasanSiskaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:ringkasan-siska')->only('index');
    }

    // ================================================================
    // HELPER: Resolve wilker IDs dari filter
    // filter_wilker  = nama wilayah_krj  (mis. "Jawa Timur")
    // filter_area    = ID WilayahKerja   (mis. 5)
    // Kalau filter_wilker aktif → ambil semua ID yang punya wilayah_krj tsb
    // Kalau filter_area  aktif → pakai ID itu langsung
    // ================================================================
    private function resolveWilkerIds(Request $request): ?array
    {
        if ($request->filter_area) {
            // Filter area kerja (ID spesifik)
            return [$request->filter_area];
        }
        if ($request->filter_wilker) {
            // Filter wilayah kerja → semua ID dengan nama wilayah_krj tersebut
            return WilayahKerja::where('wilayah_krj', $request->filter_wilker)
                ->pluck('id')->toArray();
        }
        return null; // tidak ada filter wilker
    }

    // ================================================================
    // HELPER: Base query dengan semua filter global
    // ================================================================
    private function baseQuery(Request $request)
    {
        $q = DataKaryawan::query();

        if ($request->filter_status)     $q->where('sts_kry',    $request->filter_status);
        if ($request->filter_perusahaan) $q->where('perusahaan', $request->filter_perusahaan);
        if ($request->filter_kontrak)    $q->where('sts_ktr',     $request->filter_kontrak);

        // Wilker: gunakan whereIn karena satu wilayah_krj bisa punya banyak ID
        $wilkerIds = $this->resolveWilkerIds($request);
        if ($wilkerIds !== null) {
            $q->whereIn('wilker', $wilkerIds);
        }

        // Filter departemen: satu nama_dep bisa punya banyak ID (per jabatan)
        if ($request->filter_departemen) {
            $depIds = Departemen::where('nama_dep', $request->filter_departemen)->pluck('id');
            $q->whereIn('departemen', $depIds);
        }

        return $q;
    }

    // ================================================================
    // INDEX
    // ================================================================
    public function index(Request $request)
    {
        $base = $this->baseQuery($request);

        $totalKaryawan = (clone $base)->count();
        $statAktif     = (clone $base)->where('sts_kry', 'AKTIF')->count();
        $statNonAktif  = (clone $base)->where('sts_kry', 'NON-AKTIF')->count();
        $statCalon     = (clone $base)->where('sts_kry', 'CALON')->count();
        $statLakiLaki  = (clone $base)->where('sex', 'LAKI-LAKI')->count();
        $statPerempuan = (clone $base)->where('sex', 'PEREMPUAN')->count();

        // ---- Per PT ----
        $perPT = (clone $base)
            ->select('perusahaan', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('perusahaan')
            ->groupBy('perusahaan')
            ->get()
            ->map(function ($r) {
                $p = Perusahaan::find($r->perusahaan);
                return [
                    'id'        => (int) $r->perusahaan,
                    'label'     => $p->nama_prs1 ?? 'Tidak Diketahui',
                    'singkatan' => $p->nama_prs2 ?? '-',
                    'bidang'    => $p->bidang_ush ?? '-',
                    'jumlah'    => (int) $r->jumlah,
                ];
            })
            ->sortByDesc('jumlah')
            ->values();

        // ---- Bidang Usaha ----
        $perBidangUsaha = $perPT
            ->groupBy('bidang')
            ->map(fn($g, $key) => ['label' => $key, 'jumlah' => $g->sum('jumlah')])
            ->values()
            ->sortByDesc('jumlah')
            ->values();

        // ---- Pusat & Cabang (per Wilayah Kerja) ----
        // wilker field di DataKaryawan = ID dari WilayahKerja
        $perPusatCabang = (clone $base)
            ->select('wilker', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('wilker')
            ->groupBy('wilker')
            ->get()
            ->map(function ($r) {
                $wk = WilayahKerja::find($r->wilker);
                return [
                    'id'          => (int) $r->wilker,
                    'wilayah_krj' => $wk?->wilayah_krj ?? 'Tidak Diketahui',
                    'jumlah'      => (int) $r->jumlah,
                ];
            })
            // Gabungkan per nama wilayah_krj
            ->groupBy('wilayah_krj')
            ->map(fn($g, $key) => [
                'id'     => $g->first()['id'],
                'label'  => $key,
                'jumlah' => $g->sum('jumlah'),
            ])
            ->values()
            ->sortByDesc('jumlah')
            ->values();

        // ---- Unit Kerja (per Area Kerja) ----
        $perUnitKerja = (clone $base)
            ->select('wilker', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('wilker')
            ->groupBy('wilker')
            ->get()
            ->map(function ($r) {
                $wk = WilayahKerja::find($r->wilker);
                $area = $wk?->area_krj ?? 'Tidak Diketahui';
                $skt  = $wk?->singkatan_wk ? ' (' . $wk->singkatan_wk . ')' : '';
                return [
                    'id'      => (int) $r->wilker,
                    'label'   => $area . $skt,
                    'wilayah' => $wk?->wilayah_krj ?? '-',
                    'jumlah'  => (int) $r->jumlah,
                ];
            })
            ->sortByDesc('jumlah')
            ->values();

        // ---- Departemen ----
        $perDepartemen = (clone $base)
            ->select('departemen', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('departemen')
            ->groupBy('departemen')
            ->get()
            ->map(function ($r) {
                $dep = Departemen::find($r->departemen);
                return [
                    'id'        => (int) $r->departemen,
                    'label'     => $dep->nama_dep ?? 'Tidak Diketahui',
                    'singkatan' => $dep->singkatan_dep ?? '-',
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
            ->values()
            ->sortByDesc('jumlah')
            ->values();

        // ---- Per Jabatan ----
        $perJabatan = (clone $base)
            ->select('departemen', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('departemen')
            ->groupBy('departemen')
            ->get()
            ->map(function ($r) {
                $dep = Departemen::find($r->departemen);
                return [
                    'id'         => (int) $r->departemen,
                    'label'      => $dep->nama_jbt ?? 'Tidak Diketahui',
                    'singkatan'  => $dep->singkatan_jbt ?? '-',
                    'departemen' => $dep->nama_dep ?? '-',
                    'jumlah'     => (int) $r->jumlah,
                ];
            })
            ->sortByDesc('jumlah')
            ->values();

        // ---- Kontrak ----
        $perKontrak = (clone $base)
            ->select('sts_ktr', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('sts_ktr')
            ->groupBy('sts_ktr')
            ->get()
            ->map(function ($r) {
                $ktr = KontrakKerja::find($r->sts_ktr);
                return [
                    'id'     => (int) $r->sts_ktr,
                    'label'  => $ktr->nama_ktr ?? 'Tidak Diketahui',
                    'kode'   => $ktr->singkatan_ktr ?? ($ktr->kode_ktr ?? '-'),
                    'jumlah' => (int) $r->jumlah,
                ];
            })
            ->sortByDesc('jumlah')
            ->values();

        // ================================================================
        // CROSS ANALYSIS: Departemen × Wilayah & Departemen × PT
        // ================================================================
        $filterDep        = $request->filter_departemen;
        $depWilayahMatrix = collect();
        $depPTMatrix      = collect();

        // Semua wilayah_krj unik yang ada di hasil query
        $wilkerIdsInQuery = (clone $base)
            ->select('wilker')->whereNotNull('wilker')
            ->distinct()->pluck('wilker');

        $allWilayah = WilayahKerja::whereIn('id', $wilkerIdsInQuery)
            ->select('wilayah_krj')->distinct()
            ->orderBy('wilayah_krj')
            ->pluck('wilayah_krj');

        // Semua PT unik
        $allPT = (clone $base)
            ->select('perusahaan')->whereNotNull('perusahaan')
            ->distinct()->pluck('perusahaan')
            ->map(fn($id) => [
                'id'        => $id,
                'singkatan' => Perusahaan::find($id)?->nama_prs2 ?? Perusahaan::find($id)?->nama_prs1 ?? '-',
                'nama'      => Perusahaan::find($id)?->nama_prs1 ?? '-',
            ])
            ->values();

        // Scope dep yang dimunculkan di matrix
        $depsForMatrix = $filterDep
            ? collect([$filterDep])
            : $perDepartemen->take(8)->pluck('label');

        // Matrix Dep × Wilayah
        foreach ($depsForMatrix as $depNama) {
            $depIds = Departemen::where('nama_dep', $depNama)->pluck('id');
            $row    = ['departemen' => $depNama, 'total' => 0, 'wilayah' => []];

            foreach ($allWilayah as $wNama) {
                // Cari semua ID WilayahKerja dengan wilayah_krj = $wNama
                $wkIds = WilayahKerja::where('wilayah_krj', $wNama)->pluck('id');
                $cnt   = (clone $base)
                    ->whereIn('departemen', $depIds)
                    ->whereIn('wilker', $wkIds)
                    ->count();
                $row['wilayah'][$wNama] = (int) $cnt;
                $row['total'] += $cnt;
            }
            $depWilayahMatrix->push($row);
        }

        // Matrix Dep × PT
        foreach ($depsForMatrix as $depNama) {
            $depIds = Departemen::where('nama_dep', $depNama)->pluck('id');
            $row    = ['departemen' => $depNama, 'total' => 0, 'pt' => []];

            foreach ($allPT as $pt) {
                $cnt = (clone $base)
                    ->whereIn('departemen', $depIds)
                    ->where('perusahaan', $pt['id'])
                    ->count();
                $row['pt'][$pt['singkatan']] = [
                    'jumlah' => (int) $cnt,
                    'nama'   => $pt['nama'],
                    'id'     => $pt['id'],
                ];
                $row['total'] += $cnt;
            }
            $depPTMatrix->push($row);
        }

        // ---- Master data filter ----
        $perusahaans       = Perusahaan::orderBy('nama_prs1')->get();
        $kontrakOptions    = KontrakKerja::orderBy('kode_ktr')->get();
        $departemenOptions = Departemen::select(
                                'nama_dep', 'singkatan_dep',
                                DB::raw('MIN(CAST(kode_dep AS UNSIGNED)) as min_kode')
                            )
                            ->groupBy('nama_dep', 'singkatan_dep')
                            ->orderBy('min_kode')
                            ->get();

        // Wilayah Kerja options (nama unik, bukan ID)
        $wilayahKerjaOptions = WilayahKerja::select('wilayah_krj')
            ->groupBy('wilayah_krj')
            ->orderBy('wilayah_krj')
            ->get();

        // Area Kerja options — tampilkan semua atau filter berdasarkan wilker terpilih
        $areaKerjaQuery = WilayahKerja::orderBy('area_krj');
        if ($request->filter_wilker) {
            $areaKerjaQuery->where('wilayah_krj', $request->filter_wilker);
        }
        $areaKerjaOptions = $areaKerjaQuery->get();

        $currentFilters = [
            'perusahaan' => $request->filter_perusahaan,
            'wilker'     => $request->filter_wilker,
            'area'       => $request->filter_area,
            'status'     => $request->filter_status,
            'kontrak'    => $request->filter_kontrak,
            'departemen' => $request->filter_departemen,
        ];

        return view('reports.index', compact(
            'totalKaryawan', 'statAktif', 'statNonAktif', 'statCalon', 'statLakiLaki', 'statPerempuan',
            'perPT', 'perPusatCabang', 'perUnitKerja', 'perDepartemen', 'perJabatan', 'perBidangUsaha', 'perKontrak',
            'depWilayahMatrix', 'depPTMatrix', 'allWilayah', 'allPT', 'depsForMatrix',
            'perusahaans', 'kontrakOptions', 'wilayahKerjaOptions', 'areaKerjaOptions', 'departemenOptions',
            'currentFilters'
        ));
    }

    // ================================================================
    // AJAX: Area kerja options untuk dropdown dinamis (filter wilker berubah)
    // ================================================================
    public function areaByWilker(Request $request)
    {
        $areas = WilayahKerja::when($request->wilker, fn($q) => $q->where('wilayah_krj', $request->wilker))
            ->orderBy('area_krj')
            ->get(['id', 'area_krj', 'singkatan_wk', 'wilayah_krj']);

        return response()->json($areas);
    }

    // ================================================================
    // AJAX: Detail karyawan drill-down
    // ================================================================
    public function detail(Request $request)
    {
        $type  = $request->type;
        $value = $request->value;

        $q = DataKaryawan::with([
            'perusahaanRelation', 'departemenRelation',
            'wilayahKerjaRelation', 'kontrakRelation', 'unitKerjaRelation',
        ]);

        // Terapkan filter global
        if ($request->filter_status)     $q->where('sts_kry',    $request->filter_status);
        if ($request->filter_perusahaan) $q->where('perusahaan', $request->filter_perusahaan);
        if ($request->filter_kontrak)    $q->where('sts_ktr',     $request->filter_kontrak);

        if ($request->filter_area) {
            $q->where('wilker', $request->filter_area);
        } elseif ($request->filter_wilker) {
            $wkIds = WilayahKerja::where('wilayah_krj', $request->filter_wilker)->pluck('id');
            $q->whereIn('wilker', $wkIds);
        }

        if ($request->filter_departemen) {
            $depIds = Departemen::where('nama_dep', $request->filter_departemen)->pluck('id');
            $q->whereIn('departemen', $depIds);
        }

        // Terapkan filter drill-down
        switch ($type) {
            case 'pt':
                $q->where('perusahaan', $value);
                $title = 'Karyawan — ' . (Perusahaan::find($value)?->nama_prs1 ?? $value);
                break;
            case 'wilker':
                // $value = nama wilayah_krj
                $wkIds = WilayahKerja::where('wilayah_krj', $value)->pluck('id');
                $q->whereIn('wilker', $wkIds);
                $title = 'Karyawan — Wilayah: ' . $value;
                break;
            case 'unit_kerja':
                // $value = ID WilayahKerja
                $q->where('wilker', $value);
                $wk    = WilayahKerja::find($value);
                $title = 'Karyawan — ' . ($wk?->area_krj ?? $value) . ($wk?->wilayah_krj ? ' (' . $wk->wilayah_krj . ')' : '');
                break;
            case 'departemen':
                $depIds = Departemen::where('nama_dep', $value)->pluck('id');
                $q->whereIn('departemen', $depIds);
                $title = 'Karyawan — Departemen: ' . $value;
                break;
            case 'jabatan':
                $q->where('departemen', $value);
                $title = 'Karyawan — Jabatan: ' . (Departemen::find($value)?->nama_jbt ?? $value);
                break;
            case 'bidang':
                $prsIds = Perusahaan::where('bidang_ush', $value)->pluck('id');
                $q->whereIn('perusahaan', $prsIds);
                $title = 'Karyawan — Bidang Usaha: ' . $value;
                break;
            case 'kontrak':
                $q->where('sts_ktr', $value);
                $title = 'Karyawan — Kontrak: ' . (KontrakKerja::find($value)?->nama_ktr ?? $value);
                break;
            case 'status':
                if ($value !== '') $q->where('sts_kry', $value);
                $title = $value ? 'Karyawan — Status: ' . $value : 'Semua Karyawan';
                break;
            case 'gender':
                $q->where('sex', $value);
                $title = 'Karyawan — ' . ($value === 'LAKI-LAKI' ? 'Laki-laki' : 'Perempuan');
                break;
            case 'dep_wilayah':
                [$depNama, $wNama] = explode('||', $value, 2);
                $depIds = Departemen::where('nama_dep', $depNama)->pluck('id');
                $wkIds  = WilayahKerja::where('wilayah_krj', $wNama)->pluck('id');
                $q->whereIn('departemen', $depIds)->whereIn('wilker', $wkIds);
                $title = $depNama . ' — ' . $wNama;
                break;
            case 'dep_pt':
                [$depNama, $ptId] = explode('||', $value, 2);
                $depIds = Departemen::where('nama_dep', $depNama)->pluck('id');
                $q->whereIn('departemen', $depIds)->where('perusahaan', $ptId);
                $pt    = Perusahaan::find($ptId);
                $title = $depNama . ' — ' . ($pt?->nama_prs2 ?? $pt?->nama_prs1 ?? $ptId);
                break;
            default:
                $title = 'Detail Karyawan';
        }

        $karyawans = $q->orderBy('nama')->get()->map(function ($k) {
            $dep = Departemen::find($k->departemen);
            $wk  = WilayahKerja::find($k->wilker);
            $uk  = WilayahKerja::find($k->unit_krj);
            return [
                'id'         => $k->id,
                'nrk'        => $k->nrk ?? '-',
                'nama'       => $k->nama,
                'sex'        => $k->sex ?? '-',
                'perusahaan' => $k->perusahaanRelation?->nama_prs2 ?? $k->perusahaanRelation?->nama_prs1 ?? '-',
                'departemen' => $dep?->nama_dep ?? '-',
                'jabatan'    => $dep?->nama_jbt ?? '-',
                'wilker'     => $wk?->wilayah_krj ?? '-',
                'unit_kerja' => $uk?->area_krj ?? $wk?->area_krj ?? '-',
                'kontrak'    => $k->kontrakRelation?->singkatan_ktr ?? $k->kontrakRelation?->kode_ktr ?? '-',
                'sts_kry'    => $k->sts_kry ?? '-',
                'tgl_masuk'  => $k->tgl_masuk ? $k->tgl_masuk->format('d/m/Y') : '-',
            ];
        });

        return response()->json([
            'title' => $title,
            'total' => $karyawans->count(),
            'data'  => $karyawans,
        ]);
    }

    // ================================================================
    // AJAX: Breakdown jabatan dalam satu departemen
    // ================================================================
    public function jabatanByDepartemen(Request $request, $namaDep)
    {
        $base   = $this->baseQuery($request);
        $depIds = Departemen::where('nama_dep', $namaDep)->pluck('id');

        $rows = (clone $base)
            ->whereIn('departemen', $depIds)
            ->select('departemen', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('departemen')
            ->get()
            ->map(function ($r) {
                $dep = Departemen::find($r->departemen);
                return [
                    'id'        => (int) $r->departemen,
                    'jabatan'   => $dep?->nama_jbt ?? '-',
                    'singkatan' => $dep?->singkatan_jbt ?? '-',
                    'jumlah'    => (int) $r->jumlah,
                ];
            })
            ->sortByDesc('jumlah')
            ->values();

        return response()->json([
            'departemen' => $namaDep,
            'data'       => $rows,
        ]);
    }
}