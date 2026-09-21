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

    public function index(Request $request)
    {
        // === FILTERS ===
        $filterPerusahaan = $request->filter_perusahaan;
        $filterWilker     = $request->filter_wilker;
        $filterStatus     = $request->filter_status;
        $filterKontrak    = $request->filter_kontrak;

        // Base query
        $baseQuery = DataKaryawan::query()
            ->with(['perusahaanRelation', 'departemenRelation', 'wilayahKerjaRelation', 'kontrakRelation', 'unitKerjaRelation']);

        if ($filterStatus) {
            $baseQuery->where('sts_kry', $filterStatus);
        }
        if ($filterPerusahaan) {
            $baseQuery->where('perusahaan', $filterPerusahaan);
        }
        if ($filterWilker) {
            $baseQuery->where('wilker', $filterWilker);
        }
        if ($filterKontrak) {
            $baseQuery->where('sts_ktr', $filterKontrak);
        }

        // Total
        $totalKaryawan = (clone $baseQuery)->count();

        // =========================================================
        // 1. JUMLAH KARYAWAN PER PT (Perusahaan)
        // =========================================================
        $perPT = (clone $baseQuery)
            ->select('perusahaan', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('perusahaan')
            ->with('perusahaanRelation')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->perusahaanRelation->nama_prs1 ?? 'Tidak Diketahui',
                    'singkatan' => $item->perusahaanRelation->nama_prs2 ?? '-',
                    'jumlah' => $item->jumlah,
                    'bidang' => $item->perusahaanRelation->bidang_ush ?? '-',
                ];
            });

        // =========================================================
        // 2. PEMBAGIAN: PUSAT & CABANG (berdasarkan wilayah_krj)
        // =========================================================
        $perPusatCabang = (clone $baseQuery)
            ->select('wilker', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('wilker')
            ->groupBy('wilker')
            ->get()
            ->map(function ($item) {
                $wilker = WilayahKerja::find($item->wilker);
                return [
                    'label'    => $wilker->wilayah_krj ?? $item->wilker ?? 'Tidak Diketahui',
                    'area'     => $wilker->area_krj ?? '-',
                    'jumlah'   => $item->jumlah,
                    'kode'     => $wilker->kode_wk ?? '-',
                ];
            })
            ->groupBy('label')
            ->map(function ($group) {
                return [
                    'label'  => $group->first()['label'],
                    'jumlah' => $group->sum('jumlah'),
                    'cabang' => $group->toArray(),
                ];
            })
            ->values();

        // Unit kerja breakdown (area_krj)
        $perUnitKerja = (clone $baseQuery)
            ->select('unit_krj', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('unit_krj')
            ->groupBy('unit_krj')
            ->get()
            ->map(function ($item) {
                $wk = WilayahKerja::find($item->unit_krj);
                return [
                    'label'   => ($wk->area_krj ?? 'Tidak Diketahui') . ' (' . ($wk->singkatan_wk ?? '-') . ')',
                    'wilayah' => $wk->wilayah_krj ?? '-',
                    'jumlah'  => $item->jumlah,
                ];
            });

        // =========================================================
        // 3. PEMBAGIAN: DEPARTEMEN → JABATAN
        // =========================================================
        $perDepartemen = (clone $baseQuery)
            ->select('departemen', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('departemen')
            ->groupBy('departemen')
            ->get()
            ->map(function ($item) {
                $dep = Departemen::find($item->departemen);
                return [
                    'label'  => $dep->nama_dep ?? 'Tidak Diketahui',
                    'singkatan' => $dep->singkatan_dep ?? '-',
                    'jumlah' => $item->jumlah,
                    'dep_id' => $item->departemen,
                ];
            })
            ->groupBy('label')
            ->map(function ($group) {
                return [
                    'label'     => $group->first()['label'],
                    'singkatan' => $group->first()['singkatan'],
                    'jumlah'    => $group->sum('jumlah'),
                ];
            })
            ->values()
            ->sortByDesc('jumlah')
            ->values();

        // Per Jabatan
        $perJabatan = (clone $baseQuery)
            ->select('departemen', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('departemen')
            ->groupBy('departemen')
            ->get()
            ->map(function ($item) {
                $dep = Departemen::find($item->departemen);
                return [
                    'label'      => $dep->nama_jbt ?? 'Tidak Diketahui',
                    'singkatan'  => $dep->singkatan_jbt ?? '-',
                    'departemen' => $dep->nama_dep ?? '-',
                    'jumlah'     => $item->jumlah,
                ];
            })
            ->sortByDesc('jumlah')
            ->values();

        // =========================================================
        // 4. PEMBAGIAN: BIDANG USAHA
        // =========================================================
        $perBidangUsaha = (clone $baseQuery)
            ->select('perusahaan', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('perusahaan')
            ->groupBy('perusahaan')
            ->get()
            ->map(function ($item) {
                $prs = Perusahaan::find($item->perusahaan);
                return [
                    'label'  => $prs->bidang_ush ?? 'Tidak Diketahui',
                    'jumlah' => $item->jumlah,
                ];
            })
            ->groupBy('label')
            ->map(function ($group) {
                return [
                    'label'  => $group->first()['label'],
                    'jumlah' => $group->sum('jumlah'),
                ];
            })
            ->values()
            ->sortByDesc('jumlah')
            ->values();

        // =========================================================
        // 5. PEMBAGIAN: PKWT / PKWTT / SPKK (Tipe Kontrak Kerja)
        // =========================================================
        $perKontrak = (clone $baseQuery)
            ->select('sts_ktr', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('sts_ktr')
            ->groupBy('sts_ktr')
            ->get()
            ->map(function ($item) {
                $ktr = KontrakKerja::find($item->sts_ktr);
                return [
                    'label'      => $ktr->nama_ktr ?? 'Tidak Diketahui',
                    'singkatan'  => $ktr->singkatan_ktr ?? ($ktr->kode_ktr ?? '-'),
                    'kode'       => $ktr->kode_ktr ?? '-',
                    'jumlah'     => $item->jumlah,
                ];
            })
            ->sortByDesc('jumlah')
            ->values();

        // =========================================================
        // STATISTIK TAMBAHAN
        // =========================================================
        $statAktif    = (clone $baseQuery)->where('sts_kry', 'AKTIF')->count();
        $statNonAktif = (clone $baseQuery)->where('sts_kry', 'NON-AKTIF')->count();
        $statCalon    = (clone $baseQuery)->where('sts_kry', 'CALON')->count();
        $statLakiLaki = (clone $baseQuery)->where('sex', 'LAKI-LAKI')->count();
        $statPerempuan= (clone $baseQuery)->where('sex', 'PEREMPUAN')->count();

        // =========================================================
        // MASTER DATA UNTUK FILTER
        // =========================================================
        $perusahaans = Perusahaan::orderBy('nama_prs1')->get();
        $kontrakOptions = KontrakKerja::orderBy('kode_ktr')->get();
        $wilayahKerjaOptions = WilayahKerja::select('wilayah_krj')
            ->groupBy('wilayah_krj')
            ->orderBy('wilayah_krj')
            ->get();

        $currentFilters = [
            'perusahaan' => $filterPerusahaan,
            'wilker'     => $filterWilker,
            'status'     => $filterStatus,
            'kontrak'    => $filterKontrak,
        ];

        return view('reports.index', compact(
            'totalKaryawan',
            'statAktif',
            'statNonAktif',
            'statCalon',
            'statLakiLaki',
            'statPerempuan',
            'perPT',
            'perPusatCabang',
            'perUnitKerja',
            'perDepartemen',
            'perJabatan',
            'perBidangUsaha',
            'perKontrak',
            'perusahaans',
            'kontrakOptions',
            'wilayahKerjaOptions',
            'currentFilters'
        ));
    }
}