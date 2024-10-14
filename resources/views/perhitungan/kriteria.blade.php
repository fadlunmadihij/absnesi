@extends('layouts.app')

@section('title', 'Home Kriteria')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Kriteria</h1>
    </div>
    <hr />
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <table class="table table-hover">
        <thead class="table-primary">
            <tr>
                <th>NO</th>
                <th>KODE</th>
                <th>KRITERIA</th>
                <th>BOBOT</th>
                <th>ATRIBUT</th>
            </tr>
        </thead>
        <tbody>
                @foreach($kriteria as $kt)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $kt['KODE'] }}</td>
                        <td class="align-middle">{{ $kt['KRITERIA'] }}</td>
                        <td class="align-middle">{{ $kt['BOBOT'] }}</td>
                        <td class="align-middle">{{ $kt['ATRIBUT'] }}</td>
                    </tr>
                @endforeach
        </tbody>
    </table>
@endsection
