<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\DataJenjangKarir;
use App\Models\Data\DataKaryawan;
use App\Models\DataMaster\WilayahKerja;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\DokumenKaryawan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataJenjangKarirPelaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:data-jenjang-karir-laporan')->only('index');
        $this->middleware('check.access:data-jenjang-karir-laporan,detail')->only('show');
    }

    public function index(Request $request)
    {
        // Query semua riwayat jenjang karir (bukan hanya latest), urut nama ASC, tgl_ttd DESC
        $query = DataJenjangKarir::with([
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'karyawan.unitKerjaRelation',
            'departemen',
            'wilayahKerja',
            'dokumenKaryawan',
            'creator',
            'updater',
        ])
        ->join('201_dm_data_karyawan as karyawan', '204_dm_data_jenjang_karir.id_karyawan', '=', 'karyawan.id')
        ->select('204_dm_data_jenjang_karir.*')
        ->orderBy('karyawan.nama', 'asc')
        ->orderBy('204_dm_data_jenjang_karir.tgl_ttd', 'desc');

        // Apply filters
        if ($request->filled('filter_nama')) {
            $query->where('karyawan.nama', 'LIKE', '%' . $request->filter_nama . '%');
        }

        if ($request->filled('filter_nrk')) {
            $query->where('karyawan.nrk', 'LIKE', '%' . $request->filter_nrk . '%');
        }

        if ($request->filled('filter_no_jk')) {
            $query->where('204_dm_data_jenjang_karir.no_jk', 'LIKE', '%' . $request->filter_no_jk . '%');
        }

        if ($request->filled('filter_departemen')) {
            $selectedDepartemen = Departemen::find($request->filter_departemen);
            if ($selectedDepartemen) {
                $departemenIds = Departemen::where('nama_dep', $selectedDepartemen->nama_dep)->pluck('id')->toArray();
                $query->whereIn('204_dm_data_jenjang_karir.id_departemen', $departemenIds);
            }
        }

        if ($request->filled('filter_jabatan')) {
            $query->where('204_dm_data_jenjang_karir.id_departemen', $request->filter_jabatan);
        }

        if ($request->filled('filter_jenis_kelamin')) {
            $query->where('karyawan.sex', $request->filter_jenis_kelamin);
        }

        if ($request->filled('filter_wilker')) {
            $query->where('karyawan.wilker', $request->filter_wilker);
        }

        if ($request->filled('filter_unit_kerja')) {
            $query->where('204_dm_data_jenjang_karir.id_wilayah_kerja', $request->filter_unit_kerja);
        }

        if ($request->filled('filter_tgl_ttd_start')) {
            $query->where('204_dm_data_jenjang_karir.tgl_ttd', '>=', $request->filter_tgl_ttd_start);
        }

        if ($request->filled('filter_tgl_ttd_end')) {
            $query->where('204_dm_data_jenjang_karir.tgl_ttd', '<=', $request->filter_tgl_ttd_end);
        }

        if ($request->filled('filter_jenis_dokumen')) {
            $query->where('204_dm_data_jenjang_karir.id_dokumen_karyawan', $request->filter_jenis_dokumen);
        }

        $dataJenjangKarirs = $query->get();

        // Master data untuk filter
        $wilayahKerjas    = WilayahKerja::orderBy('wilayah_krj', 'asc')->get();
        $dokumenOptions   = DokumenKaryawan::orderBy('kode_dok_kry', 'asc')->get();

        $departemenOptions = Departemen::select('nama_dep', 'singkatan_dep', DB::raw('MIN(id) as id'), DB::raw('MIN(CAST(kode_dep AS UNSIGNED)) as min_kode_dep'))
            ->groupBy('nama_dep', 'singkatan_dep')
            ->orderBy('min_kode_dep', 'asc')
            ->get();

        $jabatanOptions = Departemen::sortByCode()->get(['id', 'kode_dep', 'nama_dep', 'nama_jbt', 'singkatan_jbt']);

        $wilayahKerjaOptions = WilayahKerja::select('wilayah_krj')
            ->groupBy('wilayah_krj')
            ->orderBy('wilayah_krj', 'asc')
            ->get();

        $unitKerjaOptions = WilayahKerja::select('id', 'area_krj', 'kode_wk')
            ->orderBy('area_krj')
            ->get();

        $jenisKelaminOptions = [
            'LAKI-LAKI' => 'Laki-laki',
            'PEREMPUAN'  => 'Perempuan',
        ];

        // User permissions
        $userPermissions = [];
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->is_admin) {
                $userPermissions = ['detail' => true, 'download' => true];
            } else {
                $access = $user->userAccess()->where('menu_acs', 'data-jenjang-karir-laporan')->first();
                if ($access) {
                    $userPermissions = [
                        'detail'   => (bool) $access->detail_acs,
                        'download' => (bool) $access->download_acs,
                    ];
                }
            }
        }

        // Current filters
        $currentFilters = [
            'nama'            => $request->filter_nama,
            'nrk'             => $request->filter_nrk,
            'no_jk'           => $request->filter_no_jk,
            'departemen'      => $request->filter_departemen,
            'jabatan'         => $request->filter_jabatan,
            'jenis_kelamin'   => $request->filter_jenis_kelamin,
            'wilker'          => $request->filter_wilker,
            'unit_kerja'      => $request->filter_unit_kerja,
            'tgl_ttd_start'   => $request->filter_tgl_ttd_start,
            'tgl_ttd_end'     => $request->filter_tgl_ttd_end,
            'jenis_dokumen'   => $request->filter_jenis_dokumen,
        ];

        // Handle export
        if ($request->has('export')) {
            $filteredData = $this->getFilteredData($request->all());
            switch ($request->export) {
                case 'excel':
                    return $this->exportExcel($filteredData);
                case 'pdf':
                    return $this->exportPDF($filteredData);
                case 'csv':
                    return $this->exportCSV($filteredData);
            }
        }

        // Statistics
        $totalJK        = $dataJenjangKarirs->count();
        $totalKaryawan  = $dataJenjangKarirs->pluck('id_karyawan')->unique()->count();

        return view('data.data-jenjang-karir-laporan.index', compact(
            'dataJenjangKarirs',
            'userPermissions',
            'wilayahKerjas',
            'unitKerjaOptions',
            'departemenOptions',
            'jabatanOptions',
            'wilayahKerjaOptions',
            'jenisKelaminOptions',
            'dokumenOptions',
            'currentFilters',
            'totalJK',
            'totalKaryawan'
        ));
    }

    public function show($id)
    {
        $dataJenjangKarir = DataJenjangKarir::with([
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'karyawan.unitKerjaRelation',
            'departemen',
            'wilayahKerja',
            'dokumenKaryawan',
            'creator',
            'updater',
        ])->findOrFail($id);

        return view('data.data-jenjang-karir-laporan.show', compact('dataJenjangKarir'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function getFilteredData($filters)
    {
        $query = DataJenjangKarir::with([
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'departemen',
            'wilayahKerja',
            'dokumenKaryawan',
        ])
        ->join('201_dm_data_karyawan as karyawan', '204_dm_data_jenjang_karir.id_karyawan', '=', 'karyawan.id')
        ->select('204_dm_data_jenjang_karir.*')
        ->orderBy('karyawan.nama', 'asc')
        ->orderBy('204_dm_data_jenjang_karir.tgl_ttd', 'desc');

        if (!empty($filters['filter_nama'])) {
            $query->where('karyawan.nama', 'LIKE', '%' . $filters['filter_nama'] . '%');
        }
        if (!empty($filters['filter_nrk'])) {
            $query->where('karyawan.nrk', 'LIKE', '%' . $filters['filter_nrk'] . '%');
        }
        if (!empty($filters['filter_no_jk'])) {
            $query->where('204_dm_data_jenjang_karir.no_jk', 'LIKE', '%' . $filters['filter_no_jk'] . '%');
        }
        if (!empty($filters['filter_departemen'])) {
            $selectedDepartemen = Departemen::find($filters['filter_departemen']);
            if ($selectedDepartemen) {
                $departemenIds = Departemen::where('nama_dep', $selectedDepartemen->nama_dep)->pluck('id')->toArray();
                $query->whereIn('204_dm_data_jenjang_karir.id_departemen', $departemenIds);
            }
        }
        if (!empty($filters['filter_jabatan'])) {
            $query->where('204_dm_data_jenjang_karir.id_departemen', $filters['filter_jabatan']);
        }
        if (!empty($filters['filter_jenis_kelamin'])) {
            $query->where('karyawan.sex', $filters['filter_jenis_kelamin']);
        }
        if (!empty($filters['filter_wilker'])) {
            $query->where('karyawan.wilker', $filters['filter_wilker']);
        }
        if (!empty($filters['filter_unit_kerja'])) {
            $query->where('204_dm_data_jenjang_karir.id_wilayah_kerja', $filters['filter_unit_kerja']);
        }
        if (!empty($filters['filter_tgl_ttd_start'])) {
            $query->where('204_dm_data_jenjang_karir.tgl_ttd', '>=', $filters['filter_tgl_ttd_start']);
        }
        if (!empty($filters['filter_tgl_ttd_end'])) {
            $query->where('204_dm_data_jenjang_karir.tgl_ttd', '<=', $filters['filter_tgl_ttd_end']);
        }
        if (!empty($filters['filter_jenis_dokumen'])) {
            $query->where('204_dm_data_jenjang_karir.id_dokumen_karyawan', $filters['filter_jenis_dokumen']);
        }

        return [
            'dataJenjangKarirs' => $query->get(),
            'filters'           => $filters,
        ];
    }

    private function exportExcel($data)
    {
        $currentDate = now()->format('d-m-Y_H-i-s');
        $fileName    = 'Pelaporan_Jenjang_Karir_' . $currentDate . '.xlsx';

        return Excel::download(
            new \App\Exports\DataJenjangKarirPelaporanExport($data['dataJenjangKarirs'], $data['filters']),
            $fileName
        );
    }

    private function exportPDF($data)
    {
        // Implement PDF export if needed
    }

    private function exportCSV($data)
    {
        // Implement CSV export if needed
    }
}