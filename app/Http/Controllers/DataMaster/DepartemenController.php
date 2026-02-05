<?php

namespace App\Http\Controllers\DataMaster;

use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataMaster\Departemen;

class DepartemenController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:departemen')->only('index');
        $this->middleware('check.access:departemen,detail')->only('show');
        $this->middleware('check.access:departemen,tambah')->only('create', 'store');
        $this->middleware('check.access:departemen,ubah')->only('edit', 'update');
        $this->middleware('check.access:departemen,hapus')->only('destroy');
    }

    public function index()
    {
        // Sort by kode_dep ascending (A-Z)
        $departemens = Departemen::orderBy('kode_dep', 'asc')->get();

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
                $access = $user->userAccess()->where('menu_acs', 'departemen')->first();
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

        return view('data-master.departemen.index', compact('departemens', 'userPermissions'));
    }

    public function create()
    {
        // Generate automatic ID
        $newId = $this->generateId('103', '103_dm_departemen');

        return view('data-master.departemen.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dep' => 'required|string|max:255',
            'nama_jbt' => 'required|string|max:255',
            'singkatan_dep' => 'nullable|string|max:50',
            'singkatan_jbt' => 'nullable|string|max:50',
        ]);

        // Generate ID if not present
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('103', '103_dm_departemen');
        } else {
            $id_kode = $request->id_kode;
        }

        $departemen = Departemen::create([
            'id_kode' => $id_kode,
            'kode_dep' => $request->kode_dep,
            'nama_dep' => $request->nama_dep,
            'singkatan_dep' => $request->singkatan_dep,
            'nama_jbt' => $request->nama_jbt,
            'singkatan_jbt' => $request->singkatan_jbt,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil dibuat.');
    }

    public function show($id)
    {
        $departemen = Departemen::findOrFail($id);
        return view('data-master.departemen.show', compact('departemen'));
    }

    public function edit($id)
    {
        $departemen = Departemen::findOrFail($id);
        return view('data-master.departemen.edit', compact('departemen'));
    }

    public function update(Request $request, $id)
    {
        $departemen = Departemen::findOrFail($id);

        $request->validate([
            'nama_dep' => 'required|string|max:255',
            'nama_jbt' => 'required|string|max:255',
            'singkatan_dep' => 'nullable|string|max:50',
            'singkatan_jbt' => 'nullable|string|max:50',
        ]);

        $data = [
            'kode_dep' => $request->kode_dep,
            'nama_dep' => $request->nama_dep,
            'singkatan_dep' => $request->singkatan_dep,
            'nama_jbt' => $request->nama_jbt,
            'singkatan_jbt' => $request->singkatan_jbt,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        $departemen->update($data);

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $departemen = Departemen::findOrFail($id);
        $departemen->delete();

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil dihapus.');
    }
}