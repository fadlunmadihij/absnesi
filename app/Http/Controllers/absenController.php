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
        // ini fungsi/logika, untuk masuk kehalaman absensi dan $absen adalah parameter untuk mengambil data dari tabel absen yg ada di database
        $absen = absensi::latest()->get();
        return view('absen.index', compact('absen'));
    }


    public function create(): View
    {
        // funsi ini berfungsi untuk masuk ke halaman tambah absen, $status untuk menginisialisasikan huruf apa saja yg ada pada colom status pada tabel absen, dan $datasiswa berfungsi untuk mendapatkan data siswa
        $status = ['I', 'H', 'A', 'S'];
        $dataSiswa = data_siswa::all();
        // return berfungsi untuk masuk ke halaman tambah absen
        return view('absen.tambahabsen', compact('status', 'dataSiswa'));
    }
    public function store(Request $request)
    {
    // logika absensi
        $post = absensi::create([
            'data_siswa_id'     => $request->input('data_siswa_id'),
            'tanggal'     => $request->input('tanggal'),
            'status'      => $request->input('status')
        ]);

        return redirect('/absen');
    }
}
