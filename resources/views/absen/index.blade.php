@extends('layouts.app')

@section('title', 'Home Absensi')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Absensi</h1>
        <a href="absen/tambahabsen" class="btn btn-primary">Add Absensi</a>
    </div>
    <hr />
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <table class="table table-hover" id="table">
        <thead class="table-primary">
            <tr>
                <th>NO</th>
                <th>SISWA</th>
                <th>TANGGAL</th>
                <th>ABSEN</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>+
            @if($absen->count() > 0)
                @foreach($absen as $ab)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $ab->dataSiswa['nama'] }}</td>
                        <td class="align-middle">{{ $ab->tanggal }}</td>
                        <td class="align-middle">{{ $ab->status }}</td>
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                {{-- <a href="{{ route('siswa.show', $ds->id) }}" type="button" class="btn btn-secondary">Detail</a> --}}
                                <a href="absen/{{$ab->id}}" type="button" class="btn btn-warning">Edit</a>
                                <form action="/absen/{{$ab->id}}" method="POST" type="button" class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
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
                    <td class="text-center" colspan="5">Absensi not found</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection

@section('js')
<script>

$(document).ready(function () {
    $('#table').DataTable();
  });
 </script>
@endsection
