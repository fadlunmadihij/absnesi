<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class exampleController extends Controller
{
    public function index()
    {
        // data json untuk contoh perhitungan
        $example = [
            ['ALTERNATIF' => 'A1', 'NAMA' => 'ACH.NUR ALAM', 'C1' => '3', 'C2' => "18", 'C3' => "4", 'C4' => "1"],
            ['ALTERNATIF' => 'A2', 'NAMA' => 'NEILY ELYANA', 'C1' => '1', 'C2' => "20", 'C3' => "2", 'C4' => "3"],
            ['ALTERNATIF' => 'A3', 'NAMA' => 'MOH RISKI', 'C1' => '3', 'C2' => "16", 'C3' => "3", 'C4' => "4"],
            ['ALTERNATIF' => 'A4', 'NAMA' => 'FIANA LEGIYANANDA', 'C1' => '4', 'C2' => "18", 'C3' => "1", 'C4' => "3"],
            ['ALTERNATIF' => 'A5', 'NAMA' => 'JIHAN NUR AFIAH', 'C1' => '1', 'C2' => "23", 'C3' => "1", 'C4' => "1"],
        ];
        // return untuk menampilkan view
        return view('perhitungan.example', compact('example'));
    }
}
