<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Data\DataDokumenController;
use App\Http\Controllers\Data\DataGajiController;
use App\Http\Controllers\Data\DataJenjangKarirController;
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

    Route::get('/data-karyawan/custom-export', [DataKaryawanController::class, 'customExport'])
        ->name('data-karyawan.custom-export')
        ->middleware(['auth', 'check.access:data-karyawan,download']);

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

    // ================================================ DATA KONTRAK ROUTES ========================================= //
    // ============================================================================================================== //
    Route::get('/data-kontrak/check-employee/{id}', [DataKontrakController::class, 'checkEmployeeExists'])
        ->name('data-kontrak.check-employee');

    Route::resource('data-kontrak', DataKontrakController::class);
    Route::delete('data-kontrak/{id}', [DataKontrakController::class, 'destroy'])->name('data-kontrak.destroy');

    // NEW: Route untuk mendapatkan data kontrak aktif (untuk pewarnaan row)
    Route::get('data-kontrak/active-contracts-data', [DataKontrakController::class, 'getActiveContractsData'])
        ->name('data-kontrak.active-contracts-data');

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

    // ================================================ CONTRACT MANAGEMENT ROUTES ================================= //
    // ============================================================================================================= //

    // Contract CRUD operations for specific employee
    Route::prefix('data-kontrak')->name('data-kontrak.')->group(function () {
        // Store new contract
        Route::post('contracts', [DataKontrakController::class, 'storeContract'])
            ->name('contracts.store');

        // Get contract details
        Route::get('contracts/{id}', [DataKontrakController::class, 'getContract'])
            ->name('contracts.show');

        // Update contract
        Route::put('contracts/{id}', [DataKontrakController::class, 'updateContract'])
            ->name('contracts.update');

        // Delete contract
        Route::delete('contracts/{id}', [DataKontrakController::class, 'deleteContract'])
            ->name('contracts.destroy');

        // NEW: Additional endpoints for active contracts management
        Route::get('expiring-contracts', [DataKontrakController::class, 'getExpiringContracts'])
            ->name('expiring-contracts');

        // Legacy contract management routes (keep for backwards compatibility)
        Route::post('add-contract', [DataKontrakController::class, 'addContract'])
            ->name('add-contract');

        Route::post('add-education', [DataKontrakController::class, 'addEducation'])
            ->name('add-education');

        Route::post('add-career', [DataKontrakController::class, 'addCareer'])
            ->name('add-career');
    });


    // Export routes
    Route::post('data-kontrak/export-excel', [DataKontrakController::class, 'exportExcel'])
        ->name('data-kontrak.export-excel');


    // ================================================ DATA DOKUMEN ROUTES ========================================= //
    // ============================================================================================================== //
    // ===== DATA DOKUMEN ROUTES =====
    Route::resource('data-dokumen', DataDokumenController::class);

    Route::prefix('data-dokumen')->group(function () {
        // Employee validation & data
        Route::get('check-employee/{id}', [DataDokumenController::class, 'checkEmployeeExists']);
        Route::get('get-employee-data/{id}', [DataDokumenController::class, 'getEmployeeData']);

        // Master data helpers
        Route::get('get-jabatan-by-departemen/{namaDep}', [DataDokumenController::class, 'getJabatanByDepartemen']);
        Route::get('get-unit-kerja-by-wilayah/{wilayahKrj}', [DataDokumenController::class, 'getUnitKerjaByWilayah']);

        // Document CRUD operations (for modal)
        Route::post('documents/store', [DataDokumenController::class, 'storeDocument'])->name('data-dokumen.documents.store');
        Route::get('documents/{id}', [DataDokumenController::class, 'getDocument'])->name('data-dokumen.documents.get');
        Route::put('documents/{id}', [DataDokumenController::class, 'updateDocument'])->name('data-dokumen.documents.update');
        Route::delete('documents/{id}', [DataDokumenController::class, 'deleteDocument'])->name('data-dokumen.documents.delete');

        // Additional features
        Route::get('expiring-documents', [DataDokumenController::class, 'getExpiringDocuments']);
        Route::get('active-documents-data', [DataDokumenController::class, 'getActiveDocumentsData']);
    });

    Route::prefix('data-dokumen-laporan')->name('data-dokumen-laporan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Data\DataDokumenLaporanController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Data\DataDokumenLaporanController::class, 'show'])->name('show');
    });

    // ================================================ DATA DOKUMEN ROUTES ========================================= //
    // ============================================================================================================== //
    // ===== DATA DOKUMEN ROUTES =====
    // ================================================ DATA JENJANG KARIR ROUTES ==================================== //
    // ============================================================================================================== //

    // ===== DATA JENJANG KARIR ROUTES =====
    Route::resource('data-jenjang-karir', DataJenjangKarirController::class);

    Route::prefix('data-jenjang-karir')->group(function () {
        // Employee validation & data
        Route::get('check-employee/{id}', [DataJenjangKarirController::class, 'checkEmployeeExists']);
        Route::get('get-employee-data/{id}', [DataJenjangKarirController::class, 'getEmployeeData']);

        // Master data helpers
        Route::get('get-jabatan-by-departemen/{namaDep}', [DataJenjangKarirController::class, 'getJabatanByDepartemen']);
        Route::get('get-unit-kerja-by-wilayah/{wilayahKrj}', [DataJenjangKarirController::class, 'getUnitKerjaByWilayah']);
        Route::get('get-departemen-jabatan/{id}', [DataJenjangKarirController::class, 'getDepartemenJabatan']);
        Route::get('get-wilker-unit-krj/{id}', [DataJenjangKarirController::class, 'getWilkerUnitKrj']);

        // Career CRUD operations (for modal)
        Route::post('careers/store', [DataJenjangKarirController::class, 'storeCareer'])->name('data-jenjang-karir.careers.store');
        Route::get('careers/{id}', [DataJenjangKarirController::class, 'getCareer'])->name('data-jenjang-karir.careers.get');
        Route::put('careers/{id}', [DataJenjangKarirController::class, 'updateCareer'])->name('data-jenjang-karir.careers.update');
        Route::delete('careers/{id}', [DataJenjangKarirController::class, 'deleteCareer'])->name('data-jenjang-karir.careers.delete');
        Route::get('check-employee/{id}',        [DataJenjangKarirController::class, 'checkEmployeeExists'])->name('check-employee');
        Route::get('get-employee-data/{id}',     [DataJenjangKarirController::class, 'getEmployeeData'])->name('get-employee-data');
        Route::get('get-jabatan-by-departemen/{namaDep}', [DataJenjangKarirController::class, 'getJabatanByDepartemen'])->name('get-jabatan');
        Route::get('get-unit-kerja-by-wilayah/{wilayahKrj}', [DataJenjangKarirController::class, 'getUnitKerjaByWilayah'])->name('get-unit-kerja');
    });

    // ===== DATA GAJI ROUTES =====
    Route::resource('data-gaji', DataGajiController::class);

    Route::prefix('data-gaji')->group(function () {
        // Employee validation & data
        Route::get('check-employee/{id}',    [DataGajiController::class, 'checkEmployeeExists'])->name('data-gaji.check-employee');
        Route::get('get-employee-data/{id}', [DataGajiController::class, 'getEmployeeData'])->name('data-gaji.get-employee-data');

        // Gaji CRUD operations (for modal)
        Route::post('gaji/store',        [DataGajiController::class, 'storeGaji'])->name('data-gaji.gaji.store');
        Route::get('gaji/{id}',          [DataGajiController::class, 'getGaji'])->name('data-gaji.gaji.get');
        Route::put('gaji/{id}',          [DataGajiController::class, 'updateGaji'])->name('data-gaji.gaji.update');
        Route::delete('gaji/{id}',       [DataGajiController::class, 'deleteGaji'])->name('data-gaji.gaji.delete');

        // AJAX Routes for Salary Management (NEW)
        Route::post('/salaries', [DataGajiController::class, 'storeSalary'])->name('data-gaji.salaries.store');
        Route::get('/salaries/{id}', [DataGajiController::class, 'getSalary'])->name('data-gaji.salaries.get');
        Route::put('/salaries/{id}', [DataGajiController::class, 'updateSalary'])->name('data-gaji.salaries.update');
        Route::delete('/salaries/{id}', [DataGajiController::class, 'deleteSalary'])->name('data-gaji.salaries.delete');

        // Employee Data
        Route::get('/get-employee-data/{id}', [DataGajiController::class, 'getEmployeeData']);
        Route::get('/check-employee/{id}', [DataGajiController::class, 'checkEmployeeExists']);
    });
});
