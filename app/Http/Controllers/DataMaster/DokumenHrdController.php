<?php

namespace App\Http\Controllers\DataMaster;

use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataMaster\DokumenHrd;

class DokumenHrdController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:dokumen-hrd')->only('index');
        $this->middleware('check.access:dokumen-hrd,detail')->only('show');
        $this->middleware('check.access:dokumen-hrd,tambah')->only('create', 'store');
        $this->middleware('check.access:dokumen-hrd,ubah')->only('edit', 'update');
        $this->middleware('check.access:dokumen-hrd,hapus')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sort by kode_dok_hrd ascending (A-Z)
        $dokumens = DokumenHrd::orderBy('kode_dok_hrd', 'asc')->get();

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
                $access = $user->userAccess()->where('menu_acs', 'dokumen-hrd')->first();
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

        return view('data-master.dokumen-hrd.index', compact('dokumens', 'userPermissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate automatic ID
        $newId = $this->generateId('106', '106_dm_dok_hrd');

        return view('data-master.dokumen-hrd.create', compact('newId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_dok_hrd' => 'required|unique:106_dm_dok_hrd,kode_dok_hrd',
            'ktg_dok_hrd' => 'required',
            'jns_dok_hrd' => 'nullable',
        ]);

        // Generate ID if not present
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('106', '106_dm_dok_hrd');
        } else {
            $id_kode = $request->id_kode;
        }

        $dokumen = DokumenHrd::create([
            'id_kode' => $id_kode,
            'kode_dok_hrd' => $request->kode_dok_hrd,
            'ktg_dok_hrd' => $request->ktg_dok_hrd,
            'jns_dok_hrd' => $request->jns_dok_hrd,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('dokumen-hrd.index')
            ->with('success', 'Dokumen HRD berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dokumen = DokumenHrd::with(['creator', 'updater'])->findOrFail($id);

        return view('data-master.dokumen-hrd.show', compact('dokumen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dokumen = DokumenHrd::findOrFail($id);

        return view('data-master.dokumen-hrd.edit', compact('dokumen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dokumen = DokumenHrd::findOrFail($id);

        $request->validate([
            'kode_dok_hrd' => 'required|unique:106_dm_dok_hrd,kode_dok_hrd,' . $id . ',id',
            'ktg_dok_hrd' => 'required',
            'jns_dok_hrd' => 'nullable',
        ]);

        $data = [
            'kode_dok_hrd' => $request->kode_dok_hrd,
            'ktg_dok_hrd' => $request->ktg_dok_hrd,
            'jns_dok_hrd' => $request->jns_dok_hrd,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        $dokumen->update($data);

        return redirect()->route('dokumen-hrd.index')
            ->with('success', 'Dokumen HRD berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dokumen = DokumenHrd::findOrFail($id);

        $dokumen->delete();

        return redirect()->route('dokumen-hrd.index')
            ->with('success', 'Dokumen HRD berhasil dihapus.');
    }

    /**
     * Search documents for AJAX request.
     */
    public function search(Request $request)
    {
        $search = $request->get('search');

        $dokumens = DokumenHrd::search($search)
            ->orderBy('kode_dok_hrd', 'asc')
            ->get();

        return response()->json($dokumens);
    }
}