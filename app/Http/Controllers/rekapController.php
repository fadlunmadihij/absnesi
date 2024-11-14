<?php

namespace App\Http\Controllers;

use DB;
use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\data_siswa;
use App\Models\RekapRequest;
use Illuminate\Http\Request;
// use Datatables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\DataTables as DataTables;

class RekapController extends Controller
{
    public function index()
    {
        // Ambil semua data kelas untuk dropdown pada halaman rekap
        $kelas = Kelas::all();
        return view('rekap.index', compact('kelas'));
    }

    public function generatePdfAndSendToWA(Request $request)
{
    try {
        // Validasi input
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'kelas_id' => 'required|integer',
        ]);

        // Mendapatkan data dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $kelasId = $request->input('kelas_id');

        $cek = RekapRequest::where('status', 'pending')->where('id', 1)->get();
        if ($cek->count() > 0 ){
            return response()->json(['message' => 'Rekapsi sedang diproses, silakan tunggu', 'status' => 'warning'], 400);
        }

        $rekapRequest = RekapRequest::create([
        'kelas_id' => $request->kelas_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => 'pending', // status awal bisa disesuaikan
    ]);
        // Lanjutkan dengan proses lainnya
        // ...

        return response()->json(['message' => 'Proses berhasil', 'status' => 'success', 'data' => $rekapRequest],200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    // Fungsi untuk mengirim rekap ke WhatsApp
    private function sendRekapToWA($pdfUrl, $numbers)
    {
        try {
            // Misalnya kita menggunakan HTTP client untuk mengirim permintaan ke bot WhatsApp
            Http::post('http://localhost:3000/send-rekap', [
                'pdf_url' => $pdfUrl,
                'numbers' => $numbers,
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending rekap to WA', ['error' => $e->getMessage()]);
            throw $e; // Lempar kembali exception jika perlu
        }
    }

    public function requestRekap(Request $request)
    {
        // Validasi input
        $request->validate([
            'kelas_id' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Simpan data rekap request
        $rekapRequest = RekapRequest::create([
            'kelas_id' => $request->kelas_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending', // status awal adalah pending
        ]);

        return response()->json(['success' => true, 'message' => 'Rekap request has been saved']);
    }

    // Fungsi untuk mendapatkan rekap yang pending
    public function getPendingRekap()
    {
        $requests = RekapRequest::where('status', 'pending')->where('id', 1)->get();
        if ($requests->isEmpty()) {
            return response()->json(['message' => 'No pending rekap request found', 'status' => 'failed'], 404);
        }
        return response()->json(['data' => $requests, 'status' => 'success'], 200);
    }

    // Fungsi untuk memperbarui status rekap request
    public function updateRekapStatus()
    {

        $rekapRequest = RekapRequest::find(1);
        if ($rekapRequest) {
            $rekapRequest->start_date = null;
            $rekapRequest->end_date = null;
            $rekapRequest->kelas_id = null;
            $rekapRequest->status = "kosong";
            $rekapRequest->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Rekap request not found'], 404);
    }



    public function sendPDFtoWA(Request $request)
    {
        // Ambil tanggal dan kelas ID dari request
        $startDate = Carbon::parse($request->input('startDate'))->startOfDay();
        $endDate = Carbon::parse($request->input('endDate'))->endOfDay();
        $kelasId = $request->input('kelasId');


        // Validasi ID kelas
        $kelas = Kelas::find($kelasId);
        if (!$kelas) {
            return response()->json(['error' => 'ID kelas tidak valid.'], 400);
        }

        // Filter data absensi berdasarkan rentang tanggal dan kelas
        $query = Absensi::with(['namaKelas', 'dataSiswa'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereHas('dataSiswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });

        $absensi = $query->get();

        // Cek apakah ada data siswa dalam kelas yang dipilih
        if ($absensi->isEmpty()) {
            return response()->json(['error' => 'Tidak ada siswa dalam kelas ini pada rentang tanggal yang dipilih.'], 404);
        }

        // Rekap data absensi berdasarkan kelas dan siswa
        $rekap = [];
        $kelasName = $kelas->nama_kelas;

        foreach ($absensi as $ab) {
            if ($ab->namaKelas) {
                $kelasName = $ab->namaKelas->nama_kelas; // Set kelasName jika ada
            }

            $data_siswa = $ab->dataSiswa ? $ab->dataSiswa->nama : 'Unknown Siswa'; // Ambil nama siswa dari relasi

            if (!isset($rekap[$data_siswa])) {
                $rekap[$data_siswa] = [
                    'nama' => $data_siswa,
                    'status' => ['H' => 0, 'I' => 0, 'S' => 0, 'A' => 0]
                ];
            }

            // Increment status count
            $rekap[$data_siswa]['status'][$ab->status]++;
        }

        // Mengonversi rekap menjadi array yang dapat digunakan untuk tampilan
        $dataSiswa = [];
        foreach ($rekap as $siswa) {
            $dataSiswa[] = (object) [
                'nama' => $siswa['nama'],
                'hadir_count' => $siswa['status']['H'],
                'izin_count' => $siswa['status']['I'],
                'sakit_count' => $siswa['status']['S'],
                'alpa_count' => $siswa['status']['A'],
            ];
        }

        $startDate = $startDate->translatedFormat('d F Y');
        $endDate = $endDate->translatedFormat('d F Y');

        // Render view Blade dan kirimkan data ke view tersebut
        $html = view('rekap.templatepdf', compact('dataSiswa', 'kelasName', 'startDate', 'endDate'))->render();

        // Inisialisasi mPDF
        $mpdf = new \Mpdf\Mpdf();

        // Masukkan HTML yang dirender ke dalam PDF
        $mpdf->WriteHTML($html);
        $fileName = "Rekap_Absensi_Kelas_{$kelasName}_Per_{$startDate}_{$endDate}.pdf";
        $encodedFileName = str_replace(' ', '-', $fileName);
        $filePath = storage_path("app/public/$encodedFileName");

        // Simpan PDF ke dalam file
        $mpdf->Output($filePath, 'F');

        // Kembalikan URL untuk mengakses PDF
        return response()->json(['url' => asset("storage/$encodedFileName"), 'name' => $encodedFileName], 200);
    }

    public function getNoWa(Request $request)
    {
        // Validasi token
        if ($request->token == '12345678') {
            // dd($request->all());
            // Validasi kelas_id
            $request->validate(['kelas_id' => 'required|string']);
            $kelasId = $request->kelas_id;

            // Ambil nomor WhatsApp berdasarkan kelas
            $noWaList = data_siswa::where('kelas_id', $kelasId)->pluck('No_wa');

            if ($noWaList->isEmpty()) {
                return response()->json(['error' => 'Tidak ada data untuk kelas ini.'], 404);
            }

            return response()->json(['data' => $noWaList, 'status' => 'success'], 200);
        } else {
            return response()->json(['error' => 'Token tidak valid.'], 401);
        }
    }


    public function dataKelas()
    {

        $kelas = Kelas::all();
        // send json response data kelas to server
        return response()->json(['data' => $kelas], 200);
    }
    public function dataNomer(Request $request)
    {
        if ($request->token == '12345678') {
            $noWaList = data_siswa::pluck('No_wa');

            // send json response data noWaList to server
            return response()->json(['data' => $noWaList], 200);
        } else {
            return response()->json(['error' => 'Tidak dapat mendapatkan data.'], 401);
        }
    }

    public function viewPDF(Request $request)
    {
        // Ambil dan format tanggal mulai dan akhir dari request
        $startDate = Carbon::parse($request->input('startDate'))->startOfDay();
        $endDate = Carbon::parse($request->input('endDate'))->endOfDay();

        // Ambil ID kelas dari request
        $kelasId = $request->input('kelasId');

        // Query data absensi berdasarkan rentang tanggal dan kelas
        $query = Absensi::with(['namaKelas', 'dataSiswa'])
            ->whereBetween('tanggal', [$startDate, $endDate]);

        // Filter berdasarkan kelas jika ID kelas diberikan
        if ($kelasId) {
            $query->whereHas('dataSiswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $absensi = $query->get();

        // Inisialisasi array untuk rekap data absensi berdasarkan kelas dan siswa
        $rekap = [];
        $kelasName = Kelas::findOrFail($kelasId)->nama_kelas;

        // Loop untuk mengisi data rekap absensi berdasarkan status
        foreach ($absensi as $ab) {
            if ($ab->namaKelas) {
                $kelasName = $ab->namaKelas->nama_kelas; // Set kelasName
            }

            // Ambil nama siswa dari relasi dataSiswa
            $data_siswa = $ab->dataSiswa ? $ab->dataSiswa->nama : 'Unknown Siswa';

            // Inisialisasi status absensi siswa jika belum ada
            if (!isset($rekap[$data_siswa])) {
                $rekap[$data_siswa] = [
                    'nama' => $data_siswa,
                    'status' => ['H' => 0, 'I' => 0, 'S' => 0, 'A' => 0]
                ];
            }

            // Tambahkan jumlah ke status absensi yang sesuai
            $rekap[$data_siswa]['status'][$ab->status]++;
        }

        // Konversi rekap menjadi format array untuk tampilan
        $dataSiswa = [];
        foreach ($rekap as $siswa) {
            $dataSiswa[] = (object) [
                'nama' => $siswa['nama'],
                'hadir_count' => $siswa['status']['H'],
                'izin_count' => $siswa['status']['I'],
                'sakit_count' => $siswa['status']['S'],
                'alpa_count' => $siswa['status']['A'],
            ];
        }

        // Format ulang tanggal untuk tampilan
        $startDate = $startDate->translatedFormat('d F Y');
        $endDate = $endDate->translatedFormat('d F Y');

         // Render tampilan PDF dengan data yang sudah diolah
        $html = view('rekap.templatepdf', compact('dataSiswa', 'kelasName', 'startDate', 'endDate'))->render();

        // Inisialisasi mPDF untuk membuat file PDF
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);

        // Tampilkan PDF di browser
        return $mpdf->Output('rekap-kehadiran.pdf', 'I');
    }

    public function downloadPDF(Request $request)
    {
        // Mirip dengan viewPDF, tetapi untuk mengunduh PDF
        $startDate = Carbon::parse($request->input('startDate'))->startOfDay();
        $endDate = Carbon::parse($request->input('endDate'))->endOfDay();
        $kelasId = $request->input('kelasId');

        // Filter data absensi berdasarkan rentang tanggal
        $query = Absensi::with(['namaKelas', 'dataSiswa'])
            ->whereBetween('tanggal', [$startDate, $endDate]);
        // Jika kelasId ada, filter berdasarkan relasi ke kelas
        if ($kelasId) {
            $query->whereHas('dataSiswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $absensi = $query->get();

        // Rekap data absensi berdasarkan kelas dan siswa
        $rekap = [];
        $kelasName = Kelas::findOrFail($kelasId)->nama_kelas;

        foreach ($absensi as $ab) {
            if ($ab->namaKelas) {
                $kelasName = $ab->namaKelas->nama_kelas; // Set kelasName
            }

            $data_siswa = $ab->dataSiswa ? $ab->dataSiswa->nama : 'Unknown Siswa'; // Ambil nama siswa dari relasi

            if (!isset($rekap[$data_siswa])) {
                $rekap[$data_siswa] = [
                    'nama' => $data_siswa,
                    'status' => ['H' => 0, 'I' => 0, 'S' => 0, 'A' => 0]
                ];
            }

            // Increment status count
            $rekap[$data_siswa]['status'][$ab->status]++;
        }
        // Mengonversi rekap menjadi array yang dapat digunakan untuk tampilan
        $dataSiswa = [];
        foreach ($rekap as $siswa) {
            $dataSiswa[] = (object) [
                'nama' => $siswa['nama'],
                'hadir_count' => $siswa['status']['H'],
                'izin_count' => $siswa['status']['I'],
                'sakit_count' => $siswa['status']['S'],
                'alpa_count' => $siswa['status']['A'],
            ];
        }
        $startDate = $startDate->translatedFormat('d F Y');
        $endDate = $endDate->translatedFormat('d F Y');
        // Render view Blade dan kirimkan data ke view tersebut
        $html = view('rekap.templatepdf', compact('dataSiswa', 'kelasName', 'startDate', 'endDate'))->render();

        // Inisialisasi mPDF
        $mpdf = new \Mpdf\Mpdf();

        // Masukkan HTML yang dirender ke dalam PDF
        $mpdf->WriteHTML($html);
        $fileName = "Rekap Absensi Kelas $kelasName Per $startDate - $endDate";

        // Menampilkan file PDF di browser
        return $mpdf->Output($fileName.'.pdf', 'D');  // 'I' untuk menampilkan PDF di browser
    }




    public function filterRekap(Request $request)
    {
        // Mengambil data absensi berdasarkan rentang tanggal dan kelas tertentu
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
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

    public function sendWAPdf(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $kelasId = $request->input('kelasId');

        // Ambil data siswa berdasarkan kelas
        $students = data_siswa::where('kelas_id', $kelasId)->get();

        if ($students->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data siswa ditemukan']);
        }

        // Generate PDF berdasarkan tanggal dan kelas
        $pdf = Pdf::loadView('rekap.pdf', compact('startDate', 'endDate', 'kelasId'));
        $pdfPath = public_path('rekap_absensi.pdf');
        $pdf->save($pdfPath);

        // Loop untuk setiap siswa dan kirim pesan WhatsApp
        foreach ($students as $siswa) {
            $phoneNumber = $siswa->no_wa; // Pastikan nomor sudah dalam format internasional
            $message = "*Rekap Absensi*\n\n" .
                       "Nama: {$siswa->nama}\n" .
                       "Kelas: {$siswa->kelas->nama_kelas}\n" .
                       "Tanggal: {$startDate} s/d {$endDate}\n\n" .
                       "Silakan cek file PDF yang dilampirkan.";

            // Kirim pesan dan lampiran PDF menggunakan bot WhatsApp API
            Http::post('http://localhost:3000/send-message-pdf', [
                'number' => $phoneNumber,
                'message' => $message,
                'file' => $pdfPath,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Pesan berhasil dikirim ke semua siswa']);
    }

    public function hitungRekap(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->all());
            if ($request->start_date == "" || $request->end_date == "") {
                return response()->json([
                    "res" => "fail",
                    "message" => "Pilih Tanggal Untuk Ditampilkan Dengan Benar",
                    "detail_error" => ""
                ], 200);
            } else {

                // Hitung absensi per status dalam rentang tanggal yang diberikan
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();

                // Ambil kelas ID jika ada
                $kelasId = $request->kelas_id;

                // Ambil jumlah kehadiran, izin, sakit, dan alpa untuk setiap siswa
                $data = data_siswa::when($kelasId, function ($query) use ($kelasId) {
                    return $query->where('kelas_id', $kelasId);
                })->withCount([
                            'absen as hadir_count' => function ($query) use ($startDate, $endDate) {
                                $query->where('status', 'H')
                                    ->when($startDate, function ($query) use ($startDate) {
                                        $query->where('tanggal', '>=', $startDate);
                                    })
                                    ->when($endDate, function ($query) use ($endDate) {
                                        $query->where('tanggal', '<=', $endDate);
                                    });
                            },
                            'absen as izin_count' => function ($query) use ($startDate, $endDate) {
                                $query->where('status', 'I')
                                    ->when($startDate, function ($query) use ($startDate) {
                                        $query->where('tanggal', '>=', $startDate);
                                    })
                                    ->when($endDate, function ($query) use ($endDate) {
                                        $query->where('tanggal', '<=', $endDate);
                                    });
                            },
                            'absen as sakit_count' => function ($query) use ($startDate, $endDate) {
                                $query->where('status', 'S')
                                    ->when($startDate, function ($query) use ($startDate) {
                                        $query->where('tanggal', '>=', $startDate);
                                    })
                                    ->when($endDate, function ($query) use ($endDate) {
                                        $query->where('tanggal', '<=', $endDate);
                                    });
                            },
                            'absen as alpa_count' => function ($query) use ($startDate, $endDate) {
                                $query->where('status', 'A')
                                    ->when($startDate, function ($query) use ($startDate) {
                                        $query->where('tanggal', '>=', $startDate);
                                    })
                                    ->when($endDate, function ($query) use ($endDate) {
                                        $query->where('tanggal', '<=', $endDate);
                                    });
                            }
                        ])->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->make(true);
            }
        }
    }

    public function detail_rekap()
    {
        $startDate = Carbon::create(2024, 8, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $dates = [];
        while ($startDate->lte($endDate)) {
            $dates[] = $startDate->format('Y-m-d');
            $startDate->addDay();
        }

        $query = DB::table('data_siswas')
            ->leftJoin('absensis', 'data_siswas.id', '=', 'absensis.data_siswa_id')
            ->select('data_siswas.nama', 'data_siswas.id');
        // dd($query->get());
        foreach ($dates as $date) {
            $formattedDate = Carbon::parse($date)->translatedFormat('j F Y');
            $query->addSelect(DB::raw(
                "MAX(CASE WHEN DATE(absensis.tanggal) = '$date' THEN absensis.status ELSE '-' END) as '$formattedDate'"
            ));
        }

        $rekapAbsensi = $query
            ->groupBy('data_siswas.id')
            ->orderBy('data_siswas.nama')
            ->get();
        dd($rekapAbsensi);
    }
}