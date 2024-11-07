<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class tahap1Controller extends Controller
{
    public function index()
    {
        // data json untuk contoh perhitungan
        $tahap1 = [
            ['ALTERNATIF' => 'A1', 'C1' => 'CB/3', 'C2' => "CB/18", 'C3' => "TB/4", 'C4' => "SB/1"],
            ['ALTERNATIF' => 'A2', 'C1' => 'SB/1', 'C2' => "CB/20", 'C3' => "CB/2", 'C4' => "CB/3"],
            ['ALTERNATIF' => 'A3', 'C1' => 'CB/3', 'C2' => "CB/16", 'C3' => "CB/3", 'C4' => "TB/4"],
            ['ALTERNATIF' => 'A4', 'C1' => 'TB/4', 'C2' => "CB/18", 'C3' => "SB/1", 'C4' => "CB/3"],
            ['ALTERNATIF' => 'A5', 'C1' => 'SB/1', 'C2' => "CB/23", 'C3' => "SB/1", 'C4' => "SB/1"],
        ];

        return view('perhitungan.tahap1', compact('tahap1'));
    }
}
