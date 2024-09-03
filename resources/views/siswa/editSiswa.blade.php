@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('contents')
    <h1 class="mb-0">Edit Siswa</h1>
    <hr />
    <form action="/siswa/{{$dataSiswa->id}}" method="POST">
        @csrf
        @method('PUT')

        <select name="kelas_id" class="form-control" required>
            <option value="kelas_id" disabled selected>pilih kelas</option>
            @foreach($kelas as $kl)
                <option value="{{ $kl->id }}">{{ $kl->nama_kelas }}</option>
            @endforeach
        </select>

        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" placeholder="nama" value="{{ $dataSiswa->nama }}" >
            </div>
            <div class="col mb-3">
                <label class="form-label">Alamat</label>
                <input type="text" name="alamat" class="form-control" placeholder="alamat" value="{{ $dataSiswa->alamat }}" >
            </div>
        </div>

        <select name="jenis_kelamin" value="{{$dataSiswa->jeniskelamin}}" class="form-control" required>
            {{-- <option  disabled selected></option> --}}
            @foreach($jeniskelamin as $jk)
                <option value="{{ $jk }}">{{ ucfirst($jk) }}</option>
            @endforeach
        </select>

        <div class="row">
            <div class="col mb-3">
                <label class="form-label">NISN</label>
                <input type="text" name="NISN" class="form-control" placeholder="NISN" value="{{ $dataSiswa->NISN }}" >
            </div>
            <div class="col mb-3">
                <label class="form-label">Description</label>
                <input type="text" name="No_wa" class="form-control" placeholder="No wa" value="{{ $dataSiswa->No_wa }}" >
            </div>
        </div>
        <div class="row">
            <div class="d-grid">
                <button class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
@endsection
