<?php

namespace App\Http\Controllers\DataMaster;

use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataMaster\Perusahaan;

class PerusahaanController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:perusahaan')->only('index');
        $this->middleware('check.access:perusahaan,detail')->only('show');
        $this->middleware('check.access:perusahaan,tambah')->only('create', 'store');
        $this->middleware('check.access:perusahaan,ubah')->only('edit', 'update');
        $this->middleware('check.access:perusahaan,hapus')->only('destroy');
    }

    public function index()
    {
        $perusahaans = Perusahaan::orderBy('kode_prs', 'asc')->get();

        // Get user permissions for this menu
        $userPermissions = [];
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->is_admin) {
                // Admin has all permissions
                $userPermissions = [
                    'tambah' => true,
                    'ubah' => true,
                    'hapus' => true,
                    'download' => true,
                    'detail' => true,
                    'monitoring' => true,
                ];
            } else {
                // Get specific permissions from user access
                $access = $user->userAccess()->where('menu_acs', 'perusahaan')->first();
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

        return view('data-master.perusahaan.index', compact('perusahaans', 'userPermissions'));
    }

    public function create()
    {
        // Generate automatic ID
        $newId = $this->generateId('101', '101_dm_perusahaan');

        return view('data-master.perusahaan.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prs1' => 'required',
            'alamat_prs' => 'required',
            'kota_prs' => 'required',
            'prov_prs' => 'required',
            'tlp1' => 'required',
            'email1' => 'required|email',
            'bidang_ush' => 'required',
            'dirut' => 'required',
        ]);

        // Generate ID if not present
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('101', '101_dm_perusahaan');
        } else {
            $id_kode = $request->id_kode;
        }

        $perusahaan = Perusahaan::create([
            'id_kode' => $id_kode,
            'kode_prs' => $request->kode_prs,
            'nama_prs1' => $request->nama_prs1,
            'nama_prs2' => $request->nama_prs2,
            'alamat_prs' => $request->alamat_prs,
            'rt_rw_prs' => $request->rt_rw_prs,
            'kel_prs' => $request->kel_prs,
            'kec_prs' => $request->kec_prs,
            'kota_prs' => $request->kota_prs,
            'prov_prs' => $request->prov_prs,
            'kd_pos_prs' => $request->kd_pos_prs,
            'tlp1' => $request->tlp1,
            'tlp2' => $request->tlp2,
            'email1' => $request->email1,
            'email2' => $request->email2,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'web' => $request->web,
            'tgl_pendirian' => $request->tgl_pendirian,
            'bidang_ush' => $request->bidang_ush,
            'ijin_ush' => $request->ijin_ush,
            'golongan_ush' => $request->golongan_ush,
            'dirut' => $request->dirut,
            'direktur' => $request->direktur,
            'komisaris_utm' => $request->komisaris_utm,
            'komisaris1' => $request->komisaris1,
            'komisaris2' => $request->komisaris2,
            'komisaris3' => $request->komisaris3,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil dibuat.');
    }

    public function show($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        return view('data-master.perusahaan.show', compact('perusahaan'));
    }

    public function edit($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        return view('data-master.perusahaan.edit', compact('perusahaan'));
    }

    public function update(Request $request, $id)
    {
        $perusahaan = Perusahaan::findOrFail($id);

        $request->validate([
            'nama_prs1' => 'required',
            'alamat_prs' => 'required',
            'kota_prs' => 'required',
            'prov_prs' => 'required',
            'tlp1' => 'required',
            'email1' => 'required|email',
            'bidang_ush' => 'required',
            'dirut' => 'required',
        ]);

        $data = [
            'kode_prs' => $request->kode_prs,
            'nama_prs1' => $request->nama_prs1,
            'nama_prs2' => $request->nama_prs2,
            'alamat_prs' => $request->alamat_prs,
            'rt_rw_prs' => $request->rt_rw_prs,
            'kel_prs' => $request->kel_prs,
            'kec_prs' => $request->kec_prs,
            'kota_prs' => $request->kota_prs,
            'prov_prs' => $request->prov_prs,
            'kd_pos_prs' => $request->kd_pos_prs,
            'tlp1' => $request->tlp1,
            'tlp2' => $request->tlp2,
            'email1' => $request->email1,
            'email2' => $request->email2,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'web' => $request->web,
            'tgl_pendirian' => $request->tgl_pendirian,
            'bidang_ush' => $request->bidang_ush,
            'ijin_ush' => $request->ijin_ush,
            'golongan_ush' => $request->golongan_ush,
            'dirut' => $request->dirut,
            'direktur' => $request->direktur,
            'komisaris_utm' => $request->komisaris_utm,
            'komisaris1' => $request->komisaris1,
            'komisaris2' => $request->komisaris2,
            'komisaris3' => $request->komisaris3,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        $perusahaan->update($data);

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        $perusahaan->delete();

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil dihapus.');
    }
}
