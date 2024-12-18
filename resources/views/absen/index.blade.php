@extends('layouts.app')

@section('title', 'Home Absensi')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Absensi</h1>
        <!-- Tombol untuk menambah data absensi -->
        <a href="absen/tambahabsen" class="btn btn-primary">Add Absensi</a>
    </div>
    <hr />
    <!-- Menampilkan pesan sukses jika ada -->
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    <!-- Tabel untuk menampilkan daftar absensi -->
    <table class="table table-hover" id="table">
        <thead class="table-primary">
            <tr>
                <th>NO</th> <!-- Nomor urut -->
                <th>SISWA</th> <!-- Nama siswa -->
                <th>TANGGAL</th> <!-- Tanggal absensi -->
                <th>ABSEN</th> <!-- Status absensi -->
            </tr>
        </thead>
        <tbody>+
            <!-- Cek jika ada data absensi yang tersedia -->
            @if($absen->count() > 0)
                @foreach($absen as $ab)
                    <tr>
                        <!-- Menampilkan nomor urut menggunakan $loop->iteration -->
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <!-- Menampilkan nama siswa dari relasi dataSiswa -->
                        <td class="align-middle">{{ $ab->dataSiswa['nama'] }}</td>
                        <td class="align-middle">{{ $ab->tanggal }}</td> <!-- Tanggal absensi -->
                        <td class="align-middle">{{ $ab->status }}</td> <!-- Status absensi -->
                    </tr>
                @endforeach
            @else
             <!-- Menampilkan pesan jika tidak ada data absensi -->
                <tr>
                    <td class="text-center" colspan="5">Absensi not found</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection

@section('js')
<script>
// Inisialisasi DataTable untuk mempercantik tampilan tabel
$(document).ready(function () {
    $('#table').DataTable();
  });
 </script>
@endsection
