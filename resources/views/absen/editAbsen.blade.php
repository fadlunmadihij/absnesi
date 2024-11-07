@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('contents')
    <!-- Judul halaman -->
    <h1 class="mb-0">Edit Absensi</h1>
    <hr />

    <!-- Form untuk mengedit data absensi -->
    <form action="/absen/{{$absen->id}}" method="POST">
        @csrf <!-- Token CSRF untuk keamanan form -->
        @method('PUT') <!-- Metode PUT digunakan untuk memperbarui data -->

        <!-- Metode PUT digunakan untuk memperbarui data -->
        <select name="data_siswa_id" class="form-control" required>
            <option value="data_siswa_id" disabled selected>PILIH SISWA</option> <!-- Placeholder awal -->
            @foreach($dataSiswa as $ds)
                <!-- Menampilkan setiap siswa dalam dropdown -->
                <!-- Tidak ada logika yang menandai siswa terpilih sebelumnya -->
                <option value="{{ $ds->id }}">{{ $ds->nama }}</option>
            @endforeach
        </select>

        <div class="row">
            <div class="col mb-3">
                <!-- Input tanggal dan waktu -->
                <label for="tanggal">Tanggal dan Waktu:</label>
                <!-- Value default diisi dengan data absensi yang ada -->
                <input type="datetime-local" id="tanggal" name="tanggal" value="{{$absen->tanggal}}">
            </div>
        </div>

        <div class="row">
            <div class="col mb-3">
                <!-- Dropdown untuk memilih status absensi -->
                <select name="status" value="{{$absen->status}}" class="form-control" required>
                <!-- Placeholder yang dikomentari -->
                @foreach($status as $st)
                    <!-- Menampilkan setiap status dalam dropdown dengan huruf pertama kapital -->
                    <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <!-- Tombol submit untuk memperbarui data -->
                <button class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
@endsection
