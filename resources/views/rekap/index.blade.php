@extends('layouts.app')

@section('title', 'Home Rekap')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Rekap</h1>
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
    <table class="table table-bordered" id="tableLaporan">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Nama Siswa</th>
                <th>Hadir (H)</th>
                <th>Izin (I)</th>
                <th>Sakit (S)</th>
                <th>Alpa (A)</th>
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

            tabel = $("#tableLaporan").dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('hitungRekap.filter') }}",
                    type: "POST",
                    data: function(d) {
                        d.start_date = $("#start_date").val();
                        d.end_date = $("#end_date").val();
                        d.kelas_id = $('#kelas').find(":selected").val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'hadir_count',
                        name: 'hadir_count'
                    },
                    {
                        data: 'izin_count',
                        name: 'izin_count'
                    },
                    {
                        data: 'sakit_count',
                        name: 'sakit_count'
                    },
                    {
                        data: 'alpa_count',
                        name: 'alpa_count'
                    },
                ]

            });
        });

        function prosesData() {
            tabel.api().ajax.reload();
        }
    </script>
@endsection
