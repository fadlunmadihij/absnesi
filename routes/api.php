<?php

use App\Http\Controllers\RekapController;
use App\Http\Controllers\RekapRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/generate-pdf-and-trigger-wa', [RekapController::class, 'generatePdfAndSendToWA']);
Route::post('/get-no-wa', [RekapController::class, 'getNoWa']);
Route::post('/send-pdf-to-wa', [RekapController::class, 'sendPdfToWhatsApp']);

Route::post('/request-rekap', [RekapController::class, 'requestRekap']);
Route::get('/get-pending-rekap', [RekapController::class, 'getPendingRekap']);
Route::post('/update-rekap-status', [RekapController::class, 'updateRekapStatus']);
Route::post('/send-rekap-to-wa', [RekapController::class, 'sendRekapToWA']);
Route::post('/reset-request', [RekapController::class, 'updateRekapStatus']);



Route::post('/sendPDFtoWA', [RekapController::class, 'sendPDFtoWA']);
Route::get('/data-kelas', [RekapController::class, 'dataKelas']);
Route::post('/data-nomer', [RekapController::class, 'dataNomer']);