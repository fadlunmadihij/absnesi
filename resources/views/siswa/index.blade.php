@extends('layouts.app')

@section('title', 'Home Siswa')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Siswa</h1>
        <a href="siswa/tambahdata" class="btn btn-primary">Add Siswa</a>
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
                <th>#</th>
                <th>KELAS</th>
                <th>NAMA</th>
                <th>ALAMAT</th>
                <th>JENIS KELAMIN</th>
                <th>NISN</th>
                <th>NO WA</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>+
            @if($dataSiswa->count() > 0)
                @foreach($dataSiswa as $ds)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $ds->kelas['nama_kelas'] }}</td>
                        <td class="align-middle">{{ $ds->nama }}</td>
                        <td class="align-middle">{{ $ds->alamat }}</td>
                        <td class="align-middle">{{ $ds->jenis_kelamin }}</td>
                        <td class="align-middle">{{ $ds->NISN }}</td>
                        <td class="align-middle">{{ $ds->No_wa }}</td>
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                {{-- <a href="{{ route('siswa.show', $ds->id) }}" type="button" class="btn btn-secondary">Detail</a> --}}
                                <a href="siswa/{{$ds->id}}" type="button" class="btn btn-warning">Edit</a>
                                <form action="/siswa/{{$ds->id}}" method="POST" type="button" class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
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
                    <td class="text-center" colspan="5">Siswa not found</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
