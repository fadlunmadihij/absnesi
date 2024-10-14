<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class normalisasiController extends Controller
{
    public function index()
    {
        $normalisasi = [
            ['ALTERNATIF' => 'A1', 'C1' => '0,5', 'C2' => "1", 'C3' => "0,3", 'C4' => "1"],
            ['ALTERNATIF' => 'A2', 'C1' => '1', 'C2' => "1", 'C3' => "0,5", 'C4' => "0,5"],
            ['ALTERNATIF' => 'A3', 'C1' => '0,5', 'C2' => "1", 'C3' => "0,5", 'C4' => "1"],
            ['ALTERNATIF' => 'A4', 'C1' => '0,3', 'C2' => "1", 'C3' => "1", 'C4' => "1"],
            ['ALTERNATIF' => 'A5', 'C1' => '1', 'C2' => "1", 'C3' => "1", 'C4' => "1"],
        ];

        return view('perhitungan.normalisasi', compact('normalisasi'));
    }
}
