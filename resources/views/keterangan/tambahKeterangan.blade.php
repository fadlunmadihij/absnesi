@extends('layouts.app')

@section('title', 'Create Keterangan')

@section('contents')
    <h1 class="mb-0">Add Keterangan</h1>
    <hr />
    <form action="/keterangan/store" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-3">
            <select name="data_siswa_id" id="data_siswa_id" class="form-control">
                <option value="" disabled selected>pilih siswa</option>
                @foreach ($siswa as $s)
                    <option value="{{ $s->id }}">{{ $s->nama }}</option>
                @endforeach
            </select>
            @error('data_siswa_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="row mb-3">
            <div class="col">
                <input type="text" name="keterangan" class="form-control" placeholder="Keterangan">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label for="file">File</label>
    <input type="file" name="file" id="file" accept="image/*" required>
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
