@extends('layouts.app')

@section('title', 'Create Siswa')

@section('contents')
    <h1 class="mb-0">Add Siswa</h1>
    <hr />
    <form action="/siswa/store" method="POST" enctype="multipart/form-data">
        @csrf

  <select name="kelas_id" class="form-control" required>
      <option value="" disabled selected>pilih kelas</option>
      @foreach($kelas as $kl)
          <option value="{{ $kl->id }}">{{ $kl->nama_kelas }}</option>
      @endforeach
  </select>

        <div class="row mb-3">
            <div class="col">
                <input type="text" name="nama" class="form-control" placeholder="nama">
            </div>

            <div class="col">
                <input type="text" name="alamat" class="form-control" placeholder="alamat">
            </div>
        </div>

        <select name="jenis_kelamin" class="form-control" required>
            <option value="" disabled selected>pilih jenis kelamin</option>
            @foreach($jeniskelamin as $jk)
                <option value="{{ $jk }}">{{ ucfirst($jk) }}</option>
            @endforeach
        </select>


        <div class="row mb-3">
            <div class="col">
                <input type="text" name="NISN" class="form-control" placeholder="NISN">
            </div>

            <div class="col">
                <input type="text" name="No_wa" class="form-control" placeholder="NO WA">
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
