@extends('layouts.app')

@section('title', 'Create Absensi')

@section('contents')
    <!-- Judul halaman -->
    <h1 class="mb-0">Add Absensi</h1>
    <hr />

    <!-- Form untuk menambah data absensi -->
    <form action="/absen/store" method="POST" enctype="multipart/form-data">
        @csrf <!-- Token CSRF untuk keamanan form -->

        <!-- Dropdown untuk memilih siswa -->
        <select name="data_siswa_id" id="data_siswa_id" class="form-control" required>
            <option value="" disabled selected>PILIH SISWA</option> <!-- Placeholder awal -->
            @foreach($dataSiswa as $ds)
                <!-- Menampilkan setiap siswa dalam dropdown -->
                <option value="{{ $ds->id }}">{{ $ds->nama }}</option>
            @endforeach
        </select>

        <div class="row mb-3">
            <div class="col">
                <!-- Input tanggal dan waktu -->
                <label for="tanggal">Tanggal dan Waktu:</label>
                <input type="datetime-local" id="tanggal" name="tanggal" required> <!-- Format input datetime -->
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <!-- Dropdown untuk memilih status absensi -->
                <select name="status" class="form-control" required>
                    <option value="" disabled selected>pilih</option> <!-- Placeholder awal -->
                    @foreach($status as $st)
                        <!-- Menampilkan setiap status dalam dropdown dengan huruf pertama kapital -->
                        <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <!-- Tombol submit untuk mengirim data form -->
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
