@extends('layouts.app')

@section('title', 'Edit Keterangan')

@section('contents')
    <h1 class="mb-0">Edit Keterangan</h1>
    <hr />
    <form action="/keterangan/{{$keterangan->id}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <select name="data_siswa_id" class="form-control" required>
            <option value="" disabled>pilih siswa</option>
            @foreach($siswa as $s)
                <option value="{{ $s->id }}" {{ $keterangan->data_siswa_id == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
            @endforeach
        </select>

        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Keterangan</label>
                <input type="text" name="keterangan" class="form-control" placeholder="Keterangan" value="{{ $keterangan->keterangan }}">
            </div>
        </div>

        <div class="row">
            <div class="col mb-3">
                <label for="file">File (opsional, jika ingin mengganti)</label>
                <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror">
                @if($keterangan->file)
                    <div class="mt-2">
                        <img src="{{ asset($keterangan->file) }}" alt="File Image" width="100">
                        <p>File saat ini: {{ $keterangan->file }}</p>
                    </div>
                @endif
                @error('file')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
@endsection
