<?php

namespace App\Http\Controllers;

use App\Models\data_siswa;
use App\Models\keterangan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class keteranganController extends Controller
{
    // logika untuk menampilkan semua data keterangan
    public function index()
    {
        // Ambil semua data keterangan dari database, diurutkan dari yang terbaru
        $ket = keterangan::latest()->get();

        // Tampilkan halaman index keterangan dengan data yang sudah diambil
        return view('keterangan.index', compact('ket'));
    }

    // logika untuk menampilkan form tambah keterangan
    public function create(): View
    {
        // Ambil semua data siswa dari database
        $siswa = data_siswa::all();

        // Tampilkan halaman tambahKeterangan dengan data siswa yang sudah diambil
        return view('keterangan.tambahKeterangan', compact('siswa'));
    }

    // Method untuk menyimpan data keterangan baru ke database
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'data_siswa_id' => 'required|exists:data_siswas,id', // ID siswa harus ada di tabel data_siswa
            'keterangan' => 'required|string|max:255', // Keterangan harus berupa string maksimal 255 karakter
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048' // File harus berupa gambar (jpeg, png, jpg) maksimal 2MB
        ]);

        // Proses upload file jika ada file yang diunggah
        if ($request->hasFile('file')) {
            // Ambil file dari request
            $file = $request->file('file');

            // Buat nama file unik menggunakan timestamp
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // Pindahkan file ke folder 'uploads' di public path
            $file->move(public_path('uploads'), $filename);

            // Simpan path file ke variable untuk disimpan ke database
            $filePath = 'uploads/' . $filename;
        } else {
            // Jika file tidak ditemukan, kembalikan ke halaman sebelumnya dengan error
            return redirect()->back()->withInput()->withErrors(['file' => 'File tidak ditemukan atau tidak valid']);
        }

        // Simpan data keterangan ke database
        Keterangan::create([
            'data_siswa_id' => $request->input('data_siswa_id'), // ID siswa
            'keterangan' => $request->input('keterangan'), // Keterangan dari form
            'file' => $filePath // Path file yang diunggah
        ]);

        // Redirect ke halaman keterangan dengan pesan sukses
        return redirect('/keterangan')->with('success', 'Data berhasil ditambahkan');
    }

    // Method untuk menampilkan form edit keterangan
    public function edit(keterangan $keterangan): View
    {
        // Ambil semua data siswa dari database
        $siswa = data_siswa::all();

        // Tampilkan halaman editKeterangan dengan data keterangan dan siswa
        return view('keterangan.editKeterangan', compact('keterangan', 'siswa'));
    }

    // Method untuk memperbarui data keterangan yang ada
    public function update(Request $request, keterangan $keterangan)
    {
        // Validasi input dari form
        $request->validate([
            'data_siswa_id' => 'required|exists:data_siswas,id', // ID siswa harus ada
            'keterangan' => 'required|string|max:255', // Keterangan harus berupa string maksimal 255 karakter
            'file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // File opsional, harus berupa gambar maksimal 2MB jika diunggah
        ]);

        // Proses upload file baru jika ada file yang diunggah
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if (file_exists(public_path($keterangan->file))) {
                unlink(public_path($keterangan->file));
            }

            // Ambil file baru dari request
            $file = $request->file('file');

            // Buat nama file unik menggunakan timestamp
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // Pindahkan file baru ke folder 'uploads' di public path
            $file->move(public_path('uploads'), $filename);

            // Simpan path file baru
            $filePath = 'uploads/' . $filename;
        } else {
            // Jika tidak ada file baru, gunakan file yang lama
            $filePath = $keterangan->file;
        }

        // Update data keterangan di database
        $keterangan->update([
            'data_siswa_id' => $request->input('data_siswa_id'), // ID siswa
            'keterangan' => $request->input('keterangan'), // Keterangan dari form
            'file' => $filePath // Path file yang diunggah atau yang lama
        ]);

        // Redirect ke halaman keterangan dengan pesan sukses
        return redirect('/keterangan')->with('success', 'Data berhasil diupdate');
    }

    // Method untuk menghapus data keterangan
    public function destroy(keterangan $keterangan)
    {
        // Hapus data keterangan dari database
        $keterangan->delete();

        // Redirect ke halaman keterangan
        return redirect('/keterangan');
    }
}