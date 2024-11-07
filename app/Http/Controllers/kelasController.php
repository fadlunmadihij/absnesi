<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables as DataTables;

use function Laravel\Prompts\table;

class kelasController extends Controller
{
    public function index(Request $request)
    {
        // ini fungsi/logika, untuk masuk kehalaman kelas dan $kelas adalah parameter untuk mengambil data dari tabel kelas yg ada di database
        $kelas = kelas::latest()->get();
        return view('kelas.index', compact('kelas'));
    }

    public function create(): View
    {
        // fungsi ini digunakan untuk masuk ke halaman tambah kelas, $kelas digunakan untuk menginisialisasikan kelas yg ada
        $kelas = kelas::all();
        // return berfungsi untuk masuk ke halaman tambah kelas
        return view('kelas.tambahKelas', compact('kelas'));
    }

    public function store(Request $request)
    {
        // logika atau proses tambah data
        $post = kelas::create([
            'nama_kelas'     => $request->input('nama_kelas')
        ]);

        return redirect('/kelas');
    }

    function edit(kelas $kelas)
    {
        // untuk masuk ke halaman edit kelas
        return view('kelas/editKelas', compact('kelas'));
    }
    function update(Request $request, kelas $kelas)
    {
        // logika proses update kelas
        $kelas->update(
            $request->all()

        );
        return redirect('/kelas');
    }

    public function destroy(kelas $kelas)
    {
        // logika untuk hapus kelas
        $kelas->delete();
        // return ini berfungsi ketika tombol delete di pencet, maka kembali kehalaman kelas
        return redirect('/kelas');
    }
}
