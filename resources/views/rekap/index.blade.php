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
        <button type="submit" class="btn btn-primary" style="margin-right: 1rem" onclick="prosesData()">Rekap</button>
        <div id="hasQuery" style="display: none">
            <button type="submit" class="btn btn-primary" style="margin-right: 1rem" onclick="viewPdf()">View PDF <svg
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-notebook-text">
                    <path d="M2 6h4" />
                    <path d="M2 10h4" />
                    <path d="M2 14h4" />
                    <path d="M2 18h4" />
                    <rect width="16" height="20" x="4" y="2" rx="2" />
                    <path d="M9.5 8h5" />
                    <path d="M9.5 12H16" />
                    <path d="M9.5 16H14" />
                </svg></button>
            <button type="submit" class="btn btn-danger" onclick="downloadPdf()">Download PDF <svg
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-download">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" x2="12" y1="15" y2="3" />
                </svg></button>
        </div>
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
    const viewPdf = () => {
    let startDate = document.getElementById('start_date').value;
    let endDate = document.getElementById('end_date').value;
    let kelasId = document.getElementById('kelas').value;

    console.log(`start_date: ${startDate}, end_date: ${endDate}, kelas: ${kelasId}`);

    // Buat form secara dinamis
    let form = $('<form>', {
        action: '/rekap/view/pdf',
        method: 'POST',
        target: '_blank' // membuka file PDF di tab baru
    });

    // Tambahkan CSRF token
    form.append($('<input>', {
        type: 'hidden',
        name: '_token',
        value: $('meta[name="csrf-token"]').attr('content')
    }));

    // Tambahkan input data startDate, endDate, dan kelasId
    form.append($('<input>', {
        type: 'hidden',
        name: 'startDate',
        value: startDate
    }));

    form.append($('<input>', {
        type: 'hidden',
        name: 'endDate',
        value: endDate
    }));

    form.append($('<input>', {
        type: 'hidden',
        name: 'kelasId',
        value: kelasId
    }));

    // Tambahkan form ke body dan submit
    form.appendTo('body').submit();
};
    const downloadPdf = () => {
    let startDate = document.getElementById('start_date').value;
    let endDate = document.getElementById('end_date').value;
    let kelasId = document.getElementById('kelas').value;

    console.log(`start_date: ${startDate}, end_date: ${endDate}, kelas: ${kelasId}`);

    // Buat form secara dinamis
    let form = $('<form>', {
        action: '/rekap/download/pdf',
        method: 'POST',
        target: '_blank' // membuka file PDF di tab baru
    });

    // Tambahkan CSRF token
    form.append($('<input>', {
        type: 'hidden',
        name: '_token',
        value: $('meta[name="csrf-token"]').attr('content')
    }));

    // Tambahkan input data startDate, endDate, dan kelasId
    form.append($('<input>', {
        type: 'hidden',
        name: 'startDate',
        value: startDate
    }));

    form.append($('<input>', {
        type: 'hidden',
        name: 'endDate',
        value: endDate
    }));

    form.append($('<input>', {
        type: 'hidden',
        name: 'kelasId',
        value: kelasId
    }));

    // Tambahkan form ke body dan submit
    form.appendTo('body').submit();
};

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
            const btnViewDownload = document.getElementById('hasQuery');
            btnViewDownload.style.display = 'block';
        }
</script>
@endsection