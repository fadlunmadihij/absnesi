<?php

use App\Http\Controllers\absenController;
use App\Http\Controllers\dataSiswaController;
use App\Http\Controllers\kelasController;
use App\Http\Controllers\keteranganController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\rekapController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});

Route::controller(UserController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');

    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');

    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ROUTE DATA SISWA
Route::get('siswa', [dataSiswaController::class, 'index']);
Route::get('siswa/tambahdata', [dataSiswaController::class, 'create']);
Route::post('siswa/store', [dataSiswaController::class, 'store']);
Route::get('siswa/{dataSiswa}', [dataSiswaController::class, 'edit']);
Route::put('siswa/{dataSiswa}', [dataSiswaController::class, 'update']);
Route::delete('siswa/{dataSiswa}', [dataSiswaController::class, 'destroy']);

// ROUTE ABSENSI
Route::get('absen', [absenController::class, 'index']);
Route::get('absen/tambahabsen', [absenController::class, 'create']);
Route::post('absen/store', [absenController::class, 'store']);
Route::get('absen/{absen}', [absenController::class, 'edit']);
Route::put('absen/{absen}', [absenController::class, 'update']);
Route::delete('absen/{absen}', [absenController::class, 'destroy']);

// ROUTER Kelas
Route::get('kelas', [kelasController::class, 'index']);
Route::get('kelas/tambahkelas', [kelasController::class, 'create']);
Route::post('kelas/store', [kelasController::class, 'store']);
Route::get('kelas/{kelas}', [kelasController::class, 'edit']);
Route::put('kelas/{kelas}', [kelasController::class, 'update']);
Route::delete('kelas/{kelas}', [kelasController::class, 'destroy']);

// ROUTER KETERANGAN
Route::get('keterangan', [keteranganController::class, 'index']);
Route::get('keterangan/tambahketerangan', [keteranganController::class, 'create']);
Route::post('keterangan/store', [keteranganController::class, 'store']);
Route::get('keterangan/{keterangan}', [keteranganController::class, 'edit']);
Route::put('keterangan/{keterangan}', [keteranganController::class, 'update']);
Route::delete('keterangan/{keterangan}', [keteranganController::class, 'destroy']);

// ROUTER REKAP
Route::get('rekap', [RekapController::class, 'index']);
Route::get('/rekap/filter', [RekapController::class, 'filterRekap'])->name('rekap.filter');
// Route::get('keterangan/tambahketerangan', [keteranganController::class, 'create']);
// Route::post('keterangan/store', [keteranganController::class, 'store']);
// Route::get('keterangan/{keterangan}', [keteranganController::class, 'edit']);
// Route::put('keterangan/{keterangan}', [keteranganController::class, 'update']);
// Route::delete('keterangan/{keterangan}', [keteranganController::class, 'destroy']);

Route::get('/scan', [WelcomeController::class, 'scan'])->name('scan');
Route::post('/validasi', [WelcomeController::class, 'validasi'])->name('validasi');

Route::get('/qr-code', [QRController::class, 'index'])->name('qr-code');
Route::post('/generate-qr-code', [QRController::class, 'generateQRCode'])->name('generate-qr-code');
Route::get('/show_qr/{siswa}', [QRController::class, 'showQRCode'])->name('show_qr');


    Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('profile');
});
