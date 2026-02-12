<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAccess;
use Illuminate\Http\Request;

class UserAccessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:hak_akses')->only('edit', 'show');
        $this->middleware('check.access:hak_akses,ubah')->only('update');
    }

    /**
     * Show access rights for a user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        // Redirect to edit as they have the same view
        return redirect()->route('users.index', $user->id);
    }

    /**
     * Show the form for editing user access.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $menuList = [
            'pengguna' => 'Pengguna',
            'hak_akses' => 'Hak Akses',
            'perusahaan' => 'Perusahaan',
            'wilayah-kerja' => 'Wilayah Kerja',
            'departemen' => 'Departemen',
            'dokumen-karyawan' => 'Dokumen Karyawan',
            'data-karyawan' => 'Data Karyawan',
            'data-kontrak' => 'Data Kontrak',

        ];

        // Prepare user access data
        $userAccess = [];
        foreach ($user->userAccess as $access) {
            $userAccess[$access->menu_acs] = [
                'tambah' => $access->tambah_acs,
                'ubah' => $access->ubah_acs,
                'hapus' => $access->hapus_acs,
                'download' => $access->download_acs,
                'detail' => $access->detail_acs,
                'monitoring' => $access->monitoring_acs,
            ];
        }

        return view('data-master.user-access.edit', compact('user', 'menuList', 'userAccess'));
    }

    /**
     * Update the user access in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Update admin status if changed
        if ($user->is_admin != $request->has('is_admin')) {
            $user->update([
                'is_admin' => $request->has('is_admin') ? 1 : 0,
                'updated_by' => auth()->user()->id_kode ?? null,
            ]);
        }

        // If user is now an admin, delete all access records as they're not needed
        if ($request->has('is_admin')) {
            UserAccess::where('id_kode_a01', $user->id_kode)->delete();
            return redirect()->route('user-access.edit', $user->id)
                ->with('success', 'Pengguna berhasil diubah menjadi Administrator dengan akses penuh.');
        }

        // Delete existing access
        UserAccess::where('id_kode_a01', $user->id_kode)->delete();

        // Create new access if menu_access is provided
        if ($request->has('menu_access')) {
            foreach ($request->menu_access as $menu => $permissions) {
                UserAccess::create([
                    'id_kode_a01' => $user->id_kode,
                    'menu_acs' => $menu,
                    'tambah_acs' => isset($permissions['tambah']) ? 1 : 0,
                    'ubah_acs' => isset($permissions['ubah']) ? 1 : 0,
                    'hapus_acs' => isset($permissions['hapus']) ? 1 : 0,
                    'download_acs' => isset($permissions['download']) ? 1 : 0,
                    'detail_acs' => isset($permissions['detail']) ? 1 : 0,
                    'monitoring_acs' => isset($permissions['monitoring']) ? 1 : 0,
                    'created_by' => auth()->user()->id_kode ?? null,
                ]);
            }
        }

        return redirect()->route('users.index', $user->id)
            ->with('success', 'Hak akses pengguna berhasil diperbarui.');
    }
}
