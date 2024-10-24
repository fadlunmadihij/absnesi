<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\data_siswa;
use App\Models\DataSiswa; // Sesuaikan nama model dengan file Anda
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use Datatables;
use Yajra\DataTables\DataTables as DataTables;
use DB;

class RekapController extends Controller
{
    public function index()
    {
        // Ambil semua kelas untuk dropdown
        $kelas = Kelas::all();
        return view('rekap.index', compact('kelas'));
    }


    public function viewPDF(Request $request)
    {

        dd($request->all());
        // Ambil data kelas atau data lain yang diperlukan
        $kelas = Kelas::all();
        // dd($kelas);

        // Render view Blade dan kirimkan data ke view tersebut
        $html = view('rekap.templatepdf', compact('kelas'))->render();

        // Inisialisasi mPDF
        $mpdf = new \Mpdf\Mpdf();

        // Masukkan HTML yang dirender ke dalam PDF
        $mpdf->WriteHTML($html);

        // Menampilkan file PDF di browser (bukan untuk didownload)
        return $mpdf->Output('rekap-kehadiran.pdf', 'I');  // 'I' untuk menampilkan PDF di browser
    }



    public function filterRekap(Request $request)
    {
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

                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $kelasId = $request->kelas_id;

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