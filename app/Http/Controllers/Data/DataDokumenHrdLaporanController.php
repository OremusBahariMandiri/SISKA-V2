<?php

namespace App\Http\Controllers\Data;

use App\Helpers\FilterHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\DataDokumenHrd;
use App\Models\DataMaster\DokumenHrd;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataDokumenHrdLaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:data-dokumen-hrd-laporan')->only('index');
        $this->middleware('check.access:data-dokumen-hrd-laporan,detail')->only('show');
    }

    public function index(Request $request)
    {
        // Query untuk mengambil semua dokumen dengan relasi
        // Urutan: 1) kode_dok_hrd ASC, 2) created_at DESC
        $query = DataDokumenHrd::with([
            'dokumenHrd',
            'perusahaan',
            'creator',
            'updater'
        ])
            ->join('106_dm_dok_hrd as dok_hrd', '206_dm_data_dok_hrd.id_dokumen_hrd', '=', 'dok_hrd.id')
            ->select('206_dm_data_dok_hrd.*')
            ->orderBy('dok_hrd.kode_dok_hrd', 'asc')
            ->orderBy('206_dm_data_dok_hrd.created_at', 'desc');

        // Apply filters
        if ($request->has('filter_status') && !empty($request->filter_status)) {
            $query->where('206_dm_data_dok_hrd.sts_dok', $request->filter_status);
        }

        if ($request->has('filter_kategori') && !empty($request->filter_kategori)) {
            $query->where('dok_hrd.ktg_dok_hrd', $request->filter_kategori);
        }

        if ($request->has('filter_jenis') && !empty($request->filter_jenis)) {
            $query->where('dok_hrd.jns_dok_hrd', $request->filter_jenis);
        }

        if ($request->has('filter_no_dokumen') && !empty($request->filter_no_dokumen)) {
            $query->where('206_dm_data_dok_hrd.no_dok_hrd', 'LIKE', '%' . $request->filter_no_dokumen . '%');
        }

        // Get data
        $dataDokumenHrds = $query->get();

        // Calculate expired and expiring documents count
        $today = now();

        $expiredDocumentsCount = $dataDokumenHrds->filter(function ($dokumen) use ($today) {
            return $dokumen->tgl_akr_dok &&
                $dokumen->tgl_akr_dok < $today &&
                $dokumen->sts_dok == 'AKTIF';
        })->count();

        $expiringDocumentsCount = $dataDokumenHrds->filter(function ($dokumen) use ($today) {
            return $dokumen->tgl_prt_dok &&
                $dokumen->tgl_prt_dok <= $today->addDays(30) &&
                $dokumen->tgl_akr_dok >= $today &&
                $dokumen->sts_dok == 'AKTIF';
        })->count();

        // Get master data for filters
        $dokumenTypes = DokumenHrd::orderBy('kode_dok_hrd', 'asc')->get();

        // Status options
        $statusOptions = [
            'AKTIF' => 'AKTIF',
            'NON-AKTIF' => 'NON-AKTIF',
        ];

        // Get unique categories - sorted by kode_dok_hrd
        $kategoriOptions = DokumenHrd::select('ktg_dok_hrd', DB::raw('MIN(kode_dok_hrd) as min_kode'))
            ->whereNotNull('ktg_dok_hrd')
            ->where('ktg_dok_hrd', '!=', '')
            ->groupBy('ktg_dok_hrd')
            ->orderBy('min_kode', 'asc')
            ->pluck('ktg_dok_hrd', 'ktg_dok_hrd');

        // Get unique jenis - sorted by kode_dok_hrd
        $jenisOptions = DokumenHrd::select('jns_dok_hrd', DB::raw('MIN(kode_dok_hrd) as min_kode'))
            ->whereNotNull('jns_dok_hrd')
            ->where('jns_dok_hrd', '!=', '')
            ->groupBy('jns_dok_hrd')
            ->orderBy('min_kode', 'asc')
            ->pluck('jns_dok_hrd', 'jns_dok_hrd');

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
                $access = $user->userAccess()->where('menu_acs', 'data-dokumen-hrd-laporan')->first();
                if ($access) {
                    $userPermissions = [
                        'detail' => (bool)$access->detail_acs,
                        'download' => (bool)$access->download_acs,
                    ];
                }
            }
        }

        // Store current filters
        $currentFilters = [
            'status' => $request->filter_status ?? '',
            'kategori' => $request->filter_kategori ?? '',
            'jenis' => $request->filter_jenis ?? '',
            'no_dokumen' => $request->filter_no_dokumen ?? '',
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
        $totalDokumen = $dataDokumenHrds->count();
        $totalAktif = $dataDokumenHrds->where('sts_dok', 'AKTIF')->count();
        $totalNonAktif = $dataDokumenHrds->where('sts_dok', 'NON-AKTIF')->count();

        return view('data.data-dokumen-hrd-laporan.index', compact(
            'dataDokumenHrds',
            'userPermissions',
            'dokumenTypes',
            'statusOptions',
            'kategoriOptions',
            'jenisOptions',
            'currentFilters',
            'totalDokumen',
            'totalAktif',
            'totalNonAktif',
            'expiredDocumentsCount',
            'expiringDocumentsCount'
        ));
    }

    public function show($id)
    {
        $dataDokumenHrd = DataDokumenHrd::with([
            'dokumenHrd',
            'perusahaan',
            'creator',
            'updater'
        ])->findOrFail($id);

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
                $access = $user->userAccess()->where('menu_acs', 'data-dokumen-hrd-laporan')->first();
                if ($access) {
                    $userPermissions = [
                        'detail' => (bool)$access->detail_acs,
                        'download' => (bool)$access->download_acs,
                    ];
                }
            }
        }

        return view('data.data-dokumen-hrd-laporan.show', compact('dataDokumenHrd', 'userPermissions'));
    }

    private function getFilteredData($filters)
    {
        $query = DataDokumenHrd::with([
            'dokumenHrd',
            'perusahaan',
            'creator',
            'updater'
        ])
            ->join('106_dm_dok_hrd as dok_hrd', '206_dm_data_dok_hrd.id_dokumen_hrd', '=', 'dok_hrd.id')
            ->select('206_dm_data_dok_hrd.*')
            ->orderBy('dok_hrd.kode_dok_hrd', 'asc')
            ->orderBy('206_dm_data_dok_hrd.created_at', 'desc');

        // Apply same filters as index
        if (!empty($filters['filter_status'])) {
            $query->where('206_dm_data_dok_hrd.sts_dok', $filters['filter_status']);
        }

        if (!empty($filters['filter_kategori'])) {
            $query->where('dok_hrd.ktg_dok_hrd', $filters['filter_kategori']);
        }

        if (!empty($filters['filter_jenis'])) {
            $query->where('dok_hrd.jns_dok_hrd', $filters['filter_jenis']);
        }

        if (!empty($filters['filter_no_dokumen'])) {
            $query->where('206_dm_data_dok_hrd.no_dok_hrd', 'LIKE', '%' . $filters['filter_no_dokumen'] . '%');
        }

        $dataDokumenHrds = $query->get();

        return [
            'dataDokumenHrds' => $dataDokumenHrds,
            'filters' => $filters
        ];
    }

    private function exportExcel($data)
    {
        $dataDokumenHrds = $data['dataDokumenHrds'];
        $filters = $data['filters'];

        $currentDate = now()->format('d-m-Y_H-i-s');
        $fileName = 'laporan_dokumen_hrd_' . $currentDate . '.xlsx';

        return Excel::download(
            new \App\Exports\DataDokumenHrdLaporanExport($dataDokumenHrds, $filters),
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