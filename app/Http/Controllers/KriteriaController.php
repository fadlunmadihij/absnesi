<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    // data json untuk contoh perhitungan
    public function index()
    {
        $kriteria = [
            ['KODE' => 'C1', 'KRITERIA' => 'IZIN', 'BOBOT' => "10%", 'ATRIBUT' => "COST"],
            ['KODE' => 'C2', 'KRITERIA' => 'HADIR', 'BOBOT' => "70%", 'ATRIBUT' => "BENEFIT"],
            ['KODE' => 'C3', 'KRITERIA' => 'ALFA', 'BOBOT' => "15%", 'ATRIBUT' => "COST"],
            ['KODE' => 'C4', 'KRITERIA' => 'SAKIT', 'BOBOT' => "5%", 'ATRIBUT' => "COST"]
        ];
        // return untuk menampilkan
        return view('perhitungan.kriteria', compact('kriteria'));
    }
}
