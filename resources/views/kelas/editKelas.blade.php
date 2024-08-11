@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('contents')
    <h1 class="mb-0">Edit Kelas</h1>
    <hr />
    <form action="/kelas/{{$kels->id}}" method="POST">
        @csrf
        @method('PUT')

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
