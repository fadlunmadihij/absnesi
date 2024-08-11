@extends('layouts.app')

@section('title', 'Home Genaerate QR')

@section('contents')
<div class="container">
    <h1>Generate QR Code untuk Siswa</h1>

    <form action="{{ route('generate-qr-code') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="siswa">Pilih Siswa:</label>
            <select class="form-control" id="siswa" name="siswa_id">
                @foreach($dataSiswa as $siswaItem)
                    <option value="{{ $siswaItem->id }}">{{ $siswaItem->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Generate QR Code</button>
    </form>

    @if(isset($qrCode))
    <div class="mt-4">
        <h2>QR Code untuk Siswa: {{ $siswa->nama }}</h2>
        <img src="{{ asset('qrcodes/'.$qrCode.'.png') }}" alt="QR Code" style="width: 300px; height: 300px;">
    </div>
    @endif
</div>
@endsection
