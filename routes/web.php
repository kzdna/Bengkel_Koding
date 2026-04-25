<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController; 
use App\Http\Controllers\Admin\ObatController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']); 
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::resource('poli', PoliController::class)->names('poli');
    Route::get('polis', [PoliController::class, 'index'])->name('polis.index');    
    Route::resource('dokter', DokterController::class)->names('dokter');
    Route::resource('pasien', PasienController::class)->names('pasien');
    Route::resource('obat', ObatController::class)->names('obat');
});

Route::middleware(['auth', 'role:dokter,admin'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dokter.dashboard');
    })->name('dokter.dashboard');
    Route::resource('jadwal-periksa', JadwalPeriksaController::class);
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.pasien.dashboard');
    })->name('pasien.dashboard');
    Route::get('/daftar', [PasienController::class, 'daftarPeriksa'])->name('pasien.daftar');
    Route::post('/daftar', [PasienController::class, 'submit'])->name('pasien.daftar.submit');
});