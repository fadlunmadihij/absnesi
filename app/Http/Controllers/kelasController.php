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
        $kelas = kelas::latest()->get();
        // if ($request->ajax()) {

        //     return DataTables::of($kelas)
        //     ->addIndexColumn()
        //     ->make(true);
        // }
        return view('kelas.index', compact('kelas'));
    }

    public function create(): View
    {
        $kelas = kelas::all();
        return view('kelas.tambahKelas', compact('kelas'));
    }

    public function store(Request $request)
    {
        $post = kelas::create([
            'nama_kelas'     => $request->input('nama_kelas')
        ]);

        return redirect('/kelas');
    }

    function edit(kelas $kelas)
    {
        return view('kelas/editKelas', compact('kelas'));
    }
    function update(Request $request, kelas $kelas)
    {
        $kelas->update(
            $request->all()

        );
        return redirect('/kelas');
    }

    public function destroy(kelas $kelas)
    {
        $kelas->delete();
        return redirect('/kelas');
    }
}
