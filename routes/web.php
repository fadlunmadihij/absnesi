<?php

use App\Http\Controllers\absenController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\dataSiswaController;
use App\Http\Controllers\exampleController;
use App\Http\Controllers\exRankingController;
use App\Http\Controllers\idikatorController;
use App\Http\Controllers\indikatorController;
use App\Http\Controllers\kelasController;
use App\Http\Controllers\keteranganController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\normalisasiController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\tahap1Controller;
use App\Http\Controllers\tahap2Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Models\data_siswa;
use App\Models\Kelas;
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
    // Forgot Password Routes
    // // Rute untuk halaman memasukkan email untuk reset password
    // Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

    // // Rute untuk mengirim email reset link
    // Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // // Rute untuk menampilkan form reset password
    // Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

    // // Rute untuk melakukan reset password
    // Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        $jumlahKelas = Kelas::count();
        $sis = data_siswa::count();
        $user = auth()->user(); // Pastikan user yang login diambil
        return view('dashboard', compact('jumlahKelas', 'sis', 'user'));
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


    //perhitungan
    Route::get('kriteria', [KriteriaController::class, 'index']);
    Route::get('indikator', [indikatorController::class, 'index']);
    Route::get('example', [exampleController::class, 'index']);
    Route::get('tahap1', [tahap1Controller::class, 'index']);
    Route::get('tahap2', [tahap2Controller::class, 'index']);
    Route::get('normalisasi', [normalisasiController::class, 'index']);
    Route::get('exRanking', [exRankingController::class, 'index']);


    // ROUTER KETERANGAN
    Route::get('keterangan', [keteranganController::class, 'index']);
    Route::get('keterangan/tambahketerangan', [keteranganController::class, 'create']);
    Route::post('keterangan/store', [keteranganController::class, 'store']);
    Route::get('keterangan/{keterangan}', [keteranganController::class, 'edit']);
    Route::put('keterangan/{keterangan}', [keteranganController::class, 'update']);
    Route::delete('keterangan/{keterangan}', [keteranganController::class, 'destroy']);

    // ROUTER REKAP
    Route::get('rekap', [RekapController::class, 'index']);
    Route::post('/rekap/filter', [RekapController::class, 'hitungRekap'])->name('hitungRekap.filter');
    Route::post('rekap/view/pdf', [RekapController::class, 'viewPDF']);
    Route::post('rekap/download/pdf', [RekapController::class, 'downloadPDF']);

    // ROUTER RANKING
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');
    Route::post('/ranking/filter', [RankingController::class, 'index'])->name('ranking.filterRekap');

    Route::get('/scan', [WelcomeController::class, 'scan'])->name('scan');
    Route::post('/validasi', [WelcomeController::class, 'validasi'])->name('validasi');

    Route::get('/qr-code', [QRController::class, 'index'])->name('qr-code');
    Route::post('/generate-qr-code', [QRController::class, 'generateQRCode'])->name('generate-qr-code');
    Route::get('/show_qr/{siswa}', [QRController::class, 'showQRCode'])->name('show_qr');


    Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('profile.update');

    Route::get('detail_rekap', [RekapController::class, 'detail_rekap']);
});


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');