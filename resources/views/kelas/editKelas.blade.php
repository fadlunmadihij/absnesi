@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('contents')
    <h1 class="mb-0">Edit Kelas</h1>
    <hr />
    <form action="/kelas/{{$kelas->id}}" method="POST">
        {{-- Untuk melakukan operasi yang lebih kompleks seperti PUT, DELETE, atau PATCH, kita tetap menuliskan method="POST" pada form, lalu mengatur metode sebenarnya menggunakan anotasi Laravel (@method). --}}
        @csrf
        @method('PUT')
        {{-- biasanya digunakan untuk memperbarui update data dalam RESTful API atau resource yang ada. --}}

        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Nama Kelas</label>
                <input type="text" name="nama_kelas" class="form-control" placeholder="nama kelas" value="{{ $kelas->nama_kelas }}" >
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <button class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
@endsection
