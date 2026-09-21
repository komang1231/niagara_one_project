<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\JobPositionController;
use App\Http\Controllers\JobLevelController;
use App\Http\Controllers\CabangKantorController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatusKepegawaianController;
use App\Http\Controllers\KaryawanController;

Route::get('/test-laravel', function () {
    return 'Laravel OK';
});

// LOGIN
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.authenticate');

Route::middleware('auth')->group(function () {
    // DASHBOARD
    Route::view('/', 'dashboard')->name('dashboard.index');

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Karyawan
    // Karyawan
    Route::resource('karyawan', KaryawanController::class)
        ->parameters(['karyawan' => 'karyawan']);
    Route::get('karyawan/{id}/edit-data', [KaryawanController::class, 'editData'])
        ->name('karyawan.edit-data');
    Route::get('karyawan-trash', [KaryawanController::class, 'trash'])
        ->name('karyawan.trash');
    Route::patch('karyawan/{id}/restore', [KaryawanController::class, 'restore'])
        ->name('karyawan.restore');
        Route::delete('karyawan/{id}/force-delete', [KaryawanController::class, 'forceDelete'])
    ->name('karyawan.force-delete');
    Route::patch('karyawan/{karyawan}/toggle-status', [KaryawanController::class, 'toggleStatus'])
        ->name('karyawan.toggle-status');
    //chained dropdown
    Route::get('karyawan/get-divisi/{departemen}', [KaryawanController::class, 'getDivisi'])
        ->name('karyawan.get-divisi');
    Route::get('karyawan/get-section/{divisi}', [KaryawanController::class, 'getSection'])
        ->name('karyawan.get-section');
    Route::get('karyawan/get-job-position/{section}', [KaryawanController::class, 'getJobPosition'])
        ->name('karyawan.get-job-position');


    Route::view('/kontrak-karyawan', 'kontrak-karyawan.index')->name('kontrak-karyawan.index');
    Route::view('/perubahan-karyawan', 'perubahan-karyawan.index')->name('perubahan-karyawan.index');
    Route::view('/permintaan-resign', 'permintaan-resign.index')->name('permintaan-resign.index');
    Route::view('/surat-peringatan', 'surat-peringatan.index')->name('surat-peringatan.index');

    // Kehadiran
    Route::view('/jadwal-karyawan', 'jadwal-karyawan.index')->name('jadwal-karyawan.index');
    Route::view('/shift', 'shift.index')->name('shift.index');
    Route::view('/pola-shift', 'pola-shift.index')->name('pola-shift.index');
    Route::view('/tukar-shift', 'tukar-shift.index')->name('tukar-shift.index');
    Route::view('/lembur', 'lembur.index')->name('lembur.index');

    // Cuti
    Route::view('/saldo-cuti', 'saldo-cuti.index')->name('saldo-cuti.index');
    Route::view('/permintaan-cuti', 'permintaan-cuti.index')->name('permintaan-cuti.index');

    // test front end
    // Route::get('/departemen/{departemen}/edit', [DepartemenController::class, 'edit']);
    // Route::view('/departemen/trash', 'departemen.trash')->name('departemen.trash');

    Route::view('/section', 'section.index')->name('section.index');
    Route::view('/job-level', 'job-level.index')->name('job-level.index');
    Route::view('/job-position', 'job-position.index')->name('job-position.index');
    Route::view('/cabang-kantor', 'cabang-kantor.index')->name('cabang-kantor.index');

    // Rekrutmen
    Route::view('/rekrutmen', 'rekrutmen.index')->name('rekrutmen.index');
    Route::view('/lowongan', 'lowongan.index')->name('lowongan.index');
    Route::view('/permintaan-karyawan', 'permintaan-karyawan.index')->name('permintaan-karyawan.index');

    // Struktur Organisasi

    // Departemen
    Route::resource('departemen', DepartemenController::class)
        ->parameters(['departemen' => 'departemen']);
    Route::get('departemen/{id}/edit-data', [DepartemenController::class, 'editData'])
        ->name('departemen.edit-data');
    Route::get('departemen-trash', [DepartemenController::class, 'trash'])
        ->name('departemen.trash');
    Route::patch('departemen/{id}/restore', [DepartemenController::class, 'restore'])
        ->name('departemen.restore');
    Route::delete('departemen/{id}/force-delete', [DepartemenController::class, 'forceDelete'])
        ->name('departemen.force-delete');
    Route::patch('departemen/{departemen}/toggle-status', [DepartemenController::class, 'toggleStatus'])
        ->name('departemen.toggle-status');

    // Divisi
    Route::resource('divisi', DivisiController::class)
        ->parameters(['divisi' => 'divisi']);
    Route::get('divisi/{id}/edit-data', [DivisiController::class, 'editData'])
        ->name('divisi.edit-data');
    Route::get('divisi-trash', [DivisiController::class, 'trash'])
        ->name('divisi.trash');
    Route::patch('divisi/{id}/restore', [DivisiController::class, 'restore'])
        ->name('divisi.restore');
    Route::delete('divisi/{id}/force-delete', [DivisiController::class, 'forceDelete'])
        ->name('divisi.force-delete');
    Route::patch('divisi/{divisi}/toggle-status', [DivisiController::class, 'toggleStatus'])
        ->name('divisi.toggle-status');

    // Section
    Route::resource('section', SectionController::class)
        ->parameters(['section' => 'section']);
    Route::get('section/{id}/edit-data', [SectionController::class, 'editData'])
        ->name('section.edit-data');
    Route::get('section-trash', [SectionController::class, 'trash'])
        ->name('section.trash');
    Route::patch('section/{id}/restore', [SectionController::class, 'restore'])
        ->name('section.restore');
    Route::delete('section/{id}/force-delete', [SectionController::class, 'forceDelete'])
        ->name('section.force-delete');
    Route::patch('section/{section}/toggle-status', [SectionController::class, 'toggleStatus'])
        ->name('section.toggle-status');

    // Job Level
    Route::resource('job-level', JobLevelController::class)
        ->parameters(['job-level' => 'jobLevel']);
    Route::get('job-level/{id}/edit-data', [JobLevelController::class, 'editData'])
        ->name('job-level.edit-data');
    Route::get('job-level-trash', [JobLevelController::class, 'trash'])
        ->name('job-level.trash');
    Route::patch('job-level/{id}/restore', [JobLevelController::class, 'restore'])
        ->name('job-level.restore');
    Route::delete('job-level/{id}/force-delete', [JobLevelController::class, 'forceDelete'])
        ->name('job-level.force-delete');
    Route::patch('job-level/{jobLevel}/toggle-status', [JobLevelController::class, 'toggleStatus'])
        ->name('job-level.toggle-status');

    // Job Position
    Route::resource('job-position', JobPositionController::class)
        ->parameters(['job-position' => 'jobPosition']);
    Route::get('job-position/{id}/edit-data', [JobPositionController::class, 'editData'])
        ->name('job-position.edit-data');
    Route::get('job-position-trash', [JobPositionController::class, 'trash'])
        ->name('job-position.trash');
    Route::patch('job-position/{id}/restore', [JobPositionController::class, 'restore'])
        ->name('job-position.restore');
    Route::delete('job-position/{id}/force-delete', [JobPositionController::class, 'forceDelete'])
        ->name('job-position.force-delete');
    Route::patch('job-position/{jobPosition}/toggle-status', [JobPositionController::class, 'toggleStatus'])
        ->name('job-position.toggle-status');

    // Cabang Kantor
    Route::resource('cabang-kantor', CabangKantorController::class)
        ->parameters(['cabang-kantor' => 'cabangKantor']);

    Route::get('cabang-kantor-trash', [CabangKantorController::class, 'trash'])
        ->name('cabang-kantor.trash');

    Route::patch('cabang-kantor/{id}/restore', [CabangKantorController::class, 'restore'])
        ->name('cabang-kantor.restore');

    // Master Data
    Route::view('/sumber-pelamar', 'sumber-pelamar.index')->name('sumber-pelamar.index');

    //Status Kepegawaian
    Route::resource('status-kepegawaian', StatusKepegawaianController::class)
        ->parameters(['status-kepegawaian' => 'statusKepegawaian']);
    Route::get('status-kepegawaian/{id}/edit-data', [StatusKepegawaianController::class, 'editData'])
        ->name('status-kepegawaian.edit-data');
    Route::get('status-kepegawaian-trash', [StatusKepegawaianController::class, 'trash'])
        ->name('status-kepegawaian.trash');
    Route::patch('status-kepegawaian/{id}/restore', [StatusKepegawaianController::class, 'restore'])
        ->name('status-kepegawaian.restore');
    Route::delete('status-kepegawaian/{id}/force-delete', [StatusKepegawaianController::class, 'forceDelete'])
        ->name('status-kepegawaian.force-delete');
    Route::patch('status-kepegawaian/{statusKepegawaian}/toggle-status', [StatusKepegawaianController::class, 'toggleStatus'])
        ->name('status-kepegawaian.toggle-status');


    Route::view('/status-kawin', 'status-kawin.index')->name('status-kawin.index');
    Route::view('/jenjang-pendidikan', 'jenjang-pendidikan.index')->name('jenjang-pendidikan.index');
    Route::view('/agama', 'agama.index')->name('agama.index');
    Route::view('/bank', 'bank.index')->name('bank.index');

    // Akses & Pengguna
    Route::view('/user', 'user.index')->name('user.index');

    // Role
    Route::resource('role', RoleController::class)
        ->parameters(['role' => 'role']);
    Route::get('role/{id}/edit-data', [RoleController::class, 'editData'])
        ->name('role.edit-data');
    Route::get('role-trash', [RoleController::class, 'trash'])
        ->name('role.trash');
    Route::patch('role/{id}/restore', [RoleController::class, 'restore'])
        ->name('role.restore');
    Route::delete('role/{id}/force-delete', [RoleController::class, 'forceDelete'])
        ->name('role.force-delete');
    Route::patch('role/{role}/toggle-status', [RoleController::class, 'toggleStatus'])
        ->name('role.toggle-status');
});
