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
        // ini berfungsi untuk masuk ke halaman scannya
        return view('scan.index');
    }

    public function validasi(Request $request)
    {
        // validasi nisnnya
        if ($request->ajax()) {
            $qr_code = $request->qr_code;
            $siswa = data_siswa::where('NISN', $qr_code)->with('kelas')->first();

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

                    // Buat absensi baru
                    absensi::create([
                        'data_siswa_id' => $siswa->id,
                        'tanggal' => now(),
                        'status' => 'H'
                    ]);
                    // kalo sukses, menampilkan nama kelas nisn
                    return response()->json([
                        "status" => "success",
                        "nama" => $siswa->nama,
                        "kelas" => $siswa->kelas->nama_kelas,
                        "NISN" => $siswa->NISN
                    ], 200);
                } else {
                    // jika nisn nya tidak ada maka muncul pesan nisn tidak ditemukan
                    return response()->json([
                        "status" => "error",
                        "message" => "NISN tidak ditemukan"
                    ], 404);
                }
            } else {
                // jika waktunya sudah lewat sesuai yang sudah ditentukan, maka muncul waktu absensi sudah lewat
                return response()->json([
                    "status" => "error",
                    "message" => "Waktu absensi sudah lewat :"
                ], 400);
            }
        }
    }
}
