<?php

namespace App\Http\Controllers\DataMaster;

use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataMaster\DokumenKaryawan;

class DokumenKaryawanController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:dokumen-karyawan')->only('index');
        $this->middleware('check.access:dokumen-karyawan,detail')->only('show');
        $this->middleware('check.access:dokumen-karyawan,tambah')->only('create', 'store');
        $this->middleware('check.access:dokumen-karyawan,ubah')->only('edit', 'update');
        $this->middleware('check.access:dokumen-karyawan,hapus')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sort by kode_dok_kry ascending (A-Z)
        $dokumens = DokumenKaryawan::orderBy('kode_dok_kry', 'asc')->get();

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
                $access = $user->userAccess()->where('menu_acs', 'dokumen-karyawan')->first();
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

        return view('data-master.dokumen-karyawan.index', compact('dokumens', 'userPermissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate automatic ID
        $newId = $this->generateId('105', '105_dm_dok_kry');

        return view('data-master.dokumen-karyawan.create', compact('newId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_dok_kry' => 'required|unique:105_dm_dok_kry,kode_dok_kry',
            'ktg_dok_kry' => 'required',
            'jns_dok_kry' => 'nullable',
        ]);

        // Generate ID if not present
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('105', '105_dm_dok_kry');
        } else {
            $id_kode = $request->id_kode;
        }

        $dokumen = DokumenKaryawan::create([
            'id_kode' => $id_kode,
            'kode_dok_kry' => $request->kode_dok_kry,
            'ktg_dok_kry' => $request->ktg_dok_kry,
            'jns_dok_kry' => $request->jns_dok_kry,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('dokumen-karyawan.index')
            ->with('success', 'Dokumen Karyawan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dokumen = DokumenKaryawan::with(['creator', 'updater'])->findOrFail($id);

        return view('data-master.dokumen-karyawan.show', compact('dokumen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dokumen = DokumenKaryawan::findOrFail($id);

        return view('data-master.dokumen-karyawan.edit', compact('dokumen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dokumen = DokumenKaryawan::findOrFail($id);

        $request->validate([
            'kode_dok_kry' => 'required|unique:105_dm_dok_kry,kode_dok_kry,' . $id . ',id',
            'ktg_dok_kry' => 'required',
            'jns_dok_kry' => 'nullable',
        ]);

        $data = [
            'kode_dok_kry' => $request->kode_dok_kry,
            'ktg_dok_kry' => $request->ktg_dok_kry,
            'jns_dok_kry' => $request->jns_dok_kry,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        $dokumen->update($data);

        return redirect()->route('dokumen-karyawan.index')
            ->with('success', 'Dokumen Karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dokumen = DokumenKaryawan::findOrFail($id);

        $dokumen->delete();

        return redirect()->route('dokumen-karyawan.index')
            ->with('success', 'Dokumen Karyawan berhasil dihapus.');
    }

    /**
     * Search documents for AJAX request.
     */
    public function search(Request $request)
    {
        $search = $request->get('search');

        $dokumens = DokumenKaryawan::search($search)
            ->orderBy('kode_dok_kry', 'asc')
            ->get();

        return response()->json($dokumens);
    }
}