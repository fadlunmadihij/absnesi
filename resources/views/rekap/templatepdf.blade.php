<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
        }

        .kop-surat img {
            height: 80px;
        }

        .kop-surat h2,
        .kop-surat h3 {
            margin: 0;
        }

        .kop-surat p {
            margin-top: 5px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
            border: 1px solid black;
        }

        hr {
            border: none;
            border-top: 1px solid black;
        }

        .ttd {
            margin-top: 40px;
            text-align: right;
        }

        .ttd p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <table border="0" style="width: 100%; border: 0;">
            <tr>
                <td style="width: 10%;">
                    <img src="https://smabatuanadiwiyata.sch.id/wp-content/uploads/2024/05/cropped-logo-SMAGA.png" alt="Logo Sekolah">
                </td>
                <td style="text-align: center;">
                    <h2>PEMERINTAH DAERAH KHUSUS IBUKOTA JAKARTA</h2>
                    <h3>DINAS PENDIDIKAN</h3>
                    <h3>SEKOLAH MENENGAH ATAS NEGERI 3 SUMENEP</h3>
                    <p>Jalan Cendana No. 9A, Sumenep 20155<br>Tel/Fax: 061 8246940, e-mail: sma3_sumenep@gmail.com</p>
                </td>
            </tr>
        </table>
    </div>

    <hr>
    <p align="center"><b>Data Siswa Rekap Absensi</b></p>
    <p align="center"><b>Kelas: {{ $kelasName }}</b></p>
    <p align="center"><b>Tanggal: {{ $startDate }} s/d {{ $endDate }}</b></p>

    <!-- Tabel Kehadiran -->
    <table>
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
            @foreach ($dataSiswa as $index => $siswa)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $siswa->nama }}</td>
                <td>{{ $siswa->hadir_count }}</td>
                <td>{{ $siswa->izin_count }}</td>
                <td>{{ $siswa->sakit_count }}</td>
                <td>{{ $siswa->alpa_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd">
        <p>Sumenep, {{ now()->format('d F Y') }}</p>
        <p><b>Kepala Sekolah SMAN 3 Sumenep</b></p>
        <p><b>Dra. Hj. Yuliana, Spd.</b></p>
        <p>NIP. 13096342</p>
    </div>
</body>

</html>
