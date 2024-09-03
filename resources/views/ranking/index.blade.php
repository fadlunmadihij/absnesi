@extends('layouts.app')

@section('title', 'Home Ranking')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Rangking</h1>
    </div>

    <!-- Form untuk memilih rentang tanggal dan kelas -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="start_date" class="form-label">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label for="end_date" class="form-label">Tanggal Selesai</label>
            <input type="date" name="end_date" id="end_date" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label for="kelas" class="form-label">Kelas</label>
            <select name="kelas_id" id="kelas" class="form-control" required>
                <option value="">Pilih Kelas</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12 d-flex align-items-end mt-3">
            <button type="submit" class="btn btn-primary" onclick="prosesData()">Rekap</button>
        </div>
    </div>


    <!-- Tabel Rekap -->
    <table class="table table-bordered" id="table-rangking">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Nama Siswa</th>
                <th>Hadir (H)</th>
                <th>NA (H)</th>
                <th>Izin (I)</th>
                <th>NA (I)</th>
                <th>Sakit (S)</th>
                <th>NA (S)</th>
                <th>Alpa (A)</th>
                <th>NA (A)</th>
                <th>Hasil Akhir</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <p class="text-center">Pilih rentang tanggal dan kelas untuk melihat rekap absensi.</p>

@endsection

@section('js')
    <script>
        var table;
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


        });

        function prosesData() {

            $.get("{{ route('ranking') }}",
                function(data) {
                    // console.log(data);
                    $('#table-rangking tbody').empty();

                    $.each(data, function(index, item) {
                        var row = '<tr>' +
                            '<td>' + item.NISN + '</td>' +
                            '<td>' + item.nama + '</td>' +
                            '<td>' + item.hadir_count + '</td>' +
                            '<td>' + item.nilai_hadir + '</td>' +
                            '<td>' + item.izin_count + '</td>' +
                            '<td>' + item.nilai_izin + '</td>' +
                            '<td>' + item.sakit_count + '</td>' +
                            '<td>' + item.nilai_sakit + '</td>' +
                            '<td>' + item.alpa_count + '</td>' +
                            '<td>' + item.nilai_alpa + '</td>' +
                            '<td>' + item.nilai_akhir + '</td>' +
                            '</tr>';
                        $('#table-rangking tbody').append(row);
                    });
                },
                "json"
            );
        }
    </script>
@endsection
