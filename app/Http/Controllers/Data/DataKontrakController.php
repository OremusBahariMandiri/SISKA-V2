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
        // Initialize query with relationships
        $query = DataKontrak::with([
            'karyawan',
            'kontrakKerja',
            'perusahaan',
            'departemen',
            'wilayahKerja',
            'creator',
            'updater'
        ]);

        // Apply filters if they exist
        if ($request->has('filter_status') && !empty($request->filter_status)) {
            $query->where('sts_srt_ktr', $request->filter_status);
        }

        if ($request->has('filter_perusahaan') && !empty($request->filter_perusahaan)) {
            $query->where('id_prsh', $request->filter_perusahaan);
        }

        if ($request->has('filter_kontrak_type') && !empty($request->filter_kontrak_type)) {
            $query->where('id_ktr', $request->filter_kontrak_type);
        }

        if ($request->has('filter_departemen') && !empty($request->filter_departemen)) {
            $query->where('id_departemen', $request->filter_departemen);
        }

        if ($request->has('filter_wilayah_kerja') && !empty($request->filter_wilayah_kerja)) {
            $query->where('id_wilker', $request->filter_wilayah_kerja);
        }

        // Filter by contract status (active/expired/expiring soon)
        if ($request->has('filter_contract_status') && !empty($request->filter_contract_status)) {
            switch ($request->filter_contract_status) {
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

        // Education level filter
        if ($request->has('filter_education_level') && !empty($request->filter_education_level)) {
            $query->where('jenjang_skl', $request->filter_education_level);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Get filtered data
        $dataKontraks = $query->orderBy('created_at', 'desc')->get();

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

        // Get user permissions for this menu
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

        // Store current filters for view
        $currentFilters = [
            'status' => $request->filter_status,
            'perusahaan' => $request->filter_perusahaan,
            'kontrak_type' => $request->filter_kontrak_type,
            'departemen' => $request->filter_departemen,
            'wilayah_kerja' => $request->filter_wilayah_kerja,
            'contract_status' => $request->filter_contract_status,
            'education_level' => $request->filter_education_level,
            'search' => $request->search,
        ];

        return view('data.data-kontrak.index', compact(
            'dataKontraks',
            'userPermissions',
            'perusahaans',
            'kontrakTypes',
            'departemens',
            'wilayahKerjas',
            'statusOptions',
            'contractStatusOptions',
            'educationLevelOptions',
            'currentFilters'
        ));
    }

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
            'no_srt_ktr' => 'nullable|string|max:100',
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

        return view('data.data-kontrak.show', compact('dataKontrak'));
    }

    public function edit($id)
    {
        $dataKontrak = DataKontrak::findOrFail($id);

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

        $request->validate([
            // Same validation rules as store
            'id_data_kry' => 'required|exists:201_dm_data_karyawan,id',
            'id_ktr' => 'required|exists:104_dm_kontrak,id',
            'id_prsh' => 'required|exists:101_dm_perusahaan,id',
            'tgl_awl_ktr' => 'required|date',
            'tgl_akhir_ktr' => 'required|date|after:tgl_awl_ktr',
            'sts_srt_ktr' => 'required|in:AKTIF,NON-AKTIF,EXPIRED,PENDING',
            'file_doc_ktr' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        // Handle file upload
        $fileDocKtr = $dataKontrak->file_doc_ktr; // Keep existing file by default
        if ($request->hasFile('file_doc_ktr')) {
            // Delete old file if exists
            if ($dataKontrak->file_doc_ktr && Storage::exists('public/' . $dataKontrak->file_doc_ktr)) {
                Storage::delete('public/' . $dataKontrak->file_doc_ktr);
            }

            // Upload new file
            $file = $request->file('file_doc_ktr');
            $fileName = time() . '_' . $dataKontrak->id . '_' . $file->getClientOriginalName();
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

        $data = [
            'id_data_kry' => $request->id_data_kry,
            'id_ktr' => $request->id_ktr,
            'id_prsh' => $request->id_prsh,
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
            'jenjang_skl' => $request->jenjang_skl,
            'institusi_skl' => $request->institusi_skl,
            'skt_inst_skl' => $request->skt_inst_skl,
            'kota_skl' => $request->kota_skl,
            'fakultas_skl' => $request->fakultas_skl,
            'jurusan_skl' => $request->jurusan_skl,
            'gelar_skl' => $request->gelar_skl,
            'tgl_lulus_skl' => $request->tgl_lulus_skl,
            'id_departemen' => $request->id_departemen,
            'id_wilker' => $request->id_wilker,
            'tugas' => $request->tugas,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        $dataKontrak->update($data);

        return redirect()->route('data-kontrak.index')
            ->with('success', 'Data kontrak berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dataKontrak = DataKontrak::findOrFail($id);

        // Delete associated file if exists
        if ($dataKontrak->file_doc_ktr && Storage::exists('public/' . $dataKontrak->file_doc_ktr)) {
            Storage::delete('public/' . $dataKontrak->file_doc_ktr);
        }

        $dataKontrak->delete();

        return redirect()->route('data-kontrak.index')
            ->with('success', 'Data kontrak berhasil dihapus.');
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
        $query = DataKontrak::with([
            'karyawan',
            'kontrakKerja',
            'perusahaan',
            'departemen',
            'wilayahKerja'
        ]);

        // Apply filters
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
     * Get wilker unit kerja by ID (sama seperti data-karyawan)
     */
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
}
