<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WaController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Mengambil input dari form
        $phoneNumber = $request->input('phoneNumber');
        $message = $request->input('message');

        // Path ke bot.js dalam resources/js
        $botScriptPath = resource_path('js/bot.js');

        // Menentukan perintah untuk menjalankan bot menggunakan nodemon atau node
        $command = "node $botScriptPath $phoneNumber $message"; // Ganti dengan nodemon jika diperlukan

        // Menjalankan perintah nodemon atau node
        $output = shell_exec($command);

        // Log output untuk debugging
        Log::info($output);

        return response()->json(['status' => 'success', 'message' => 'Pesan berhasil dikirim!']);
    }
}