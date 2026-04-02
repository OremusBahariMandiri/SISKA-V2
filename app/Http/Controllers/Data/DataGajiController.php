<?php

namespace App\Http\Controllers\Data;

use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Data\DataGaji;
use App\Models\Data\DataKaryawan;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\WilayahKerja;
use Illuminate\Support\Facades\DB;

class DataGajiController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:data-gaji')->only('index');
        $this->middleware('check.access:data-gaji,detail')->only('show');
        $this->middleware('check.access:data-gaji,tambah')->only('create', 'store');
        $this->middleware('check.access:data-gaji,ubah')->only('edit', 'update');
        $this->middleware('check.access:data-gaji,hapus')->only('destroy');
    }

    public function index(Request $request)
    {
        $userPermissions = $this->getUserPermissions('data-gaji');

        // Initialize filter arrays
        $currentFilters = [
            'nama'          => $request->get('filter_nama'),
            'nrk'           => $request->get('filter_nrk'),
            'departemen'    => $request->get('filter_departemen'),
            'jabatan'       => $request->get('filter_jabatan'),
            'jenis_kelamin' => $request->get('filter_jenis_kelamin'),
            'wilker'        => $request->get('filter_wilker'),
            'unit_kerja'    => $request->get('filter_unit_kerja'),
            'status'        => $request->get('filter_status'),
        ];

        // Start query with eager loading
        $query = DataGaji::with([
            'karyawan.departemenRelation',
            'karyawan.wilayahKerjaRelation',
            'karyawan.unitKerjaRelation',
            'creator',
            'updater'
        ]);

        // Apply filters
        if (!empty($currentFilters['nama'])) {
            $query->whereHas('karyawan', function ($q) use ($currentFilters) {
                $q->where('nama', 'LIKE', '%' . $currentFilters['nama'] . '%');
            });
        }

        if (!empty($currentFilters['nrk'])) {
            $query->whereHas('karyawan', function ($q) use ($currentFilters) {
                $q->where('nrk', 'LIKE', '%' . $currentFilters['nrk'] . '%');
            });
        }

        if (!empty($currentFilters['departemen'])) {
            $query->whereHas('karyawan', function ($q) use ($currentFilters) {
                $q->where('departemen', $currentFilters['departemen']);
            });
        }

        if (!empty($currentFilters['jabatan'])) {
            $query->whereHas('karyawan.departemenRelation', function ($q) use ($currentFilters) {
                $q->where('id', $currentFilters['jabatan']);
            });
        }

        if (!empty($currentFilters['jenis_kelamin'])) {
            $query->whereHas('karyawan', function ($q) use ($currentFilters) {
                $q->where('sex', $currentFilters['jenis_kelamin']);
            });
        }

        if (!empty($currentFilters['wilker'])) {
            $query->whereHas('karyawan.wilayahKerjaRelation', function ($q) use ($currentFilters) {
                $q->where('wilayah_krj', 'LIKE', '%' . $currentFilters['wilker'] . '%');
            });
        }

        if (!empty($currentFilters['unit_kerja'])) {
            $query->whereHas('karyawan.unitKerjaRelation', function ($q) use ($currentFilters) {
                $q->where('id', $currentFilters['unit_kerja']);
            });
        }

        if (!empty($currentFilters['status'])) {
            $query->where('sts_data_gaji', $currentFilters['status']);
        }

        // Get filtered data
        $dataGajis = $query->orderBy('created_at', 'desc')->get();

        // Get all active employees for create form
        $karyawans = DataKaryawan::where('sts_kry', 'AKTIF')
            ->with([
                'departemenRelation',
                'wilayahKerjaRelation',
                'unitKerjaRelation'
            ])
            ->orderBy('nama', 'asc')
            ->get();

        // Get filter options
        $departemenOptions = Departemen::select('id', 'nama_dep', 'singkatan_dep', 'nama_jbt', 'singkatan_jbt')
            ->distinct()
            ->orderBy('nama_dep', 'asc')
            ->get();

        $jabatanOptions = Departemen::select('id', 'nama_jbt', 'singkatan_jbt', 'nama_dep')
            ->whereNotNull('nama_jbt')
            ->distinct()
            ->orderBy('nama_jbt', 'asc')
            ->get();

        $wilayahKerjaOptions = WilayahKerja::select('id', 'wilayah_krj', 'area_krj', 'singkatan_wk')
            ->distinct()
            ->orderBy('wilayah_krj', 'asc')
            ->get();

        $unitKerjaOptions = WilayahKerja::select('id', 'area_krj', 'singkatan_wk')
            ->distinct()
            ->orderBy('area_krj', 'asc')
            ->get();

        // Jenis Kelamin options
        $jenisKelaminOptions = [
            'LAKI-LAKI'  => 'Laki-laki',
            'PEREMPUAN'  => 'Perempuan',
        ];

        // Status options
        $statusOptions = [
            'AKTIF'      => 'Aktif',
            'NON-AKTIF'  => 'Non-Aktif',
        ];

        return view('data.data-gaji.index', compact(
            'dataGajis',
            'karyawans',
            'userPermissions',
            'currentFilters',
            'departemenOptions',
            'jabatanOptions',
            'wilayahKerjaOptions',
            'unitKerjaOptions',
            'jenisKelaminOptions',
            'statusOptions'
        ));
    }

    public function create()
    {
        $newId = $this->generateId('205', '205_dm_data_gaji');

        $karyawans = DataKaryawan::where('sts_kry', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->get();

        return view('data.data-gaji.create', compact('newId', 'karyawans'));
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules());

        try {
            DB::beginTransaction();

            DataGaji::create($this->prepareData($request, withIds: true));

            DB::commit();

            return redirect()->route('data-gaji.index')
                ->with('success', 'Data gaji berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
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

        // Get user permissions
        $userPermissions = $this->getUserPermissions('data-gaji');

        return view('data.data-gaji.show', compact('dataGaji', 'allGajis', 'userPermissions'));
    }

    public function edit($id)
    {
        $dataGaji = DataGaji::with(['karyawan'])->findOrFail($id);

        // PERBAIKAN: Ambil semua data gaji untuk karyawan ini
        $allGajis = DataGaji::where('id_karyawan', $dataGaji->id_karyawan)
            ->with(['creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->get();

        $karyawans = DataKaryawan::where('sts_kry', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->get();

        return view('data.data-gaji.edit', compact('dataGaji', 'karyawans', 'allGajis'));
    }

    public function update(Request $request, $id)
    {
        $dataGaji = DataGaji::findOrFail($id);
        $request->validate($this->validationRules());

        try {
            DB::beginTransaction();

            $dataGaji->update($this->prepareData($request, withIds: false, isUpdate: true));

            DB::commit();

            return redirect()->route('data-gaji.index')
                ->with('success', 'Data gaji berhasil diperbarui.');
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

            $dataGaji     = DataGaji::findOrFail($id);
            $employeeName = $dataGaji->karyawan->nama ?? 'Unknown';
            $dataGaji->delete();

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data gaji {$employeeName} berhasil dihapus.",
                ]);
            }

            return redirect()->route('data-gaji.index')
                ->with('success', "Data gaji {$employeeName} berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollback();

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('data-gaji.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // SALARY MANAGEMENT METHODS (AJAX)
    // =========================================================================

    public function storeGaji(Request $request)
    {
        $request->validate(array_merge(
            ['employee_id' => 'required|exists:201_dm_data_karyawan,id'],
            $this->validationRules(withEmployee: false)
        ));

        try {
            DB::beginTransaction();

            $idGaji = $this->generateAutoIncrement();

            $gaji = DataGaji::create(array_merge(
                [
                    'id_karyawan' => $request->employee_id,
                    'id_kode'     => $idGaji,
                ],
                $this->prepareData($request, withIds: true, idGaji: $idGaji)
            ));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data gaji berhasil ditambahkan.',
                'data'    => $gaji,
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data gaji: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getGaji($id)
    {
        try {
            $gaji = DataGaji::with(['karyawan'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data'    => $gaji->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data gaji tidak ditemukan: ' . $e->getMessage(),
            ], 404);
        }
    }

    public function updateGaji(Request $request, $id)
    {
        $request->validate($this->validationRules(withEmployee: false));

        try {
            DB::beginTransaction();

            $gaji = DataGaji::findOrFail($id);
            $gaji->update($this->prepareData($request, withIds: false, isUpdate: true));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data gaji berhasil diperbarui.',
                'data'    => $gaji->fresh(),
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate data gaji: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteGaji($id)
    {
        try {
            DB::beginTransaction();

            $gaji         = DataGaji::findOrFail($id);
            $employeeName = $gaji->karyawan->nama ?? 'N/A';
            $gaji->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Data gaji {$employeeName} berhasil dihapus.",
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    public function getEmployeeData($id)
    {
        try {
            $employee = DataKaryawan::with([
                'departemenRelation',
                'wilayahKerjaRelation',
                'unitKerjaRelation',
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'nik'                     => $employee->nik,
                    'nrk'                     => $employee->nrk,
                    'nama'                    => $employee->nama,
                    'foto_dokumen'            => $employee->foto_dokumen,
                    'tpt_lahir'               => $employee->tpt_lahir,
                    'tgl_lahir'               => $employee->tgl_lahir?->format('Y-m-d'),
                    'tgl_lahir_formatted'     => $employee->tgl_lahir?->format('d-m-Y'),
                    'sex'                     => $employee->sex,
                    'tlp1'                    => $employee->tlp1,
                    'sts_nikah'               => $employee->sts_nikah,
                    'jml_anak'                => $employee->jml_anak,
                    'email1'                  => $employee->email1,
                    'jenjang_skl'             => $employee->jenjang_skl,
                    'institusi_skl'           => $employee->institusi_skl,
                    'kota_skl'                => $employee->kota_skl,
                    'fakultas_skl'            => $employee->fakultas_skl,
                    'jurusan_skl'             => $employee->jurusan_skl,
                    'gelar_skl'               => $employee->gelar_skl,
                    'tgl_lulus_skl'           => $employee->tgl_lulus_skl?->format('Y-m-d'),
                    'tgl_lulus_skl_formatted' => $employee->tgl_lulus_skl?->format('d-m-Y'),
                    'departemen'              => optional($employee->departemenRelation)->nama_dep,
                    'skt_dep'                 => optional($employee->departemenRelation)->singkatan_dep ?? $employee->skt_dep,
                    'jabatan'                 => optional($employee->departemenRelation)->nama_jbt,
                    'skt_jbt'                 => optional($employee->departemenRelation)->singkatan_jbt ?? $employee->skt_jbt,
                    'wilker'                  => optional($employee->wilayahKerjaRelation)->wilayah_krj,
                    'unit_krj'                => optional($employee->unitKerjaRelation)->area_krj,
                    'skt_wil_krj'             => optional($employee->unitKerjaRelation)->singkatan_wk ?? $employee->skt_wil_krj,
                    'tugas'                   => $employee->tugas,
                    'tgl_masuk'               => $employee->tgl_masuk?->format('Y-m-d'),
                    'tgl_masuk_formatted'     => $employee->tgl_masuk?->format('d-m-Y'),
                    'sts_kry'                 => $employee->sts_kry,
                    'tgl_phk'                 => $employee->tgl_phk?->format('Y-m-d'),
                    'tgl_phk_formatted'       => $employee->tgl_phk?->format('d-m-Y'),
                    'ket_phk'                 => $employee->ket_phk,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan: ' . $e->getMessage(),
            ], 404);
        }
    }

    public function checkEmployeeExists($id)
    {
        try {
            $employee     = DataKaryawan::findOrFail($id);
            $existingGaji = DataGaji::where('id_karyawan', $id)->count();

            if ($existingGaji > 0) {
                $latestGaji = DataGaji::where('id_karyawan', $id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                return response()->json([
                    'success'        => false,
                    'exists'         => true,
                    'message'        => 'Karyawan sudah memiliki data gaji sebelumnya',
                    'employee_name'  => $employee->nama,
                    'employee_nrk'   => $employee->nrk,
                    'existing_count' => $existingGaji,
                    'latest_gaji'    => [
                        'id_gaji'  => $latestGaji->id_gaji  ?? 'N/A',
                        'gj_pokok' => $latestGaji->gj_pokok ?? 0,
                    ],
                ], 409);
            }

            return response()->json([
                'success'       => true,
                'exists'        => false,
                'message'       => 'Karyawan belum memiliki data gaji, dapat dilanjutkan',
                'employee_name' => $employee->nama,
                'employee_nrk'  => $employee->nrk,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak ditemukan: ' . $e->getMessage(),
            ], 404);
        }
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Validation rules — nama kolom sesuai migration.
     */
    private function validationRules(bool $withEmployee = true): array
    {
        $rules = [
            // Pendapatan Tetap
            'gj_pokok'  => 'nullable|numeric|min:0',
            'tunjab'    => 'nullable|numeric|min:0',
            'tunkom'    => 'nullable|numeric|min:0',
            'fot'       => 'nullable|numeric|min:0',
            'tunmal'    => 'nullable|numeric|min:0',

            // Pendapatan Tidak Tetap
            'lbr_harian' => 'nullable|numeric|min:0',
            'lbr_perjam' => 'nullable|numeric|min:0',
            'tukin'      => 'nullable|numeric|min:0',
            'insentif'   => 'nullable|numeric|min:0',
            'bonus'      => 'nullable|numeric|min:0',
            'thr'        => 'nullable|numeric|min:0',

            // Potongan
            'bpjs_tkj'       => 'nullable|numeric|min:0',
            'bpjs_kes'       => 'nullable|numeric|min:0',
            'iuran_koperasi' => 'nullable|numeric|min:0',
            'tps_kry'        => 'nullable|numeric|min:0',
            'pjk_pkp'        => 'nullable|numeric|min:0',
            'pjk_pph'        => 'nullable|numeric|min:0',
            'ptg_thr'        => 'nullable|numeric|min:0',
            'pjm_kop'        => 'nullable|numeric|min:0',
            'dda_sanksi'     => 'nullable|numeric|min:0',

            // Beban Perusahaan
            'bpjs_tkj_prs' => 'nullable|numeric|min:0',
            'bpjs_kes_prs' => 'nullable|numeric|min:0',
            'tps_prs'      => 'nullable|numeric|min:0',
            'askes_prs'    => 'nullable|numeric|min:0',

            // TAMBAHAN: Field Status (mirip kontrak)
            'sts_data_gaji' => 'required|in:AKTIF,NON-AKTIF',
            'tgl_na_gaji'   => 'nullable|date',
            'ket_na_gaji'   => 'nullable|string|max:255',
        ];

        if ($withEmployee) {
            $rules['id_karyawan'] = 'required|exists:201_dm_data_karyawan,id';
        }

        return $rules;
    }

    /**
     * Menyiapkan array data untuk create/update.
     * Nama kolom sesuai migration terbaru.
     */
    private function prepareData(Request $request, bool $withIds = false, bool $isUpdate = false, ?string $idGaji = null): array
    {
        $idGaji = $idGaji ?? ($withIds ? $this->generateAutoIncrement() : null);

        $data = [
            // Pendapatan Tetap
            'gj_pokok' => $request->gj_pokok,
            'tunjab'   => $request->tunjab,
            'tunkom'   => $request->tunkom,
            'fot'      => $request->fot,
            'tunmal'   => $request->tunmal,

            // Pendapatan Tidak Tetap
            'lbr_harian' => $request->lbr_harian,
            'lbr_perjam' => $request->lbr_perjam,
            'tukin'      => $request->tukin,
            'insentif'   => $request->insentif,
            'bonus'      => $request->bonus,
            'thr'        => $request->thr,

            // Potongan
            'bpjs_tkj'       => $request->bpjs_tkj,
            'bpjs_kes'       => $request->bpjs_kes,
            'iuran_koperasi' => $request->iuran_koperasi,
            'tps_kry'        => $request->tps_kry,
            'pjk_pkp'        => $request->pjk_pkp,
            'pjk_pph'        => $request->pjk_pph,
            'ptg_thr'        => $request->ptg_thr,
            'pjm_kop'        => $request->pjm_kop,
            'dda_sanksi'     => $request->dda_sanksi,

            // Beban Perusahaan
            'bpjs_tkj_prs' => $request->bpjs_tkj_prs,
            'bpjs_kes_prs' => $request->bpjs_kes_prs,
            'tps_prs'      => $request->tps_prs,
            'askes_prs'    => $request->askes_prs,

            // Status Data Gaji
            'sts_data_gaji' => $request->sts_data_gaji ?? 'AKTIF',
            'tgl_na_gaji'   => $request->tgl_na_gaji,
            'ket_na_gaji'   => $request->ket_na_gaji,
        ];

        // Hitung totals
        $ttlPendapatanTtp = collect([
            $request->gj_pokok,
            $request->tunjab,
            $request->tunkom,
            $request->fot,
            $request->tunmal,
        ])->sum();

        $ttlPendapatanTdkTtp = collect([
            $request->lbr_harian,
            $request->lbr_perjam,
            $request->tukin,
            $request->insentif,
            $request->bonus,
            $request->thr,
        ])->sum();

        $ttlPotongan = collect([
            $request->bpjs_tkj,
            $request->bpjs_kes,
            $request->iuran_koperasi,
            $request->tps_kry,
            $request->pjk_pkp,
            $request->pjk_pph,
            $request->ptg_thr,
            $request->pjm_kop,
            $request->dda_sanksi,
        ])->sum();

        $data['ttl_pendapatan_ttp']    = $ttlPendapatanTtp;
        $data['ttl_pendapatan_tdk_ttp'] = $ttlPendapatanTdkTtp;
        $data['ttl_pendapatan']        = $ttlPendapatanTtp + $ttlPendapatanTdkTtp;
        $data['ttl_potongan']          = $ttlPotongan;
        $data['ttl_terima_gaji']       = ($ttlPendapatanTtp + $ttlPendapatanTdkTtp) - $ttlPotongan;

        // ⭐ PENTING: Tambah ID-ID sub-dokumen hanya saat create
        if ($withIds) {
            $data = array_merge($data, [
                'id_karyawan'   => $request->id_karyawan,
                'id_kode'       => $idGaji,
                'id_gaji'       => $idGaji,

                // ⭐ ID-ID WAJIB untuk sub-dokumen (yang menyebabkan error)
                'id_lembur'     => $this->generateAutoIncrement(),
                'id_tukin'      => $this->generateAutoIncrement(),
                'id_insentif'   => $this->generateAutoIncrement(),
                'id_bonus'      => $this->generateAutoIncrement(),
                'id_thr'        => $this->generateAutoIncrement(),
                'id_bpjs_tkj'   => $this->generateAutoIncrement(),
                'id_bpjs_kes'   => $this->generateAutoIncrement(),
                'id_kop'        => $this->generateAutoIncrement(),
                'id_tps'        => $this->generateAutoIncrement(),
                'id_pjk_pph'    => $this->generateAutoIncrement(),
                'id_ptg_thr'    => $this->generateAutoIncrement(),
                'id_pjm_kop'    => $this->generateAutoIncrement(),
                'id_dda_sanksi' => $this->generateAutoIncrement(),
                'id_askes_prs'  => $this->generateAutoIncrement(),

                'created_by'    => auth()->user()->id_kode ?? null,
            ]);
        }

        if ($isUpdate) {
            $data['updated_by'] = auth()->user()->id_kode ?? null;
        }

        return $data;
    }

    /**
     * Ambil permissions user yang sedang login.
     */
    private function getUserPermissions(string $menu): array
    {
        if (! auth()->check()) {
            return [];
        }

        $user = auth()->user();

        if ($user->is_admin) {
            return array_fill_keys(['tambah', 'ubah', 'hapus', 'download', 'detail', 'monitoring'], true);
        }

        $access = $user->userAccess()->where('menu_acs', $menu)->first();

        if (! $access) {
            return [];
        }

        return [
            'tambah'     => (bool) $access->tambah_acs,
            'ubah'       => (bool) $access->ubah_acs,
            'hapus'      => (bool) $access->hapus_acs,
            'download'   => (bool) $access->download_acs,
            'detail'     => (bool) $access->detail_acs,
            'monitoring' => (bool) $access->monitoring_acs,
        ];
    }

    private function generateAutoIncrement(): string
    {
        return now()->format('YmdHis') . rand(1000, 9999);
    }

    private function getFilteredData(array $filters): array
    {
        $query = DataGaji::with(['karyawan'])
            ->whereIn('id', function ($subquery) {
                $subquery->select(DB::raw('MAX(id)'))
                    ->from('205_dm_data_gaji')
                    ->groupBy('id_karyawan');
            });

        if (! empty($filters['filter_nama'])) {
            $query->whereHas('karyawan', fn($q) => $q->where('nama', 'LIKE', '%' . $filters['filter_nama'] . '%'));
        }
        if (! empty($filters['filter_nrk'])) {
            $query->whereHas('karyawan', fn($q) => $q->where('nrk', 'LIKE', '%' . $filters['filter_nrk'] . '%'));
        }
        if (! empty($filters['filter_nik'])) {
            $query->whereHas('karyawan', fn($q) => $q->where('nik', 'LIKE', '%' . $filters['filter_nik'] . '%'));
        }
        if (! empty($filters['filter_jenis_kelamin'])) {
            $query->whereHas('karyawan', fn($q) => $q->where('sex', $filters['filter_jenis_kelamin']));
        }
        if (! empty($filters['filter_id_gaji'])) {
            $query->where('id_gaji', 'LIKE', '%' . $filters['filter_id_gaji'] . '%');
        }

        return [
            'dataGajis' => $query->orderBy('id', 'desc')->get(),
            'filters'   => $filters,
        ];
    }

    private function exportExcel($filteredData) {}
    private function exportPDF($filteredData) {}
    private function exportCSV($filteredData) {}

    // Tambahkan method-method ini ke DataGajiController.php

// ================================================ SALARY MANAGEMENT METHODS (AJAX) ================================================ //

    /**
     * Store a new salary record for an employee
     */
    public function storeSalary(Request $request)
    {
        $request->validate(array_merge(
            ['employee_id' => 'required|exists:201_dm_data_karyawan,id'],
            $this->validationRules(withEmployee: false)
        ));

        try {
            DB::beginTransaction();

            $idGaji = $this->generateAutoIncrement();

            // VALIDASI: hanya boleh ada 1 data gaji AKTIF per karyawan
            if ($request->sts_data_gaji === 'AKTIF') {
                $existingActiveSalary = DataGaji::where('id_karyawan', $request->employee_id)
                    ->where('sts_data_gaji', 'AKTIF')
                    ->first();

                if ($existingActiveSalary) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Karyawan sudah memiliki data gaji aktif. Hanya diperbolehkan 1 data gaji aktif per karyawan.',
                        'existing_salary' => [
                            'id_gaji'  => $existingActiveSalary->id_gaji,
                            'gj_pokok' => $existingActiveSalary->gj_pokok,
                        ]
                    ], 400);
                }
            }

            $gaji = DataGaji::create(array_merge(
                [
                    'id_karyawan' => $request->employee_id,
                    'id_kode'     => $idGaji,
                ],
                $this->prepareData($request, withIds: true, idGaji: $idGaji)
            ));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data gaji berhasil ditambahkan.',
                'data'    => $gaji,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get salary details
     */
    public function getSalary($id)
    {
        try {
            $gaji = DataGaji::with(['karyawan', 'creator', 'updater'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data'    => $gaji->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data gaji tidak ditemukan: ' . $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update salary
     */
    public function updateSalary(Request $request, $id)
    {
        $request->validate($this->validationRules(withEmployee: false));

        try {
            DB::beginTransaction();

            $gaji = DataGaji::findOrFail($id);

            // VALIDASI: jika status diubah menjadi AKTIF
            if ($request->sts_data_gaji === 'AKTIF' && $gaji->sts_data_gaji !== 'AKTIF') {
                $existingActiveSalary = DataGaji::where('id_karyawan', $gaji->id_karyawan)
                    ->where('sts_data_gaji', 'AKTIF')
                    ->where('id', '!=', $id)
                    ->first();

                if ($existingActiveSalary) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Karyawan sudah memiliki data gaji aktif lain.',
                        'existing_salary' => [
                            'id_gaji'  => $existingActiveSalary->id_gaji,
                            'gj_pokok' => $existingActiveSalary->gj_pokok,
                        ]
                    ], 400);
                }
            }

            $gaji->update($this->prepareData($request, withIds: false, isUpdate: true));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data gaji berhasil diperbarui.',
                'data'    => $gaji->fresh(),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete salary
     */
    public function deleteSalary($id)
    {
        try {
            DB::beginTransaction();

            $gaji = DataGaji::findOrFail($id);
            $employeeName = $gaji->karyawan->nama ?? 'N/A';

            // Check if this is the only salary for the employee
            $employeeSalariesCount = DataGaji::where('id_karyawan', $gaji->id_karyawan)->count();

            if ($employeeSalariesCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat menghapus data gaji terakhir karyawan {$employeeName}. Setiap karyawan harus memiliki minimal 1 data gaji dalam sistem."
                ], 400);
            }

            $gaji->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data gaji berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
