<?php

namespace App\Http\Controllers\Data;

use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Data\DataJenjangKarir;
use App\Models\Data\DataKaryawan;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\WilayahKerja;
use App\Models\DataMaster\DokumenKaryawan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DataJenjangKarirController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:data-jenjang-karir')->only('index');
        $this->middleware('check.access:data-jenjang-karir,detail')->only('show');
        $this->middleware('check.access:data-jenjang-karir,tambah')->only('create', 'store');
        $this->middleware('check.access:data-jenjang-karir,ubah')->only('edit', 'update');
        $this->middleware('check.access:data-jenjang-karir,hapus')->only('destroy');
    }

    public function index(Request $request)
    {
        // Initialize query with relationships
        $query = DataJenjangKarir::with([
            'karyawan',
            'departemen',
            'wilayahKerja',
            'dokumenKaryawan'
        ]);

        // Get latest career record per employee first
        $query->whereIn('id', function ($subquery) {
            $subquery->select(DB::raw('MAX(id)'))
                ->from('204_dm_data_jenjang_karir')
                ->groupBy('id_karyawan');
        });

        // Apply filters if they exist

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

        // Filter by Departemen (grouped by nama_dep)
        if ($request->has('filter_departemen') && !empty($request->filter_departemen)) {
            $selectedDepartemen = Departemen::find($request->filter_departemen);
            if ($selectedDepartemen) {
                $departemenIds = Departemen::where('nama_dep', $selectedDepartemen->nama_dep)->pluck('id')->toArray();
                $query->whereIn('id_departemen', $departemenIds);
            }
        }

        // Filter by Jabatan (specific position)
        if ($request->has('filter_jabatan') && !empty($request->filter_jabatan)) {
            $query->where('id_departemen', $request->filter_jabatan);
        }

        // Filter by Jenis Kelamin
        if ($request->has('filter_jenis_kelamin') && !empty($request->filter_jenis_kelamin)) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('sex', $request->filter_jenis_kelamin);
            });
        }

        // Filter by Wilayah Kerja (wilker field stores STRING)
        if ($request->has('filter_wilker') && !empty($request->filter_wilker)) {
            $query->whereHas('wilayahKerja', function ($q) use ($request) {
                $q->where('wilayah_krj', $request->filter_wilker);
            });
        }

        // Filter by Unit Kerja (area_krj)
        if ($request->has('filter_unit_kerja') && !empty($request->filter_unit_kerja)) {
            $query->where('id_wilayah_kerja', $request->filter_unit_kerja);
        }

        // Filter by Nomor Jenjang Karir
        if ($request->has('filter_no_jk') && !empty($request->filter_no_jk)) {
            $query->where('no_jk', 'LIKE', '%' . $request->filter_no_jk . '%');
        }

        // Filter by Tanggal TTD Range
        if ($request->has('filter_tgl_ttd_start') && !empty($request->filter_tgl_ttd_start)) {
            $query->where('tgl_ttd', '>=', $request->filter_tgl_ttd_start);
        }
        if ($request->has('filter_tgl_ttd_end') && !empty($request->filter_tgl_ttd_end)) {
            $query->where('tgl_ttd', '<=', $request->filter_tgl_ttd_end);
        }

        // Get filtered data
        $dataJenjangKarirs = $query->orderBy('tgl_ttd', 'desc')->get();

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

        // Calculate career counts per employee
        $careerCounts = [];
        foreach ($dataJenjangKarirs as $karir) {
            $employeeId = $karir->id_karyawan;

            if (!isset($careerCounts[$employeeId])) {
                $totalCareers = DataJenjangKarir::where('id_karyawan', $employeeId)->count();

                $careerCounts[$employeeId] = [
                    'total' => $totalCareers
                ];
            }
        }

        // Get master data for filter dropdowns
        $departemens = Departemen::sortByCode()->get();
        $wilayahKerjas = WilayahKerja::orderBy('wilayah_krj', 'asc')->get();

        // Gender options
        $jenisKelaminOptions = [
            'LAKI-LAKI' => 'Laki-laki',
            'PEREMPUAN' => 'Perempuan'
        ];

        // Get unique departemen names for filter dropdown
        $departemenOptions = Departemen::select('nama_dep', 'singkatan_dep', DB::raw('MIN(id) as id'), DB::raw('MIN(CAST(kode_dep AS UNSIGNED)) as min_kode_dep'))
            ->groupBy('nama_dep', 'singkatan_dep')
            ->orderBy('min_kode_dep', 'asc')
            ->get();

        // Get all jabatan/positions for filter dropdown
        $jabatanOptions = Departemen::sortByCode()->get(['id', 'kode_dep', 'nama_dep', 'nama_jbt', 'singkatan_jbt']);

        // Get UNIQUE wilayah_krj for filter dropdown
        $wilayahKerjaOptions = WilayahKerja::select('wilayah_krj')
            ->groupBy('wilayah_krj')
            ->orderBy('wilayah_krj', 'asc')
            ->get();

        // Unit Kerja options
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
                $access = $user->userAccess()->where('menu_acs', 'data-jenjang-karir')->first();
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
            'nama' => $request->filter_nama ?? '',
            'nrk' => $request->filter_nrk ?? '',
            'departemen' => $request->filter_departemen ?? '',
            'jabatan' => $request->filter_jabatan ?? '',
            'jenis_kelamin' => $request->filter_jenis_kelamin ?? '',
            'wilker' => $request->filter_wilker ?? '',
            'unit_kerja' => $request->filter_unit_kerja ?? '',
            'no_jk' => $request->filter_no_jk ?? '',
            'tgl_ttd_start' => $request->filter_tgl_ttd_start ?? '',
            'tgl_ttd_end' => $request->filter_tgl_ttd_end ?? '',
        ];

        // Get DataKaryawan collection for summary statistics
        $dataKaryawans = collect();
        foreach ($dataJenjangKarirs as $karir) {
            if ($karir->karyawan) {
                $dataKaryawans->push($karir->karyawan);
            }
        }

        return view('data.data-jenjang-karir.index', compact(
            'dataJenjangKarirs',
            'careerCounts',
            'userPermissions',
            'departemens',
            'wilayahKerjas',
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
        $newId = $this->generateId('204', '204_dm_data_jenjang_karir');

        // Get master data for dropdowns
        $karyawans = DataKaryawan::where('sts_kry', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->get();
        $departemens = Departemen::sortByCode()->get();
        $wilayahKerjas = WilayahKerja::orderBy('wilayah_krj', 'asc')->get();
        $dokumenKaryawans = DokumenKaryawan::orderBy('kode_dok_kry', 'asc')->get();

        return view('data.data-jenjang-karir.create', compact(
            'newId',
            'karyawans',
            'departemens',
            'wilayahKerjas',
            'dokumenKaryawans'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Employee basic info
            'id_karyawan' => 'required|exists:201_dm_data_karyawan,id',

            // Career details
            'no_jk' => 'nullable|string|max:100',
            'tgl_ttd' => 'required|date',
            'id_departemen' => 'required|exists:103_dm_departemen,id',
            'id_wilayah_kerja' => 'nullable|exists:102_dm_wilker,id',
            'tugas' => 'nullable|string',
            'id_dokumen_karyawan' => 'nullable|exists:105_dm_dok_kry,id',
            'id_kontrak_kerja' => 'nullable|string',

            // File upload
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120', // 5MB max
        ]);

        // Generate IDs
        $id_jenjang_karir = $this->generateAutoIncrement();

        // Handle file upload
        $fileDokumen = null;
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $fileName = time() . '_' . $id_jenjang_karir . '_' . $file->getClientOriginalName();
            $file->storeAs('public/jenjang-karir/dokumen', $fileName);
            $fileDokumen = 'jenjang-karir/dokumen/' . $fileName;
        }

        try {
            DB::beginTransaction();

            $dataJenjangKarir = DataJenjangKarir::create([
                'id_jenjang_karir' => $id_jenjang_karir,
                'id_karyawan' => $request->id_karyawan,
                'id_dokumen_karyawan' => $request->id_dokumen_karyawan,
                'no_jk' => $request->no_jk,
                'tgl_ttd' => $request->tgl_ttd,
                'id_departemen' => $request->id_departemen,
                'id_wilayah_kerja' => $request->id_wilayah_kerja,
                'tugas' => $request->tugas,
                'file_dokumen' => $fileDokumen,
                'id_kontrak_kerja' => $request->id_kontrak_kerja,
                'created_by' => auth()->user()->id_kode ?? null,
            ]);

            DB::commit();

            return redirect()->route('data-jenjang-karir.index')
                ->with('success', 'Data jenjang karir berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();

            // Delete uploaded file if exists
            if ($fileDokumen && Storage::exists('public/' . $fileDokumen)) {
                Storage::delete('public/' . $fileDokumen);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
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
        ])->findOrFail($id);

        // Get all career history for this employee
        $allCareers = $dataJenjangKarir->allEmployeeCareers();

        // Get user permissions
        $userPermissions = [];
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->is_admin) {
                $userPermissions = [
                    'tambah'     => true,
                    'ubah'       => true,
                    'hapus'      => true,
                    'download'   => true,
                    'detail'     => true,
                    'monitoring' => true,
                ];
            } else {
                $access = $user->userAccess()->where('menu_acs', 'data-jenjang-karir')->first();
                if ($access) {
                    $userPermissions = [
                        'tambah'     => (bool) $access->tambah_acs,
                        'ubah'       => (bool) $access->ubah_acs,
                        'hapus'      => (bool) $access->hapus_acs,
                        'download'   => (bool) $access->download_acs,
                        'detail'     => (bool) $access->detail_acs,
                        'monitoring' => (bool) $access->monitoring_acs,
                    ];
                }
            }
        }

        return view('data.data-jenjang-karir.show', compact(
            'dataJenjangKarir',
            'allCareers',
            'userPermissions'
        ));
    }

    public function edit($id)
    {
        $dataJenjangKarir = DataJenjangKarir::findOrFail($id);

        // Get all career history for this employee
        $allCareers = $dataJenjangKarir->allEmployeeCareers();

        // Get master data for dropdowns
        $karyawans = DataKaryawan::where('sts_kry', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->get();
        $departemens = Departemen::sortByCode()->get();
        $wilayahKerjas = WilayahKerja::orderBy('wilayah_krj', 'asc')->get();
        $dokumenKaryawans = DokumenKaryawan::orderBy('kode_dok_kry', 'asc')->get();

        return view('data.data-jenjang-karir.edit', compact(
            'dataJenjangKarir',
            'allCareers',
            'karyawans',
            'departemens',
            'wilayahKerjas',
            'dokumenKaryawans'
        ));
    }

    public function update(Request $request, $id)
    {
        $dataJenjangKarir = DataJenjangKarir::findOrFail($id);

        $request->validate([
            'id_karyawan' => 'required|exists:201_dm_data_karyawan,id',
            'no_jk' => 'nullable|string|max:100',
            'tgl_ttd' => 'required|date',
            'id_departemen' => 'required|exists:103_dm_departemen,id',
            'id_wilayah_kerja' => 'nullable|exists:102_dm_wilker,id',
            'tugas' => 'nullable|string',
            'id_dokumen_karyawan' => 'nullable|exists:105_dm_dok_kry,id',
            'id_kontrak_kerja' => 'nullable|string',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        try {
            DB::beginTransaction();

            // Handle file upload
            $fileDokumen = $dataJenjangKarir->file_dokumen;
            if ($request->hasFile('file_dokumen')) {
                // Delete old file if exists
                if ($dataJenjangKarir->file_dokumen && Storage::exists('public/' . $dataJenjangKarir->file_dokumen)) {
                    Storage::delete('public/' . $dataJenjangKarir->file_dokumen);
                }

                $file = $request->file('file_dokumen');
                $fileName = time() . '_' . $dataJenjangKarir->id_jenjang_karir . '_' . $file->getClientOriginalName();
                $file->storeAs('public/jenjang-karir/dokumen', $fileName);
                $fileDokumen = 'jenjang-karir/dokumen/' . $fileName;
            }

            $dataJenjangKarir->update([
                'id_karyawan' => $request->id_karyawan,
                'id_dokumen_karyawan' => $request->id_dokumen_karyawan,
                'no_jk' => $request->no_jk,
                'tgl_ttd' => $request->tgl_ttd,
                'id_departemen' => $request->id_departemen,
                'id_wilayah_kerja' => $request->id_wilayah_kerja,
                'tugas' => $request->tugas,
                'file_dokumen' => $fileDokumen,
                'id_kontrak_kerja' => $request->id_kontrak_kerja,
                'updated_by' => auth()->user()->id_kode ?? null,
            ]);

            DB::commit();

            return redirect()->route('data-jenjang-karir.index')
                ->with('success', 'Data jenjang karir berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $dataJenjangKarir = DataJenjangKarir::findOrFail($id);
            $employeeId = $dataJenjangKarir->id_karyawan;
            $employeeName = $dataJenjangKarir->karyawan->nama ?? 'Unknown';

            // Get all career records for this employee
            $allEmployeeCareers = DataJenjangKarir::where('id_karyawan', $employeeId)->get();

            // Delete all associated files for this employee's career records
            foreach ($allEmployeeCareers as $career) {
                if ($career->file_dokumen && Storage::exists('public/' . $career->file_dokumen)) {
                    Storage::delete('public/' . $career->file_dokumen);
                }
            }

            // Delete all career records for this employee
            $deletedCount = DataJenjangKarir::where('id_karyawan', $employeeId)->delete();

            DB::commit();

            // Untuk AJAX request
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Semua data jenjang karir {$employeeName} berhasil dihapus. Total {$deletedCount} record dihapus."
                ]);
            }

            // Untuk non-AJAX request
            return redirect()->route('data-jenjang-karir.index')
                ->with('success', "Semua data jenjang karir {$employeeName} berhasil dihapus. Total {$deletedCount} record dihapus.");
        } catch (\Exception $e) {
            DB::rollback();

            // Untuk AJAX request
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
                ], 500);
            }

            // Untuk non-AJAX request
            return redirect()->route('data-jenjang-karir.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    // ================================================ CAREER MANAGEMENT METHODS ===================================== //
    // ========================================================================================================== //

    /**
     * Store a new career record for an employee
     */
    public function storeCareer(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:201_dm_data_karyawan,id',
            'no_jk' => 'nullable|string|max:100',
            'tgl_ttd' => 'required|date',
            'id_departemen' => 'required|exists:103_dm_departemen,id',
            'id_wilayah_kerja' => 'nullable|exists:102_dm_wilker,id',
            'tugas' => 'nullable|string',
            'id_dokumen_karyawan' => 'nullable|exists:105_dm_dok_kry,id',
            'id_kontrak_kerja' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Handle file upload if exists
            $fileDokumen = null;
            if ($request->hasFile('file_dokumen')) {
                $file = $request->file('file_dokumen');
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/jenjang-karir/dokumen', $fileName);
                $fileDokumen = 'jenjang-karir/dokumen/' . $fileName;
            }

            $career = DataJenjangKarir::create([
                'id_jenjang_karir' => $this->generateAutoIncrement(),
                'id_karyawan' => $request->employee_id,
                'id_dokumen_karyawan' => $request->id_dokumen_karyawan,
                'no_jk' => $request->no_jk,
                'tgl_ttd' => $request->tgl_ttd,
                'id_departemen' => $request->id_departemen,
                'id_wilayah_kerja' => $request->id_wilayah_kerja,
                'tugas' => $request->tugas,
                'file_dokumen' => $fileDokumen,
                'id_kontrak_kerja' => $request->id_kontrak_kerja,
                'created_by' => auth()->user()->id_kode ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data jenjang karir berhasil ditambahkan.',
                'data' => $career
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data jenjang karir: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get career details
     */
    public function getCareer($id)
    {
        try {
            $career = DataJenjangKarir::with([
                'departemen',
                'wilayahKerja',
                'dokumenKaryawan',
            ])->findOrFail($id);

            // Format tgl_ttd secara eksplisit agar JS bisa langsung set ke <input type="date">
            $data = $career->toArray();
            $data['tgl_ttd'] = $career->tgl_ttd
                ? $career->tgl_ttd->format('Y-m-d')
                : null;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data jenjang karir tidak ditemukan: ' . $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update career
     */
    public function updateCareer(Request $request, $id)
    {
        $request->validate([
            'no_jk' => 'nullable|string|max:100',
            'tgl_ttd' => 'required|date',
            'id_departemen' => 'required|exists:103_dm_departemen,id',
            'id_wilayah_kerja' => 'nullable|exists:102_dm_wilker,id',
            'tugas' => 'nullable|string',
            'id_dokumen_karyawan' => 'nullable|exists:105_dm_dok_kry,id',
            'id_kontrak_kerja' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $career = DataJenjangKarir::findOrFail($id);

            // Handle file upload if exists
            $fileDokumen = $career->file_dokumen;
            if ($request->hasFile('file_dokumen')) {
                // Delete old file if exists
                if ($career->file_dokumen && Storage::exists('public/' . $career->file_dokumen)) {
                    Storage::delete('public/' . $career->file_dokumen);
                }

                $file = $request->file('file_dokumen');
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/jenjang-karir/dokumen', $fileName);
                $fileDokumen = 'jenjang-karir/dokumen/' . $fileName;
            }

            $career->update([
                'id_dokumen_karyawan' => $request->id_dokumen_karyawan,
                'no_jk' => $request->no_jk,
                'tgl_ttd' => $request->tgl_ttd,
                'id_departemen' => $request->id_departemen,
                'id_wilayah_kerja' => $request->id_wilayah_kerja,
                'tugas' => $request->tugas,
                'file_dokumen' => $fileDokumen,
                'id_kontrak_kerja' => $request->id_kontrak_kerja,
                'updated_by' => auth()->user()->id_kode ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data jenjang karir berhasil diperbarui.',
                'data' => $career->fresh()
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate data jenjang karir: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete career
     */
    public function deleteCareer($id)
    {
        try {
            DB::beginTransaction();

            $career = DataJenjangKarir::findOrFail($id);
            $employeeName = $career->karyawan->nama ?? 'N/A';

            // Check if this is the only career record for the employee
            $employeeCareersCount = DataJenjangKarir::where('id_karyawan', $career->id_karyawan)->count();

            if ($employeeCareersCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat menghapus data jenjang karir terakhir karyawan {$employeeName}. Setiap karyawan harus memiliki minimal 1 data jenjang karir dalam sistem."
                ], 400);
            }

            // Delete associated file if exists
            if ($career->file_dokumen && Storage::exists('public/' . $career->file_dokumen)) {
                Storage::delete('public/' . $career->file_dokumen);
            }

            $career->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data jenjang karir berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================================================ HELPER METHODS ============================================== //
    // ========================================================================================================== //

    /**
     * Get employee data for AJAX call
     * MATCHING DATA DOKUMEN STRUCTURE + ADDITIONAL TABS DATA
     */
    public function getEmployeeData($id)
    {
        try {
            $employee = DataKaryawan::with([
                'departemenRelation',
                'wilayahKerjaRelation',
                'unitKerjaRelation',
            ])->findOrFail($id);

            $tglLahirFormatted  = $employee->tgl_lahir     ? $employee->tgl_lahir->format('d-m-Y')     : null;
            $tglMasukFormatted  = $employee->tgl_masuk     ? $employee->tgl_masuk->format('d-m-Y')     : null;
            $tglPhkFormatted    = $employee->tgl_phk       ? $employee->tgl_phk->format('d-m-Y')       : null;
            $tglLulusFormatted  = $employee->tgl_lulus_skl ? $employee->tgl_lulus_skl->format('d-m-Y') : null;

            return response()->json([
                'success' => true,
                'data' => [

                    // ── Tab 1: Informasi Dasar ──────────────────────────────────────
                    'nik'               => $employee->nik,
                    'nrk'               => $employee->nrk,
                    'nama'              => $employee->nama,
                    'foto_dokumen'      => $employee->foto_dokumen,
                    'tpt_lahir'         => $employee->tpt_lahir,
                    'tgl_lahir'         => $employee->tgl_lahir ? $employee->tgl_lahir->format('Y-m-d') : null,
                    'tgl_lahir_formatted' => $tglLahirFormatted,
                    'sex'               => $employee->sex,
                    'tlp1'              => $employee->tlp1,
                    'sts_nikah'         => $employee->sts_nikah,
                    'jml_anak'          => $employee->jml_anak,
                    'email1'            => $employee->email1,

                    // ── Tab 3: Pendidikan ───────────────────────────────────────────
                    // Menggunakan nama kolom ASLI dari tabel 201_dm_data_karyawan
                    'jenjang_skl'             => $employee->jenjang_skl,
                    'institusi_skl'           => $employee->institusi_skl,
                    'kota_skl'                => $employee->kota_skl,
                    'fakultas_skl'            => $employee->fakultas_skl,
                    'jurusan_skl'             => $employee->jurusan_skl,
                    'gelar_skl'               => $employee->gelar_skl,
                    'tgl_lulus_skl'           => $employee->tgl_lulus_skl ? $employee->tgl_lulus_skl->format('Y-m-d') : null,
                    'tgl_lulus_skl_formatted' => $tglLulusFormatted,

                    // ── Tab 4: Posisi / Jenjang Karir Saat Ini ─────────────────────
                    // kolom di data_karyawan menyimpan ID (int), relasi diprioritaskan
                    'departemen'  => optional($employee->departemenRelation)->nama_dep    ?? null,
                    'skt_dep'     => optional($employee->departemenRelation)->singkatan_dep ?? $employee->skt_dep ?? null,
                    'jabatan'     => optional($employee->departemenRelation)->nama_jbt    ?? null,
                    'skt_jbt'     => optional($employee->departemenRelation)->singkatan_jbt ?? $employee->skt_jbt ?? null,
                    'wilker'      => optional($employee->wilayahKerjaRelation)->wilayah_krj ?? null,
                    'unit_krj'    => optional($employee->unitKerjaRelation)->area_krj       ?? null,
                    'skt_wil_krj' => optional($employee->unitKerjaRelation)->singkatan_wk   ?? $employee->skt_wil_krj ?? null,
                    'tugas'       => $employee->tugas,

                    // ── Tab 5: Hubungan Industrial ─────────────────────────────────
                    'tgl_masuk'           => $employee->tgl_masuk ? $employee->tgl_masuk->format('Y-m-d') : null,
                    'tgl_masuk_formatted' => $tglMasukFormatted,
                    'sts_kry'             => $employee->sts_kry,
                    'tgl_phk'             => $employee->tgl_phk ? $employee->tgl_phk->format('Y-m-d') : null,
                    'tgl_phk_formatted'   => $tglPhkFormatted,
                    'ket_phk'             => $employee->ket_phk,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan: ' . $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Get jabatan by departemen name
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
     * Get unit kerja by wilayah name
     */
    public function getUnitKerjaByWilayah($wilayahKrj)
    {
        try {
            // Di DataJenjangKarirController::getUnitKerjaByWilayah()
            $unitKerjas = WilayahKerja::where('wilayah_krj', $wilayahKrj)
                ->orderByRaw('CAST(kode_wk AS UNSIGNED) ASC')
                ->orderBy('kode_wk', 'ASC')
                ->get(['id', 'kode_wk', 'wilayah_krj', 'area_krj', 'singkatan_wk', 'skt_wilker']); // tambah skt_wilker

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
     * Get departemen jabatan by ID
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
     * Get wilker unit kerja by ID
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
     * Check if employee already has career records
     * MATCHING DATA DOKUMEN STRUCTURE
     */
    public function checkEmployeeExists($id)
    {
        try {
            $employee = DataKaryawan::findOrFail($id);

            // Check if employee already has career records
            $existingCareers = DataJenjangKarir::where('id_karyawan', $id)->count();

            if ($existingCareers > 0) {
                // Get latest career info
                $latestCareer = DataJenjangKarir::where('id_karyawan', $id)
                    ->with(['departemen', 'wilayahKerja'])
                    ->orderBy('created_at', 'desc')
                    ->first();

                return response()->json([
                    'success' => false,
                    'exists' => true,
                    'message' => 'Karyawan sudah memiliki data jenjang karir sebelumnya',
                    'employee_name' => $employee->nama,
                    'employee_nrk' => $employee->nrk,
                    'existing_careers_count' => $existingCareers,
                    'latest_career' => [
                        'departemen' => optional($latestCareer->departemen)->nama_dep ?? 'N/A',
                        'jabatan' => optional($latestCareer->departemen)->nama_jbt ?? 'N/A',
                        'wilayah_kerja' => optional($latestCareer->wilayahKerja)->area_krj ?? 'N/A',
                        'tgl_ttd' => $latestCareer->tgl_ttd ? \Carbon\Carbon::parse($latestCareer->tgl_ttd)->format('d-m-Y') : 'N/A',
                        'no_jk' => $latestCareer->no_jk ?? 'N/A'
                    ]
                ], 409);
            }

            return response()->json([
                'success' => true,
                'exists' => false,
                'message' => 'Karyawan belum memiliki data jenjang karir, dapat dilanjutkan',
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
     * Generate auto-increment ID
     */
    private function generateAutoIncrement(): string
    {
        return (string) (now()->format('YmdHis') . rand(1000, 9999));
    }

    /**
     * Get filtered data for export
     */
    private function getFilteredData($filters)
    {
        $query = DataJenjangKarir::with([
            'karyawan',
            'departemen',
            'wilayahKerja',
            'dokumenKaryawan'
        ]);

        // Get latest career record per employee
        $query->whereIn('id', function ($subquery) {
            $subquery->select(DB::raw('MAX(id)'))
                ->from('204_dm_data_jenjang_karir')
                ->groupBy('id_karyawan');
        });

        // Apply same filters as index
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

        if (!empty($filters['filter_departemen'])) {
            $selectedDepartemen = Departemen::find($filters['filter_departemen']);
            if ($selectedDepartemen) {
                $departemenIds = Departemen::where('nama_dep', $selectedDepartemen->nama_dep)->pluck('id')->toArray();
                $query->whereIn('id_departemen', $departemenIds);
            }
        }

        if (!empty($filters['filter_jabatan'])) {
            $query->where('id_departemen', $filters['filter_jabatan']);
        }

        if (!empty($filters['filter_jenis_kelamin'])) {
            $query->whereHas('karyawan', function ($q) use ($filters) {
                $q->where('sex', $filters['filter_jenis_kelamin']);
            });
        }

        if (!empty($filters['filter_wilker'])) {
            $query->whereHas('wilayahKerja', function ($q) use ($filters) {
                $q->where('wilayah_krj', $filters['filter_wilker']);
            });
        }

        if (!empty($filters['filter_unit_kerja'])) {
            $query->where('id_wilayah_kerja', $filters['filter_unit_kerja']);
        }

        if (!empty($filters['filter_no_jk'])) {
            $query->where('no_jk', 'LIKE', '%' . $filters['filter_no_jk'] . '%');
        }

        if (!empty($filters['filter_tgl_ttd_start'])) {
            $query->where('tgl_ttd', '>=', $filters['filter_tgl_ttd_start']);
        }

        if (!empty($filters['filter_tgl_ttd_end'])) {
            $query->where('tgl_ttd', '<=', $filters['filter_tgl_ttd_end']);
        }

        $dataJenjangKarirs = $query->orderBy('tgl_ttd', 'desc')->get();

        return [
            'dataJenjangKarirs' => $dataJenjangKarirs,
            'filters' => $filters
        ];
    }

    /**
     * Export to Excel (placeholder - implement with export class)
     */
    private function exportExcel($filteredData)
    {
        // Implement Excel export using Maatwebsite\Excel
        // return Excel::download(new DataJenjangKarirExport($filteredData), 'data-jenjang-karir.xlsx');
    }

    /**
     * Export to PDF (placeholder - implement with PDF library)
     */
    private function exportPDF($filteredData)
    {
        // Implement PDF export
    }

    /**
     * Export to CSV (placeholder - implement CSV generation)
     */
    private function exportCSV($filteredData)
    {
        // Implement CSV export
    }
}
