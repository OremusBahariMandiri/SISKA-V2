<?php

namespace App\Http\Controllers\Data;

use App\Helpers\FilterHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\DataKontrak;
use App\Models\Data\DataKaryawan;
use App\Models\DataMaster\Perusahaan;
use App\Models\DataMaster\WilayahKerja;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\KontrakKerja;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataKontrakPelaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:data-kontrak-laporan')->only('index');
        $this->middleware('check.access:data-kontrak-laporan,detail')->only('show');
    }

    public function index(Request $request)
    {
        // Query untuk mengambil semua kontrak dengan relasi karyawan
        // Urutan: 1) Nama karyawan ASC, 2) tgl_awl_ktr DESC
        $query = DataKontrak::with([
            'karyawan.perusahaanRelation',
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'karyawan.unitKerjaRelation',
            'kontrakKerja',
            'perusahaan',
            'creator',
            'updater'
        ])
            ->join('201_dm_data_karyawan as karyawan', '202_dm_data_kontrak.id_data_kry', '=', 'karyawan.id')
            ->select('202_dm_data_kontrak.*')
            ->orderBy('karyawan.nama', 'asc')
            ->orderBy('202_dm_data_kontrak.tgl_awl_ktr', 'desc');

        // Apply filters
        if ($request->has('filter_status') && !empty($request->filter_status)) {
            $query->where('sts_srt_ktr', $request->filter_status);
        }

        // Filter by Jabatan (specific position)
        if ($request->has('filter_jabatan') && !empty($request->filter_jabatan)) {
            $query->where('id_departemen', $request->filter_jabatan);
        }

        // Filter by Perusahaan
        if ($request->has('filter_perusahaan') && !empty($request->filter_perusahaan)) {
            $query->where('id_prsh', $request->filter_perusahaan);
        }

        // Filter by Jenis Kontrak
        if ($request->has('filter_kontrak') && !empty($request->filter_kontrak)) {
            $query->where('id_ktr', $request->filter_kontrak);
        }

        // Filter by Wilayah Kerja (wilker field stores STRING)
        if ($request->has('filter_wilker') && !empty($request->filter_wilker)) {
            $query->whereHas('wilayahKerja', function ($q) use ($request) {
                $q->where('wilayah_krj', $request->filter_wilker);
            });
        }

        // Filter by Jenis Kelamin
        if ($request->has('filter_jenis_kelamin') && !empty($request->filter_jenis_kelamin)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('sex', $request->filter_jenis_kelamin);
            });
        }

        // Filter by Unit Kerja (area_krj)
        if ($request->has('filter_unit_kerja') && !empty($request->filter_unit_kerja)) {
            $query->where('id_wilker', $request->filter_unit_kerja);
        }

        // Filter by Departemen (grouped by nama_dep)
        if ($request->has('filter_departemen') && !empty($request->filter_departemen)) {
            $selectedDepartemen = Departemen::find($request->filter_departemen);
            if ($selectedDepartemen) {
                $departemenIds = Departemen::where('nama_dep', $selectedDepartemen->nama_dep)->pluck('id')->toArray();
                $query->whereIn('id_departemen', $departemenIds);
            }
        }

        // Filter by Nama Karyawan
        if ($request->has('filter_nama') && !empty($request->filter_nama)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('nama', 'LIKE', '%' . $request->filter_nama . '%');
            });
        }

        // Filter by NRK
        if ($request->has('filter_nrk') && !empty($request->filter_nrk)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('nrk', 'LIKE', '%' . $request->filter_nrk . '%');
            });
        }

        // Get data
        $dataKontraks = $query->orderBy('created_at', 'desc')->get();

        // Get master data for filters
        $perusahaans = Perusahaan::orderBy('nama_prs1', 'asc')->get();
        $wilayahKerjas = WilayahKerja::orderBy('wilayah_krj', 'asc')->get();
        $kontrakTypes = KontrakKerja::orderBy('kode_ktr', 'asc')->get();

        // Get unique kategori kontrak
        $kategoriKontrakOptions = KontrakKerja::select('nama_ktr')
            ->whereNotNull('nama_ktr')
            ->where('nama_ktr', '!=', '')
            ->groupBy('nama_ktr')
            ->orderBy('nama_ktr', 'asc')
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
                $access = $user->userAccess()->where('menu_acs', 'data-kontrak-laporan')->first();
                if ($access) {
                    $userPermissions = [
                        'detail' => (bool)$access->detail_acs,
                        'download' => (bool)$access->download_acs,
                    ];
                }
            }
        }

        $filterOptions = FilterHelper::getFilterDataOptions();

        // Current filters
        // ===== STORE CURRENT FILTERS =====
        $currentFilters = [
            'status' => $request->filter_status ?? '',
            'jabatan' => $request->filter_jabatan ?? '',
            'perusahaan' => $request->filter_perusahaan ?? '',
            'kontrak' => $request->filter_kontrak ?? '',           // ← UBAH dari filter_jenis_kontrak
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
        $totalKontrak = $dataKontraks->count();
        $totalAktif = $dataKontraks->where('sts_srt_ktr', 'AKTIF')->count();
        $totalNonAktif = $dataKontraks->where('sts_srt_ktr', 'NON-AKTIF')->count();

        // Calculate unique karyawan and perusahaan
        $totalKaryawan = $dataKontraks->pluck('id_data_kry')->unique()->count();
        $totalPerusahaan = $dataKontraks->filter(function ($kontrak) {
            return $kontrak->karyawan && $kontrak->karyawan->perusahaan;
        })->pluck('karyawan.perusahaan')->unique()->count();

        // Calculate expired and expiring contracts
        $today = now();
        $expiredContractsCount = $dataKontraks->filter(function ($kontrak) use ($today) {
            return $kontrak->tgl_akhir_ktr &&
                $kontrak->tgl_akhir_ktr < $today &&
                $kontrak->sts_srt_ktr == 'AKTIF';
        })->count();

        $expiringContractsCount = $dataKontraks->filter(function ($kontrak) use ($today) {
            return $kontrak->tgl_pgt_ktr &&
                $kontrak->tgl_pgt_ktr <= $today &&
                $kontrak->tgl_akhir_ktr >= $today &&
                $kontrak->sts_srt_ktr == 'AKTIF';
        })->count();

        return view('data.data-kontrak-laporan.index', compact(
            'dataKontraks',
            'userPermissions',
            'perusahaans',
            'wilayahKerjas',
            'unitKerjaOptions',
            'kontrakTypes',
            'kategoriKontrakOptions',
            'statusOptions',
            'departemenOptions',
            'jabatanOptions',
            'jenisKelaminOptions',
            'wilayahKerjaOptions',
            'currentFilters',
            'filterOptions',
            'totalKontrak',
            'totalAktif',
            'totalNonAktif',
            'totalKaryawan',
            'totalPerusahaan',
            'expiredContractsCount',
            'expiringContractsCount'
        ));
    }

    public function show($id)
    {
        $dataKontrak = DataKontrak::with([
            'karyawan.perusahaanRelation',
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'karyawan.unitKerjaRelation',
            'kontrakKerja',
            'perusahaan',
            'creator',
            'updater'
        ])->findOrFail($id);

        return view('data.data-kontrak-laporan.show', compact('dataKontrak'));
    }

    private function getFilteredData($filters)
    {
        $query = DataKontrak::with([
            'karyawan.perusahaanRelation',
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'karyawan.unitKerjaRelation',
            'kontrakKerja'
        ])
            ->join('201_dm_data_karyawan as karyawan', '202_dm_data_kontrak.id_data_kry', '=', 'karyawan.id')
            ->select('202_dm_data_kontrak.*')
            ->orderBy('karyawan.nama', 'asc')
            ->orderBy('202_dm_data_kontrak.tgl_awl_ktr', 'desc');

        // Apply same filters as index
        if (!empty($filters['filter_status'])) {
            $query->where('202_dm_data_kontrak.sts_srt_ktr', $filters['filter_status']);
        }

        if (!empty($filters['filter_jenis_kontrak'])) {
            $query->where('202_dm_data_kontrak.id_ktr', $filters['filter_jenis_kontrak']);
        }

        if (!empty($filters['filter_kategori_kontrak'])) {
            $query->where('202_dm_data_kontrak.ktg_ktk', $filters['filter_kategori_kontrak']);
        }

        if (!empty($filters['filter_nama'])) {
            $query->where('karyawan.nama', 'LIKE', '%' . $filters['filter_nama'] . '%');
        }

        if (!empty($filters['filter_nrk'])) {
            $query->where('karyawan.nrk', 'LIKE', '%' . $filters['filter_nrk'] . '%');
        }

        if (!empty($filters['filter_no_kontrak'])) {
            $query->where('202_dm_data_kontrak.no_srt_ktr', 'LIKE', '%' . $filters['filter_no_kontrak'] . '%');
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

        $dataKontraks = $query->get();

        return [
            'dataKontraks' => $dataKontraks,
            'filters' => $filters
        ];
    }

    private function exportExcel($data)
    {
        $dataKontraks = $data['dataKontraks'];
        $filters = $data['filters'];

        $currentDate = now()->format('d-m-Y_H-i-s');
        $fileName = 'Pelaporan_Kontrak_' . $currentDate . '.xlsx';

        return Excel::download(
            new \App\Exports\DataKontrakPelaporanExport($dataKontraks, $filters),
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
