<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class tahap2Controller extends Controller
{
    public function index()
    {
        $tahap2 = [
            ['ALTERNATIF' => 'A1', 'C1' => '2', 'C2' => "2", 'C3' => "3", 'C4' => "1"],
            ['ALTERNATIF' => 'A2', 'C1' => '1', 'C2' => "2", 'C3' => "2", 'C4' => "2"],
            ['ALTERNATIF' => 'A3', 'C1' => '2', 'C2' => "2", 'C3' => "2", 'C4' => "3"],
            ['ALTERNATIF' => 'A4', 'C1' => '3', 'C2' => "2", 'C3' => "1", 'C4' => "2"],
            ['ALTERNATIF' => 'A5', 'C1' => '1', 'C2' => "2", 'C3' => "1", 'C4' => "1"],
        ];

        return view('perhitungan.tahap2', compact('tahap2'));
    }
}
