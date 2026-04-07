<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\DataGaji;
use App\Models\Data\DataKaryawan;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\WilayahKerja;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataGajiPelaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:data-gaji-laporan')->only('index');
        $this->middleware('check.access:data-gaji-laporan,detail')->only('show');
    }

    public function index(Request $request)
    {
        // Query untuk mengambil semua data gaji dengan relasi karyawan
        // Urutan: 1) Nama karyawan ASC, 2) created_at DESC
        $query = DataGaji::with([
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'karyawan.unitKerjaRelation',
            'creator',
            'updater'
        ])
        ->join('201_dm_data_karyawan as karyawan', '205_dm_data_gaji.id_karyawan', '=', 'karyawan.id')
        ->select('205_dm_data_gaji.*')
        ->orderBy('karyawan.nama', 'asc')
        ->orderBy('205_dm_data_gaji.created_at', 'desc');

        // Apply filters
        if ($request->has('filter_status') && !empty($request->filter_status)) {
            $query->where('205_dm_data_gaji.sts_data_gaji', $request->filter_status);
        }

        if ($request->has('filter_nama') && !empty($request->filter_nama)) {
            $query->where('karyawan.nama', 'LIKE', '%' . $request->filter_nama . '%');
        }

        if ($request->has('filter_nrk') && !empty($request->filter_nrk)) {
            $query->where('karyawan.nrk', 'LIKE', '%' . $request->filter_nrk . '%');
        }

        if ($request->has('filter_nik') && !empty($request->filter_nik)) {
            $query->where('karyawan.nik', 'LIKE', '%' . $request->filter_nik . '%');
        }

        if ($request->has('filter_id_gaji') && !empty($request->filter_id_gaji)) {
            $query->where('205_dm_data_gaji.id_gaji', 'LIKE', '%' . $request->filter_id_gaji . '%');
        }

        if ($request->has('filter_departemen') && !empty($request->filter_departemen)) {
            $selectedDepartemen = Departemen::find($request->filter_departemen);
            if ($selectedDepartemen) {
                $departemenIds = Departemen::where('nama_dep', $selectedDepartemen->nama_dep)->pluck('id')->toArray();
                $query->whereIn('karyawan.departemen', $departemenIds);
            }
        }

        if ($request->has('filter_jabatan') && !empty($request->filter_jabatan)) {
            $query->where('karyawan.departemen', $request->filter_jabatan);
        }

        if ($request->has('filter_jenis_kelamin') && !empty($request->filter_jenis_kelamin)) {
            $query->where('karyawan.sex', $request->filter_jenis_kelamin);
        }

        if ($request->has('filter_wilker') && !empty($request->filter_wilker)) {
            $query->where('karyawan.wilker', $request->filter_wilker);
        }

        if ($request->has('filter_unit_kerja') && !empty($request->filter_unit_kerja)) {
            $query->where('karyawan.unit_krj', $request->filter_unit_kerja);
        }

        // Range filter untuk gaji pokok
        if ($request->has('filter_gaji_min') && !empty($request->filter_gaji_min)) {
            $query->where('205_dm_data_gaji.gj_pokok', '>=', $request->filter_gaji_min);
        }

        if ($request->has('filter_gaji_max') && !empty($request->filter_gaji_max)) {
            $query->where('205_dm_data_gaji.gj_pokok', '<=', $request->filter_gaji_max);
        }

        // Get data
        $dataGajis = $query->get();

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

        // Get master data for filters
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
                $access = $user->userAccess()->where('menu_acs', 'data-gaji-laporan')->first();
                if ($access) {
                    $userPermissions = [
                        'detail' => (bool)$access->detail_acs,
                        'download' => (bool)$access->download_acs,
                    ];
                }
            }
        }

        // Current filters
        $currentFilters = [
            'status' => $request->filter_status,
            'nama' => $request->filter_nama,
            'nrk' => $request->filter_nrk,
            'nik' => $request->filter_nik,
            'id_gaji' => $request->filter_id_gaji,
            'departemen' => $request->filter_departemen,
            'jabatan' => $request->filter_jabatan,
            'jenis_kelamin' => $request->filter_jenis_kelamin,
            'wilker' => $request->filter_wilker,
            'unit_kerja' => $request->filter_unit_kerja,
            'gaji_min' => $request->filter_gaji_min,
            'gaji_max' => $request->filter_gaji_max,
        ];

        // Calculate statistics
        $totalGaji = $dataGajis->count();
        $totalAktif = $dataGajis->where('sts_data_gaji', 'AKTIF')->count();
        $totalNonAktif = $dataGajis->where('sts_data_gaji', 'NON-AKTIF')->count();

        // Calculate unique karyawan
        $totalKaryawan = $dataGajis->pluck('id_karyawan')->unique()->count();

        // Calculate total pendapatan dan potongan
        $totalPendapatan = $dataGajis->sum('ttl_pendapatan');
        $totalPotongan = $dataGajis->sum('ttl_potongan');
        $totalGajiBersih = $dataGajis->sum('ttl_terima_gaji');

        // Calculate rata-rata gaji
        $rataRataGajiPokok = $dataGajis->avg('gj_pokok');
        $rataRataGajiBersih = $dataGajis->avg('ttl_terima_gaji');

        return view('data.data-gaji-laporan.index', compact(
            'dataGajis',
            'userPermissions',
            'statusOptions',
            'departemenOptions',
            'jabatanOptions',
            'jenisKelaminOptions',
            'wilayahKerjaOptions',
            'unitKerjaOptions',
            'currentFilters',
            'totalGaji',
            'totalAktif',
            'totalNonAktif',
            'totalKaryawan',
            'totalPendapatan',
            'totalPotongan',
            'totalGajiBersih',
            'rataRataGajiPokok',
            'rataRataGajiBersih'
        ));
    }

    public function show($id)
    {
        $dataGaji = DataGaji::with([
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'karyawan.unitKerjaRelation',
            'creator',
            'updater'
        ])->findOrFail($id);

        // Get all salaries for this employee
        $allGajis = DataGaji::where('id_karyawan', $dataGaji->id_karyawan)
            ->with(['creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('data.data-gaji-laporan.show', compact('dataGaji', 'allGajis'));
    }

    private function getFilteredData($filters)
    {
        $query = DataGaji::with([
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'karyawan.unitKerjaRelation'
        ])
        ->join('201_dm_data_karyawan as karyawan', '205_dm_data_gaji.id_karyawan', '=', 'karyawan.id')
        ->select('205_dm_data_gaji.*')
        ->orderBy('karyawan.nama', 'asc')
        ->orderBy('205_dm_data_gaji.created_at', 'desc');

        // Apply same filters as index
        if (!empty($filters['filter_status'])) {
            $query->where('205_dm_data_gaji.sts_data_gaji', $filters['filter_status']);
        }

        if (!empty($filters['filter_nama'])) {
            $query->where('karyawan.nama', 'LIKE', '%' . $filters['filter_nama'] . '%');
        }

        if (!empty($filters['filter_nrk'])) {
            $query->where('karyawan.nrk', 'LIKE', '%' . $filters['filter_nrk'] . '%');
        }

        if (!empty($filters['filter_nik'])) {
            $query->where('karyawan.nik', 'LIKE', '%' . $filters['filter_nik'] . '%');
        }

        if (!empty($filters['filter_id_gaji'])) {
            $query->where('205_dm_data_gaji.id_gaji', 'LIKE', '%' . $filters['filter_id_gaji'] . '%');
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

        if (!empty($filters['filter_gaji_min'])) {
            $query->where('205_dm_data_gaji.gj_pokok', '>=', $filters['filter_gaji_min']);
        }

        if (!empty($filters['filter_gaji_max'])) {
            $query->where('205_dm_data_gaji.gj_pokok', '<=', $filters['filter_gaji_max']);
        }

        $dataGajis = $query->get();

        return [
            'dataGajis' => $dataGajis,
            'filters' => $filters
        ];
    }

    private function exportExcel($data)
    {
        $dataGajis = $data['dataGajis'];
        $filters = $data['filters'];

        $currentDate = now()->format('d-m-Y_H-i-s');
        $fileName = 'Pelaporan_Gaji_' . $currentDate . '.xlsx';

        return Excel::download(
            new \App\Exports\DataGajiPelaporanExport($dataGajis, $filters),
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