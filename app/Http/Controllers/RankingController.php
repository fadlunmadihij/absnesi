<?php
namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\data_siswa;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    private $bobot = [
        "H" => 0.70, // Hadir (benefit)
        "I" => 0.10, // Izin (cost)
        "S" => 0.05, // Sakit (cost)
        "A" => 0.15, // Alfa (cost)
    ];

    public function index()
    {
        // Ambil data absensi dengan relasi dataSiswa dan namaKelas
        $absensi = Absensi::with('dataSiswa', 'namaKelas')->get();

        // Buat array untuk menyimpan skor
        $skorSiswa = [];

        foreach ($absensi as $item) {
            $dataSiswa = $item->dataSiswa; // Mengakses relasi dataSiswa

            if (!isset($skorSiswa[$dataSiswa->id])) {
                $skorSiswa[$dataSiswa->id] = [
                    'nama' => $dataSiswa->nama,
                    'skor' => 0
                ];
            }

            // Menambahkan skor sesuai dengan status absensi
            switch ($item->status) {
                case 'H':
                    $skorSiswa[$dataSiswa->id]['skor'] += $this->bobot['H'];
                    break;
                case 'I':
                    $skorSiswa[$dataSiswa->id]['skor'] += $this->bobot['I'];
                    break;
                case 'S':
                    $skorSiswa[$dataSiswa->id]['skor'] += $this->bobot['S'];
                    break;
                case 'A':
                    $skorSiswa[$dataSiswa->id]['skor'] += $this->bobot['A'];
                    break;
            }
        }

        // Urutkan skor siswa dari yang tertinggi
        uasort($skorSiswa, function ($a, $b) {
            return $b['skor'] <=> $a['skor'];
        });

        // Menambahkan urutan peringkat
        $peringkatSiswa = [];
        $rank = 1;
        foreach ($skorSiswa as $id => $siswa) {
            $peringkatSiswa[] = [
                'rank' => $rank++,
                'nama' => $siswa['nama'],
                'skor' => $siswa['skor']
            ];
        }

        // Kirim data ke view
        return view('ranking.index', ['peringkatSiswa' => $peringkatSiswa]);
    }



    public function ranking()
{
    // Ambil semua absensi
    $absensis = Absensi::with('dataSiswa')->get();

    // Buat array untuk menyimpan skor per siswa
    $skorSiswa = [];

    foreach ($absensis as $absensi) {
        $dataSiswaId = $absensi->data_siswa_id;
        $status = $absensi->status;

        // Tentukan bobot berdasarkan status
        $bobot = [
            'I' => 0.35,
            'H' => 0.35,
            'S' => 0.15,
            'A' => 0.10,
        ];

        // Tambahkan bobot ke skor siswa
        if (!isset($skorSiswa[$dataSiswaId])) {
            $skorSiswa[$dataSiswaId] = 0;
        }

        $skorSiswa[$dataSiswaId] += $bobot[$status];
    }

    // Ambil data siswa
    $dataSiswa = data_siswa::find(array_keys($skorSiswa));

    // Gabungkan data siswa dengan skor
    $peringkat = [];
    foreach ($dataSiswa as $siswa) {
        $peringkat[] = [
            'nama' => $siswa->nama,
            'skor' => $skorSiswa[$siswa->id],
        ];
    }

    // Urutkan berdasarkan skor
    usort($peringkat, function($a, $b) {
        return $b['skor'] <=> $a['skor'];
    });

    // Kirim data ke view
    return view('ranking.index', ['peringkat' => $peringkat]);
}




    public function showNormalisasi()
    {
        dd($this->normalisasi());
    }

    private function normalisasi()
    {
        $max = $this->nilaiMax();
        $nilais = Absensi::with('dataSiswa', 'namaKelas')->get();

        $hasil = collect();

        foreach ($nilais as $nilai) {
            if ($nilai->dataSiswa && $nilai->namaKelas) {
                $temp = [
                    "nama" => $nilai->dataSiswa->nama,
                    "kelas" => $nilai->namaKelas->nama_kelas,
                    "H" => ($nilai->status == 'H' ? 1 : 0) / ($max['H'] ?: 1),
                    "I" => ($nilai->status == 'I' ? 1 : 0) / ($max['I'] ?: 1),
                    "S" => ($nilai->status == 'S' ? 1 : 0) / ($max['S'] ?: 1),
                    "A" => ($nilai->status == 'A' ? 1 : 0) / ($max['A'] ?: 1),
                ];

                $temp['nilaiAkhir'] = (
                    ($temp['H'] * $this->bobot['H']) +
                    ($temp['I'] * $this->bobot['I']) +
                    ($temp['S'] * $this->bobot['S']) +
                    ($temp['A'] * $this->bobot['A'])
                );

                $hasil->push($temp);
            }
        }

        return $hasil;
    }

    private function nilaiMax()
    {
        return [
            "H" => Absensi::where('status', 'H')->count(),
            "I" => Absensi::where('status', 'I')->count(),
            "S" => Absensi::where('status', 'S')->count(),
            "A" => Absensi::where('status', 'A')->count(),
        ];
    }
}
