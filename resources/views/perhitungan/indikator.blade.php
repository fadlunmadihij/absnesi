@extends('layouts.app')

@section('title', 'Home Indikator')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Indikator</h1>
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
                <th>KODE KRITERIA</th>
                <th>INDIKATOR</th>
                <th>NILAI</th>
                <th>SUB KRITERIA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($indikator as $id)
                <tr>
                    <td class="align-middle" rowspan="{{ count($id['sub_kriteria']) }}">{{ $loop->iteration }}</td>
                    <td class="align-middle" rowspan="{{ count($id['sub_kriteria']) }}">{{ $id['kode'] }}</td>

                    @foreach($id['sub_kriteria'] as $index => $sub)
                        @if($index > 0) <tr> @endif
                            <td class="align-middle">{{ $sub['indikator'] }}</td>
                            <td class="align-middle">{{ $sub['nilai'] }}</td>
                            <td class="align-middle">{{ $sub['sub_kriteria'] }}</td>
                        </tr>
                    @endforeach
            @endforeach
        </tbody>
    </table>
@endsection
