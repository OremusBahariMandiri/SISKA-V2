<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use App\Traits\GenerateIdTrait;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:pengguna')->only('index');
        $this->middleware('check.access:pengguna,detail')->only('show');
        $this->middleware('check.access:pengguna,tambah')->only('create', 'store');
        $this->middleware('check.access:pengguna,ubah')->only('edit', 'update');
        $this->middleware('check.access:pengguna,hapus')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('data-master.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate automatic ID
        $newId = $this->generateId('001', '001_dm_users');

        return view('data-master.users.create', compact('newId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik_kry' => 'required',
            'nama_kry' => 'required',
            'departemen_kry' => 'required',
            'jabatan_kry' => 'required',
            'wilker_kry' => 'required',
            'password_kry' => 'required|min:6|confirmed',
        ]);

        // Generate ID if not present
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('001', '001_dm_users');
        } else {
            $id_kode = $request->id_kode;
        }

        $user = User::create([
            'id_kode' => $id_kode,
            'nik_kry' => $request->nik_kry,
            'nama_kry' => $request->nama_kry,
            'departemen_kry' => $request->departemen_kry,
            'jabatan_kry' => $request->jabatan_kry,
            'wilker_kry' => $request->wilker_kry,
            'password_kry' => $request->password_kry, // Hash dilakukan oleh mutator di model
            'is_admin' => $request->has('is_admin') ? 1 : 0,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dibuat. Anda dapat mengatur hak akses melalui tombol kunci.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('data-master.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('data-master.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validationRules = [
            'nik_kry' => 'required',
            'nama_kry' => 'required',
            'departemen_kry' => 'required',
            'jabatan_kry' => 'required',
            'wilker_kry' => 'required',
        ];

        // Add password validation rules if password is being updated
        if ($request->filled('password_kry')) {
            $validationRules['password_kry'] = 'required|min:6|confirmed';
        }

        $request->validate($validationRules);

        $data = [
            'nik_kry' => $request->nik_kry,
            'nama_kry' => $request->nama_kry,
            'departemen_kry' => $request->departemen_kry,
            'jabatan_kry' => $request->jabatan_kry,
            'wilker_kry' => $request->wilker_kry,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        // PERBAIKAN: Preservasi status admin
        // Hanya update is_admin jika:
        // 1. Ada parameter is_admin di request (dari form yang memiliki checkbox admin)
        // 2. User yang mengedit bukan dirinya sendiri, ATAU
        // 3. User yang mengedit adalah dirinya sendiri tapi dia super admin atau ada permission khusus

        if ($request->has('is_admin')) {
            // Jika ada checkbox is_admin di form (biasanya dari halaman admin management)
            $data['is_admin'] = $request->has('is_admin') ? 1 : 0;
        } else {
            // Jika tidak ada checkbox is_admin (dari form edit profile biasa)
            // Pertahankan status admin yang sudah ada
            $data['is_admin'] = $user->is_admin;
        }

        // Only update password if provided
        if ($request->filled('password_kry')) {
            $data['password_kry'] = $request->password_kry; // Hash dilakukan oleh mutator di model
        }

        $user->update($data);

        // If user was changed to admin, remove all user access records
        if ($user->is_admin) {
            UserAccess::where('id_kode_a01', $user->id_kode)->delete();
        }

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Delete user access
        UserAccess::where('id_kode_a01', $user->id_kode)->delete();

        // Delete user
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}