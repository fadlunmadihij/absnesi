<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class indikatorController extends Controller
{
    public function index()
    {
        // data json untuk contoh perhitungan
        $indikator = [
            [
                'kode' => 'C1/Izin',
                'sub_kriteria' => [
                    ['indikator' => 'Tidak Baik', 'nilai' => 3, 'sub_kriteria' => '>3'],
                    ['indikator' => 'Cukup Baik', 'nilai' => 2, 'sub_kriteria' => '1-3'],
                    ['indikator' => 'Sangat Baik', 'nilai' => 1, 'sub_kriteria' => '0'],
                ]
            ],
            [
                'kode' => 'C2/Hadir',
                'sub_kriteria' => [
                    ['indikator' => 'Tidak Baik', 'nilai' => 1, 'sub_kriteria' => '<10'],
                    ['indikator' => 'Cukup Baik', 'nilai' => 2, 'sub_kriteria' => '<=24'],
                    ['indikator' => 'Sangat Baik', 'nilai' => 3, 'sub_kriteria' => '>=25'],
                ]
            ],
            [
                'kode' => 'C3/Alfa',
                'sub_kriteria' => [
                    ['indikator' => 'Tidak Baik', 'nilai' => 3, 'sub_kriteria' => '>3'],
                    ['indikator' => 'Cukup Baik', 'nilai' => 2, 'sub_kriteria' => '1-3'],
                    ['indikator' => 'Sangat Baik', 'nilai' => 1, 'sub_kriteria' => '0'],
                ]
            ],
            [
                'kode' => 'C4/Sakit',
                'sub_kriteria' => [
                    ['indikator' => 'Tidak Baik', 'nilai' => 3, 'sub_kriteria' => '>3'],
                    ['indikator' => 'Cukup Baik', 'nilai' => 2, 'sub_kriteria' => '1-3'],
                    ['indikator' => 'Sangat Baik', 'nilai' => 1, 'sub_kriteria' => '0'],
                ]
            ],
            // Tambahkan data kriteria lainnya di sini...
        ];
        // untuk menampilkan view
        return view('perhitungan.indikator', compact('indikator'));

    }
}
