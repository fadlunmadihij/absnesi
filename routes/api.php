<?php

use App\Http\Controllers\RekapController;
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

Route::post('/sendPDFtoWA', [RekapController::class, 'sendPDFtoWA']);
Route::get('/data-kelas', [RekapController::class, 'dataKelas']);
Route::post('/data-nomer', [RekapController::class, 'dataNomer']);