<?php

namespace App\Http\Controllers\DataMaster;

use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataMaster\WilayahKerja;

class WilayahKerjaController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:wilayah-kerja')->only('index');
        $this->middleware('check.access:wilayah-kerja,detail')->only('show');
        $this->middleware('check.access:wilayah-kerja,tambah')->only('create', 'store');
        $this->middleware('check.access:wilayah-kerja,ubah')->only('edit', 'update');
        $this->middleware('check.access:wilayah-kerja,hapus')->only('destroy');
    }

    public function index()
    {
        // Sort by kode_wk ascending (A-Z)
        $wilayahKerjas = WilayahKerja::orderBy('kode_wk', 'asc')->get();

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
                $access = $user->userAccess()->where('menu_acs', 'wilayah-kerja')->first();
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

        return view('data-master.wilayah-kerja.index', compact('wilayahKerjas', 'userPermissions'));
    }

    public function create()
    {
        // Generate automatic ID
        $newId = $this->generateId('102', '102_dm_wilker');

        return view('data-master.wilayah-kerja.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'wilayah_krj' => 'required',
            'area_krj' => 'required',
            'alamat_wk' => 'required',
            'kota_wk' => 'required',
            'prov_wk' => 'required',
            'tlp1' => 'required',
            'email1' => 'required|email',
            'foto_dokumen' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx', // Max 2MB
        ]);

        // Generate ID if not present
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('102', '102_dm_wilker');
        } else {
            $id_kode = $request->id_kode;
        }

        // Handle file upload
        $fotoDokumen = null;
        if ($request->hasFile('foto_dokumen')) {
            $file = $request->file('foto_dokumen');
            $fileName = time() . '_' . $id_kode . '_' . $file->getClientOriginalName();
            $file->storeAs('public/wilayah-kerja/dokumen', $fileName);
            $fotoDokumen = 'wilayah-kerja/dokumen/' . $fileName;
        }

        $wilayahKerja = WilayahKerja::create([
            'id_kode' => $id_kode,
            'kode_wk' => $request->kode_wk,
            'wilayah_krj' => $request->wilayah_krj,
            'area_krj' => $request->area_krj,
            'singkatan_wk' => $request->singkatan_wk,
            'alamat_wk' => $request->alamat_wk,
            'rt_rw_wk' => $request->rt_rw_wk,
            'kel_wk' => $request->kel_wk,
            'kec_wk' => $request->kec_wk,
            'kota_wk' => $request->kota_wk,
            'prov_wk' => $request->prov_wk,
            'kd_pos_wk' => $request->kd_pos_wk,
            'tlp1' => $request->tlp1,
            'tlp2' => $request->tlp2,
            'email1' => $request->email1,
            'email2' => $request->email2,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'foto_dokumen' => $fotoDokumen,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('wilayah-kerja.index')
            ->with('success', 'Wilayah Kerja berhasil dibuat.');
    }

    public function show($id)
    {
        $wilayahKerja = WilayahKerja::findOrFail($id);
        return view('data-master.wilayah-kerja.show', compact('wilayahKerja'));
    }

    public function edit($id)
    {
        $wilayahKerja = WilayahKerja::findOrFail($id);
        return view('data-master.wilayah-kerja.edit', compact('wilayahKerja'));
    }

    public function update(Request $request, $id)
    {
        $wilayahKerja = WilayahKerja::findOrFail($id);

        $request->validate([
            'wilayah_krj' => 'required',
            'area_krj' => 'required',
            'alamat_wk' => 'required',
            'kota_wk' => 'required',
            'prov_wk' => 'required',
            'tlp1' => 'required',
            'email1' => 'required|email',
            'foto_dokumen' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx', // Max 2MB
        ]);

        // Handle file upload
        $fotoDokumen = $wilayahKerja->foto_dokumen; // Keep existing file by default
        if ($request->hasFile('foto_dokumen')) {
            // Delete old file if exists
            if ($wilayahKerja->foto_dokumen && \Storage::exists('public/' . $wilayahKerja->foto_dokumen)) {
                \Storage::delete('public/' . $wilayahKerja->foto_dokumen);
            }

            // Upload new file
            $file = $request->file('foto_dokumen');
            $fileName = time() . '_' . $wilayahKerja->id_kode . '_' . $file->getClientOriginalName();
            $file->storeAs('public/wilayah-kerja/dokumen', $fileName);
            $fotoDokumen = 'wilayah-kerja/dokumen/' . $fileName;
        }

        $data = [
            'wilayah_krj' => $request->wilayah_krj,
            'area_krj' => $request->area_krj,
            'singkatan_wk' => $request->singkatan_wk,
            'alamat_wk' => $request->alamat_wk,
            'rt_rw_wk' => $request->rt_rw_wk,
            'kel_wk' => $request->kel_wk,
            'kec_wk' => $request->kec_wk,
            'kota_wk' => $request->kota_wk,
            'prov_wk' => $request->prov_wk,
            'kd_pos_wk' => $request->kd_pos_wk,
            'tlp1' => $request->tlp1,
            'tlp2' => $request->tlp2,
            'email1' => $request->email1,
            'email2' => $request->email2,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'foto_dokumen' => $fotoDokumen,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        $wilayahKerja->update($data);

        return redirect()->route('wilayah-kerja.index')
            ->with('success', 'Wilayah Kerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $wilayahKerja = WilayahKerja::findOrFail($id);

        // Delete associated file if exists
        if ($wilayahKerja->foto_dokumen && \Storage::exists('public/' . $wilayahKerja->foto_dokumen)) {
            \Storage::delete('public/' . $wilayahKerja->foto_dokumen);
        }

        $wilayahKerja->delete();

        return redirect()->route('wilayah-kerja.index')
            ->with('success', 'Wilayah Kerja berhasil dihapus.');
    }
}
