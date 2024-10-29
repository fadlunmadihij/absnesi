<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class exRankingController extends Controller
{
    public function index()
    {
        $exRanking = [
            ['ALTERNATIF' => 'A5', 'NILAI Vi' => '100', 'RANKING' => "1"],
            ['ALTERNATIF' => 'A4', 'NILAI Vi' => '93', 'RANKING' => "2"],
            ['ALTERNATIF' => 'A2', 'NILAI Vi' => '90', 'RANKING' => "3"],
            ['ALTERNATIF' => 'A3', 'NILAI Vi' => '87,5', 'RANKING' => "4"],
            ['ALTERNATIF' => 'A1', 'NILAI Vi' => '84,5', 'RANKING' => "5"],
        ];

        return view('perhitungan.exRanking', compact('exRanking'));
    }
}
