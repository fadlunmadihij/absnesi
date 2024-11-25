<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\data_siswa;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    // Deklarasi bobot untuk setiap jenis absensi yang akan digunakan dalam perhitungan SAW
    private $bobot = [
        "izin" => 0.10,
        "hadir" => 0.70,
        "alpa" => 0.15,
        "sakit" => 0.05,
    ];
    // Method index untuk menampilkan data ranking atau halaman ranking
    public function index(Request $request)
    {
        // Mengambil semua data kelas
        $kelas = Kelas::all();
        // Mengecek jika request berasal dari AJAX
        if ($request->ajax()) {
            // Mendefinisikan range tanggal awal dan akhir berdasarkan input request
            $startDate = Carbon::parse($request->start)->startOfDay();
            $endDate = Carbon::parse($request->end)->endOfDay();
            $kelasId = $request->kelas_id;
            // Mengambil data siswa berdasarkan kelas dan menghitung jumlah absen setiap status
            $datas = data_siswa::when($kelasId, function ($query) use ($kelasId) {
                return $query->where('kelas_id', $kelasId);
            })->withCount([
                // Menghitung jumlah kehadiran (H) dalam rentang tanggal tertentu
                'absen as hadir_count' => function ($query) use ($startDate, $endDate) {
                    $query->where('status', 'H')
                        ->when($startDate, function ($query) use ($startDate) {
                            $query->where('tanggal', '>=', $startDate);
                        })
                        ->when($endDate, function ($query) use ($endDate) {
                            $query->where('tanggal', '<=', $endDate);
                        });
                },
                // Menghitung jumlah izin (I) dalam rentang tanggal tertentu
                'absen as izin_count' => function ($query) use ($startDate, $endDate) {
                    $query->where('status', 'I')
                        ->when($startDate, function ($query) use ($startDate) {
                            $query->where('tanggal', '>=', $startDate);
                        })
                        ->when($endDate, function ($query) use ($endDate) {
                            $query->where('tanggal', '<=', $endDate);
                        });
                },
                // Menghitung jumlah sakit (S) dalam rentang tanggal tertentu
                'absen as sakit_count' => function ($query) use ($startDate, $endDate) {
                    $query->where('status', 'S')
                        ->when($startDate, function ($query) use ($startDate) {
                            $query->where('tanggal', '>=', $startDate);
                        })
                        ->when($endDate, function ($query) use ($endDate) {
                            $query->where('tanggal', '<=', $endDate);
                        });
                },
                // Menghitung jumlah alpa (A) dalam rentang tanggal tertentu
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

            // Melakukan normalisasi data absensi
            $normalisasi = $this->normalisasi($datas);

            // Mengurutkan hasil normalisasi berdasarkan nilai_akhir secara descending
            $hasil_akhir2 = $normalisasi->sortByDesc('nilai_akhir')->values()->all();

            // Mengembalikan hasil ranking dalam bentuk JSON
            return response()->json($hasil_akhir2, 200);
        }
        // Menampilkan halaman ranking dengan data kelas
        return view('ranking.index', compact('kelas'));
    }

    // Method untuk melakukan normalisasi data absensi
    private function normalisasi($datas)
    {

        // Mendapatkan nilai maksimum untuk setiap kategori absensi
        $max = $this->max_data($datas);
        $hasil = collect();
        // dd($max);
        foreach ($datas as $data) {
            // Menyiapkan data normalisasi untuk setiap siswa
            $temp = [
                "NISN" => $data->NISN,
                "No_wa" => $data->No_wa,
                "alamat" => $data->alamat,
                "alpa_count" => $data->alpa_count,
                "hadir_count" => $data->hadir_count,
                "izin_count" => $data->izin_count,
                "sakit_count" => $data->sakit_count,
                "nama" => $data->nama,
                "jenis_kelamin" => $data->jenis_kelamin,
                // Menghitung nilai normalisasi untuk setiap status
                "nilai_sakit" => $max["sakit"] / $this->count_sakit($data->sakit_count),
                "nilai_izin" => $max["izin"] / $this->count_izin($data->izin_count),
                "nilai_alpa" =>  $max["alpa"] / $this->count_alpa($data->alpa_count),
                "nilai_hadir" => $this->count_hadir($data->hadir_count) / $max["hadir"]
            ];
            // Menghitung nilai akhir berdasarkan bobot setiap status
            $temp += [
                "nilai_akhir" => ($temp["nilai_sakit"] * $this->bobot["sakit"]) +
                    ($temp["nilai_alpa"] * $this->bobot["alpa"]) +
                    ($temp["nilai_izin"] * $this->bobot["izin"]) +
                    ($temp["nilai_hadir"] * $this->bobot["hadir"])
            ];

            $temp["kategori"] = $this->tentukanKategori($temp["nilai_akhir"]);
            $hasil->push($temp); // Menambahkan hasil normalisasi siswa ke koleksi hasil
        }

        return $hasil;
    }

        private function tentukanKategori($nilaiAkhir)
    {
        if ($nilaiAkhir >= 0.9) {
            return "Siswa Terbaik";
        } elseif ($nilaiAkhir >= 0.8) {
            return "Siswa Berprestasi";
        } elseif ($nilaiAkhir >= 0.7) {
            return "Siswa Aktif";
        } else {
            return "Perlu Bimbingan";
        }
    }

    // Fungsi untuk menghitung skor izin dengan batas tertentu
    private function count_izin($data)
    {
        if ($data > 3) {
            return 3;
        } else if ($data >= 1) {
            return 2;
        } else {
            return 1;
        }
    }
     // Fungsi untuk menghitung skor sakit dengan batas tertentu
    private function count_sakit($data)
    {
        if ($data > 3) {
            return 3;
        } else if ($data >= 1) {
            return 2;
        } else {
            return 1;
        }
    }
    // Fungsi untuk menghitung skor alpa dengan batas tertentu
    private function count_alpa($data)
    {
        if ($data > 3) {
            return 3;
        } else if ($data >= 1) {
            return 2;
        } else {
            return 1;
        }
    }
     // Fungsi untuk menghitung skor hadir dengan batas tertentu
    private function count_hadir($data)
    {
        if ($data < 10) {
            return 1;
        } else if ($data <= 24) {
            return 2;
        } else {
            return 3;
        }
    }

     // Fungsi untuk mendapatkan nilai maksimum dari setiap jenis absensi untuk normalisasi
    private function max_data($data)
    {
        $max = [
            "izin" => $this->count_izin($data->min('izin_count')),
            "alpa" => $this->count_alpa($data->min('alpa_count')),
            "sakit" => $this->count_sakit($data->min('sakit_count')),
            "hadir" => $this->count_hadir($data->max('hadir_count')),
        ];
        // dd($max);

        return $max;
    }
}