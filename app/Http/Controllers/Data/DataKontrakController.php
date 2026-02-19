<?php

namespace App\Http\Controllers\Data;

use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Data\DataKontrak;
use App\Models\Data\DataKaryawan;
use App\Models\DataMaster\Perusahaan;
use App\Models\DataMaster\WilayahKerja;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\KontrakKerja;
use Illuminate\Support\Facades\Storage;
use App\Exports\DataKontrakExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class DataKontrakController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:data-kontrak')->only('index');
        $this->middleware('check.access:data-kontrak,detail')->only('show');
        $this->middleware('check.access:data-kontrak,tambah')->only('create', 'store');
        $this->middleware('check.access:data-kontrak,ubah')->only('edit', 'update');
        $this->middleware('check.access:data-kontrak,hapus')->only('destroy');
    }

    public function index(Request $request)
    {
        // Get latest contract per employee using raw query for better performance
        $latestContractsQuery = DB::table('202_dm_data_kontrak as dk')
            ->select('dk.*')
            ->whereIn('dk.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('202_dm_data_kontrak')
                    ->groupBy('id_data_kry');
            });

        // Apply filters to the subquery
        if ($request->has('filter_status') && !empty($request->filter_status)) {
            $latestContractsQuery->where('dk.sts_srt_ktr', $request->filter_status);
        }

        if ($request->has('filter_perusahaan') && !empty($request->filter_perusahaan)) {
            $latestContractsQuery->where('dk.id_prsh', $request->filter_perusahaan);
        }

        if ($request->has('filter_kontrak_type') && !empty($request->filter_kontrak_type)) {
            $latestContractsQuery->where('dk.id_ktr', $request->filter_kontrak_type);
        }

        if ($request->has('filter_departemen') && !empty($request->filter_departemen)) {
            $latestContractsQuery->where('dk.id_departemen', $request->filter_departemen);
        }

        if ($request->has('filter_wilayah_kerja') && !empty($request->filter_wilayah_kerja)) {
            $latestContractsQuery->where('dk.id_wilker', $request->filter_wilayah_kerja);
        }

        // Filter by contract status (active/expired/expiring soon)
        if ($request->has('filter_contract_status') && !empty($request->filter_contract_status)) {
            switch ($request->filter_contract_status) {
                case 'active':
                    $latestContractsQuery->where('dk.sts_srt_ktr', 'AKTIF')
                        ->whereNotNull('dk.tgl_awl_ktr')
                        ->whereNotNull('dk.tgl_akhir_ktr')
                        ->whereRaw('STR_TO_DATE(dk.tgl_awl_ktr, "%Y-%m-%d") <= CURDATE()')
                        ->whereRaw('STR_TO_DATE(dk.tgl_akhir_ktr, "%Y-%m-%d") >= CURDATE()');
                    break;
                case 'expired':
                    $latestContractsQuery->whereNotNull('dk.tgl_akhir_ktr')
                        ->whereRaw('STR_TO_DATE(dk.tgl_akhir_ktr, "%Y-%m-%d") < CURDATE()');
                    break;
                case 'expiring_soon':
                    $latestContractsQuery->whereNotNull('dk.tgl_akhir_ktr')
                        ->whereRaw('STR_TO_DATE(dk.tgl_akhir_ktr, "%Y-%m-%d") BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)');
                    break;
            }
        }

        // Education level filter
        if ($request->has('filter_education_level') && !empty($request->filter_education_level)) {
            $latestContractsQuery->where('dk.jenjang_skl', $request->filter_education_level);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $latestContractsQuery->where(function ($query) use ($request) {
                $search = $request->search;
                $query->where('dk.no_srt_ktr', 'like', '%' . $search . '%')
                    ->orWhereExists(function ($subQuery) use ($search) {
                        $subQuery->select(DB::raw(1))
                            ->from('201_dm_data_karyawan as k')
                            ->whereColumn('k.id', 'dk.id_data_kry')
                            ->where(function ($kQuery) use ($search) {
                                $kQuery->where('k.nama', 'like', '%' . $search . '%')
                                    ->orWhere('k.nrk', 'like', '%' . $search . '%')
                                    ->orWhere('k.nik', 'like', '%' . $search . '%');
                            });
                    });
            });
        }

        if ($request->has('export')) {
            $exportType = $request->export;

            // Get filtered data first
            $data = $this->getFilteredData($request->all());

            switch ($exportType) {
                case 'excel':
                    return $this->exportExcel($request);
                case 'pdf':
                    return $this->exportPDF($request);
                case 'csv':
                    return $this->exportCSV($request);
            }
        }

        // Get the latest contracts with relationships
        $dataKontraks = DataKontrak::whereIn('id', function ($query) use ($latestContractsQuery) {
            $query->select('id')->fromSub($latestContractsQuery, 'latest_contracts');
        })->with([
            'karyawan',
            'kontrakKerja',
            'perusahaan',
            'departemen',
            'wilayahKerja',
            'creator',
            'updater'
        ])->orderBy('created_at', 'desc')->get();

        $contractCounts = [];
        foreach ($dataKontraks as $kontrak) {
            $employeeId = $kontrak->id_data_kry;

            if (!isset($contractCounts[$employeeId])) {
                // Count total contracts for this employee
                $totalContracts = DataKontrak::where('id_data_kry', $employeeId)->count();

                // Count active contracts
                $activeContracts = DataKontrak::where('id_data_kry', $employeeId)
                    ->where('sts_srt_ktr', 'AKTIF')
                    ->count();

                // Count non-active contracts
                $nonActiveContracts = DataKontrak::where('id_data_kry', $employeeId)
                    ->where('sts_srt_ktr', '!=', 'AKTIF')
                    ->count();

                $contractCounts[$employeeId] = [
                    'total' => $totalContracts,
                    'active' => $activeContracts,
                    'non_active' => $nonActiveContracts
                ];
            }
        }

        // ========== ACTIVE CONTRACTS DATA CALCULATION ==========
        $activeContractsData = [];
        foreach ($dataKontraks as $kontrak) {
            $employeeId = $kontrak->id_data_kry;

            // For the latest contract display, we'll use the current contract data
            if ($kontrak->sts_srt_ktr === 'AKTIF' && $kontrak->tgl_akhir_ktr) {

                $endDate = \Carbon\Carbon::parse($kontrak->tgl_akhir_ktr);
                $reminderDate = $kontrak->tgl_pgt_ktr ? \Carbon\Carbon::parse($kontrak->tgl_pgt_ktr) : null;
                $today = \Carbon\Carbon::now()->startOf('day');

                $daysUntilExpiry = $today->diffInDays($endDate, false);
                $daysUntilReminder = $reminderDate ? $today->diffInDays($reminderDate, false) : null;

                // Determine priority and status based on reminder date first, then end date
                $priority = 4; // default normal
                $status = 'normal';
                $badgeClass = 'bg-success';
                $warningText = 'Aman';

                if ($reminderDate && $daysUntilReminder <= 0) {
                    // Reminder date has passed or is today
                    $priority = 1;
                    $status = 'expired';
                    $badgeClass = 'bg-danger';
                    $warningText = $daysUntilReminder == 0 ? 'Hari ini' : 'Terlambat ' . abs($daysUntilReminder) . ' hari';
                } elseif ($daysUntilExpiry < 0) {
                    // Contract expired
                    $priority = 1;
                    $status = 'expired';
                    $badgeClass = 'bg-danger';
                    $warningText = 'Terlambat ' . abs($daysUntilExpiry) . ' hari';
                } elseif ($reminderDate && $daysUntilReminder <= 7) {
                    // Urgent - reminder within 7 days
                    $priority = 2;
                    $status = 'urgent';
                    $badgeClass = 'bg-warning text-dark';
                    $warningText = $daysUntilReminder . ' hari lagi';
                } elseif ($daysUntilExpiry <= 7) {
                    // Urgent - expires within 7 days
                    $priority = 2;
                    $status = 'urgent';
                    $badgeClass = 'bg-warning text-dark';
                    $warningText = $daysUntilExpiry . ' hari lagi';
                } elseif ($reminderDate && $daysUntilReminder <= 30) {
                    // Warning - reminder within 30 days
                    $priority = 3;
                    $status = 'warning';
                    $badgeClass = 'bg-info';
                    $warningText = $daysUntilReminder . ' hari lagi';
                } elseif ($daysUntilExpiry <= 30) {
                    // Warning - expires within 30 days
                    $priority = 3;
                    $status = 'warning';
                    $badgeClass = 'bg-info';
                    $warningText = $daysUntilExpiry . ' hari lagi';
                } else {
                    // Safe
                    $warningText = $reminderDate ? $daysUntilReminder . ' hari lagi' : $daysUntilExpiry . ' hari lagi';
                }

                $activeContractsData[$employeeId] = [
                    'contract_id' => $kontrak->id,
                    'end_date' => $kontrak->tgl_akhir_ktr,
                    'end_date_formatted' => $endDate->format('d-m-Y'),
                    'reminder_date' => $kontrak->tgl_pgt_ktr,
                    'reminder_date_formatted' => $reminderDate ? $reminderDate->format('d-m-Y') : null,
                    'days_until_expiry' => $daysUntilExpiry,
                    'days_until_reminder' => $daysUntilReminder,
                    'is_expired' => $daysUntilExpiry < 0,
                    'is_expiring_soon' => $daysUntilExpiry >= 0 && $daysUntilExpiry <= 30,
                    'is_urgent' => $daysUntilExpiry >= 0 && $daysUntilExpiry <= 7,
                    'contract_number' => $kontrak->no_srt_ktr,
                    'contract_type' => optional($kontrak->kontrakKerja)->nama_ktr ?? 'N/A',
                    'priority' => $priority,
                    'status' => $status,
                    'badge_class' => $badgeClass,
                    'warning_text' => $warningText,
                    'has_active_contract' => true
                ];
            } else {
                // No active contract or contract is not active
                $activeContractsData[$employeeId] = [
                    'has_active_contract' => false,
                    'priority' => 5, // lowest priority
                    'status' => 'no_active',
                    'badge_class' => 'bg-secondary',
                    'warning_text' => 'Tidak ada kontrak aktif'
                ];
            }
        }

        // Sort contracts by priority (expired first, then by priority level)
        $dataKontraks = $dataKontraks->sort(function ($a, $b) use ($activeContractsData) {
            $priorityA = $activeContractsData[$a->id_data_kry]['priority'] ?? 5;
            $priorityB = $activeContractsData[$b->id_data_kry]['priority'] ?? 5;

            return $priorityA <=> $priorityB;
        })->values();

        $today = \Carbon\Carbon::now()->startOf('day')->format('Y-m-d');

        $expiredContractsCount = DataKontrak::uniqueEmployees()
            ->where('sts_srt_ktr', 'AKTIF')
            ->whereNotNull('tgl_pgt_ktr')
            ->whereRaw("STR_TO_DATE(tgl_pgt_ktr, '%Y-%m-%d') <= ?", [$today])
            ->count();

        $expiringContractsCount = DataKontrak::uniqueEmployees()
            ->where('sts_srt_ktr', 'AKTIF')
            ->whereNotNull('tgl_pgt_ktr')
            ->whereRaw("STR_TO_DATE(tgl_pgt_ktr, '%Y-%m-%d') > ?", [$today])
            ->whereRaw("STR_TO_DATE(tgl_pgt_ktr, '%Y-%m-%d') <= DATE_ADD(?, INTERVAL 30 DAY)", [$today])
            ->count();

        // Get master data for filter dropdowns
        $perusahaans = Perusahaan::orderBy('nama_prs1', 'asc')->get();
        $kontrakTypes = KontrakKerja::orderBy('kode_ktr', 'asc')->get();
        $departemens = Departemen::sortByCode()->get();
        $wilayahKerjas = WilayahKerja::orderBy('wilayah_krj', 'asc')->get();

        // Status options
        $statusOptions = [
            'AKTIF' => 'AKTIF',
            'NON-AKTIF' => 'NON-AKTIF',
            'EXPIRED' => 'EXPIRED',
            'PENDING' => 'PENDING'
        ];

        // Contract status options
        $contractStatusOptions = [
            'active' => 'Kontrak Aktif',
            'expired' => 'Kontrak Berakhir',
            'expiring_soon' => 'Akan Berakhir (30 hari)'
        ];

        // Education level options
        $educationLevelOptions = [
            'SD' => 'SD',
            'SMP' => 'SMP',
            'SMA' => 'SMA',
            'SMK' => 'SMK',
            'D1' => 'D1',
            'D2' => 'D2',
            'D3' => 'D3',
            'D4' => 'D4',
            'S1' => 'S1',
            'S2' => 'S2',
            'S3' => 'S3'
        ];

        // Gender options
        $jenisKelaminOptions = [
            'LAKI-LAKI' => 'LAKI-LAKI',
            'PEREMPUAN' => 'PEREMPUAN'
        ];

        // Department options (for filter)
        $departemenOptions = $departemens->unique('nama_dep')->values();

        // Jabatan options (same as departemen for filter)
        $jabatanOptions = $departemens;

        // Wilayah Kerja options
        $wilayahKerjaOptions = $wilayahKerjas->unique('wilayah_krj')->values();

        // Kontrak options
        $kontrakOptions = $kontrakTypes;

        // FIXED: Get DataKaryawan collection for summary statistics
        $dataKaryawans = collect();
        foreach ($dataKontraks as $kontrak) {
            if ($kontrak->karyawan) {
                $dataKaryawans->push($kontrak->karyawan);
            }
        }

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
                $access = $user->userAccess()->where('menu_acs', 'data-kontrak')->first();
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

        // FIXED: Store current filters for view - include ALL possible filter keys
        $currentFilters = [
            'status' => $request->filter_status ?? '',
            'perusahaan' => $request->filter_perusahaan ?? '',
            'kontrak_type' => $request->filter_kontrak_type ?? '',
            'departemen' => $request->filter_departemen ?? '',
            'wilayah_kerja' => $request->filter_wilayah_kerja ?? '',
            'contract_status' => $request->filter_contract_status ?? '',
            'education_level' => $request->filter_education_level ?? '',
            'search' => $request->search ?? '',
            // ADD missing filter keys to prevent undefined array key errors
            'nama' => $request->filter_nama ?? '',
            'jenis_kelamin' => $request->filter_jenis_kelamin ?? '',
            'jabatan' => $request->filter_jabatan ?? '',
            'wilker' => $request->filter_wilker ?? '',
            'kontrak' => $request->filter_kontrak ?? '',
        ];

        return view('data.data-kontrak.index', compact(
            'dataKontraks',
            'activeContractsData',
            'contractCounts',
            'userPermissions',
            'perusahaans',
            'kontrakTypes',
            'departemens',
            'wilayahKerjas',
            'statusOptions',
            'contractStatusOptions',
            'educationLevelOptions',
            'expiredContractsCount',
            'expiringContractsCount',
            'jenisKelaminOptions',
            'departemenOptions',
            'jabatanOptions',
            'wilayahKerjaOptions',
            'kontrakOptions',
            'currentFilters',
            'dataKaryawans' // Add this for summary statistics
        ));
    }

    // ... Rest of the methods remain the same ...
    public function create()
    {
        // Generate automatic ID for main record
        $newId = $this->generateId('202', '202_dm_data_kontrak');

        // Get master data for dropdowns
        $karyawans = DataKaryawan::where('sts_kry', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->get();
        $kontrakTypes = KontrakKerja::orderBy('kode_ktr', 'asc')->get();
        $perusahaans = Perusahaan::orderBy('kode_prs', 'asc')->get();
        $departemens = Departemen::sortByCode()->get();
        $wilayahKerjas = WilayahKerja::orderBy('wilayah_krj', 'asc')->get();

        return view('data.data-kontrak.create', compact(
            'newId',
            'karyawans',
            'kontrakTypes',
            'perusahaans',
            'departemens',
            'wilayahKerjas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Employee and contract basic info
            'id_data_kry' => 'required|exists:201_dm_data_karyawan,id',
            'id_ktr' => 'required|exists:104_dm_kontrak,id',
            'id_prsh' => 'required|exists:101_dm_perusahaan,id',

            // Contract details
            'no_srt_ktr' => 'nullable|string|max:100|unique:202_dm_data_kontrak,no_srt_ktr',
            'tgl_srt_ktr' => 'nullable|date',
            'tgl_awl_ktr' => 'required|date',
            'tgl_lahir' => 'nullable|date',
            'ktg_ktk' => 'nullable|string|max:50',
            'tgl_akhir_ktr' => 'nullable|date|after:tgl_awl_ktr',
            'durasi_ktr' => 'nullable|integer|min:1',
            'tgl_pgt_ktr' => 'nullable|date|after:tgl_awl_ktr',
            'durasi_pgt' => 'nullable|integer|min:1',
            'sts_srt_ktr' => 'required|in:AKTIF,NON-AKTIF,EXPIRED,PENDING',
            'tgl_sr_na' => 'nullable|date',
            'ket_sr_na' => 'nullable|string|max:255',

            // Education
            'jenjang_skl' => 'nullable|string|max:50',
            'institusi_skl' => 'nullable|string|max:255',
            'kota_skl' => 'nullable|string|max:100',
            'fakultas_skl' => 'nullable|string|max:255',
            'jurusan_skl' => 'nullable|string|max:255',
            'gelar_skl' => 'nullable|string|max:100',
            'tgl_lulus_skl' => 'nullable|date',

            // Career
            'id_departemen' => 'nullable|exists:103_dm_departemen,id',
            'id_wilker' => 'nullable|exists:102_dm_wilker,id',
            'tugas' => 'nullable|string',

            // File upload
            'file_doc_ktr' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120', // 5MB max
        ]);

        // Generate IDs
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('202', '202_dm_data_kontrak');
        } else {
            $id_kode = $request->id_kode;
        }

        // Handle file upload
        $fileDocKtr = null;
        if ($request->hasFile('file_doc_ktr')) {
            $file = $request->file('file_doc_ktr');
            $fileName = time() . '_' . $id_kode . '_' . $file->getClientOriginalName();
            $file->storeAs('public/kontrak/dokumen', $fileName);
            $fileDocKtr = 'kontrak/dokumen/' . $fileName;
        }

        // Calculate contract duration if not provided
        $durasi_ktr = $request->durasi_ktr;
        if (!$durasi_ktr && $request->tgl_awl_ktr && $request->tgl_akhir_ktr) {
            $startDate = \Carbon\Carbon::parse($request->tgl_awl_ktr);
            $endDate = \Carbon\Carbon::parse($request->tgl_akhir_ktr);
            $durasi_ktr = $startDate->diffInMonths($endDate);
        }

        if ($request->sts_srt_ktr === 'AKTIF') {
            $existingActiveContract = DataKontrak::where('id_data_kry', $request->id_data_kry)
                ->where('sts_srt_ktr', 'AKTIF')
                ->first();

            if ($existingActiveContract) {
                return redirect()->back()
                    ->withErrors(['sts_srt_ktr' => 'Karyawan sudah memiliki kontrak aktif. Hanya diperbolehkan 1 kontrak aktif per karyawan.'])
                    ->withInput();
            }
        }

        $dataKontrak = DataKontrak::create([
            // Employee and contract basic info
            'id_data_ktr' => $this->generateAutoIncrement(),
            'id_data_kry' => $request->id_data_kry,
            'id_ktr' => $request->id_ktr,
            'id_prsh' => $request->id_prsh,

            // Contract details
            'no_srt_ktr' => $request->no_srt_ktr,
            'tgl_srt_ktr' => $request->tgl_srt_ktr,
            'tgl_awl_ktr' => $request->tgl_awl_ktr,
            'tgl_lahir' => $request->tgl_lahir,
            'ktg_ktk' => $request->ktg_ktk,
            'tgl_akhir_ktr' => $request->tgl_akhir_ktr,
            'durasi_ktr' => $durasi_ktr,
            'tgl_pgt_ktr' => $request->tgl_pgt_ktr,
            'durasi_pgt' => $request->durasi_pgt,
            'file_doc_ktr' => $fileDocKtr,
            'sts_srt_ktr' => $request->sts_srt_ktr,
            'tgl_sr_na' => $request->tgl_sr_na,
            'ket_sr_na' => $request->ket_sr_na,

            // Education - auto-increment
            'id_pendidikan' => $this->generateAutoIncrement(),
            'jenjang_skl' => $request->jenjang_skl,
            'institusi_skl' => $request->institusi_skl,
            'skt_inst_skl' => $request->skt_inst_skl,
            'kota_skl' => $request->kota_skl,
            'fakultas_skl' => $request->fakultas_skl,
            'jurusan_skl' => $request->jurusan_skl,
            'gelar_skl' => $request->gelar_skl,
            'tgl_lulus_skl' => $request->tgl_lulus_skl,

            // Career - auto-increment
            'id_karir' => $this->generateAutoIncrement(),
            'id_departemen' => $request->id_departemen,
            'id_wilker' => $request->id_wilker,
            'tugas' => $request->tugas,

            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('data-kontrak.index')
            ->with('success', 'Data kontrak berhasil dibuat.');
    }

    public function show($id)
    {
        $dataKontrak = DataKontrak::with([
            'karyawan',
            'kontrakKerja',
            'perusahaan',
            'departemen',
            'wilayahKerja',
            'creator',
            'updater'
        ])->findOrFail($id);

        // Get all related data for this employee
        $allContracts = $dataKontrak->allEmployeeContracts();
        $allEducation = $dataKontrak->allEmployeeEducation();
        $allCareers = $dataKontrak->allEmployeeCareers();

        return view('data.data-kontrak.show', compact(
            'dataKontrak',
            'allContracts',
            'allEducation',
            'allCareers'
        ));
    }

    public function edit($id)
    {
        $dataKontrak = DataKontrak::findOrFail($id);

        // Get all related data for this employee to show in tabs
        $allContracts = $dataKontrak->allEmployeeContracts();
        $allEducation = $dataKontrak->allEmployeeEducation();
        $allCareers = $dataKontrak->allEmployeeCareers();

        // Get master data for dropdowns
        $karyawans = DataKaryawan::where('sts_kry', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->get();
        $kontrakTypes = KontrakKerja::orderBy('kode_ktr', 'asc')->get();
        $perusahaans = Perusahaan::orderBy('kode_prs', 'asc')->get();
        $departemens = Departemen::sortByCode()->get();
        $wilayahKerjas = WilayahKerja::orderBy('wilayah_krj', 'asc')->get();

        return view('data.data-kontrak.edit', compact(
            'dataKontrak',
            'allContracts',
            'allEducation',
            'allCareers',
            'karyawans',
            'kontrakTypes',
            'perusahaans',
            'departemens',
            'wilayahKerjas'
        ));
    }

    public function update(Request $request, $id)
    {
        $dataKontrak = DataKontrak::findOrFail($id);

        // Determine which data we're updating based on form submission
        $updateType = $request->input('update_type', 'main');

        switch ($updateType) {
            case 'education':
                return $this->updateEducation($request, $dataKontrak);
            case 'career':
                return $this->updateCareer($request, $dataKontrak);
            default:
                return $this->updateMainData($request, $dataKontrak);
        }
    }

    private function updateMainData(Request $request, $dataKontrak)
    {
        $request->validate([
            'id_data_kry' => 'required|exists:201_dm_data_karyawan,id',
            'id_ktr' => 'required|exists:104_dm_kontrak,id',
            'id_prsh' => 'required|exists:101_dm_perusahaan,id',
            'ktg_ktk' => 'required|in:TETAP,TIDAK TETAP',
        ]);

        $dataKontrak->update([
            'id_data_kry' => $request->id_data_kry,
            'id_ktr' => $request->id_ktr,
            'id_prsh' => $request->id_prsh,
            'ktg_ktk' => $request->ktg_ktk,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('data-kontrak.edit', $dataKontrak->id)
            ->with('success', 'Data utama kontrak berhasil diperbarui.');
    }

    private function updateEducation(Request $request, $dataKontrak)
    {
        $request->validate([
            'jenjang_skl' => 'nullable|string|max:50',
            'institusi_skl' => 'nullable|string|max:255',
            'jurusan_skl' => 'nullable|string|max:255',
            'tgl_lulus_skl' => 'nullable|date',
        ]);

        $dataKontrak->update([
            'jenjang_skl' => $request->jenjang_skl,
            'institusi_skl' => $request->institusi_skl,
            'skt_inst_skl' => $request->skt_inst_skl,
            'kota_skl' => $request->kota_skl,
            'fakultas_skl' => $request->fakultas_skl,
            'jurusan_skl' => $request->jurusan_skl,
            'gelar_skl' => $request->gelar_skl,
            'tgl_lulus_skl' => $request->tgl_lulus_skl,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('data-kontrak.edit', $dataKontrak->id)
            ->with('success', 'Data pendidikan berhasil diperbarui.');
    }

    private function updateCareer(Request $request, $dataKontrak)
    {
        $request->validate([
            'id_departemen' => 'nullable|exists:103_dm_departemen,id',
            'id_wilker' => 'nullable|exists:102_dm_wilker,id',
            'tugas' => 'nullable|string',
        ]);

        $dataKontrak->update([
            'id_departemen' => $request->id_departemen,
            'id_wilker' => $request->id_wilker,
            'tugas' => $request->tugas,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('data-kontrak.edit', $dataKontrak->id)
            ->with('success', 'Data karir berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $dataKontrak = DataKontrak::findOrFail($id);
            $employeeId = $dataKontrak->id_data_kry;
            $employeeName = $dataKontrak->karyawan->nama ?? 'Unknown';

            // Get all contracts for this employee
            $allEmployeeContracts = DataKontrak::where('id_data_kry', $employeeId)->get();

            // Delete all associated files for this employee's contracts
            foreach ($allEmployeeContracts as $contract) {
                if ($contract->file_doc_ktr && Storage::exists('public/' . $contract->file_doc_ktr)) {
                    Storage::delete('public/' . $contract->file_doc_ktr);
                }
            }

            // Delete all contracts for this employee
            $deletedCount = DataKontrak::where('id_data_kry', $employeeId)->delete();

            DB::commit();

            return redirect()->route('data-kontrak.index')
                ->with('success', "Semua data kontrak karyawan {$employeeName} berhasil dihapus. Total {$deletedCount} record dihapus.");
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('data-kontrak.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data kontrak: ' . $e->getMessage());
        }
    }

    // ================================================ CONTRACT MANAGEMENT METHODS ===================================== //
    // ========================================================================================================== //

    /**
     * Store a new contract for an employee
     */
    public function storeContract(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:201_dm_data_karyawan,id',
            'id_ktr' => 'required|exists:104_dm_kontrak,id',
            'id_prsh' => 'required|exists:101_dm_perusahaan,id',
            'no_srt_ktr' => 'nullable|string|max:100|unique:202_dm_data_kontrak,no_srt_ktr',
            'tgl_srt_ktr' => 'nullable|date',
            'tgl_awl_ktr' => 'required|date',
            'tgl_akhir_ktr' => 'nullable|date|after:tgl_awl_ktr',
            'tgl_pgt_ktr' => 'nullable|date',
            'sts_srt_ktr' => 'required|in:AKTIF,NON-AKTIF,EXPIRED,PENDING',
            'ktg_ktk' => 'required|in:TETAP,TIDAK TETAP', // DIPERBAIKI
            'tgl_sr_na' => 'nullable|date',
            'ket_sr_na' => 'nullable|string|max:255',
        ]);

        // VALIDASI: hanya boleh ada 1 kontrak aktif per karyawan
        if ($request->sts_srt_ktr === 'AKTIF') {
            $existingActiveContract = DataKontrak::where('id_data_kry', $request->employee_id)
                ->where('sts_srt_ktr', 'AKTIF')
                ->first();

            if ($existingActiveContract) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan sudah memiliki kontrak aktif. Hanya diperbolehkan 1 kontrak aktif per karyawan.',
                    'existing_contract' => [
                        'no_srt_ktr' => $existingActiveContract->no_srt_ktr,
                        'tgl_awl_ktr' => $existingActiveContract->tgl_awl_ktr,
                        'tgl_akhir_ktr' => $existingActiveContract->tgl_akhir_ktr
                    ]
                ], 400);
            }
        }

        try {
            DB::beginTransaction();

            // Calculate duration if dates provided
            $durasi_ktr = null;
            if ($request->tgl_awl_ktr && $request->tgl_akhir_ktr) {
                $startDate = \Carbon\Carbon::parse($request->tgl_awl_ktr);
                $endDate = \Carbon\Carbon::parse($request->tgl_akhir_ktr);
                $durasi_ktr = $startDate->diffInMonths($endDate);
            }

            // Calculate reminder duration if dates provided
            $durasi_pgt = null;
            if ($request->tgl_pgt_ktr && $request->tgl_akhir_ktr) {
                $reminderDate = \Carbon\Carbon::parse($request->tgl_pgt_ktr);
                $endDate = \Carbon\Carbon::parse($request->tgl_akhir_ktr);
                $durasi_pgt = $reminderDate->diffInDays($endDate);
            }

            // Handle file upload if exists
            $fileDocKtr = null;
            if ($request->hasFile('file_doc_ktr')) {
                $file = $request->file('file_doc_ktr');
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/kontrak/dokumen', $fileName);
                $fileDocKtr = 'kontrak/dokumen/' . $fileName;
            }

            $contract = DataKontrak::create([
                'id_data_ktr' => $this->generateAutoIncrement(),
                'id_data_kry' => $request->employee_id,
                'id_ktr' => $request->id_ktr,
                'id_prsh' => $request->id_prsh,
                'no_srt_ktr' => $request->no_srt_ktr,
                'tgl_srt_ktr' => $request->tgl_srt_ktr,
                'tgl_awl_ktr' => $request->tgl_awl_ktr,
                'tgl_akhir_ktr' => $request->tgl_akhir_ktr,
                'durasi_ktr' => $durasi_ktr,
                'tgl_pgt_ktr' => $request->tgl_pgt_ktr,
                'durasi_pgt' => $durasi_pgt,
                'sts_srt_ktr' => $request->sts_srt_ktr,
                'ktg_ktk' => $request->ktg_ktk, // DIPERBAIKI
                'file_doc_ktr' => $fileDocKtr,
                'tgl_sr_na' => $request->tgl_sr_na,
                'ket_sr_na' => $request->ket_sr_na,
                'created_by' => auth()->user()->id_kode ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kontrak berhasil ditambahkan.',
                'data' => $contract
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan kontrak: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get contract details
     */
    public function getContract($id)
    {
        try {
            $contract = DataKontrak::with(['kontrakKerja', 'perusahaan'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $contract
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kontrak tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update contract
     */
    public function updateContract(Request $request, $id)
    {
        $request->validate([
            'id_ktr' => 'required|exists:104_dm_kontrak,id',
            'id_prsh' => 'required|exists:101_dm_perusahaan,id',
            'no_srt_ktr' => 'nullable|string|max:100',
            'tgl_srt_ktr' => 'nullable|date',
            'tgl_awl_ktr' => 'required|date',
            'tgl_akhir_ktr' => 'nullable|date|after:tgl_awl_ktr',
            'tgl_pgt_ktr' => 'nullable|date',
            'sts_srt_ktr' => 'required|in:AKTIF,NON-AKTIF,EXPIRED,PENDING',
            'ktg_ktk' => 'required|in:TETAP,TIDAK TETAP', // DIPERBAIKI
            'tgl_sr_na' => 'nullable|date',
            'ket_sr_na' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $contract = DataKontrak::findOrFail($id);

            // VALIDASI: jika status diubah menjadi AKTIF, pastikan tidak ada kontrak aktif lain
            if ($request->sts_srt_ktr === 'AKTIF' && $contract->sts_srt_ktr !== 'AKTIF') {
                $existingActiveContract = DataKontrak::where('id_data_kry', $contract->id_data_kry)
                    ->where('sts_srt_ktr', 'AKTIF')
                    ->where('id', '!=', $id)
                    ->first();

                if ($existingActiveContract) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Karyawan sudah memiliki kontrak aktif lain. Hanya diperbolehkan 1 kontrak aktif per karyawan.',
                        'existing_contract' => [
                            'no_srt_ktr' => $existingActiveContract->no_srt_ktr,
                            'tgl_awl_ktr' => $existingActiveContract->tgl_awl_ktr,
                            'tgl_akhir_ktr' => $existingActiveContract->tgl_akhir_ktr
                        ]
                    ], 400);
                }
            }

            // Calculate duration if dates provided
            $durasi_ktr = $contract->durasi_ktr; // Keep existing if not recalculated
            if ($request->tgl_awl_ktr && $request->tgl_akhir_ktr) {
                $startDate = \Carbon\Carbon::parse($request->tgl_awl_ktr);
                $endDate = \Carbon\Carbon::parse($request->tgl_akhir_ktr);
                $durasi_ktr = $startDate->diffInMonths($endDate);
            }

            // Calculate reminder duration if dates provided
            $durasi_pgt = $contract->durasi_pgt; // Keep existing if not recalculated
            if ($request->tgl_pgt_ktr && $request->tgl_akhir_ktr) {
                $reminderDate = \Carbon\Carbon::parse($request->tgl_pgt_ktr);
                $endDate = \Carbon\Carbon::parse($request->tgl_akhir_ktr);
                $durasi_pgt = $reminderDate->diffInDays($endDate);
            }

            // Handle file upload if exists
            $fileDocKtr = $contract->file_doc_ktr; // Keep existing file
            if ($request->hasFile('file_doc_ktr')) {
                // Delete old file if exists
                if ($contract->file_doc_ktr && Storage::exists('public/' . $contract->file_doc_ktr)) {
                    Storage::delete('public/' . $contract->file_doc_ktr);
                }

                $file = $request->file('file_doc_ktr');
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/kontrak/dokumen', $fileName);
                $fileDocKtr = 'kontrak/dokumen/' . $fileName;
            }

            // UPDATE SEMUA FIELD - DIPERBAIKI LENGKAP
            $contract->update([
                'id_ktr' => $request->id_ktr,
                'id_prsh' => $request->id_prsh,
                'no_srt_ktr' => $request->no_srt_ktr,
                'tgl_srt_ktr' => $request->tgl_srt_ktr,
                'tgl_awl_ktr' => $request->tgl_awl_ktr,
                'tgl_akhir_ktr' => $request->tgl_akhir_ktr,
                'durasi_ktr' => $durasi_ktr,
                'tgl_pgt_ktr' => $request->tgl_pgt_ktr,
                'durasi_pgt' => $durasi_pgt,
                'sts_srt_ktr' => $request->sts_srt_ktr,
                'ktg_ktk' => $request->ktg_ktk, // DIPERBAIKI - FIELD INI YANG HILANG
                'file_doc_ktr' => $fileDocKtr,
                'tgl_sr_na' => $request->tgl_sr_na, // DIPERBAIKI
                'ket_sr_na' => $request->ket_sr_na, // DIPERBAIKI
                'updated_by' => auth()->user()->id_kode ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kontrak berhasil diperbarui.',
                'data' => $contract->fresh() // Refresh data
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate kontrak: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete contract
     */
    public function deleteContract($id)
    {
        try {
            DB::beginTransaction();

            $contract = DataKontrak::findOrFail($id);
            $employeeName = $contract->karyawan->nama ?? 'N/A';

            // Check if this is the only contract for the employee
            $employeeContractsCount = DataKontrak::where('id_data_kry', $contract->id_data_kry)->count();

            if ($employeeContractsCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat menghapus kontrak terakhir karyawan {$employeeName}. Setiap karyawan harus memiliki minimal 1 kontrak dalam sistem."
                ], 400);
            }

            // Delete associated file if exists
            if ($contract->file_doc_ktr && Storage::exists('public/' . $contract->file_doc_ktr)) {
                Storage::delete('public/' . $contract->file_doc_ktr);
            }

            $contract->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kontrak berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kontrak: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================================================ LEGACY METHODS ============================================== //
    // ========================================================================================================== //

    /**
     * Add new contract for employee
     */
    public function addContract(Request $request)
    {
        $request->validate([
            'id_data_kry' => 'required|exists:201_dm_data_karyawan,id',
            'id_ktr' => 'required|exists:104_dm_kontrak,id',
            'id_prsh' => 'required|exists:101_dm_perusahaan,id',
            'no_srt_ktr' => 'nullable|string|max:100',
            'tgl_srt_ktr' => 'nullable|date',
            'tgl_awl_ktr' => 'required|date',
            'tgl_akhir_ktr' => 'nullable|date|after:tgl_awl_ktr',
            'sts_srt_ktr' => 'required|in:AKTIF,NON-AKTIF,EXPIRED,PENDING',
        ]);

        // Calculate duration
        $durasi_ktr = null;
        if ($request->tgl_awl_ktr && $request->tgl_akhir_ktr) {
            $startDate = \Carbon\Carbon::parse($request->tgl_awl_ktr);
            $endDate = \Carbon\Carbon::parse($request->tgl_akhir_ktr);
            $durasi_ktr = $startDate->diffInMonths($endDate);
        }

        DataKontrak::create([
            'id_data_ktr' => $this->generateAutoIncrement(),
            'id_data_kry' => $request->id_data_kry,
            'id_ktr' => $request->id_ktr,
            'id_prsh' => $request->id_prsh,
            'no_srt_ktr' => $request->no_srt_ktr,
            'tgl_srt_ktr' => $request->tgl_srt_ktr,
            'tgl_awl_ktr' => $request->tgl_awl_ktr,
            'tgl_akhir_ktr' => $request->tgl_akhir_ktr,
            'durasi_ktr' => $durasi_ktr,
            'tgl_pgt_ktr' => $request->tgl_pgt_ktr,
            'sts_srt_ktr' => $request->sts_srt_ktr,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kontrak baru berhasil ditambahkan.'
        ]);
    }

    /**
     * Add new education record for employee
     */
    public function addEducation(Request $request)
    {
        $request->validate([
            'id_data_kry' => 'required|exists:201_dm_data_karyawan,id',
            'jenjang_skl' => 'required|string|max:50',
            'institusi_skl' => 'required|string|max:255',
            'jurusan_skl' => 'nullable|string|max:255',
            'tgl_lulus_skl' => 'nullable|date',
        ]);

        DataKontrak::create([
            'id_data_kry' => $request->id_data_kry,
            'id_pendidikan' => $this->generateAutoIncrement(),
            'jenjang_skl' => $request->jenjang_skl,
            'institusi_skl' => $request->institusi_skl,
            'skt_inst_skl' => $request->skt_inst_skl,
            'kota_skl' => $request->kota_skl,
            'fakultas_skl' => $request->fakultas_skl,
            'jurusan_skl' => $request->jurusan_skl,
            'gelar_skl' => $request->gelar_skl,
            'tgl_lulus_skl' => $request->tgl_lulus_skl,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pendidikan baru berhasil ditambahkan.'
        ]);
    }

    /**
     * Add new career record for employee
     */
    public function addCareer(Request $request)
    {
        $request->validate([
            'id_data_kry' => 'required|exists:201_dm_data_karyawan,id',
            'id_departemen' => 'required|exists:103_dm_departemen,id',
            'id_wilker' => 'nullable|exists:102_dm_wilker,id',
            'tugas' => 'nullable|string',
        ]);

        DataKontrak::create([
            'id_data_kry' => $request->id_data_kry,
            'id_karir' => $this->generateAutoIncrement(),
            'id_departemen' => $request->id_departemen,
            'id_wilker' => $request->id_wilker,
            'tugas' => $request->tugas,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data karir baru berhasil ditambahkan.'
        ]);
    }

    /**
     * Generate auto-increment ID for related tables
     */
    private function generateAutoIncrement(): int
    {
        return (int) now()->format('YmdHis') + rand(1000, 9999);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getFilteredData($request->all());

        $currentDate = now()->format('d-m-Y_H-i-s');
        $fileName = 'Data_Kontrak_' . $currentDate . '.xlsx';

        return Excel::download(
            new DataKontrakExport($data['dataKontraks'], $data['filters']),
            $fileName
        );
    }

    private function getFilteredData($filters)
    {
        // Instead of using uniqueEmployees() scope, get all contracts
        $query = DataKontrak::with([
            'karyawan',
            'kontrakKerja',
            'perusahaan',
            'departemen',
            'wilayahKerja'
        ]); // Apply unique employees scope

        // Apply filters (same as index method)
        if (!empty($filters['filter_status'])) {
            $query->where('sts_srt_ktr', $filters['filter_status']);
        }

        if (!empty($filters['filter_perusahaan'])) {
            $query->where('id_prsh', $filters['filter_perusahaan']);
        }

        if (!empty($filters['filter_kontrak_type'])) {
            $query->where('id_ktr', $filters['filter_kontrak_type']);
        }

        if (!empty($filters['filter_departemen'])) {
            $query->where('id_departemen', $filters['filter_departemen']);
        }

        if (!empty($filters['filter_wilayah_kerja'])) {
            $query->where('id_wilker', $filters['filter_wilayah_kerja']);
        }

        if (!empty($filters['filter_contract_status'])) {
            switch ($filters['filter_contract_status']) {
                case 'active':
                    $query->where('sts_srt_ktr', 'AKTIF')
                        ->whereNotNull('tgl_awl_ktr')
                        ->whereNotNull('tgl_akhir_ktr')
                        ->whereRaw('STR_TO_DATE(tgl_awl_ktr, "%Y-%m-%d") <= CURDATE()')
                        ->whereRaw('STR_TO_DATE(tgl_akhir_ktr, "%Y-%m-%d") >= CURDATE()');
                    break;
                case 'expired':
                    $query->expired();
                    break;
                case 'expiring_soon':
                    $query->expiringSoon(30);
                    break;
            }
        }

        if (!empty($filters['filter_education_level'])) {
            $query->where('jenjang_skl', $filters['filter_education_level']);
        }

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        $dataKontraks = $query->orderBy('created_at', 'desc')->get();

        return [
            'dataKontraks' => $dataKontraks,
            'filters' => $filters
        ];
    }

    /**
     * Get employee data for AJAX call (enhanced version)
     */
    public function getEmployeeData($id)
    {
        try {
            $employee = DataKaryawan::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    // Basic employee info
                    'nik' => $employee->nik,
                    'nrk' => $employee->nrk,
                    'nama' => $employee->nama,
                    'tpt_lahir' => $employee->tpt_lahir,
                    'tgl_lahir' => $employee->tgl_lahir ? $employee->tgl_lahir->format('Y-m-d') : null,
                    'tgl_lahir_formatted' => $employee->tgl_lahir ? $employee->tgl_lahir->format('d-m-Y') : null,
                    'sex' => $employee->sex,
                    'agama' => $employee->agama,
                    'kewarganegaraan' => $employee->kewarganegaraan,
                    'sts_nikah' => $employee->sts_nikah,
                    'sts_keluarga' => $employee->sts_keluarga,
                    'jml_anak' => $employee->jml_anak,

                    // Contact info
                    'tlp1' => $employee->tlp1,
                    'tlp2' => $employee->tlp2,
                    'email1' => $employee->email1,
                    'email2' => $employee->email2,
                    'instagram' => $employee->instagram,
                    'facebook' => $employee->facebook,

                    // Address info
                    'alamat_ktp' => $employee->alamat_ktp,
                    'alamat_dom' => $employee->alamat_dom,
                    'rt_rw_ktp' => $employee->rt_rw_ktp,
                    'rt_rw_dom' => $employee->rt_rw_dom,
                    'kel_ktp' => $employee->kel_ktp,
                    'kel_dom' => $employee->kel_dom,
                    'kec_ktp' => $employee->kec_ktp,
                    'kec_dom' => $employee->kec_dom,
                    'kota_ktp' => $employee->kota_ktp,
                    'kota_dom' => $employee->kota_dom,
                    'prov_ktp' => $employee->prov_ktp,
                    'prov_dom' => $employee->prov_dom,
                    'kd_pos_ktp' => $employee->kd_pos_ktp,
                    'kd_pos_dom' => $employee->kd_pos_dom,

                    // Employment info
                    'nrk' => $employee->nrk,
                    'tgl_masuk' => $employee->tgl_masuk ? $employee->tgl_masuk->format('Y-m-d') : null,
                    'tgl_masuk_formatted' => $employee->tgl_masuk ? $employee->tgl_masuk->format('d-m-Y') : null,
                    'sts_kry' => $employee->sts_kry,
                    'id_hubin' => $employee->id_hubin,
                    'tgl_phk' => $employee->tgl_phk ? $employee->tgl_phk->format('Y-m-d') : null,
                    'tgl_phk_formatted' => $employee->tgl_phk ? $employee->tgl_phk->format('d-m-Y') : null,
                    'ket_phk' => $employee->ket_phk,

                    // Education info
                    'jenjang_skl' => $employee->jenjang_skl,
                    'institusi_skl' => $employee->institusi_skl,
                    'skt_inst_skl' => $employee->skt_inst_skl,
                    'kota_skl' => $employee->kota_skl,
                    'fakultas_skl' => $employee->fakultas_skl,
                    'jurusan_skl' => $employee->jurusan_skl,
                    'gelar_skl' => $employee->gelar_skl,
                    'tgl_lulus_skl' => $employee->tgl_lulus_skl ? $employee->tgl_lulus_skl->format('Y-m-d') : null,
                    'tgl_lulus_skl_formatted' => $employee->tgl_lulus_skl ? $employee->tgl_lulus_skl->format('d-m-Y') : null,

                    // Contract info
                    'perusahaan' => $employee->perusahaan,
                    'skt_prs' => $employee->skt_prs,
                    'tgl_awal_ktr' => $employee->tgl_awal_ktr ? $employee->tgl_awal_ktr->format('Y-m-d') : null,
                    'tgl_awal_ktr_formatted' => $employee->tgl_awal_ktr ? $employee->tgl_awal_ktr->format('d-m-Y') : null,
                    'tgl_akhir_ktr' => $employee->tgl_akhir_ktr ? $employee->tgl_akhir_ktr->format('Y-m-d') : null,
                    'tgl_akhir_ktr_formatted' => $employee->tgl_akhir_ktr ? $employee->tgl_akhir_ktr->format('d-m-Y') : null,
                    'durasi_ktr' => $employee->durasi_ktr,

                    // Career info
                    'departemen' => $employee->departemen,
                    'skt_dep' => $employee->skt_dep,
                    'jabatan' => $employee->jabatan,
                    'skt_jbt' => $employee->skt_jbt,
                    'wilker' => $employee->wilker,
                    'skt_wil_krj' => $employee->skt_wil_krj,
                    'unit_krj' => $employee->unit_krj,
                    'tugas' => $employee->tugas,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get jabatan by departemen name (sama seperti data-karyawan)
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
     * Get unit kerja by wilayah name (sama seperti data-karyawan)
     */
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
     * Get departemen jabatan by ID (sama seperti data-karyawan)
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
     * Get wilker unit kerja by ID (diperbaiki untuk konsistensi)
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

    /**
     * Get contracts expiring soon for dashboard/notifications
     */
    public function getExpiringContracts(Request $request)
    {
        $days = $request->get('days', 30);

        $expiring = DataKontrak::with(['karyawan', 'kontrakKerja', 'perusahaan'])
            ->uniqueEmployees() // Only unique employees
            ->expiringSoon($days)
            ->where('sts_srt_ktr', 'AKTIF')
            ->orderBy('tgl_akhir_ktr', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $expiring->count(),
            'data' => $expiring->map(function ($kontrak) {
                return [
                    'id' => $kontrak->id,
                    'employee_name' => $kontrak->karyawan->nama ?? 'N/A',
                    'employee_nrk' => $kontrak->karyawan->nrk ?? 'N/A',
                    'contract_type' => $kontrak->kontrakKerja->nama_ktr ?? 'N/A',
                    'company' => $kontrak->perusahaan->nama_prs2 ?? 'N/A',
                    'start_date' => $kontrak->tgl_awl_ktr,
                    'end_date' => $kontrak->tgl_akhir_ktr,
                    'days_until_expiry' => $kontrak->days_until_expiry,
                    'is_active' => $kontrak->is_active,
                    'is_expired' => $kontrak->is_expired,
                ];
            })
        ]);
    }

    private function generateRandom(): int
    {
        return random_int(1, 9999);
    }

    public function getActiveContractsData()
    {
        try {
            // Get all employees with their active contracts
            $activeContracts = DataKontrak::select('id_data_kry')
                ->distinct()
                ->get()
                ->mapWithKeys(function ($item) {
                    $employeeId = $item->id_data_kry;

                    $activeContract = DataKontrak::where('id_data_kry', $employeeId)
                        ->where('sts_srt_ktr', 'AKTIF')
                        ->whereNotNull('tgl_akhir_ktr')
                        ->orderBy('tgl_akhir_ktr', 'desc')
                        ->first();

                    if (!$activeContract) {
                        return [$employeeId => null];
                    }

                    $endDate = \Carbon\Carbon::parse($activeContract->tgl_akhir_ktr);
                    $reminderDate = $activeContract->tgl_pgt_ktr ? \Carbon\Carbon::parse($activeContract->tgl_pgt_ktr) : null;
                    $today = \Carbon\Carbon::now()->startOf('day');

                    $daysUntilExpiry = $today->diffInDays($endDate, false);
                    $daysUntilReminder = $reminderDate ? $today->diffInDays($reminderDate, false) : null;

                    return [$employeeId => [
                        'contract_id' => $activeContract->id,
                        'end_date' => $activeContract->tgl_akhir_ktr,
                        'reminder_date' => $activeContract->tgl_pgt_ktr,
                        'days_until_expiry' => $daysUntilExpiry,
                        'days_until_reminder' => $daysUntilReminder,
                        'is_expired' => $daysUntilExpiry < 0,
                        'is_urgent' => $daysUntilExpiry >= 0 && $daysUntilExpiry <= 7,
                        'is_warning' => $daysUntilExpiry >= 0 && $daysUntilExpiry <= 30,
                    ]];
                });

            return response()->json([
                'success' => true,
                'data' => $activeContracts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching active contracts: ' . $e->getMessage()
            ], 500);
        }
    }
    public function checkEmployeeExists($id)
    {
        try {
            $employee = DataKaryawan::findOrFail($id);

            // Check if employee already has contract records
            $existingContracts = DataKontrak::where('id_data_kry', $id)->count();

            if ($existingContracts > 0) {
                // Get latest contract info for better error message
                $latestContract = DataKontrak::where('id_data_kry', $id)
                    ->with(['kontrakKerja', 'perusahaan'])
                    ->orderBy('created_at', 'desc')
                    ->first();

                return response()->json([
                    'success' => false,
                    'exists' => true,
                    'message' => 'Karyawan sudah memiliki data kontrak sebelumnya',
                    'employee_name' => $employee->nama,
                    'employee_nrk' => $employee->nrk,
                    'existing_contracts_count' => $existingContracts,
                    'latest_contract' => [
                        'contract_type' => optional($latestContract->kontrakKerja)->nama_ktr ?? 'N/A',
                        'company' => optional($latestContract->perusahaan)->nama_prs1 ?? 'N/A',
                        'status' => $latestContract->sts_srt_ktr ?? 'N/A',
                        'created_at' => $latestContract->created_at->format('d-m-Y') ?? 'N/A',
                        'contract_number' => $latestContract->no_srt_ktr ?? 'N/A'
                    ]
                ], 409); // 409 Conflict
            }

            return response()->json([
                'success' => true,
                'exists' => false,
                'message' => 'Karyawan belum memiliki data kontrak, dapat dilanjutkan',
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

    private function hasActiveContract($employeeId, $excludeContractId = null)
    {
        $query = DataKontrak::where('id_data_kry', $employeeId)
            ->where('sts_srt_ktr', 'AKTIF');

        if ($excludeContractId) {
            $query->where('id', '!=', $excludeContractId);
        }

        return $query->exists();
    }
}
