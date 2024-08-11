@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('contents')
    <h1 class="mb-0">Edit Absensi</h1>
    <hr />
    <form action="/absen/{{$absen->id}}" method="POST">
        @csrf
        @method('PUT')

        <select name="data_siswa_id" class="form-control" required>
            <option value="data_siswa_id" disabled selected>PILIH SISWA</option>
            @foreach($dataSiswa as $ds)
                <option value="{{ $ds->id }}">{{ $ds->nama }}</option>
            @endforeach
        </select>

        <div class="row">
            <div class="col mb-3">
                <label for="tanggal">Tanggal dan Waktu:</label>
                <input type="datetime-local" id="tanggal" name="tanggal" value="{{$absen->tanggal}}">
            </div>
        </div>

        <div class="row">
            <div class="col mb-3">
                <select name="status" value="{{$absen->status}}" class="form-control" required>
                {{-- <option  disabled selected></option> --}}
                @foreach($status as $st)
                    <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <button class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
@endsection
