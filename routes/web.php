<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Data\DataKaryawanController;
use App\Http\Controllers\Data\DataKontrakController;
use App\Http\Controllers\DataMaster\DepartemenController;
use App\Http\Controllers\DataMaster\DokumenKaryawanController;
use App\Http\Controllers\DataMaster\KontrakKerjaController;
use App\Http\Controllers\DataMaster\PerusahaanController;
use App\Http\Controllers\DataMaster\UserAccessController;
use App\Http\Controllers\DataMaster\UserController;
use App\Http\Controllers\DataMaster\WilayahKerjaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ================================================ GENERAL ROUTE =============================================== //
// ============================================================================================================== //

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // ================================================ DATA MASTER ROUTE =========================================== //
    // ============================================================================================================== //

    // User Management
    Route::resource('users', UserController::class);
    Route::get('users/{id}/view-document', [UserController::class, 'viewDocument'])->name('users.view-document');

    // User Access Management
    Route::prefix('user-access')->name('user-access.')->group(function () {
        Route::get('/{user}', [UserAccessController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserAccessController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserAccessController::class, 'update'])->name('update');
    });

    Route::resource('perusahaan', PerusahaanController::class);
    Route::resource('wilayah-kerja', WilayahKerjaController::class);
    Route::resource('departemen', DepartemenController::class);
    Route::resource('kontrak-kerja', KontrakKerjaController::class);
    Route::resource('dokumen-karyawan', DokumenKaryawanController::class);

    // ================================================ MANAJEMEN DATA ROUTE ======================================== //
    // ============================================================================================================== //

    Route::resource('data-karyawan', DataKaryawanController::class);
    Route::post('data-karyawan/export-excel', [DataKaryawanController::class, 'exportExcel'])
        ->name('data-karyawan.export-excel');
    // Route untuk mendapatkan jabatan berdasarkan departemen
    Route::get('/departemen/{id}/jabatan', [DataKaryawanController::class, 'getDepartemenJabatan'])
        ->name('departemen.jabatan');
    // Route untuk mendapatkan jabatan berdasarkan departemen
    Route::get('/wilker/{id}/unitkrj', [DataKaryawanController::class, 'getWilkerUnitKrj'])
        ->name('wilker.unitkrj');
    // Tambahkan di dalam group route data-karyawan
    Route::get('/data-karyawan/jabatan/{namaDep}', [DataKaryawanController::class, 'getJabatanByDepartemen'])->name('data-karyawan.jabatan');
    Route::get('/data-karyawan/unit-kerja/{wilayahKrj}', [DataKaryawanController::class, 'getUnitKerjaByWilayah'])->name('data-karyawan.unit-kerja');
    Route::get('/data-karyawan/unit-kerja/{wilayahKrj}', [DataKaryawanController::class, 'getUnitKerjaByWilayah'])->name('data-karyawan.unit-kerja');
    Route::get('/data-karyawan/wilker-detail/{id}', [DataKaryawanController::class, 'getWilkerUnitKrj'])->name('data-karyawan.wilker-detail');

    Route::get('data-karyawan/get-jabatan-by-departemen/{namaDep}', [DataKaryawanController::class, 'getJabatanByDepartemen']);
    Route::get('data-karyawan/get-unit-kerja-by-wilayah/{wilayahKrj}', [DataKaryawanController::class, 'getUnitKerjaByWilayah']);
    Route::get('data-karyawan/get-departemen-jabatan/{id}', [DataKaryawanController::class, 'getDepartemenJabatan']);
    Route::get('data-karyawan/get-wilker-unit-krj/{id}', [DataKaryawanController::class, 'getWilkerUnitKrj']);

    Route::resource('data-kontrak', DataKontrakController::class);
    // Route untuk mendapatkan data employee untuk kontrak
    Route::get('data-kontrak/get-employee-data/{id}', [DataKontrakController::class, 'getEmployeeData'])
        ->name('data-kontrak.get-employee-data');

    // Route untuk mendapatkan jabatan berdasarkan departemen (sama seperti data-karyawan)
    Route::get('data-kontrak/get-jabatan-by-departemen/{namaDep}', [DataKontrakController::class, 'getJabatanByDepartemen'])
        ->name('data-kontrak.get-jabatan-by-departemen');

    // Route untuk mendapatkan unit kerja berdasarkan wilayah (sama seperti data-karyawan)
    Route::get('data-kontrak/get-unit-kerja-by-wilayah/{wilayahKrj}', [DataKontrakController::class, 'getUnitKerjaByWilayah'])
        ->name('data-kontrak.get-unit-kerja-by-wilayah');

    // Route untuk mendapatkan departemen jabatan berdasarkan ID
    Route::get('data-kontrak/get-departemen-jabatan/{id}', [DataKontrakController::class, 'getDepartemenJabatan'])
        ->name('data-kontrak.get-departemen-jabatan');

    // Route untuk mendapatkan wilker unit kerja berdasarkan ID
    Route::get('data-kontrak/get-wilker-unit-krj/{id}', [DataKontrakController::class, 'getWilkerUnitKrj'])
        ->name('data-kontrak.get-wilker-unit-krj');
});
