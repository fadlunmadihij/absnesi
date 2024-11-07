<?php

namespace App\Http\Controllers;

use App\Models\data_siswa;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class QRController extends Controller
{

    // Menampilkan halaman index untuk memilih siswa yang ingin dibuat QR Code-nya
    public function index()
    {
        $dataSiswa = data_siswa::all();// Mengambil semua data siswa dari database
        return view('qr.qr_code', compact('dataSiswa'));// Menampilkan halaman qr_code dengan data siswa
    }

    // Membuat QR Code untuk siswa berdasarkan NISN
    public function generateQRCode(Request $request)
{
    $request->validate([
        'siswa_id' => 'required|exists:data_siswas,id', // Validasi input siswa_id harus ada di tabel data_siswas
    ]);

    try {
        $siswa = data_siswa::findOrFail($request->siswa_id);// Mencari data siswa berdasarkan siswa_id


        // Pastikan NISN tidak null
        if (is_null($siswa->NISN) || $siswa->NISN === '') {
            return back()->withErrors(['NISN tidak ditemukan atau kosong untuk siswa ini.']);// Kembali dengan pesan error jika NISN kosong
        }

        // Generate QR Code berdasarkan NISN siswa
        $qrCode = Builder::create()
            ->data($siswa->NISN)  // Menambahkan NISN sebagai data dalam QR Code
            ->build();

        // Menyimpan QR Code sebagai file gambar di direktori public/qrcodes
        $qrCode->saveToFile(public_path('qrcodes/'.$siswa->NISN.'.png'));

        // Redirect ke halaman show QR Code untuk menampilkan QR Code yang baru saja dibuat
        return redirect()->route('show_qr', $siswa->id);
    } catch (ModelNotFoundException $e) {
        abort(404, 'Siswa tidak ditemukan'); // Tampilkan error 404 jika siswa tidak ditemukan
    }
}

 // Menampilkan QR Code siswa pada halaman tertentu
public function showQRCode(data_siswa $siswa)
{
    // Generate QR Code berdasarkan NISN siswa
    $qrCode = Builder::create()
        ->data($siswa->NISN)
        ->build();
    // Menampilkan halaman show_qr dengan data siswa dan QR Code
    return view('qr.show_qr', compact('siswa', 'qrCode'));
}
}
