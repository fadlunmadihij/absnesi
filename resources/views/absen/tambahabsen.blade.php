@extends('layouts.app')

@section('title', 'Create Absensi')

@section('contents')
    <h1 class="mb-0">Add Absensi</h1>
    <hr />
    <form action="/absen/store" method="POST" enctype="multipart/form-data">
        @csrf

        <select name="data_siswa_id" id="data_siswa_id" class="form-control" required>
            <option value="" disabled selected>PILIH SISWA</option>
            @foreach($dataSiswa as $ds)
                <option value="{{ $ds->id }}">{{ $ds->nama }}</option>
            @endforeach
        </select>

        <div class="row mb-3">
            <div class="col">
                <label for="tanggal">Tanggal dan Waktu:</label>
                <input type="datetime-local" id="tanggal" name="tanggal" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <select name="status" class="form-control" required>
                <option value="" disabled selected>pilih</option>
                @foreach($status as $st)
                    <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
