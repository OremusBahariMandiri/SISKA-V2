<?php

namespace App\Http\Controllers\DataMaster;

use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataMaster\Kontrak;
use App\Models\DataMaster\KontrakKerja;

class KontrakKerjaController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:kontrak-kerja')->only('index');
        $this->middleware('check.access:kontrak-kerja,detail')->only('show');
        $this->middleware('check.access:kontrak-kerja,tambah')->only('create', 'store');
        $this->middleware('check.access:kontrak-kerja,ubah')->only('edit', 'update');
        $this->middleware('check.access:kontrak-kerja,hapus')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sort by kode_kontrak ascending (A-Z)
        $kontraks = KontrakKerja::orderBy('kode_ktr', 'asc')->get();

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
                $access = $user->userAccess()->where('menu_acs', 'kontrak-kerja')->first();
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

        return view('data-master.kontrak-kerja.index', compact('kontraks', 'userPermissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate automatic ID
        $newId = $this->generateId('104', '104_dm_kontrak');

        return view('data-master.kontrak-kerja.create', compact('newId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_ktr' => 'required|unique:104_dm_kontrak,kode_ktr',
            'nama_ktr' => 'required',
            'singkatan_ktr' => 'required',
        ]);

        // Generate ID if not present
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('104', '104_dm_kontrak');
        } else {
            $id_kode = $request->id_kode;
        }

        $kontrak = KontrakKerja::create([
            'id_kode' => $id_kode,
            'kode_ktr' => $request->kode_ktr,
            'nama_ktr' => $request->nama_ktr,
            'singkatan_ktr' => $request->singkatan_ktr,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('kontrak-kerja.index')
            ->with('success', 'Kontrak Kerja berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kontrak = KontrakKerja::with(['karyawan', 'creator', 'updater'])->findOrFail($id);

        return view('data-master.kontrak-kerja.show', compact('kontrak'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kontrak = KontrakKerja::findOrFail($id);

        return view('data-master.kontrak-kerja.edit', compact('kontrak'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kontrak = KontrakKerja::findOrFail($id);

        $request->validate([
            'kode_ktr' => 'required|unique:104_dm_kontrak,kode_ktr,' . $id . ',id',
            'nama_ktr' => 'required',
            'singkatan_ktr' => 'required',
        ]);

        $data = [
            'kode_ktr' => $request->kode_ktr,
            'nama_ktr' => $request->nama_ktr,
            'singkatan_ktr' => $request->singkatan_ktr,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        $kontrak->update($data);

        return redirect()->route('kontrak-kerja.index')
            ->with('success', 'Kontrak Kerja berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kontrak = KontrakKerja::findOrFail($id);

        // Check if there are employees using this contract
        if ($kontrak->hasKaryawan()) {
            return redirect()->route('kontrak-kerja.index')
                ->with('error', 'Kontrak Kerja tidak dapat dihapus karena masih digunakan oleh karyawan.');
        }

        $kontrak->delete();

        return redirect()->route('kontrak-kerja.index')
            ->with('success', 'Kontrak Kerja berhasil dihapus.');
    }

    /**
     * Get contracts by type for AJAX request.
     */
    public function getByType(Request $request)
    {
        $type = $request->get('type');

        $kontraks = KontrakKerja::when($type, function ($query) use ($type) {
            return $query->byType($type);
        })->orderBy('kode_ktr', 'asc')->get();

        return response()->json($kontraks);
    }

    /**
     * Search contracts for AJAX request.
     */
    public function search(Request $request)
    {
        $search = $request->get('search');

        $kontraks = KontrakKerja::search($search)
            ->orderBy('kode_ktr', 'asc')
            ->get();

        return response()->json($kontraks);
    }

    /**
     * Get contract statistics.
     */
    public function statistics()
    {
        $totalKontraks = KontrakKerja::count();
        $kontrakByType = KontrakKerja::selectRaw('jenis_kontrak, COUNT(*) as total')
            ->groupBy('jenis_kontrak')
            ->get();

        $kontrakWithKaryawan = KontrakKerja::has('karyawan')->count();
        $kontrakWithoutKaryawan = $totalKontraks - $kontrakWithKaryawan;

        return view('data-master.kontrak-kerja.statistics', compact(
            'totalKontraks',
            'kontrakByType',
            'kontrakWithKaryawan',
            'kontrakWithoutKaryawan'
        ));
    }
}