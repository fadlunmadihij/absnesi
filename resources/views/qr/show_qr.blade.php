@extends('layouts.app')

@section('title', 'Show QR')

@section('contents')
<div class="container">
    <h1>QR Code untuk Siswa: {{ $siswa->nama }}</h1>

    <div class="mt-4">
        <img src="{{ asset('qrcodes/'.$siswa->NISN.'.png') }}" alt="QR Code" style="width: 300px; height: 300px;">
    </div>
</div>
@endsection
