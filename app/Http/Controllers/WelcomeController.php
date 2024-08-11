<?php

namespace App\Http\Controllers;

use App\Models\data_siswa;
use App\Models\absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    public function scan()
    {
        return view('scan.index');
    }

    public function validasi(Request $request)
    {
        if ($request->ajax()) {
            $qr_code = $request->qr_code;
            $siswa = data_siswa::where('NISN', $qr_code)->first();

            // Validasi waktu absensi
            $startTime = Carbon::createFromTimeString($request->start_time);
            $endTime = Carbon::createFromTimeString($request->end_time);
            $currentTime = Carbon::now();

            if ($currentTime->between($startTime, $endTime)) {
                if ($siswa) {
                    // Periksa apakah siswa sudah melakukan absen hari ini
                    $todayAbsence = absensi::where('data_siswa_id', $siswa->id)
                        ->whereDate('tanggal', Carbon::today())
                        ->first();

                    if ($todayAbsence) {
                        return response()->json([
                            "status" => "error",
                            "message" => "Siswa sudah melakukan absen hari ini"
                        ], 400);
                    }

                    // Buat entri absensi baru
                    absensi::create([
                        'data_siswa_id' => $siswa->id,
                        'tanggal' => now(),
                        'status' => 'H'
                    ]);

                    return response()->json([
                        "status" => "success",
                        "nama" => $siswa->nama,
                        "kelas" => $siswa->kelas->nama,
                        "NISN" => $siswa->NISN
                    ], 200);
                } else {
                    return response()->json([
                        "status" => "error",
                        "message" => "NISN tidak ditemukan"
                    ], 404);
                }
            } else {
                return response()->json([
                    "status" => "error",
                    "message" => "Waktu absensi sudah lewat"
                ], 400);
            }
        }
    }
}
