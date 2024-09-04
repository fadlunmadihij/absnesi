@extends('layouts.app')

@section('title', 'Home Keterangan')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Keterangan</h1>
        <a href="keterangan/tambahketerangan" class="btn btn-primary">Add Keterangan</a>
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
                <th>Siswa</th>
                <th>Detail Keterangan</th>
                <th>File</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($ket->count() > 0)
                @foreach($ket as $kt)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">
                            @if ($kt->data_siswa)
                                {{ $kt->data_siswa->nama }}
                            @else
                                No Siswa Found
                            @endif
                        </td>
                        <td class="align-middle">{{ $kt->keterangan }}</td>
                        <td class="align-middle">@if ($kt->file)
                            <img src="{{ asset($kt->file) }}" width="100" alt="Image">
                        @else
                            No Image
                        @endif</td>
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="keterangan/{{$kt->id}}" type="button" class="btn btn-warning">Edit</a>
                                <form action="/keterangan/{{$kt->id}}" method="POST" class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger m-0">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="5">Keterangan not found</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
