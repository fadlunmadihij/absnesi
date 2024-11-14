<?php

namespace App\Http\Controllers;

use App\Models\data_siswa;
use App\Models\kelas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use function Laravel\Prompts\table;

class dataSiswaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua data kelas untuk dropdown filter
        $kelasList = kelas::all();

        // Ambil parameter filter dari request
        $kelasId = $request->input('kelas_id');

        // Jika ada filter kelas, tampilkan siswa sesuai kelas yang dipilih
        if ($kelasId) {
            $dataSiswa = data_siswa::where('kelas_id', $kelasId)->latest()->get();
        } else {
            // Jika tidak ada filter, tampilkan semua siswa
            $dataSiswa = data_siswa::latest()->get();
        }

    return view('siswa.index', compact('dataSiswa', 'kelasList', 'kelasId'));
    }



    public function create(): View
    {
        // fungsi ini digunakan untuk masuk ke halaman tambah data siswa, $jeniskelamin digunakan untuk menginisialisasikan untuk jenis kelamin yg ada menggunakan boolean yah itu laki-laki dan perempuan, $kelas digunakan untuk mengambil id kelas sesuai siswanya ada di kelas mana
        $jeniskelamin = ['laki-laki', 'perempuan'];
        $kelas = kelas::all();
        return view('siswa.tambahdata', compact('kelas', 'jeniskelamin'));
    }
    public function store(Request $request)
    {
        // logika proses penambahan data siswa
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
        // fungsi untuk masuk ke halaman edit siswa
        $jeniskelamin = ['laki-laki', 'perempuan'];
        $kelas = kelas::all();
        return view('siswa/editSiswa', compact('kelas', 'jeniskelamin', 'dataSiswa'));
    }
    function update(Request $request, data_siswa $dataSiswa)
    {
        // logika update siswa
        $dataSiswa->update(
            $request->all()

        );
        return redirect('/siswa');
    }
    public function destroy(data_siswa $dataSiswa)
    {
        // logika hapus siswa
        $dataSiswa->delete();
        return redirect('/siswa');
    }
}
