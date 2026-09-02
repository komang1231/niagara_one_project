<?php

use Illuminate\Support\Facades\Route;


// Dashboard
Route::view('/', 'dashboard')->name('dashboard.index');

// Karyawan
Route::view('/data-karyawan', 'data-karyawan.index')->name('data-karyawan.index');
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

// Rekrutmen
Route::view('/rekrutmen', 'rekrutmen.index')->name('rekrutmen.index');
Route::view('/lowongan', 'lowongan.index')->name('lowongan.index');
Route::view('/permintaan-karyawan', 'permintaan-karyawan.index')->name('permintaan-karyawan.index');

// Struktur Organisasi
Route::view('/departemen', 'departemen.index')->name('departemen.index');
Route::view('/divisi', 'divisi.index')->name('divisi.index');
Route::view('/section', 'section.index')->name('section.index');
Route::view('/job-level', 'job-level.index')->name('job-level.index');
Route::view('/job-position', 'job-position.index')->name('job-position.index');
Route::view('/cabang-kantor', 'cabang-kantor.index')->name('cabang-kantor.index');

// Master Data
Route::view('/sumber-pelamar', 'sumber-pelamar.index')->name('sumber-pelamar.index');
Route::view('/status-kepegawaian', 'status-kepegawaian.index')->name('status-kepegawaian.index');
Route::view('/status-kawin', 'status-kawin.index')->name('status-kawin.index');
Route::view('/jenjang-pendidikan', 'jenjang-pendidikan.index')->name('jenjang-pendidikan.index');
Route::view('/agama', 'agama.index')->name('agama.index');
Route::view('/bank', 'bank.index')->name('bank.index');

// Akses & Pengguna
Route::view('/user', 'user.index')->name('user.index');
Route::view('/role', 'role.index')->name('role.index');