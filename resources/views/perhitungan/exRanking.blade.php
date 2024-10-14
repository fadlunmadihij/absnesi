@extends('layouts.app')

@section('title', 'Home Ranking')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Ranking</h1>
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
                <th>NILAI Vi</th>
                <th>RANKING</th>
            </tr>
        </thead>
        <tbody>
                @foreach($exRanking as $er)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $er['ALTERNATIF'] }}</td>
                        <td class="align-middle">{{ $er['NILAI Vi'] }}</td>
                        <td class="align-middle">{{ $er['RANKING'] }}</td>
                    </tr>
                @endforeach
        </tbody>
    </table>
@endsection
