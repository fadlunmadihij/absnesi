@extends('layouts.app')

@section('title', 'Home Matrik Ternormalisasi')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Matrik Ternormalisasi</h1>
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
                <th>ALTERNATIF</th>
                <th>C1</th>
                <th>C2</th>
                <th>C3</th>
                <th>C4</th>
            </tr>
        </thead>
        <tbody>
                @foreach($normalisasi as $nm)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $nm['ALTERNATIF'] }}</td>
                        <td class="align-middle">{{ $nm['C1'] }}</td>
                        <td class="align-middle">{{ $nm['C2'] }}</td>
                        <td class="align-middle">{{ $nm['C3'] }}</td>
                        <td class="align-middle">{{ $nm['C4'] }}</td>
                    </tr>
                @endforeach
        </tbody>
    </table>
@endsection
