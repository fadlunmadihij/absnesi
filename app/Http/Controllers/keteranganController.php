<?php

namespace App\Http\Controllers;

use App\Models\data_siswa;
use App\Models\keterangan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class keteranganController extends Controller
{
    public function index()
    {
        $ket = keterangan::latest()->get();
        return view('keterangan.index', compact('ket'));
    }

    public function create(): View
    {
        $siswa = data_siswa::all();
        return view('keterangan.tambahKeterangan', compact('siswa'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'data_siswa_id' => 'required|exists:data_siswas,id',
            'keterangan' => 'required|string|max:255',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Proses upload file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $filePath = 'uploads/' . $filename;
        } else {
            return redirect()->back()->withInput()->withErrors(['file' => 'File tidak ditemukan atau tidak valid']);
        }

        // Simpan data ke database
        Keterangan::create([
            'data_siswa_id' => $request->input('data_siswa_id'),
            'keterangan' => $request->input('keterangan'),
            'file' => $filePath
        ]);

        return redirect('/keterangan')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(keterangan $keterangan): View
    {
        $siswa = data_siswa::all();
        return view('keterangan.editKeterangan', compact('keterangan', 'siswa'));
    }

    public function update(Request $request, keterangan $keterangan)
    {
        // Validasi input
        $request->validate([
            'data_siswa_id' => 'required|exists:data_siswas,id',
            'keterangan' => 'required|string|max:255',
            'file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // file bersifat opsional
        ]);

        // Proses unggah file baru jika ada
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if (file_exists(public_path($keterangan->file))) {
                unlink(public_path($keterangan->file));
            }

            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $filePath = 'uploads/' . $filename;
        } else {
            $filePath = $keterangan->file; // Menggunakan file yang sudah ada
        }

        // Update data di database
        $keterangan->update([
            'data_siswa_id' => $request->input('data_siswa_id'),
            'keterangan' => $request->input('keterangan'),
            'file' => $filePath
        ]);

        return redirect('/keterangan')->with('success', 'Data berhasil diupdate');
    }

    public function destroy(keterangan $keterangan)
    {
        $keterangan->delete();
        return redirect('/keterangan');
    }
}
