@extends('layouts.app')

@section('title', 'Home Rekap')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Rekap</h1>
    </div>

    <!-- Form untuk memilih rentang tanggal dan kelas -->
    <form action="{{ route('rekap.filter') }}" method="GET">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="kelas" class="form-label">Kelas</label>
                <select name="kelas_id" id="kelas" class="form-control" required>
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 d-flex align-items-end mt-3">
                <button type="submit" class="btn btn-primary">Rekap</button>
            </div>
        </div>
    </form>

    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    @if(isset($rekap) && !empty($rekap))
        <!-- Tabel Rekap -->
        <table class="table table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Kelas</th>
                    <th>Nama Siswa</th>
                    <th>Izin (I)</th>
                    <th>Hadir (H)</th>
                    <th>Sakit (S)</th>
                    <th>Alpa (A)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekap as $kelas => $siswas)
                    @foreach($siswas as $siswa)
                        <tr>
                            <td>{{ $kelas }}</td>
                            <td>{{ $siswa['nama'] }}</td>
                            <td>{{ $siswa['status']['I'] }}</td>
                            <td>{{ $siswa['status']['H'] }}</td>
                            <td>{{ $siswa['status']['S'] }}</td>
                            <td>{{ $siswa['status']['A'] }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center">Pilih rentang tanggal dan kelas untuk melihat rekap absensi.</p>
    @endif
@endsection
