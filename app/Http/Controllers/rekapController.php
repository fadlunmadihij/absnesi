<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\DataSiswa; // Sesuaikan nama model dengan file Anda
use App\Models\Kelas;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index()
    {
        // Ambil semua kelas untuk dropdown
        $kelas = Kelas::all();
        return view('rekap.index', compact('kelas'));
    }

    public function filterRekap(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $kelasId = $request->input('kelas_id');

        // Ambil absensi berdasarkan rentang tanggal dan kelas yang dipilih
        $query = Absensi::with(['namaKelas', 'dataSiswa']) // Pastikan nama relasi benar
                        ->whereBetween('created_at', [$startDate, $endDate]);

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $absensi = $query->get();

        // Rekap data berdasarkan kelas dan siswa
        $rekap = [];

        foreach ($absensi as $ab) {
            $kelas = $ab->kelas ? $ab->kelas->nama_kelas : 'Unknown Kelas'; // Periksa relasi
            $data_siswa = $ab->siswa ? $ab->siswa->nama : 'Unknown Siswa'; // Periksa relasi

            if (!isset($rekap[$kelas])) {
                $rekap[$kelas] = [];
            }

            if (!isset($rekap[$kelas][$data_siswa])) {
                $rekap[$kelas][$data_siswa] = [
                    'nama' => $data_siswa,
                    'status' => ['I' => 0, 'H' => 0, 'S' => 0, 'A' => 0]
                ];
            }

            $rekap[$kelas][$data_siswa]['status'][$ab->status]++;
        }

        // Ambil semua kelas untuk dropdown
        $kelas = Kelas::all();
        return view('rekap.index', compact('rekap', 'kelas'));
    }
}
