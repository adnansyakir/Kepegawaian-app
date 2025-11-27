<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Authentication routes (manual)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.perform')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    return redirect()->route('login');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Karyawan
    Route::resource('karyawan', App\Http\Controllers\Admin\KaryawanController::class);
    Route::post('karyawan/import', [App\Http\Controllers\Admin\KaryawanController::class, 'import'])->name('karyawan.import');
    
    // Dosen
    Route::resource('dosen', App\Http\Controllers\Admin\DosenController::class);
    Route::post('dosen/import', [App\Http\Controllers\Admin\DosenController::class, 'import'])->name('dosen.import');
    
    // Teknisi
    Route::resource('teknisi', App\Http\Controllers\Admin\TeknisiController::class);
    Route::post('teknisi/import', [App\Http\Controllers\Admin\TeknisiController::class, 'import'])->name('teknisi.import');
    
    // Users
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show']);
    
    // Data Master
    Route::resource('unit-kerja', App\Http\Controllers\Admin\UnitKerjaController::class);
    Route::resource('jurusan', App\Http\Controllers\Admin\JurusanController::class);
    Route::resource('prodi', App\Http\Controllers\Admin\ProdiController::class);
    Route::resource('pangkat', App\Http\Controllers\Admin\PangkatController::class);
    Route::resource('golongan', App\Http\Controllers\Admin\GolonganController::class);
});

// Direktur Routes
Route::middleware(['auth', 'direktur'])->prefix('direktur')->name('direktur.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Direktur\DashboardController::class, 'index'])->name('dashboard');
    
    // Karyawan (Read Only)
    Route::get('/karyawan', [App\Http\Controllers\Direktur\KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/karyawan/{karyawan}', [App\Http\Controllers\Direktur\KaryawanController::class, 'show'])->name('karyawan.show');
    
    // Dosen (Read Only)
    Route::get('/dosen', [App\Http\Controllers\Direktur\DosenController::class, 'index'])->name('dosen.index');
    Route::get('/dosen/{dosen}', [App\Http\Controllers\Direktur\DosenController::class, 'show'])->name('dosen.show');
    
    // Teknisi (Read Only)
    Route::get('/teknisi', [App\Http\Controllers\Direktur\TeknisiController::class, 'index'])->name('teknisi.index');
    Route::get('/teknisi/{teknisi}', [App\Http\Controllers\Direktur\TeknisiController::class, 'show'])->name('teknisi.show');
});
