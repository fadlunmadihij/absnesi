<?php

namespace App\Http\Controllers;

use App\Models\absensi;
use App\Models\data_siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use function Laravel\Prompts\table;

class absenController extends Controller
{
    public function index()
    {
        $absen = absensi::latest()->get();
        return view('absen.index', compact('absen'));
    }


    public function create(): View
    {
        $status = ['I', 'H', 'A', 'S'];
        $dataSiswa = data_siswa::all();
        return view('absen.tambahabsen', compact('status', 'dataSiswa'));
    }
    public function store(Request $request)
    {
        $post = absensi::create([
            'data_siswa_id'     => $request->input('data_siswa_id'),
            'tanggal'     => $request->input('tanggal'),
            'status'      => $request->input('status')
        ]);

        return redirect('/absen');
    }

    function edit(absensi $absen)
    {
        $status = ['I', 'H', 'A', 'S'];
        $dataSiswa = data_siswa::all();
        return view('absen/editAbsen', compact('status', 'absen', 'dataSiswa'));
    }
    function update(Request $request, absensi $absen)
    {
        $absen->update(
            $request->all()

        );
        return redirect('/absen');
    }
    public function destroy(absensi $absen)
    {
        $absen->delete();
        return redirect('/absen');
    }
}
