<?php

namespace App\Http\Controllers\Data;

use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Data\DataDokumen;
use App\Models\Data\DataKaryawan;
use App\Models\DataMaster\DokumenKaryawan;
use Illuminate\Support\Facades\Storage;
use App\Exports\DataDokumenExport;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\KontrakKerja;
use App\Models\DataMaster\Perusahaan;
use App\Models\DataMaster\WilayahKerja;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class DataDokumenController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:data-dokumen')->only('index');
        $this->middleware('check.access:data-dokumen,detail')->only('show');
        $this->middleware('check.access:data-dokumen,tambah')->only('create', 'store');
        $this->middleware('check.access:data-dokumen,ubah')->only('edit', 'update');
        $this->middleware('check.access:data-dokumen,hapus')->only('destroy');
    }

    public function index(Request $request)
    {
        // Initialize query with relationships
        $query = DataDokumen::with([
            'karyawan',
            'dokumenKaryawan',
            'creator',
            'updater'
        ]);


        // Get latest document per employee first
        $query->whereIn('id', function ($subquery) {
            $subquery->select(DB::raw('MAX(id)'))
                ->from('203_dm_data_dokumen')
                ->groupBy('id_data_kry');
        });

        // Apply filters if they exist

        // Filter by Status Dokumen
        if ($request->has('filter_status') && !empty($request->filter_status)) {
            $query->where('sts_dok', $request->filter_status);
        }

        // Filter by Jenis Dokumen
        if ($request->has('filter_jenis_dokumen') && !empty($request->filter_jenis_dokumen)) {
            $query->where('id_dokumen', $request->filter_jenis_dokumen);
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

        // Filter by No Dokumen
        if ($request->has('filter_no_dokumen') && !empty($request->filter_no_dokumen)) {
            $query->where('no_dok', 'LIKE', '%' . $request->filter_no_dokumen . '%');
        }

        // Filter by Departemen (grouped by nama_dep)
        if ($request->has('filter_departemen') && !empty($request->filter_departemen)) {
            $selectedDepartemen = Departemen::find($request->filter_departemen);
            if ($selectedDepartemen) {
                $departemenIds = Departemen::where('nama_dep', $selectedDepartemen->nama_dep)->pluck('id')->toArray();
                $query->whereHas('karyawan', function ($q) use ($departemenIds) {
                    $q->whereIn('departemen', $departemenIds);
                });
            }
        }

        // Filter by Jabatan (specific position)
        if ($request->has('filter_jabatan') && !empty($request->filter_jabatan)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('departemen', $request->filter_jabatan);
            });
        }

        // Filter by Perusahaan
        if ($request->has('filter_perusahaan') && !empty($request->filter_perusahaan)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('perusahaan', $request->filter_perusahaan);
            });
        }

        // Filter by Jenis Kelamin
        if ($request->has('filter_jenis_kelamin') && !empty($request->filter_jenis_kelamin)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('sex', $request->filter_jenis_kelamin);
            });
        }

        // Filter by Wilayah Kerja (wilker field stores ID that references wilayah_krj STRING)
        if ($request->has('filter_wilker') && !empty($request->filter_wilker)) {
            $query->whereHas('karyawan.wilayahKerjaRelation', function ($q) use ($request) {
                $q->where('wilayah_krj', $request->filter_wilker);
            });
        }

        // Filter by Unit Kerja (area_krj)
        if ($request->has('filter_unit_kerja') && !empty($request->filter_unit_kerja)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('unit_krj', $request->filter_unit_kerja);
            });
        }

        // Get filtered data
        $dataDokumens = $query->orderBy('created_at', 'desc')->get();

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

        // Calculate document counts per employee
        $documentCounts = [];
        foreach ($dataDokumens as $dokumen) {
            $employeeId = $dokumen->id_data_kry;

            if (!isset($documentCounts[$employeeId])) {
                $totalDocuments = DataDokumen::where('id_data_kry', $employeeId)->count();
                $activeDocuments = DataDokumen::where('id_data_kry', $employeeId)
                    ->where('sts_dok', 'AKTIF')
                    ->count();
                $nonActiveDocuments = DataDokumen::where('id_data_kry', $employeeId)
                    ->where('sts_dok', '!=', 'AKTIF')
                    ->count();

                $documentCounts[$employeeId] = [
                    'total' => $totalDocuments,
                    'active' => $activeDocuments,
                    'non_active' => $nonActiveDocuments
                ];
            }
        }

        // Calculate active documents data
        $activeDocumentsData = [];
        foreach ($dataDokumens as $dokumen) {
            $employeeId = $dokumen->id_data_kry;

            if ($dokumen->sts_dok === 'AKTIF' && $dokumen->tgl_akr_dok) {
                $expiryDate = \Carbon\Carbon::parse($dokumen->tgl_akr_dok);
                $reminderDate = $dokumen->tgl_pgt_dok ? \Carbon\Carbon::parse($dokumen->tgl_pgt_dok) : null;
                $today = \Carbon\Carbon::now()->startOf('day');

                $daysUntilExpiry = $today->diffInDays($expiryDate, false);
                $daysUntilReminder = $reminderDate ? $today->diffInDays($reminderDate, false) : null;

                $priority = 4;
                $status = 'normal';
                $badgeClass = 'bg-success';
                $warningText = 'Aman';

                if ($reminderDate && $daysUntilReminder <= 0) {
                    $priority = 1;
                    $status = 'expired';
                    $badgeClass = 'bg-danger';
                    $warningText = $daysUntilReminder == 0 ? 'Hari ini' : 'Terlambat ' . abs($daysUntilReminder) . ' hari';
                } elseif ($daysUntilExpiry < 0) {
                    $priority = 1;
                    $status = 'expired';
                    $badgeClass = 'bg-danger';
                    $warningText = 'Terlambat ' . abs($daysUntilExpiry) . ' hari';
                } elseif ($reminderDate && $daysUntilReminder <= 7) {
                    $priority = 2;
                    $status = 'urgent';
                    $badgeClass = 'bg-warning text-dark';
                    $warningText = $daysUntilReminder . ' hari lagi';
                } elseif ($daysUntilExpiry <= 7) {
                    $priority = 2;
                    $status = 'urgent';
                    $badgeClass = 'bg-warning text-dark';
                    $warningText = $daysUntilExpiry . ' hari lagi';
                } elseif ($reminderDate && $daysUntilReminder <= 30) {
                    $priority = 3;
                    $status = 'warning';
                    $badgeClass = 'bg-info';
                    $warningText = $daysUntilReminder . ' hari lagi';
                } elseif ($daysUntilExpiry <= 30) {
                    $priority = 3;
                    $status = 'warning';
                    $badgeClass = 'bg-info';
                    $warningText = $daysUntilExpiry . ' hari lagi';
                } else {
                    $warningText = $reminderDate ? $daysUntilReminder . ' hari lagi' : $daysUntilExpiry . ' hari lagi';
                }

                $activeDocumentsData[$employeeId] = [
                    'document_id' => $dokumen->id,
                    'expiry_date' => $dokumen->tgl_akr_dok,
                    'expiry_date_formatted' => $expiryDate->format('d-m-Y'),
                    'reminder_date' => $dokumen->tgl_pgt_dok,
                    'reminder_date_formatted' => $reminderDate ? $reminderDate->format('d-m-Y') : null,
                    'days_until_expiry' => $daysUntilExpiry,
                    'days_until_reminder' => $daysUntilReminder,
                    'is_expired' => $daysUntilExpiry < 0,
                    'is_expiring_soon' => $daysUntilExpiry >= 0 && $daysUntilExpiry <= 30,
                    'is_urgent' => $daysUntilExpiry >= 0 && $daysUntilExpiry <= 7,
                    'document_number' => $dokumen->no_dok,
                    'document_type' => optional($dokumen->dokumenKaryawan)->nama_dok ?? 'N/A',
                    'priority' => $priority,
                    'status' => $status,
                    'badge_class' => $badgeClass,
                    'warning_text' => $warningText,
                    'has_active_document' => true
                ];
            } else {
                $activeDocumentsData[$employeeId] = [
                    'has_active_document' => false,
                    'priority' => 5,
                    'status' => 'no_active',
                    'badge_class' => 'bg-secondary',
                    'warning_text' => 'Tidak ada dokumen aktif'
                ];
            }
        }

        // Sort documents by priority
        $dataDokumens = $dataDokumens->sort(function ($a, $b) use ($activeDocumentsData) {
            $priorityA = $activeDocumentsData[$a->id_data_kry]['priority'] ?? 5;
            $priorityB = $activeDocumentsData[$b->id_data_kry]['priority'] ?? 5;
            return $priorityA <=> $priorityB;
        })->values();

        // Calculate expired and expiring documents count
        $today = \Carbon\Carbon::now()->startOf('day')->format('Y-m-d');

        $expiredDocumentsCount = DataDokumen::uniqueEmployees()
            ->where('sts_dok', 'AKTIF')
            ->whereNotNull('tgl_pgt_dok')
            ->whereRaw("STR_TO_DATE(tgl_pgt_dok, '%Y-%m-%d') <= ?", [$today])
            ->count();

        $expiringDocumentsCount = DataDokumen::uniqueEmployees()
            ->where('sts_dok', 'AKTIF')
            ->whereNotNull('tgl_pgt_dok')
            ->whereRaw("STR_TO_DATE(tgl_pgt_dok, '%Y-%m-%d') > ?", [$today])
            ->whereRaw("STR_TO_DATE(tgl_pgt_dok, '%Y-%m-%d') <= DATE_ADD(?, INTERVAL 30 DAY)", [$today])
            ->count();

        // Get master data for filter dropdowns
        $dokumenTypes = DokumenKaryawan::orderBy('kode_dok_kry', 'asc')->get();
        $perusahaans = Perusahaan::orderBy('nama_prs1', 'asc')->get();
        $wilayahKerjas = WilayahKerja::orderBy('wilayah_krj', 'asc')->get();

        // Status options
        $statusOptions = [
            'AKTIF' => 'AKTIF',
            'NON-AKTIF' => 'NON-AKTIF',
        ];

        // Gender options - SAMA DENGAN DATA KONTRAK
        $jenisKelaminOptions = [
            'LAKI-LAKI' => 'Laki-laki',
            'PEREMPUAN' => 'Perempuan'
        ];

        // Get unique departemen names for filter dropdown - SAMA DENGAN DATA KONTRAK
        $departemenOptions = Departemen::select('nama_dep', 'singkatan_dep', DB::raw('MIN(id) as id'), DB::raw('MIN(CAST(kode_dep AS UNSIGNED)) as min_kode_dep'))
            ->groupBy('nama_dep', 'singkatan_dep')
            ->orderBy('min_kode_dep', 'asc')
            ->get();

        // Get all jabatan/positions for filter dropdown - SAMA DENGAN DATA KONTRAK
        $jabatanOptions = Departemen::sortByCode()->get(['id', 'kode_dep', 'nama_dep', 'nama_jbt', 'singkatan_jbt']);

        // Get UNIQUE wilayah_krj for filter dropdown - SAMA DENGAN DATA KONTRAK
        $wilayahKerjaOptions = WilayahKerja::select('wilayah_krj')
            ->groupBy('wilayah_krj')
            ->orderBy('wilayah_krj', 'asc')
            ->get();

        // Unit Kerja options - SAMA DENGAN DATA KONTRAK
        $unitKerjaOptions = WilayahKerja::select('id', 'area_krj', 'kode_wk')
            ->orderBy('area_krj')
            ->get();

        // Get user permissions
        $userPermissions = [];
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->is_admin) {
                $userPermissions = [
                    'tambah' => true,
                    'ubah' => true,
                    'hapus' => true,
                    'download' => true,
                    'detail' => true,
                    'monitoring' => true,
                ];
            } else {
                $access = $user->userAccess()->where('menu_acs', 'data-dokumen')->first();
                if ($access) {
                    $userPermissions = [
                        'tambah' => (bool)$access->tambah_acs,
                        'ubah' => (bool)$access->ubah_acs,
                        'hapus' => (bool)$access->hapus_acs,
                        'download' => (bool)$access->download_acs,
                        'detail' => (bool)$access->detail_acs,
                        'monitoring' => (bool)$access->monitoring_acs,
                    ];
                }
            }
        }

        // Store current filters for view
        $currentFilters = [
            'status' => $request->filter_status ?? '',
            'jenis_dokumen' => $request->filter_jenis_dokumen ?? '',
            'nama' => $request->filter_nama ?? '',
            'nrk' => $request->filter_nrk ?? '',
            'no_dokumen' => $request->filter_no_dokumen ?? '',
            'departemen' => $request->filter_departemen ?? '',
            'jabatan' => $request->filter_jabatan ?? '',
            'perusahaan' => $request->filter_perusahaan ?? '',
            'jenis_kelamin' => $request->filter_jenis_kelamin ?? '',
            'wilker' => $request->filter_wilker ?? '',
            'unit_kerja' => $request->filter_unit_kerja ?? '',
        ];


        // Get DataKaryawan collection for summary statistics
        $dataKaryawans = collect();
        foreach ($dataDokumens as $dokumen) {
            if ($dokumen->karyawan) {
                $dataKaryawans->push($dokumen->karyawan);
            }
        }

        return view('data.data-dokumen.index', compact(
            'dataDokumens',
            'activeDocumentsData',
            'documentCounts',
            'userPermissions',
            'dokumenTypes',
            'perusahaans',
            'wilayahKerjas',
            'statusOptions',
            'expiredDocumentsCount',
            'expiringDocumentsCount',
            'jenisKelaminOptions',
            'departemenOptions',
            'jabatanOptions',
            'wilayahKerjaOptions',
            'unitKerjaOptions',
            'currentFilters',
            'dataKaryawans'
        ));
    }

    public function create()
    {
        // Generate automatic ID for main record
        $newId = $this->generateId('203', '203_dm_data_dokumen');

        // Get master data for dropdowns
        $karyawans = DataKaryawan::where('sts_kry', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->get();
        $dokumenTypes = DokumenKaryawan::orderBy('kode_dok_kry', 'asc')->get();

        // Tambahkan master data untuk tab Kontrak Kerja & Jenjang Karir
        $perusahaans = Perusahaan::orderBy('nama_prs1', 'asc')->get();
        $kontrakTypes = KontrakKerja::orderBy('kode_ktr', 'asc')->get();
        $departemens = Departemen::sortByCode()->get();
        $wilayahKerjas = WilayahKerja::orderBy('wilayah_krj', 'asc')->get();

        return view('data.data-dokumen.create', compact(
            'newId',
            'karyawans',
            'dokumenTypes',
            'perusahaans',
            'kontrakTypes',
            'departemens',
            'wilayahKerjas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Employee and document basic info
            'id_data_kry' => 'required|exists:201_dm_data_karyawan,id',
            'id_dokumen' => 'required|exists:105_dm_dok_kry,id',

            // Document details
            'kode_dok_kry' => 'nullable|string|max:50',
            'no_dok' => 'nullable|string|max:100',
            'tgl_ttd' => 'nullable|date',
            'jns_msb_dok' => 'nullable|string|max:50',
            'tgl_akr_dok' => 'nullable|date|after_or_equal:tgl_ttd',
            'msb_dok' => 'nullable|integer|min:1',
            'tgl_pgt_dok' => 'nullable|date|before_or_equal:tgl_akr_dok',
            'durasi_pgt' => 'nullable|integer|min:1',
            'ket_dok' => 'nullable|string|max:255',
            'ctt_dok' => 'nullable|string',
            'sts_dok' => 'required|in:AKTIF,NON-AKTIF,EXPIRED,PENDING',
            'tgl_dok_na' => 'nullable|date',
            'ket_dok_na' => 'nullable|string|max:255',

            // File upload
            'file_dok' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Generate IDs
        $id_kode = empty($request->id_kode)
            ? $this->generateId('203', '203_dm_data_dokumen')
            : $request->id_kode;

        // Generate unique id_dok_kry (auto increment)
        $id_dok_kry = $this->generateAutoIncrement();

        // Handle file upload
        $fileDok = null;
        if ($request->hasFile('file_dok')) {
            $file = $request->file('file_dok');
            $fileName = time() . '_' . $id_kode . '_' . $file->getClientOriginalName();
            $file->storeAs('public/dokumen/files', $fileName);
            $fileDok = 'dokumen/files/' . $fileName;
        }

        // Calculate masa berlaku dokumen (msb_dok) in MONTHS if dates provided
        $msb_dok = $request->msb_dok; // Use provided value if exists
        if (!$msb_dok && $request->tgl_ttd && $request->tgl_akr_dok) {
            $signatureDate = \Carbon\Carbon::parse($request->tgl_ttd);
            $expiryDate = \Carbon\Carbon::parse($request->tgl_akr_dok);
            $msb_dok = $signatureDate->diffInMonths($expiryDate);
        }

        // Calculate durasi peringatan (durasi_pgt) in DAYS from tgl_pgt_dok to tgl_akr_dok
        $durasi_pgt = $request->durasi_pgt; // Use provided value if exists
        if (!$durasi_pgt && $request->tgl_pgt_dok && $request->tgl_akr_dok) {
            $reminderDate = \Carbon\Carbon::parse($request->tgl_pgt_dok);
            $expiryDate = \Carbon\Carbon::parse($request->tgl_akr_dok);
            $durasi_pgt = $reminderDate->diffInDays($expiryDate);
        }

        $dataDokumen = DataDokumen::create([
            'id_kode' => $id_kode,
            'id_dok_kry' => $id_dok_kry,
            'id_data_kry' => $request->id_data_kry,
            'id_dokumen' => $request->id_dokumen,
            'kode_dok_kry' => $request->kode_dok_kry,
            'no_dok' => $request->no_dok,
            'tgl_ttd' => $request->tgl_ttd,
            'jns_msb_dok' => $request->jns_msb_dok,
            'tgl_akr_dok' => $request->tgl_akr_dok,
            'msb_dok' => $msb_dok,
            'tgl_pgt_dok' => $request->tgl_pgt_dok,
            'durasi_pgt' => $durasi_pgt,
            'file_dok' => $fileDok,
            'sts_dok' => $request->sts_dok,
            'ket_dok' => $request->ket_dok,
            'ctt_dok' => $request->ctt_dok,
            'tgl_dok_na' => $request->tgl_dok_na,
            'ket_dok_na' => $request->ket_dok_na,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('data-dokumen.index')
            ->with('success', 'Data dokumen berhasil dibuat.');
    }

    public function show($id)
    {
        $dataDokumen = DataDokumen::with([
            'karyawan',
            'dokumenKaryawan',
            'creator',
            'updater'
        ])->findOrFail($id);

        // Get all related data for this employee
        $allDocuments = $dataDokumen->allEmployeeDocuments();

        return view('data.data-dokumen.show', compact(
            'dataDokumen',
            'allDocuments'
        ));
    }

    public function edit($id)
    {
        $dataDokumen = DataDokumen::with([
            'karyawan',
            'dokumenKaryawan',
            'creator',
            'updater'
        ])->findOrFail($id);

        // Get all related data for this employee - SORTED BY kode_dok_kry
        $allDocuments = DataDokumen::where('id_data_kry', $dataDokumen->id_data_kry)
            ->with([
                'dokumenKaryawan',
                'creator',
                'updater'
            ])
            ->leftJoin('105_dm_dok_kry', '203_dm_data_dokumen.id_dokumen', '=', '105_dm_dok_kry.id')
            ->select('203_dm_data_dokumen.*')
            ->orderBy('105_dm_dok_kry.kode_dok_kry', 'asc')
            ->get();

        // Get master data for dropdowns
        $dokumenTypes = DokumenKaryawan::orderBy('kode_dok_kry', 'asc')->get();

        return view('data.data-dokumen.edit', compact(
            'dataDokumen',
            'allDocuments',
            'dokumenTypes'
        ));
    }

    public function update(Request $request, $id)
    {
        $dataDokumen = DataDokumen::findOrFail($id);

        $request->validate([
            'id_data_kry' => 'required|exists:201_dm_data_karyawan,id',
            'id_dokumen' => 'required|exists:105_dm_dok_kry,id',
            'kode_dok_kry' => 'nullable|string|max:50',
            'no_dok' => 'nullable|string|max:100',
            'tgl_ttd' => 'nullable|date',
            'jns_msb_dok' => 'nullable|string|max:50',
            'tgl_akr_dok' => 'nullable|date|after_or_equal:tgl_ttd',
            'msb_dok' => 'nullable|integer|min:1',
            'tgl_pgt_dok' => 'nullable|date|before_or_equal:tgl_akr_dok',
            'durasi_pgt' => 'nullable|integer|min:1',
            'ket_dok' => 'nullable|string|max:255',
            'ctt_dok' => 'nullable|string',
            'sts_dok' => 'required|in:AKTIF,NON-AKTIF,EXPIRED,PENDING',
            'tgl_dok_na' => 'nullable|date',
            'ket_dok_na' => 'nullable|string|max:255',
            'file_dok' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // Handle file upload
        $fileDok = $dataDokumen->file_dok;
        if ($request->hasFile('file_dok')) {
            // Delete old file if exists
            if ($dataDokumen->file_dok && Storage::exists('public/' . $dataDokumen->file_dok)) {
                Storage::delete('public/' . $dataDokumen->file_dok);
            }

            $file = $request->file('file_dok');
            $fileName = time() . '_' . $dataDokumen->id_kode . '_' . $file->getClientOriginalName();
            $file->storeAs('public/dokumen/files', $fileName);
            $fileDok = 'dokumen/files/' . $fileName;
        }

        // Calculate masa berlaku dokumen (msb_dok) in MONTHS if dates provided
        $msb_dok = $request->msb_dok; // Use provided value if exists
        if (!$msb_dok && $request->tgl_ttd && $request->tgl_akr_dok) {
            $signatureDate = \Carbon\Carbon::parse($request->tgl_ttd);
            $expiryDate = \Carbon\Carbon::parse($request->tgl_akr_dok);
            $msb_dok = $signatureDate->diffInMonths($expiryDate);
        }

        // Calculate durasi peringatan (durasi_pgt) in DAYS from tgl_pgt_dok to tgl_akr_dok
        $durasi_pgt = $request->durasi_pgt; // Use provided value if exists
        if (!$durasi_pgt && $request->tgl_pgt_dok && $request->tgl_akr_dok) {
            $reminderDate = \Carbon\Carbon::parse($request->tgl_pgt_dok);
            $expiryDate = \Carbon\Carbon::parse($request->tgl_akr_dok);
            $durasi_pgt = $reminderDate->diffInDays($expiryDate);
        }

        $dataDokumen->update([
            'id_data_kry' => $request->id_data_kry,
            'id_dokumen' => $request->id_dokumen,
            'kode_dok_kry' => $request->kode_dok_kry,
            'no_dok' => $request->no_dok,
            'tgl_ttd' => $request->tgl_ttd,
            'jns_msb_dok' => $request->jns_msb_dok,
            'tgl_akr_dok' => $request->tgl_akr_dok,
            'msb_dok' => $msb_dok,
            'tgl_pgt_dok' => $request->tgl_pgt_dok,
            'durasi_pgt' => $durasi_pgt,
            'file_dok' => $fileDok,
            'sts_dok' => $request->sts_dok,
            'ket_dok' => $request->ket_dok,
            'ctt_dok' => $request->ctt_dok,
            'tgl_dok_na' => $request->tgl_dok_na,
            'ket_dok_na' => $request->ket_dok_na,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('data-dokumen.edit', $dataDokumen->id)
            ->with('success', 'Data dokumen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $dataDokumen = DataDokumen::findOrFail($id);
            $employeeId = $dataDokumen->id_data_kry;
            $employeeName = $dataDokumen->karyawan->nama ?? 'Unknown';

            // Get all documents for this employee
            $allEmployeeDocuments = DataDokumen::where('id_data_kry', $employeeId)->get();

            // Delete all associated files for this employee's documents
            foreach ($allEmployeeDocuments as $document) {
                if ($document->file_dok && Storage::exists('public/' . $document->file_dok)) {
                    Storage::delete('public/' . $document->file_dok);
                }
            }

            // Delete all documents for this employee
            $deletedCount = DataDokumen::where('id_data_kry', $employeeId)->delete();

            DB::commit();

            // Untuk AJAX request
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Semua data dokumen karyawan {$employeeName} berhasil dihapus. Total {$deletedCount} record dihapus."
                ]);
            }

            // Untuk non-AJAX request
            return redirect()->route('data-dokumen.index')
                ->with('success', "Semua data dokumen karyawan {$employeeName} berhasil dihapus. Total {$deletedCount} record dihapus.");
        } catch (\Exception $e) {
            DB::rollback();

            // Untuk AJAX request
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data dokumen: ' . $e->getMessage()
                ], 500);
            }

            // Untuk non-AJAX request
            return redirect()->route('data-dokumen.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data dokumen: ' . $e->getMessage());
        }
    }

    // ================================================ DOCUMENT MANAGEMENT METHODS ===================================== //
    // ========================================================================================================== //

    /**
     * Store a new document for an employee
     */
    public function storeDocument(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:201_dm_data_karyawan,id',
            'id_dokumen' => 'required|exists:105_dm_dok_kry,id',
            'kode_dok_kry' => 'nullable|string|max:50',
            'no_dok' => 'nullable|string|max:100',
            'tgl_ttd' => 'nullable|date',
            'jns_msb_dok' => 'nullable|string|max:50',
            'tgl_akr_dok' => 'nullable|date|after_or_equal:tgl_ttd',
            'tgl_pgt_dok' => 'nullable|date|before_or_equal:tgl_akr_dok',
            'sts_dok' => 'required|in:AKTIF,NON-AKTIF,EXPIRED,PENDING',
            'ket_dok' => 'nullable|string|max:255',
            'ctt_dok' => 'nullable|string',
            'tgl_dok_na' => 'nullable|date',
            'ket_dok_na' => 'nullable|string|max:255',
            'file_dok' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::beginTransaction();

            // Generate id_kode and id_dok_kry
            $id_kode = $this->generateId('203', '203_dm_data_dokumen');
            $id_dok_kry = $this->generateAutoIncrement();

            // Calculate masa berlaku dokumen (msb_dok) in MONTHS
            $msb_dok = null;
            if ($request->tgl_ttd && $request->tgl_akr_dok) {
                $signatureDate = \Carbon\Carbon::parse($request->tgl_ttd);
                $expiryDate = \Carbon\Carbon::parse($request->tgl_akr_dok);
                $msb_dok = $signatureDate->diffInMonths($expiryDate);
            }

            // Calculate durasi peringatan (durasi_pgt) in DAYS from tgl_pgt_dok to tgl_akr_dok
            $durasi_pgt = null;
            if ($request->tgl_pgt_dok && $request->tgl_akr_dok) {
                $reminderDate = \Carbon\Carbon::parse($request->tgl_pgt_dok);
                $expiryDate = \Carbon\Carbon::parse($request->tgl_akr_dok);
                $durasi_pgt = $reminderDate->diffInDays($expiryDate);
            }

            // Handle file upload if exists
            $fileDok = null;
            if ($request->hasFile('file_dok')) {
                $file = $request->file('file_dok');
                $fileName = time() . '_' . $id_kode . '_' . $file->getClientOriginalName();
                $file->storeAs('public/dokumen/files', $fileName);
                $fileDok = 'dokumen/files/' . $fileName;
            }

            $document = DataDokumen::create([
                'id_kode' => $id_kode,
                'id_dok_kry' => $id_dok_kry,
                'id_data_kry' => $request->employee_id,
                'id_dokumen' => $request->id_dokumen,
                'kode_dok_kry' => $request->kode_dok_kry,
                'no_dok' => $request->no_dok,
                'tgl_ttd' => $request->tgl_ttd,
                'jns_msb_dok' => $request->jns_msb_dok,
                'tgl_akr_dok' => $request->tgl_akr_dok,
                'msb_dok' => $msb_dok,
                'tgl_pgt_dok' => $request->tgl_pgt_dok,
                'durasi_pgt' => $durasi_pgt,
                'sts_dok' => $request->sts_dok,
                'file_dok' => $fileDok,
                'ket_dok' => $request->ket_dok,
                'ctt_dok' => $request->ctt_dok,
                'tgl_dok_na' => $request->tgl_dok_na,
                'ket_dok_na' => $request->ket_dok_na,
                'created_by' => auth()->user()->id_kode ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil ditambahkan.',
                'data' => $document
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get document details
     */
    public function getDocument($id)
    {
        try {
            $document = DataDokumen::with([
                'dokumenKaryawan',
                'creator',
                'updater'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $document->id,
                    'id_data_kry' => $document->id_data_kry,
                    'id_dokumen' => $document->id_dokumen,

                    // Data dari relasi dokumenKaryawan
                    'kode_dok_kry' => $document->dokumenKaryawan->kode_dok_kry ?? $document->kode_dok_kry,
                    'ktg_dok_kry' => $document->dokumenKaryawan->ktg_dok_kry ?? null,
                    'jns_dok_kry' => $document->dokumenKaryawan->jns_dok_kry ?? null,
                    'nama_dok' => $document->dokumenKaryawan->nama_dok ?? null,

                    // Data dokumen
                    // Data dokumen
                    'no_dok'      => $document->no_dok,
                    'tgl_ttd'     => $document->tgl_ttd     ? \Carbon\Carbon::parse($document->tgl_ttd)->format('Y-m-d')     : null,
                    'jns_msb_dok' => $document->jns_msb_dok,
                    'tgl_akr_dok' => $document->tgl_akr_dok ? \Carbon\Carbon::parse($document->tgl_akr_dok)->format('Y-m-d') : null,
                    'msb_dok'     => $document->msb_dok,
                    'tgl_pgt_dok' => $document->tgl_pgt_dok ? \Carbon\Carbon::parse($document->tgl_pgt_dok)->format('Y-m-d') : null,
                    'durasi_pgt'  => $document->durasi_pgt,
                    'file_dok'    => $document->file_dok,
                    'sts_dok'     => $document->sts_dok,
                    'ket_dok'     => $document->ket_dok,
                    'ctt_dok'     => $document->ctt_dok,
                    'tgl_dok_na'  => $document->tgl_dok_na  ? \Carbon\Carbon::parse($document->tgl_dok_na)->format('Y-m-d')  : null,
                    'ket_dok_na'  => $document->ket_dok_na,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update document
     */
    public function updateDocument(Request $request, $id)
    {
        $request->validate([
            'id_dokumen' => 'required|exists:105_dm_dok_kry,id',
            'kode_dok_kry' => 'nullable|string|max:50',
            'no_dok' => 'nullable|string|max:100',
            'tgl_ttd' => 'nullable|date',
            'jns_msb_dok' => 'nullable|string|max:50',
            'tgl_akr_dok' => 'nullable|date|after_or_equal:tgl_ttd',
            'tgl_pgt_dok' => 'nullable|date|before_or_equal:tgl_akr_dok',
            'sts_dok' => 'required|in:AKTIF,NON-AKTIF,EXPIRED,PENDING',
            'ket_dok' => 'nullable|string|max:255',
            'ctt_dok' => 'nullable|string',
            'tgl_dok_na' => 'nullable|date',
            'ket_dok_na' => 'nullable|string|max:255',
            'file_dok' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $document = DataDokumen::findOrFail($id);

            // Calculate masa berlaku dokumen (msb_dok) in MONTHS if dates provided
            $msb_dok = $document->msb_dok; // Keep existing if not recalculated
            if ($request->tgl_ttd && $request->tgl_akr_dok) {
                $signatureDate = \Carbon\Carbon::parse($request->tgl_ttd);
                $expiryDate = \Carbon\Carbon::parse($request->tgl_akr_dok);
                $msb_dok = $signatureDate->diffInMonths($expiryDate);
            }

            // Calculate durasi peringatan (durasi_pgt) in DAYS from tgl_pgt_dok to tgl_akr_dok
            $durasi_pgt = $document->durasi_pgt; // Keep existing if not recalculated
            if ($request->tgl_pgt_dok && $request->tgl_akr_dok) {
                $reminderDate = \Carbon\Carbon::parse($request->tgl_pgt_dok);
                $expiryDate = \Carbon\Carbon::parse($request->tgl_akr_dok);
                $durasi_pgt = $reminderDate->diffInDays($expiryDate);
            }

            // Handle file upload if exists
            $fileDok = $document->file_dok; // Keep existing file
            if ($request->hasFile('file_dok')) {
                // Delete old file if exists
                if ($document->file_dok && Storage::exists('public/' . $document->file_dok)) {
                    Storage::delete('public/' . $document->file_dok);
                }

                $file = $request->file('file_dok');
                $fileName = time() . '_' . $document->id_kode . '_' . $file->getClientOriginalName();
                $file->storeAs('public/dokumen/files', $fileName);
                $fileDok = 'dokumen/files/' . $fileName;
            }

            // UPDATE SEMUA FIELD
            $document->update([
                'id_dokumen' => $request->id_dokumen,
                'kode_dok_kry' => $request->kode_dok_kry,
                'no_dok' => $request->no_dok,
                'tgl_ttd' => $request->tgl_ttd,
                'jns_msb_dok' => $request->jns_msb_dok,
                'tgl_akr_dok' => $request->tgl_akr_dok,
                'msb_dok' => $msb_dok,
                'tgl_pgt_dok' => $request->tgl_pgt_dok,
                'durasi_pgt' => $durasi_pgt,
                'sts_dok' => $request->sts_dok,
                'file_dok' => $fileDok,
                'ket_dok' => $request->ket_dok,
                'ctt_dok' => $request->ctt_dok,
                'tgl_dok_na' => $request->tgl_dok_na,
                'ket_dok_na' => $request->ket_dok_na,
                'updated_by' => auth()->user()->id_kode ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diperbarui.',
                'data' => $document->fresh() // Refresh data
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete document
     */
    public function deleteDocument($id)
    {
        try {
            DB::beginTransaction();

            $document = DataDokumen::findOrFail($id);
            $employeeName = $document->karyawan->nama ?? 'N/A';

            // Check if this is the only document for the employee
            $employeeDocumentsCount = DataDokumen::where('id_data_kry', $document->id_data_kry)->count();

            if ($employeeDocumentsCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat menghapus dokumen terakhir karyawan {$employeeName}. Setiap karyawan harus memiliki minimal 1 dokumen dalam sistem."
                ], 400);
            }

            // Delete associated file if exists
            if ($document->file_dok && Storage::exists('public/' . $document->file_dok)) {
                Storage::delete('public/' . $document->file_dok);
            }

            $document->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================================================ LEGACY METHODS ============================================== //
    // ========================================================================================================== //

    /**
     * Add new document for employee (Legacy method - use storeDocument instead)
     */
    public function addDocument(Request $request)
    {
        $request->validate([
            'id_data_kry' => 'required|exists:201_dm_data_karyawan,id',
            'id_dokumen' => 'required|exists:105_dm_dok_kry,id',
            'no_dok' => 'nullable|string|max:100',
            'tgl_ttd' => 'nullable|date',
            'tgl_akr_dok' => 'nullable|date|after_or_equal:tgl_ttd',
            'sts_dok' => 'required|in:AKTIF,NON-AKTIF,EXPIRED,PENDING',
        ]);

        // Generate id_kode and id_dok_kry
        $id_kode = $this->generateId('203', '203_dm_data_dokumen');
        $id_dok_kry = $this->generateAutoIncrement();

        // Calculate masa berlaku dokumen (msb_dok) in MONTHS
        $msb_dok = null;
        if ($request->tgl_ttd && $request->tgl_akr_dok) {
            $signatureDate = \Carbon\Carbon::parse($request->tgl_ttd);
            $expiryDate = \Carbon\Carbon::parse($request->tgl_akr_dok);
            $msb_dok = $signatureDate->diffInMonths($expiryDate);
        }

        DataDokumen::create([
            'id_kode' => $id_kode,
            'id_dok_kry' => $id_dok_kry,
            'id_data_kry' => $request->id_data_kry,
            'id_dokumen' => $request->id_dokumen,
            'kode_dok_kry' => $request->kode_dok_kry,
            'no_dok' => $request->no_dok,
            'tgl_ttd' => $request->tgl_ttd,
            'jns_msb_dok' => $request->jns_msb_dok,
            'tgl_akr_dok' => $request->tgl_akr_dok,
            'msb_dok' => $msb_dok,
            'tgl_pgt_dok' => $request->tgl_pgt_dok,
            'sts_dok' => $request->sts_dok,
            'ket_dok' => $request->ket_dok,
            'ctt_dok' => $request->ctt_dok,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dokumen baru berhasil ditambahkan.'
        ]);
    }

    /**
     * Generate auto-increment ID for id_dok_kry
     */
    private function generateAutoIncrement(): int
    {
        return (int) now()->format('YmdHis') + rand(1000, 9999);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getFilteredData($request->all());

        $currentDate = now()->format('d-m-Y_H-i-s');
        $fileName = 'Data_Dokumen_' . $currentDate . '.xlsx';

        return Excel::download(
            new DataDokumenExport($data['dataDokumens'], $data['filters']),
            $fileName
        );
    }

    private function getFilteredData($filters)
    {
        $query = DataDokumen::with([
            'karyawan',
            'dokumenKaryawan'
        ]);

        // Get latest document per employee
        $query->whereIn('id', function ($subquery) {
            $subquery->select(DB::raw('MAX(id)'))
                ->from('203_dm_data_dokumen')
                ->groupBy('id_data_kry');
        });

        // Apply filters - SAMA DENGAN INDEX
        if (!empty($filters['filter_status'])) {
            $query->where('sts_dok', $filters['filter_status']);
        }

        if (!empty($filters['filter_jenis_dokumen'])) {
            $query->where('id_dokumen', $filters['filter_jenis_dokumen']);
        }

        if (!empty($filters['filter_nama'])) {
            $query->whereHas('karyawan', function ($q) use ($filters) {
                $q->where('nama', 'LIKE', '%' . $filters['filter_nama'] . '%');
            });
        }

        if (!empty($filters['filter_nrk'])) {
            $query->whereHas('karyawan', function ($q) use ($filters) {
                $q->where('nrk', 'LIKE', '%' . $filters['filter_nrk'] . '%');
            });
        }

        if (!empty($filters['filter_no_dokumen'])) {
            $query->where('no_dok', 'LIKE', '%' . $filters['filter_no_dokumen'] . '%');
        }

        if (!empty($filters['filter_departemen'])) {
            $selectedDepartemen = Departemen::find($filters['filter_departemen']);
            if ($selectedDepartemen) {
                $departemenIds = Departemen::where('nama_dep', $selectedDepartemen->nama_dep)->pluck('id')->toArray();
                $query->whereHas('karyawan', function ($q) use ($departemenIds) {
                    $q->whereIn('departemen', $departemenIds);
                });
            }
        }

        if (!empty($filters['filter_jabatan'])) {
            $query->whereHas('karyawan', function ($q) use ($filters) {
                $q->where('departemen', $filters['filter_jabatan']);
            });
        }

        if (!empty($filters['filter_perusahaan'])) {
            $query->whereHas('karyawan', function ($q) use ($filters) {
                $q->where('perusahaan', $filters['filter_perusahaan']);
            });
        }

        if (!empty($filters['filter_jenis_kelamin'])) {
            $query->whereHas('karyawan', function ($q) use ($filters) {
                $q->where('sex', $filters['filter_jenis_kelamin']);
            });
        }

        if (!empty($filters['filter_wilker'])) {
            $query->whereHas('karyawan.wilayahKerjaRelation', function ($q) use ($filters) {
                $q->where('wilayah_krj', $filters['filter_wilker']);
            });
        }

        if (!empty($filters['filter_unit_kerja'])) {
            $query->whereHas('karyawan', function ($q) use ($filters) {
                $q->where('unit_krj', $filters['filter_unit_kerja']);
            });
        }

        $dataDokumens = $query->orderBy('created_at', 'desc')->get();

        return [
            'dataDokumens' => $dataDokumens,
            'filters' => $filters
        ];
    }

    /**
     * Get employee data for AJAX call (enhanced version)
     */

    public function getEmployeeData($id)
    {
        try {
            $employee = DataKaryawan::with([
                'perusahaanRelation',
                'kontrakRelation',
                'departemenRelation',
                'unitKerjaRelation',
                'wilayahKerjaRelation'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    // Basic employee info
                    'nik' => $employee->nik,
                    'nrk' => $employee->nrk,
                    'nama' => $employee->nama,
                    'foto_dokumen' => $employee->foto_dokumen,
                    'tpt_lahir' => $employee->tpt_lahir,
                    'tgl_lahir' => $employee->tgl_lahir ? $employee->tgl_lahir->format('Y-m-d') : null,
                    'tgl_lahir_formatted' => $employee->tgl_lahir ? $employee->tgl_lahir->format('d-m-Y') : null,
                    'sex' => $employee->sex,
                    'tlp1' => $employee->tlp1,
                    'sts_nikah' => $employee->sts_nikah,
                    'jml_anak' => $employee->jml_anak,
                    'email1' => $employee->email1,

                    // Employment info
                    'tgl_masuk' => $employee->tgl_masuk ? $employee->tgl_masuk->format('Y-m-d') : null,
                    'tgl_masuk_formatted' => $employee->tgl_masuk ? $employee->tgl_masuk->format('d-m-Y') : null,
                    'sts_kry' => $employee->sts_kry,

                    // Contract info
                    'perusahaan' => $employee->perusahaan,
                    'perusahaan_nama' => optional($employee->perusahaanRelation)->nama_prs1,
                    'skt_prs' => $employee->skt_prs,
                    'sts_ktr' => $employee->sts_ktr,
                    'kontrak_nama' => optional($employee->kontrakRelation)->nama_ktr,
                    'skt_sts_ktr' => $employee->skt_sts_ktr,
                    'tgl_awal_ktr' => $employee->tgl_awal_ktr ? $employee->tgl_awal_ktr->format('Y-m-d') : null,
                    'tgl_awal_ktr_formatted' => $employee->tgl_awal_ktr ? $employee->tgl_awal_ktr->format('d-m-Y') : null,
                    'tgl_akhir_ktr' => $employee->tgl_akhir_ktr ? $employee->tgl_akhir_ktr->format('Y-m-d') : null,
                    'tgl_akhir_ktr_formatted' => $employee->tgl_akhir_ktr ? $employee->tgl_akhir_ktr->format('d-m-Y') : null,
                    'durasi_ktr' => $employee->durasi_ktr,

                    // Career info
                    'departemen' => $employee->departemen,
                    'departemen_nama' => optional($employee->departemenRelation)->nama_dep,
                    'skt_dep' => $employee->skt_dep,
                    'jabatan' => $employee->jabatan,
                    'jabatan_nama' => optional($employee->departemenRelation)->nama_jbt,
                    'skt_jbt' => $employee->skt_jbt,
                    'wilker' => $employee->wilker,
                    'wilker_nama' => optional($employee->wilayahKerjaRelation)->wilayah_krj,
                    'skt_wil_krj' => $employee->skt_wil_krj,
                    'unit_krj' => $employee->unit_krj,
                    'unit_krj_nama' => optional($employee->unitKerjaRelation)->area_krj,
                    'tugas' => $employee->tugas,

                    // PHK info
                    'tgl_phk' => $employee->tgl_phk ? $employee->tgl_phk->format('Y-m-d') : null,
                    'tgl_phk_formatted' => $employee->tgl_phk ? $employee->tgl_phk->format('d-m-Y') : null,
                    'ket_phk' => $employee->ket_phk,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    public function getUnitKerjaByWilayah($wilayahKrj)
    {
        try {
            $unitKerjas = WilayahKerja::where('wilayah_krj', $wilayahKrj)
                ->orderByRaw('CAST(kode_wk AS UNSIGNED) ASC')
                ->orderBy('kode_wk', 'ASC')
                ->get(['id', 'kode_wk', 'wilayah_krj', 'area_krj', 'singkatan_wk']);

            return response()->json([
                'success' => true,
                'data' => $unitKerjas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data unit kerja: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get documents expiring soon for dashboard/notifications
     */
    public function getExpiringDocuments(Request $request)
    {
        $days = $request->get('days', 30);

        $expiring = DataDokumen::with(['karyawan', 'dokumenKaryawan'])
            ->uniqueEmployees() // Only unique employees
            ->expiringSoon($days)
            ->where('sts_dok', 'AKTIF')
            ->orderBy('tgl_akr_dok', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $expiring->count(),
            'data' => $expiring->map(function ($dokumen) {
                return [
                    'id' => $dokumen->id,
                    'employee_name' => $dokumen->karyawan->nama ?? 'N/A',
                    'employee_nrk' => $dokumen->karyawan->nrk ?? 'N/A',
                    'document_type' => $dokumen->dokumenKaryawan->nama_dok ?? 'N/A',
                    'document_number' => $dokumen->no_dok ?? 'N/A',
                    'signature_date' => $dokumen->tgl_ttd,
                    'expiry_date' => $dokumen->tgl_akr_dok,
                    'days_until_expiry' => $dokumen->days_until_expiry,
                    'is_active' => $dokumen->is_active,
                    'is_expired' => $dokumen->is_expired,
                ];
            })
        ]);
    }

    public function getActiveDocumentsData()
    {
        try {
            // Get all employees with their active documents
            $activeDocuments = DataDokumen::select('id_data_kry')
                ->distinct()
                ->get()
                ->mapWithKeys(function ($item) {
                    $employeeId = $item->id_data_kry;

                    $activeDocument = DataDokumen::where('id_data_kry', $employeeId)
                        ->where('sts_dok', 'AKTIF')
                        ->whereNotNull('tgl_akr_dok')
                        ->orderBy('tgl_akr_dok', 'desc')
                        ->first();

                    if (!$activeDocument) {
                        return [$employeeId => null];
                    }

                    $expiryDate = \Carbon\Carbon::parse($activeDocument->tgl_akr_dok);
                    $reminderDate = $activeDocument->tgl_pgt_dok ? \Carbon\Carbon::parse($activeDocument->tgl_pgt_dok) : null;
                    $today = \Carbon\Carbon::now()->startOf('day');

                    $daysUntilExpiry = $today->diffInDays($expiryDate, false);
                    $daysUntilReminder = $reminderDate ? $today->diffInDays($reminderDate, false) : null;

                    return [$employeeId => [
                        'document_id' => $activeDocument->id,
                        'expiry_date' => $activeDocument->tgl_akr_dok,
                        'reminder_date' => $activeDocument->tgl_pgt_dok,
                        'days_until_expiry' => $daysUntilExpiry,
                        'days_until_reminder' => $daysUntilReminder,
                        'is_expired' => $daysUntilExpiry < 0,
                        'is_urgent' => $daysUntilExpiry >= 0 && $daysUntilExpiry <= 7,
                        'is_warning' => $daysUntilExpiry >= 0 && $daysUntilExpiry <= 30,
                    ]];
                });

            return response()->json([
                'success' => true,
                'data' => $activeDocuments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching active documents: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkEmployeeExists($id)
    {
        try {
            $employee = DataKaryawan::findOrFail($id);

            // Check if employee already has document records
            $existingDocuments = DataDokumen::where('id_data_kry', $id)->count();

            if ($existingDocuments > 0) {
                // Get latest document info for better error message
                $latestDocument = DataDokumen::where('id_data_kry', $id)
                    ->with(['dokumenKaryawan'])
                    ->orderBy('created_at', 'desc')
                    ->first();

                return response()->json([
                    'success' => false,
                    'exists' => true,
                    'message' => 'Karyawan sudah memiliki data dokumen sebelumnya',
                    'employee_name' => $employee->nama,
                    'employee_nrk' => $employee->nrk,
                    'existing_documents_count' => $existingDocuments,
                    'latest_document' => [
                        'document_type' => optional($latestDocument->dokumenKaryawan)->nama_dok ?? 'N/A',
                        'document_number' => $latestDocument->no_dok ?? 'N/A',
                        'status' => $latestDocument->sts_dok ?? 'N/A',
                        'created_at' => $latestDocument->created_at->format('d-m-Y') ?? 'N/A',
                    ]
                ], 409); // 409 Conflict
            }

            return response()->json([
                'success' => true,
                'exists' => false,
                'message' => 'Karyawan belum memiliki data dokumen, dapat dilanjutkan',
                'employee_name' => $employee->nama,
                'employee_nrk' => $employee->nrk
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get jabatan by departemen name (sama seperti data-kontrak)
     */
    public function getJabatanByDepartemen($namaDep)
    {
        try {
            $jabatans = Departemen::where('nama_dep', $namaDep)
                ->orderByRaw('CAST(kode_dep AS UNSIGNED) ASC')
                ->orderBy('kode_dep', 'ASC')
                ->get(['id', 'kode_dep', 'nama_jbt', 'singkatan_jbt']);

            return response()->json([
                'success' => true,
                'data' => $jabatans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data jabatan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get departemen jabatan by ID (sama seperti data-kontrak)
     */
    public function getDepartemenJabatan($id)
    {
        try {
            $departemen = Departemen::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $departemen->id,
                    'kode_dep' => $departemen->kode_dep,
                    'nama_dep' => $departemen->nama_dep,
                    'singkatan_dep' => $departemen->singkatan_dep,
                    'nama_jbt' => $departemen->nama_jbt,
                    'singkatan_jbt' => $departemen->singkatan_jbt,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data departemen tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get wilker unit kerja by ID (sama seperti data-kontrak)
     */
    public function getWilkerUnitKrj($id)
    {
        try {
            $wilker = WilayahKerja::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $wilker->id,
                    'kode_wk' => $wilker->kode_wk,
                    'wilayah_krj' => $wilker->wilayah_krj,
                    'area_krj' => $wilker->area_krj,
                    'singkatan_wk' => $wilker->singkatan_wk,
                    'alamat_wk' => $wilker->alamat_wk,
                    'kota_wk' => $wilker->kota_wk,
                    'prov_wk' => $wilker->prov_wk,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data wilayah kerja tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }
}
