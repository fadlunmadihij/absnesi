@extends('layouts.app')

@section('title', 'Ranking Absensi')

@section('contents')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Ranking Absensi</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Nama Siswa</th>
                                <th>Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peringkatSiswa as $siswa)
                                <tr>
                                    <td>{{ $siswa['rank'] }}</td>
                                    <td>{{ $siswa['nama'] }}</td>
                                    <td>{{ number_format($siswa['skor'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
