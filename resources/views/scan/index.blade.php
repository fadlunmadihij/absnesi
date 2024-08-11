@extends('layouts.app')

@section('title', 'Home Scan')

@section('contents')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    body {
        background-color: #f7fafc; /* Warna background lebih terang untuk mode terang */
    }
    .scanner-wrapper {
        max-width: 400px; /* Batasi lebar maksimal */
        width: 100%;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    #reader {
        width: 100%;
    }
    #hasil {
        margin-top: 20px;
        text-align: center;
        font-size: 1.2em;
        color: #333;
    }
    .settings-wrapper {
        margin-top: 20px;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
<body class="antialiased">
    <div class="container">
        <div class="scanner-wrapper">
            <div id="reader"></div>
            <input type="hidden" name="result" id="result">
            <h1 id="hasil">Hasil Scan akan ditampilkan di sini</h1>
        </div>

        <div class="settings-wrapper">
            <h2>Pengaturan Batas Waktu Absensi</h2>
            <form id="settingsForm">
                <div class="form-group">
                    <label for="start_time">Waktu Mulai Absensi (HH:MM):</label>
                    <input type="time" id="start_time" name="start_time" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="end_time">Waktu Selesai Absensi (HH:MM):</label>
                    <input type="time" id="end_time" name="end_time" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
            </form>
        </div>
    </div>
</body>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    let startTime = null;
    let endTime = null;

    // Fungsi untuk menyimpan pengaturan waktu
    function saveSettings(event) {
        event.preventDefault();
        startTime = document.getElementById('start_time').value;
        endTime = document.getElementById('end_time').value;

        alert("Pengaturan waktu berhasil disimpan");
    }

    // Menambahkan event listener pada form pengaturan
    document.getElementById('settingsForm').addEventListener('submit', saveSettings);

    function onScanSuccess(decodedText, decodedResult) {
        let id = decodedText;
        let currentTime = new Date();
        let start = new Date();
        let end = new Date();

        if (startTime && endTime) {
            let [startHours, startMinutes] = startTime.split(':');
            start.setHours(startHours, startMinutes, 0);
            let [endHours, endMinutes] = endTime.split(':');
            end.setHours(endHours, endMinutes, 0);

            if (currentTime >= start && currentTime <= end) {
                html5QrcodeScanner.clear().then(_ => {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('validasi') }}",
                        type: 'POST',
                        data: {
                            _method: "POST",
                            _token: CSRF_TOKEN,
                            qr_code: id,
                            start_time: startTime,
                            end_time: endTime
                        },
                        success: function (response) {
                            console.log(response); // Log respons dari server
                            if (response.status == "success") {
                                $("#hasil").html(
                                    `<p>Nama: ${response.nama}</p>
                                     <p>Kelas: ${response.kelas}</p>
                                     <p>NISN: ${response.NISN}</p>`
                                );
                            } else {
                                $("#hasil").html("Scan gagal: " + response.message);
                            }
                            // Render ulang scanner setelah proses selesai
                            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                        },
                        error: function (xhr, status, error) {
                            console.error("Error: " + status + " " + error); // Log error jika ada
                            $("#hasil").html("Scan gagal: " + xhr.responseJSON.message);
                            // Render ulang scanner setelah proses selesai
                            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                        }
                    });
                }).catch(error => {
                    alert('something wrong');
                });
            } else {
                $("#hasil").html("Waktu absensi sudah lewat");
            }
        } else {
            alert("Pengaturan waktu absensi belum disimpan");
        }
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
        // for example:
        // console.warn(`Code scan error = ${error}`);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: { width: 250, height: 250 } },
        /* verbose= */ false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endsection
