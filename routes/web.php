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
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\ShiftController;
use App\Models\HariLibur;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\PermintaanCutiController;
use App\Http\Controllers\PermintaanResignController;
use App\Http\Controllers\PermintaanLemburController;
use App\Http\Controllers\PermintaanTukarShiftController;
use App\Http\Controllers\PermintaanKaryawanController;


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

    // ============================================================================
    // PERMINTAAN
    // ============================================================================

    // Halaman utama semua permintaan
    Route::get('/permintaan', [PermintaanController::class, 'index'])
        ->name('permintaan.index');



    // ============================================================================
    // PERMINTAAN KARYAWAN
    // ============================================================================

    Route::get('permintaan-karyawan-trash', [PermintaanKaryawanController::class, 'trash'])
        ->name('permintaan-karyawan.trash');
    Route::post('permintaan-karyawan', [PermintaanKaryawanController::class, 'store'])
        ->name('permintaan-karyawan.store');
    Route::put('permintaan-karyawan/{permintaanKaryawan}', [PermintaanKaryawanController::class, 'update'])
        ->name('permintaan-karyawan.update');
    Route::delete('permintaan-karyawan/{permintaanKaryawan}', [PermintaanKaryawanController::class, 'destroy'])
        ->name('permintaan-karyawan.destroy');
    Route::get('permintaan-karyawan/{id}/edit-data', [PermintaanKaryawanController::class, 'editData'])
        ->name('permintaan-karyawan.edit-data');
    Route::patch('permintaan-karyawan/{id}/restore', [PermintaanKaryawanController::class, 'restore'])
        ->name('permintaan-karyawan.restore');
    Route::delete('permintaan-karyawan/{id}/force-delete', [PermintaanKaryawanController::class, 'forceDelete'])
        ->name('permintaan-karyawan.force-delete');
    Route::get('permintaan-karyawan/get-divisi/{departemen}', [PermintaanKaryawanController::class, 'getDivisi'])
        ->name('permintaan-karyawan.get-divisi');
    Route::get('permintaan-karyawan/get-section/{divisi}', [PermintaanKaryawanController::class, 'getSection'])
        ->name('permintaan-karyawan.get-section');
    Route::get('permintaan-karyawan/get-job-position/{section}', [PermintaanKaryawanController::class, 'getJobPosition'])
        ->name('permintaan-karyawan.get-job-position');
    Route::patch('permintaan-karyawan/{permintaanKaryawan}/approve', [PermintaanKaryawanController::class, 'approve'])
        ->name('permintaan-karyawan.approve');
    Route::patch('permintaan-karyawan/{permintaanKaryawan}/reject', [PermintaanKaryawanController::class, 'reject'])
        ->name('permintaan-karyawan.reject');


    // ============================================================================
    // PERMINTAAN CUTI
    // ============================================================================

    Route::get('permintaan-cuti-trash', [PermintaanCutiController::class, 'trash'])
        ->name('permintaan-cuti.trash');
    Route::post('permintaan-cuti', [PermintaanCutiController::class, 'store'])
        ->name('permintaan-cuti.store');
    Route::put('permintaan-cuti/{permintaanCuti}', [PermintaanCutiController::class, 'update'])
        ->name('permintaan-cuti.update');
    Route::delete('permintaan-cuti/{permintaanCuti}', [PermintaanCutiController::class, 'destroy'])
        ->name('permintaan-cuti.destroy');
    Route::get('permintaan-cuti/{id}/edit-data', [PermintaanCutiController::class, 'editData'])
        ->name('permintaan-cuti.edit-data');
    Route::patch('permintaan-cuti/{id}/restore', [PermintaanCutiController::class, 'restore'])
        ->name('permintaan-cuti.restore');
    Route::delete('permintaan-cuti/{id}/force-delete', [PermintaanCutiController::class, 'forceDelete'])
        ->name('permintaan-cuti.force-delete');
    Route::patch('permintaan-cuti/{permintaanCuti}/approve', [PermintaanCutiController::class, 'approve'])
        ->name('permintaan-cuti.approve');
    Route::patch('permintaan-cuti/{permintaanCuti}/reject', [PermintaanCutiController::class, 'reject'])
        ->name('permintaan-cuti.reject');


    // ============================================================================
    // PERMINTAAN LEMBUR
    // ============================================================================

    Route::get('permintaan-lembur-trash', [PermintaanLemburController::class, 'trash'])
        ->name('permintaan-lembur.trash');
    Route::post('permintaan-lembur', [PermintaanLemburController::class, 'store'])
        ->name('permintaan-lembur.store');
    Route::put('permintaan-lembur/{permintaanLembur}', [PermintaanLemburController::class, 'update'])
        ->name('permintaan-lembur.update');
    Route::delete('permintaan-lembur/{permintaanLembur}', [PermintaanLemburController::class, 'destroy'])
        ->name('permintaan-lembur.destroy');
    Route::get('permintaan-lembur/{id}/edit-data', [PermintaanLemburController::class, 'editData'])
        ->name('permintaan-lembur.edit-data');
    Route::patch('permintaan-lembur/{id}/restore', [PermintaanLemburController::class, 'restore'])
        ->name('permintaan-lembur.restore');
    Route::delete('permintaan-lembur/{id}/force-delete', [PermintaanLemburController::class, 'forceDelete'])
        ->name('permintaan-lembur.force-delete');
    Route::patch('permintaan-lembur/{permintaanLembur}/approve', [PermintaanLemburController::class, 'approve'])
        ->name('permintaan-lembur.approve');
    Route::patch('permintaan-lembur/{permintaanLembur}/reject', [PermintaanLemburController::class, 'reject'])
        ->name('permintaan-lembur.reject');


    // ============================================================================
    // PERMINTAAN RESIGN
    // ============================================================================

    Route::get('permintaan-resign-trash', [PermintaanResignController::class, 'trash'])
        ->name('permintaan-resign.trash');
    Route::post('permintaan-resign', [PermintaanResignController::class, 'store'])
        ->name('permintaan-resign.store');
    Route::put('permintaan-resign/{permintaanResign}', [PermintaanResignController::class, 'update'])
        ->name('permintaan-resign.update');
    Route::delete('permintaan-resign/{permintaanResign}', [PermintaanResignController::class, 'destroy'])
        ->name('permintaan-resign.destroy');
    Route::get('permintaan-resign/{id}/edit-data', [PermintaanResignController::class, 'editData'])
        ->name('permintaan-resign.edit-data');
    Route::patch('permintaan-resign/{id}/restore', [PermintaanResignController::class, 'restore'])
        ->name('permintaan-resign.restore');
    Route::delete('permintaan-resign/{id}/force-delete', [PermintaanResignController::class, 'forceDelete'])
        ->name('permintaan-resign.force-delete');
    Route::patch('permintaan-resign/{permintaanResign}/approve', [PermintaanResignController::class, 'approve'])
        ->name('permintaan-resign.approve');
    Route::patch('permintaan-resign/{permintaanResign}/reject', [PermintaanResignController::class, 'reject'])
        ->name('permintaan-resign.reject');


    // ============================================================================
    // PERMINTAAN TUKAR SHIFT
    // ============================================================================

    Route::get('permintaan-tukar-shift-trash', [PermintaanTukarShiftController::class, 'trash'])
        ->name('permintaan-tukar-shift.trash');
    Route::post('permintaan-tukar-shift', [PermintaanTukarShiftController::class, 'store'])
        ->name('permintaan-tukar-shift.store');
    Route::put('permintaan-tukar-shift/{permintaanTukarShift}', [PermintaanTukarShiftController::class, 'update'])
        ->name('permintaan-tukar-shift.update');
    Route::delete('permintaan-tukar-shift/{permintaanTukarShift}', [PermintaanTukarShiftController::class, 'destroy'])
        ->name('permintaan-tukar-shift.destroy');
    Route::get('permintaan-tukar-shift/{id}/edit-data', [PermintaanTukarShiftController::class, 'editData'])
        ->name('permintaan-tukar-shift.edit-data');
    Route::patch('permintaan-tukar-shift/{id}/restore', [PermintaanTukarShiftController::class, 'restore'])
        ->name('permintaan-tukar-shift.restore');
    Route::delete('permintaan-tukar-shift/{id}/force-delete', [PermintaanTukarShiftController::class, 'forceDelete'])
        ->name('permintaan-tukar-shift.force-delete');
    Route::patch('permintaan-tukar-shift/{permintaanTukarShift}/approve', [PermintaanTukarShiftController::class, 'approve'])
        ->name('permintaan-tukar-shift.approve');
    Route::patch('permintaan-tukar-shift/{permintaanTukarShift}/reject', [PermintaanTukarShiftController::class, 'reject'])
        ->name('permintaan-tukar-shift.reject');


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

    Route::resource('hari-libur', HariLiburController::class)
        ->parameters(['hari-libur' => 'hari-libur']);
    Route::get('hari-libur/{id}/edit-data', [HariLiburController::class, 'editData'])
        ->name('hari-libur.edit-data');
    Route::get('hari-libur-trash', [HariLiburController::class, 'trash'])
        ->name('hari-libur.trash');
    Route::patch('hari-libur/{id}/restore', [HariLiburController::class, 'restore'])
        ->name('hari-libur.restore');
    Route::delete('hari-libur/{id}/force-delete', [HariLiburController::class, 'forceDelete'])
        ->name('hari-libur.force-delete');
    Route::patch('hari-libur/{hariLibur}/toggle-status', [HariLiburController::class, 'toggleStatus'])
        ->name('hari-libur.toggle-status');

    Route::resource('cuti', CutiController::class)
        ->parameters(['cuti' => 'cuti']);
    Route::get('cuti/{id}/edit-data', [CutiController::class, 'editData'])
        ->name('cuti.edit-data');
    Route::get('cuti-trash', [CutiController::class, 'trash'])
        ->name('cuti.trash');
    Route::patch('cuti/{id}/restore', [CutiController::class, 'restore'])
        ->name('cuti.restore');
    Route::delete('cuti/{id}/force-delete', [CutiController::class, 'forceDelete'])
        ->name('cuti.force-delete');
    Route::patch('cuti/{cuti}/toggle-status', [CutiController::class, 'toggleStatus'])
        ->name('cuti.toggle-status');

    Route::resource('shift', ShiftController::class)
        ->parameters(['shift' => 'shift']);
    Route::get('shift/{id}/edit-data', [ShiftController::class, 'editData'])
        ->name('shift.edit-data');
    Route::get('shift-trash', [ShiftController::class, 'trash'])
        ->name('shift.trash');
    Route::patch('shift/{id}/restore', [ShiftController::class, 'restore'])
        ->name('shift.restore');
    Route::delete('shift/{id}/force-delete', [ShiftController::class, 'forceDelete'])
        ->name('shift.force-delete');
    Route::patch('shift/{shift}/toggle-status', [ShiftController::class, 'toggleStatus'])
        ->name('shift.toggle-status');

    Route::view('/perubahan-karyawan', 'perubahan-karyawan.index')->name('perubahan-karyawan.index');
    // Route::view('/permintaan-resign', 'permintaan-resign.index')->name('permintaan-resign.index');
    Route::view('/surat-peringatan', 'surat-peringatan.index')->name('surat-peringatan.index');

    // Kehadiran
    Route::view('/jadwal-karyawan', 'jadwal-karyawan.index')->name('jadwal-karyawan.index');
    Route::view('/pola-shift', 'pola-shift.index')->name('pola-shift.index');
    Route::view('/tukar-shift', 'tukar-shift.index')->name('tukar-shift.index');
    Route::view('/lembur', 'lembur.index')->name('lembur.index');

    // Cuti
    Route::view('/saldo-cuti', 'saldo-cuti.index')->name('saldo-cuti.index');
    // Route::view('/permintaan-cuti', 'permintaan-cuti.index')->name('permintaan-cuti.index');

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
    // Route::view('/permintaan-karyawan', 'permintaan-karyawan.index')->name('permintaan-karyawan.index');

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
