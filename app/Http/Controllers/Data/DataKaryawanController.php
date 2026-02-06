<?php

namespace App\Http\Controllers\Data;

use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Data\DataKaryawan;
use App\Models\DataMaster\Perusahaan;
use App\Models\DataMaster\WilayahKerja;
use App\Models\DataMaster\Departemen;
use Illuminate\Support\Facades\Storage;
use App\Exports\DataKaryawanExport;
use App\Models\DataMaster\KontrakKerja;
use Maatwebsite\Excel\Facades\Excel;

class DataKaryawanController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:data-karyawan')->only('index');
        $this->middleware('check.access:data-karyawan,detail')->only('show');
        $this->middleware('check.access:data-karyawan,tambah')->only('create', 'store');
        $this->middleware('check.access:data-karyawan,ubah')->only('edit', 'update');
        $this->middleware('check.access:data-karyawan,hapus')->only('destroy');
    }

    public function index()
    {
        // Sort by nrk ascending (A-Z)
        $dataKaryawans = DataKaryawan::with(['perusahaanRelation', 'departemenRelation', 'wilayahKerjaRelation'])
            ->orderBy('nama', 'asc')
            ->get();

        // Get master data for filter dropdowns
        $perusahaans = Perusahaan::orderBy('nama_prs1', 'asc')->get();
        $wilayahKerjas = WilayahKerja::orderBy('wilayah_krj', 'asc')->get();
        $departemens = Departemen::orderBy('nama_dep', 'asc')->get();

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
                $access = $user->userAccess()->where('menu_acs', 'data-karyawan')->first();
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

        return view('data.data-karyawan.index', compact(
            'dataKaryawans',
            'userPermissions',
            'perusahaans',
            'wilayahKerjas',
            'departemens'
        ));
    }

    public function create()
    {
        // Generate automatic ID for main record
        $newId = $this->generateId('201', '201_dm_data_karyawan');

        // Get master data for dropdowns
        $kontraks = KontrakKerja::orderBy('singkatan_ktr', 'asc')->get();
        $perusahaans = Perusahaan::orderBy('nama_prs1', 'asc')->get();

        // Get unique wilayah kerja (grouped by wilayah_krj)
        $wilayahKerjas = WilayahKerja::select('wilayah_krj', 'singkatan_wk')
            ->groupBy('wilayah_krj',  'singkatan_wk')
            ->orderBy('wilayah_krj', 'asc')
            ->get();

        // Get unique departemen (grouped by nama_dep)
        $departemens = Departemen::select('nama_dep', 'singkatan_dep',)
            ->groupBy('nama_dep', 'singkatan_dep')
            ->orderBy('nama_dep', 'asc')
            ->get();

        return view('data.data-karyawan.create', compact('newId', 'perusahaans', 'wilayahKerjas', 'departemens', 'kontraks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Biodata
            'nik' => 'required|string|size:16|unique:201_dm_data_karyawan,nik',
            'nama' => 'required|string|max:255',
            'tpt_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'sex' => 'required|in:LAKI-LAKI,PEREMPUAN',
            'agama' => 'required|string|max:50',
            'kewarganegaraan' => 'nullable|string|max:100',
            'sts_nikah' => 'required|string|max:50',
            'sts_keluarga' => 'nullable|string|max:50',
            'jml_anak' => 'nullable|integer|min:0',
            'tlp1' => 'required|string|max:20',
            'tlp2' => 'nullable|string|max:20',
            'email1' => 'nullable|email|max:255',
            'email2' => 'nullable|email|max:255',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            // Alamat KTP
            'alamat_ktp' => 'required|string',
            'rt_rw_ktp' => 'nullable|string|max:10',
            'kel_ktp' => 'nullable|string|max:100',
            'kec_ktp' => 'nullable|string|max:100',
            'kota_ktp' => 'required|string|max:100',
            'prov_ktp' => 'required|string|max:100',
            'kd_pos_ktp' => 'nullable|string|max:10',
            // Alamat Domisili
            'alamat_dom' => 'required|string',
            'rt_rw_dom' => 'nullable|string|max:10',
            'kel_dom' => 'nullable|string|max:100',
            'kec_dom' => 'nullable|string|max:100',
            'kota_dom' => 'required|string|max:100',
            'prov_dom' => 'required|string|max:100',
            'kd_pos_dom' => 'nullable|string|max:10',
            // Pendidikan
            'jenjang_skl' => 'nullable|string|max:50',
            'institusi_skl' => 'nullable|string|max:255',
            'kota_skl' => 'nullable|string|max:100',
            'fakultas_skl' => 'nullable|string|max:255',
            'jurusan_skl' => 'nullable|string|max:255',
            'gelar_skl' => 'nullable|string|max:100',
            'tgl_lulus_skl' => 'nullable|date',
            // Kontrak
            'sts_ktr' => 'nullable|string|max:50',
            'tgl_awal_ktr' => 'nullable|date',
            'tgl_akhir_ktr' => 'nullable|date',
            'durasi_ktr' => 'nullable|integer',
            'perusahaan' => 'nullable|exists:101_dm_perusahaan,id',
            // Karir
            'departemen' => 'nullable|string|max:255',
            'jabatan' => 'nullable|exists:103_dm_departemen,id',
            'tugas' => 'nullable|string',
            'unit_krj' => 'nullable|string|max:255',
            'wilker' => 'nullable|string|max:255',
            // Hubungan Industrial
            'sts_kry' => 'required|in:CALON,AKTIF,NON-AKTIF',
            'tgl_phk' => 'nullable|date',
            'ket_phk' => 'nullable|string|max:255',
            'tgl_masuk' => 'nullable|date',
            'nrk' => 'nullable|string|max:50',
            // File
            'foto_dokumen' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx',
        ]);

        // Generate main ID
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('201', '201_dm_data_karyawan');
        } else {
            $id_kode = $request->id_kode;
        }

        // Handle file upload
        $fotoDokumen = null;
        if ($request->hasFile('foto_dokumen')) {
            $file = $request->file('foto_dokumen');
            $fileName = time() . '_' . $id_kode . '_' . $file->getClientOriginalName();
            $file->storeAs('public/karyawan/dokumen', $fileName);
            $fotoDokumen = 'karyawan/dokumen/' . $fileName;
        }

        $dataKaryawan = DataKaryawan::create([
            'id_kode' => $id_kode,
            'idkry' => $this->generateAutoIncrement(),
            'tgl_masuk' => $request->tgl_masuk,
            'nrk' => $request->nrk,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tpt_lahir' => $request->tpt_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'sex' => $request->sex,
            'agama' => $request->agama,
            'kewarganegaraan' => $request->kewarganegaraan ?? 'INDONESIA',
            'sts_nikah' => $request->sts_nikah,
            'sts_keluarga' => $request->sts_keluarga,
            'jml_anak' => $request->jml_anak ?? 0,
            'tlp1' => $request->tlp1,
            'tlp2' => $request->tlp2,
            'email1' => $request->email1,
            'email2' => $request->email2,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'foto_dokumen' => $fotoDokumen,
            // Alamat KTP
            'alamat_ktp' => $request->alamat_ktp,
            'rt_rw_ktp' => $request->rt_rw_ktp,
            'kel_ktp' => $request->kel_ktp,
            'kec_ktp' => $request->kec_ktp,
            'kota_ktp' => $request->kota_ktp,
            'prov_ktp' => $request->prov_ktp,
            'kd_pos_ktp' => $request->kd_pos_ktp,
            // Alamat Domisili
            'alamat_dom' => $request->alamat_dom,
            'rt_rw_dom' => $request->rt_rw_dom,
            'kel_dom' => $request->kel_dom,
            'kec_dom' => $request->kec_dom,
            'kota_dom' => $request->kota_dom,
            'prov_dom' => $request->prov_dom,
            'kd_pos_dom' => $request->kd_pos_dom,
            // Auto-increment fields
            'id_pendidikan' => $this->generateAutoIncrement(),
            'jenjang_skl' => $request->jenjang_skl,
            'institusi_skl' => $request->institusi_skl,
            'skt_inst_skl' => $request->skt_inst_skl,
            'kota_skl' => $request->kota_skl,
            'fakultas_skl' => $request->fakultas_skl,
            'jurusan_skl' => $request->jurusan_skl,
            'gelar_skl' => $request->gelar_skl,
            'tgl_lulus_skl' => $request->tgl_lulus_skl,
            // Kontrak
            'idktr' => $this->generateAutoIncrement(),
            'sts_ktr' => $request->sts_ktr,
            'skt_sts_ktr' => $request->skt_sts_ktr,
            'tgl_awal_ktr' => $request->tgl_awal_ktr,
            'tgl_akhir_ktr' => $request->tgl_akhir_ktr,
            'durasi_ktr' => $request->durasi_ktr,
            'perusahaan' => $request->perusahaan,
            'skt_prs' => $request->skt_prs,
            // Karir
            'id_karir' => $this->generateAutoIncrement(),
            'departemen' => $request->jabatan, // Store the departemen ID from jabatan select
            'skt_dep' => $request->skt_dep,
            'jabatan' => $request->departemen, // Store the departemen name
            'skt_jbt' => $request->skt_jbt,
            'tugas' => $request->tugas,
            'unit_krj' => $request->unit_krj,
            'skt_wil_krj' => $request->skt_wil_krj,
            'wilker' => $request->wilker,
            // Hubungan Industrial
            'id_hubin' => $this->generateAutoIncrement(),
            'sts_kry' => $request->sts_kry,
            'skt_sts_kry' => $request->skt_sts_kry,
            'tgl_phk' => $request->tgl_phk,
            'ket_phk' => $request->ket_phk,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('data-karyawan.index')
            ->with('success', 'Data karyawan berhasil dibuat.');
    }

    public function show($id)
    {
        $dataKaryawan = DataKaryawan::with(['perusahaanRelation', 'departemenRelation', 'wilayahKerjaRelation','kontrakRelation', 'creator', 'updater'])
            ->findOrFail($id);
        return view('data.data-karyawan.show', compact('dataKaryawan'));
    }

    public function edit($id)
    {
        $dataKaryawan = DataKaryawan::findOrFail($id);

        // Get master data for dropdowns
        $perusahaans = Perusahaan::orderBy('nama_prs1', 'asc')->get();
        $kontraks = KontrakKerja::orderBy('singkatan_ktr', 'asc')->get();

        // Get unique wilayah kerja (grouped by wilayah_krj)
        $wilayahKerjas = WilayahKerja::select('wilayah_krj', 'singkatan_wk')
            ->groupBy('wilayah_krj', 'singkatan_wk')
            ->orderBy('wilayah_krj', 'asc')
            ->get();

        // Get unique departemen (grouped by nama_dep)
        $departemens = Departemen::select('nama_dep', 'singkatan_dep')
            ->groupBy('nama_dep', 'singkatan_dep')
            ->orderBy('nama_dep', 'asc')
            ->get();


        return view('data.data-karyawan.edit', compact('dataKaryawan', 'perusahaans', 'wilayahKerjas', 'departemens', 'kontraks'));
    }

    public function update(Request $request, $id)
    {
        $dataKaryawan = DataKaryawan::findOrFail($id);

        $request->validate([
            // Same validation rules as store
            'nik' => 'required|string|size:16|unique:201_dm_data_karyawan,nik,' . $id,
            'nama' => 'required|string|max:255',
            'tpt_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'sex' => 'required|in:LAKI-LAKI,PEREMPUAN',
            'agama' => 'required|string|max:50',
            'sts_nikah' => 'required|string|max:50',
            'tlp1' => 'required|string|max:20',
            'alamat_ktp' => 'required|string',
            'kota_ktp' => 'required|string|max:100',
            'prov_ktp' => 'required|string|max:100',
            'alamat_dom' => 'required|string',
            'kota_dom' => 'required|string|max:100',
            'prov_dom' => 'required|string|max:100',
            'sts_kry' => 'required|in:CALON,AKTIF,NON-AKTIF',
            'foto_dokumen' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx',
        ]);

        // Handle file upload
        $fotoDokumen = $dataKaryawan->foto_dokumen; // Keep existing file by default
        if ($request->hasFile('foto_dokumen')) {
            // Delete old file if exists
            if ($dataKaryawan->foto_dokumen && Storage::exists('public/' . $dataKaryawan->foto_dokumen)) {
                Storage::delete('public/' . $dataKaryawan->foto_dokumen);
            }

            // Upload new file
            $file = $request->file('foto_dokumen');
            $fileName = time() . '_' . $dataKaryawan->idkry . '_' . $file->getClientOriginalName();
            $file->storeAs('public/karyawan/dokumen', $fileName);
            $fotoDokumen = 'karyawan/dokumen/' . $fileName;
        }

        $data = [
            'tgl_masuk' => $request->tgl_masuk,
            'nrk' => $request->nrk,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tpt_lahir' => $request->tpt_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'sex' => $request->sex,
            'agama' => $request->agama,
            'kewarganegaraan' => $request->kewarganegaraan ?? 'INDONESIA',
            'sts_nikah' => $request->sts_nikah,
            'sts_keluarga' => $request->sts_keluarga,
            'jml_anak' => $request->jml_anak ?? 0,
            'tlp1' => $request->tlp1,
            'tlp2' => $request->tlp2,
            'email1' => $request->email1,
            'email2' => $request->email2,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'foto_dokumen' => $fotoDokumen,
            // Alamat
            'alamat_ktp' => $request->alamat_ktp,
            'rt_rw_ktp' => $request->rt_rw_ktp,
            'kel_ktp' => $request->kel_ktp,
            'kec_ktp' => $request->kec_ktp,
            'kota_ktp' => $request->kota_ktp,
            'prov_ktp' => $request->prov_ktp,
            'kd_pos_ktp' => $request->kd_pos_ktp,
            'alamat_dom' => $request->alamat_dom,
            'rt_rw_dom' => $request->rt_rw_dom,
            'kel_dom' => $request->kel_dom,
            'kec_dom' => $request->kec_dom,
            'kota_dom' => $request->kota_dom,
            'prov_dom' => $request->prov_dom,
            'kd_pos_dom' => $request->kd_pos_dom,
            // Pendidikan
            'jenjang_skl' => $request->jenjang_skl,
            'institusi_skl' => $request->institusi_skl,
            'skt_inst_skl' => $request->skt_inst_skl,
            'kota_skl' => $request->kota_skl,
            'fakultas_skl' => $request->fakultas_skl,
            'jurusan_skl' => $request->jurusan_skl,
            'gelar_skl' => $request->gelar_skl,
            'tgl_lulus_skl' => $request->tgl_lulus_skl,
            // Kontrak
            'sts_ktr' => $request->sts_ktr,
            'skt_sts_ktr' => $request->skt_sts_ktr,
            'tgl_awal_ktr' => $request->tgl_awal_ktr,
            'tgl_akhir_ktr' => $request->tgl_akhir_ktr,
            'durasi_ktr' => $request->durasi_ktr,
            'perusahaan' => $request->perusahaan,
            'skt_prs' => $request->skt_prs,
            // Karir
            'departemen' => $request->jabatan,
            'skt_dep' => $request->skt_dep,
            'jabatan' => $request->departemen,
            'skt_jbt' => $request->skt_jbt,
            'tugas' => $request->tugas,
            'unit_krj' => $request->unit_krj,
            'skt_wil_krj' => $request->skt_wil_krj,
            'wilker' => $request->wilker,
            // Hubungan Industrial
            'sts_kry' => $request->sts_kry,
            'skt_sts_kry' => $request->skt_sts_kry,
            'tgl_phk' => $request->tgl_phk,
            'ket_phk' => $request->ket_phk,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        $dataKaryawan->update($data);

        return redirect()->route('data-karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dataKaryawan = DataKaryawan::findOrFail($id);

        // Delete associated file if exists
        if ($dataKaryawan->foto_dokumen && Storage::exists('public/' . $dataKaryawan->foto_dokumen)) {
            Storage::delete('public/' . $dataKaryawan->foto_dokumen);
        }

        $dataKaryawan->delete();

        return redirect()->route('data-karyawan.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
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
        $fileName = 'Data_Karyawan_' . $currentDate . '.xlsx';

        return Excel::download(
            new DataKaryawanExport($data['dataKaryawans'], $data['filters']),
            $fileName
        );
    }

    private function getFilteredData($filters)
    {
        $query = DataKaryawan::with(['perusahaanRelation', 'departemenRelation', 'wilayahKerjaRelation']);

        // Apply filters
        if (!empty($filters['nrk'])) {
            $query->where('nrk', 'like', '%' . $filters['nrk'] . '%');
        }

        if (!empty($filters['nama'])) {
            $query->where('nama', 'like', '%' . $filters['nama'] . '%');
        }

        if (!empty($filters['nik'])) {
            $query->where('nik', 'like', '%' . $filters['nik'] . '%');
        }

        if (!empty($filters['sex'])) {
            $query->where('sex', $filters['sex']);
        }

        if (!empty($filters['agama'])) {
            $query->where('agama', $filters['agama']);
        }

        if (!empty($filters['sts_nikah'])) {
            $query->where('sts_nikah', $filters['sts_nikah']);
        }

        if (!empty($filters['perusahaan'])) {
            $query->where('perusahaan', $filters['perusahaan']);
        }

        if (!empty($filters['departemen'])) {
            $query->where('departemen', $filters['departemen']);
        }

        if (!empty($filters['jabatan'])) {
            $query->where('jabatan', 'like', '%' . $filters['jabatan'] . '%');
        }

        if (!empty($filters['wilker'])) {
            $query->where('wilker', $filters['wilker']);
        }

        if (!empty($filters['sts_kry'])) {
            $query->where('sts_kry', $filters['sts_kry']);
        }

        if (!empty($filters['tgl_masuk_from']) && !empty($filters['tgl_masuk_to'])) {
            $query->whereBetween('tgl_masuk', [$filters['tgl_masuk_from'], $filters['tgl_masuk_to']]);
        } elseif (!empty($filters['tgl_masuk_from'])) {
            $query->where('tgl_masuk', '>=', $filters['tgl_masuk_from']);
        } elseif (!empty($filters['tgl_masuk_to'])) {
            $query->where('tgl_masuk', '<=', $filters['tgl_masuk_to']);
        }

        $dataKaryawans = $query->orderBy('nrk', 'asc')->get();

        return [
            'dataKaryawans' => $dataKaryawans,
            'filters' => $filters
        ];
    }

    /**
     * Get jabatan list by departemen name
     */
    public function getJabatanByDepartemen($namaDep)
    {
        try {
            $jabatans = Departemen::where('nama_dep', $namaDep)
                ->orderBy('nama_jbt', 'asc')
                ->get(['id', 'nama_jbt', 'singkatan_jbt']);

            return response()->json([
                'success' => true,
                'data' => $jabatans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data jabatan'
            ], 500);
        }
    }

    /**
     * Get unit kerja (area) list by wilayah kerja
     */
    public function getUnitKerjaByWilayah($wilayahKrj)
    {
        try {
            $unitKerjas = WilayahKerja::where('wilayah_krj', $wilayahKrj)
                ->orderBy('area_krj', 'asc')
                ->get(['id', 'area_krj', 'wilayah_krj']);

            return response()->json([
                'success' => true,
                'data' => $unitKerjas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data unit kerja'
            ], 500);
        }
    }

    public function getDepartemenJabatan($id)
    {
        try {
            $departemen = Departemen::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'nama_jbt' => $departemen->nama_jbt,
                    'singkatan_jbt' => $departemen->singkatan_jbt,
                    'display_position' => $departemen->display_position
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Departemen tidak ditemukan'
            ], 404);
        }
    }

    public function getWilkerUnitKrj($id)
    {
        try {
            $wilker = WilayahKerja::findOrFail($id);

            return response()->json([
                'success' => true,
                'datawilker' => [
                    'wilayah_krj' => $wilker->wilayah_krj,
                    'area_krj' => $wilker->area_krj,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Wilayah Kerja tidak ditemukan'
            ], 404);
        }
    }
}
