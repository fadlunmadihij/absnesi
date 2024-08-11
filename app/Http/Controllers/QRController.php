<?php

namespace App\Http\Controllers;

use App\Models\data_siswa;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class QRController extends Controller
{
    public function index()
    {
        $dataSiswa = data_siswa::all();
        return view('qr.qr_code', compact('dataSiswa'));
    }

    public function generateQRCode(Request $request)
{
    $request->validate([
        'siswa_id' => 'required|exists:data_siswas,id',
    ]);

    try {
        $siswa = data_siswa::findOrFail($request->siswa_id);

        // Pastikan NISN tidak null
        if (is_null($siswa->NISN) || $siswa->NISN === '') {
            return back()->withErrors(['NISN tidak ditemukan atau kosong untuk siswa ini.']);
        }

        // Generate QR Code berdasarkan NISN siswa
        $qrCode = Builder::create()
            ->data($siswa->NISN)
            ->build();

        // Simpan QR Code sebagai file gambar di direktori public/qrcodes
        $qrCode->saveToFile(public_path('qrcodes/'.$siswa->NISN.'.png'));

        // Tampilkan halaman dengan QR Code yang baru dibuat
        return redirect()->route('show_qr', $siswa->id);
    } catch (ModelNotFoundException $e) {
        abort(404, 'Siswa tidak ditemukan');
    }
}

public function showQRCode(data_siswa $siswa)
{
    // Generate QR Code berdasarkan NISN siswa
    $qrCode = Builder::create()
        ->data($siswa->NISN)
        ->build();

    return view('qr.show_qr', compact('siswa', 'qrCode'));
}



//     public function generate($nisn)
//     {
//         $result = Builder::create()
//             ->data($nisn)
//             ->build();

//         header('Content-Type: '.$result->getMimeType());
//         echo $result->getString();
//     }

//     public function showQRCode()
//     {
//         // Ambil siswa pertama atau sesuai kriteria yang Anda tentukan
//         $siswa = data_siswa::first(); // Misalnya, ambil siswa pertama

//         if (!$siswa) {
//             abort(404, 'Siswa tidak ditemukan');
//         }

//         // Generate QR Code berdasarkan NISN siswa
//         $qrCode = Builder::create()
//             ->data($siswa->NISN)
//             ->build();

//         // Simpan QR Code sebagai file gambar di direktori public/qrcodes
//         $qrCode->saveToFile(public_path('qrcodes/'.$siswa->NISN.'.png'));

//         // Tampilkan view dengan data siswa dan path ke QR Code
//         return view('qr.qr_code', compact('siswa'));
//     }
//     public function generateAuto()
//     {
//         // Misalnya, ambil siswa dengan ID terakhir
//         $siswa = data_siswa::latest()->firstOrFail();

//         // Generate QR Code berdasarkan NISN siswa
//         $nisn = $siswa->NISN;

//         return view('qr.qr_code', compact('nisn', 'siswa'));
//     }
//     public function generateForSiswa($siswaId)
// {
//     // Ambil siswa berdasarkan ID yang diberikan
//     $siswa = data_siswa::findOrFail($siswaId);

//     // Generate QR Code berdasarkan NISN siswa
//     $qrCode = Builder::create()
//         ->data($siswa->NISN)
//         ->build();

//     // Simpan QR Code sebagai file gambar di direktori public/qrcodes
//     $qrCode->saveToFile(public_path('qrcodes/'.$siswa->NISN.'.png'));


//     // Tampilkan view dengan data siswa dan path ke QR Code
//     return view('qr.qr_code', compact('siswa'));
// }

}
