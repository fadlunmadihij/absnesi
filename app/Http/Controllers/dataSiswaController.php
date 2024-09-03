<?php

namespace App\Http\Controllers;

use App\Models\data_siswa;
use App\Models\kelas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use function Laravel\Prompts\table;

class dataSiswaController extends Controller
{
    public function index()
    {
        $dataSiswa = data_siswa::latest()->get();
        return view('siswa.index', compact('dataSiswa'));
    }


    public function create(): View
    {
        $jeniskelamin = ['laki-laki', 'perempuan'];
        $kelas = kelas::all();
        return view('siswa.tambahdata', compact('kelas', 'jeniskelamin'));
    }
    public function store(Request $request)
    {
        $post = data_siswa::create([
            'kelas_id'     => $request->input('kelas_id'),
            'nama'     => $request->input('nama'),
            'alamat'      => $request->input('alamat'),
            'jenis_kelamin'   => $request->input('jenis_kelamin'),
            'NISN'      => $request->input('NISN'),
            'No_wa'      => $request->input('No_wa'),
        ]);

        return redirect('/siswa');
    }

    function edit(data_siswa $dataSiswa)
    {
        $jeniskelamin = ['laki-laki', 'perempuan'];
        $kelas = kelas::all();
        return view('siswa/editSiswa', compact('kelas', 'jeniskelamin', 'dataSiswa'));
    }
    function update(Request $request, data_siswa $dataSiswa)
    {
        $dataSiswa->update(
            $request->all()

        );
        return redirect('/siswa');
    }
    public function destroy(data_siswa $dataSiswa)
    {
        $dataSiswa->delete();
        return redirect('/siswa');
    }
}
