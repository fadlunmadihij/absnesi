@extends('layouts.app')

@section('title', 'Create Siswa')

@section('contents')
    <h1 class="mb-0">Add Siswa</h1>
    <hr />
    <form action="/kelas/store" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-3">
            <div class="col">
                <input type="text" name="nama_kelas" class="form-control" placeholder="nama kelas">
            </div>

        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
