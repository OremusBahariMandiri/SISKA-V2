<?php

namespace App\Http\Controllers\Data;

use App\Helpers\FilterHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\DataDokumen;
use App\Models\Data\DataKaryawan;
use App\Models\DataMaster\Perusahaan;
use App\Models\DataMaster\WilayahKerja;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\DokumenKaryawan;
use App\Models\DataMaster\KontrakKerja;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataDokumenLaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:data-dokumen-laporan')->only('index');
        $this->middleware('check.access:data-dokumen-laporan,detail')->only('show');
    }

    public function index(Request $request)
    {
        // Query untuk mengambil semua dokumen dengan relasi karyawan
        // Urutan: 1) Nama karyawan ASC, 2) kode_dok_kry ASC
        $query = DataDokumen::with([
            'karyawan.perusahaanRelation',
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'karyawan.unitKerjaRelation',
            'dokumenType',
            'creator',
            'updater'
        ])
        ->join('201_dm_data_karyawan as karyawan', '203_dm_data_dokumen.id_data_kry', '=', 'karyawan.id')
        ->join('105_dm_dok_kry as dok_kry', '203_dm_data_dokumen.id_dokumen', '=', 'dok_kry.id')
        ->select('203_dm_data_dokumen.*')
        ->orderBy('karyawan.nama', 'asc')
        ->orderBy('dok_kry.kode_dok_kry', 'asc');

        // Apply filters - SAMA SEPERTI DATA KONTRAK LAPORAN
        if ($request->has('filter_status') && !empty($request->filter_status)) {
            $query->where('sts_dok', $request->filter_status); // BUKAN sts_srt_ktr
        }

        // ✅ Filter Jabatan — id_departemen ada di tabel dokumen? Kalau tidak, pakai whereHas karyawan
        if ($request->has('filter_jabatan') && !empty($request->filter_jabatan)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('departemen', $request->filter_jabatan);
            });
        }

        // ✅ Filter Perusahaan — via karyawan
        if ($request->has('filter_perusahaan') && !empty($request->filter_perusahaan)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('perusahaan', $request->filter_perusahaan);
            });
        }

        // ✅ Filter Kontrak — id_ktr TIDAK ADA di tabel dokumen, ambil via DataKontrak
        if ($request->has('filter_kontrak') && !empty($request->filter_kontrak)) {
            $karyawanIds = \App\Models\Data\DataKontrak::where('id_ktr', $request->filter_kontrak)
                ->pluck('id_data_kry')
                ->toArray();
            $query->whereIn('id_data_kry', $karyawanIds);
        }

        // ✅ Filter Wilayah Kerja — via relasi wilayahKerja
        if ($request->has('filter_wilker') && !empty($request->filter_wilker)) {
            $query->whereHas('karyawan.wilayahKerjaRelation', function ($q) use ($request) {
                $q->where('wilayah_krj', $request->filter_wilker);
            });
        }

        // ✅ Filter Jenis Kelamin — via karyawan
        if ($request->has('filter_jenis_kelamin') && !empty($request->filter_jenis_kelamin)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('sex', $request->filter_jenis_kelamin);
            });
        }

        // ✅ Filter Unit Kerja — cek apakah kolom id_wilker ada di tabel dokumen
        // Kalau ada pakai where langsung, kalau tidak pakai whereHas karyawan
        if ($request->has('filter_unit_kerja') && !empty($request->filter_unit_kerja)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('unit_krj', $request->filter_unit_kerja);
            });
        }

        // ✅ Filter Departemen — via karyawan
        if ($request->has('filter_departemen') && !empty($request->filter_departemen)) {
            $selectedDepartemen = Departemen::find($request->filter_departemen);
            if ($selectedDepartemen) {
                $departemenIds = Departemen::where('nama_dep', $selectedDepartemen->nama_dep)
                    ->pluck('id')
                    ->toArray();
                $query->whereHas('karyawan', function ($q) use ($departemenIds) {
                    $q->whereIn('departemen', $departemenIds);
                });
            }
        }

        // ✅ Filter Nama — via karyawan
        if ($request->has('filter_nama') && !empty($request->filter_nama)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('nama', 'LIKE', '%' . $request->filter_nama . '%');
            });
        }

        // ✅ Filter NRK — via karyawan
        if ($request->has('filter_nrk') && !empty($request->filter_nrk)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('nrk', 'LIKE', '%' . $request->filter_nrk . '%');
            });
        }

        // Get data
        $dataDokumens = $query->get();

        // Get master data for filters
        $perusahaans = Perusahaan::orderBy('nama_prs1', 'asc')->get();
        $wilayahKerjas = WilayahKerja::orderBy('wilayah_krj', 'asc')->get();
        $dokumenTypes = DokumenKaryawan::orderBy('kode_dok_kry', 'asc')->get();

        // Get unique kategori dokumen
        $kategoriDokumenOptions = DokumenKaryawan::select('ktg_dok_kry')
            ->whereNotNull('ktg_dok_kry')
            ->where('ktg_dok_kry', '!=', '')
            ->groupBy('ktg_dok_kry')
            ->orderBy('ktg_dok_kry', 'asc')
            ->get();

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

        $statusOptions = [
            'AKTIF' => 'AKTIF',
            'NON-AKTIF' => 'NON-AKTIF'
        ];

        $jenisKelaminOptions = [
            'LAKI-LAKI' => 'Laki-laki',
            'PEREMPUAN' => 'Perempuan'
        ];

        // Get user permissions
        $userPermissions = [];
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->is_admin) {
                $userPermissions = [
                    'detail' => true,
                    'download' => true,
                ];
            } else {
                $access = $user->userAccess()->where('menu_acs', 'data-dokumen-laporan')->first();
                if ($access) {
                    $userPermissions = [
                        'detail' => (bool)$access->detail_acs,
                        'download' => (bool)$access->download_acs,
                    ];
                }
            }
        }

         // ===== GET FILTER OPTIONS DARI HELPER =====
         $filterOptions = FilterHelper::getFilterDataOptions();

         // ===== STORE CURRENT FILTERS =====
         $currentFilters = [
             'status' => $request->filter_status ?? '',
             'jabatan' => $request->filter_jabatan ?? '',
             'perusahaan' => $request->filter_perusahaan ?? '',
             'kontrak' => $request->filter_kontrak ?? '',
             'wilker' => $request->filter_wilker ?? '',
             'jenis_kelamin' => $request->filter_jenis_kelamin ?? '',
             'unit_kerja' => $request->filter_unit_kerja ?? '',
             'departemen' => $request->filter_departemen ?? '',
             'nama' => $request->filter_nama ?? '',
             'nrk' => $request->filter_nrk ?? '',
         ];


        // Handle export
        if ($request->has('export')) {
            $exportType = $request->export;
            $filteredData = $this->getFilteredData($request->all());

            switch ($exportType) {
                case 'excel':
                    return $this->exportExcel($filteredData);
                case 'pdf':
                    return $this->exportPDF($filteredData);
                case 'csv':
                    return $this->exportCSV($filteredData);
            }
        }

        // Calculate statistics
        $totalDokumen = $dataDokumens->count();
        $totalAktif = $dataDokumens->where('sts_dok', 'AKTIF')->count();
        $totalNonAktif = $dataDokumens->where('sts_dok', 'NON-AKTIF')->count();

        // Calculate unique karyawan and perusahaan
        $totalKaryawan = $dataDokumens->pluck('id_data_kry')->unique()->count();
        $totalPerusahaan = $dataDokumens->filter(function($dok) {
            return $dok->karyawan && $dok->karyawan->perusahaan;
        })->pluck('karyawan.perusahaan')->unique()->count();

        // Calculate expired and expiring documents
        $today = now();
        $expiredDocumentsCount = $dataDokumens->filter(function ($dokumen) use ($today) {
            return $dokumen->tgl_akr_dok && $dokumen->tgl_akr_dok < $today && $dokumen->sts_dok == 'AKTIF';
        })->count();

        $expiringDocumentsCount = $dataDokumens->filter(function ($dokumen) use ($today) {
            return $dokumen->tgl_pgt_dok &&
                   $dokumen->tgl_pgt_dok <= $today &&
                   $dokumen->tgl_akr_dok >= $today &&
                   $dokumen->sts_dok == 'AKTIF';
        })->count();

        return view('data.data-dokumen-laporan.index', compact(
            'dataDokumens',
            'userPermissions',
            'perusahaans',
            'wilayahKerjas',
            'unitKerjaOptions',
            'dokumenTypes',
            'kategoriDokumenOptions',
            'statusOptions',
            'departemenOptions',
            'jabatanOptions',
            'jenisKelaminOptions',
            'wilayahKerjaOptions',
            'currentFilters',
            'totalDokumen',
            'totalAktif',
            'filterOptions',
            'totalNonAktif',
            'totalKaryawan',
            'totalPerusahaan',
            'expiredDocumentsCount',
            'expiringDocumentsCount'
        ));
    }



    public function show($id)
    {
        $dataDokumen = DataDokumen::with([
            'karyawan.perusahaanRelation',
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'karyawan.unitKerjaRelation',
            'dokumenType',
            'creator',
            'updater'
        ])->findOrFail($id);

        return view('data.data-dokumen-laporan.show', compact('dataDokumen'));
    }

    private function getFilteredData($filters)
    {
        $query = DataDokumen::with([
            'karyawan.perusahaanRelation',
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'karyawan.unitKerjaRelation',
            'dokumenType'
        ])
        ->join('201_dm_data_karyawan as karyawan', '203_dm_data_dokumen.id_data_kry', '=', 'karyawan.id')
        ->join('105_dm_dok_kry as dok_kry', '203_dm_data_dokumen.id_dokumen', '=', 'dok_kry.id')
        ->select('203_dm_data_dokumen.*')
        ->orderBy('karyawan.nama', 'asc')
        ->orderBy('dok_kry.kode_dok_kry', 'asc');

        // Apply same filters as index
        if (!empty($filters['filter_status'])) {
            $query->where('203_dm_data_dokumen.sts_dok', $filters['filter_status']);
        }

        if (!empty($filters['filter_jenis_dokumen'])) {
            $query->where('203_dm_data_dokumen.id_dokumen', $filters['filter_jenis_dokumen']);
        }

        if (!empty($filters['filter_kategori_dokumen'])) {
            $query->where('dok_kry.ktg_dok_kry', $filters['filter_kategori_dokumen']);
        }

        if (!empty($filters['filter_nama'])) {
            $query->where('karyawan.nama', 'LIKE', '%' . $filters['filter_nama'] . '%');
        }

        if (!empty($filters['filter_nrk'])) {
            $query->where('karyawan.nrk', 'LIKE', '%' . $filters['filter_nrk'] . '%');
        }

        if (!empty($filters['filter_no_dokumen'])) {
            $query->where('203_dm_data_dokumen.no_dok', 'LIKE', '%' . $filters['filter_no_dokumen'] . '%');
        }

        if (!empty($filters['filter_perusahaan'])) {
            $query->where('karyawan.perusahaan', $filters['filter_perusahaan']);
        }

        if (!empty($filters['filter_departemen'])) {
            $selectedDepartemen = Departemen::find($filters['filter_departemen']);
            if ($selectedDepartemen) {
                $departemenIds = Departemen::where('nama_dep', $selectedDepartemen->nama_dep)->pluck('id')->toArray();
                $query->whereIn('karyawan.departemen', $departemenIds);
            }
        }

        if (!empty($filters['filter_jabatan'])) {
            $query->where('karyawan.departemen', $filters['filter_jabatan']);
        }

        if (!empty($filters['filter_jenis_kelamin'])) {
            $query->where('karyawan.sex', $filters['filter_jenis_kelamin']);
        }

        if (!empty($filters['filter_wilker'])) {
            $query->where('karyawan.wilker', $filters['filter_wilker']);
        }

        if (!empty($filters['filter_unit_kerja'])) {
            $query->where('karyawan.unit_krj', $filters['filter_unit_kerja']);
        }

        $dataDokumens = $query->get();

        return [
            'dataDokumens' => $dataDokumens,
            'filters' => $filters
        ];
    }

    private function exportExcel($data)
    {
        $dataDokumens = $data['dataDokumens'];
        $filters = $data['filters'];

        $currentDate = now()->format('d-m-Y_H-i-s');
        $fileName = 'laporan_Dokumen_' . $currentDate . '.xlsx';

        return Excel::download(
            new \App\Exports\DataDokumenLaporanExport($dataDokumens, $filters),
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
