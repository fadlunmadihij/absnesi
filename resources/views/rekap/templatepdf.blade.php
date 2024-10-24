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

        .kop-surat h2 {
            margin: 0;
        }

        .kop-surat h3 {
            margin: 0;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="kop-surat">
        <img src="https://smabatuanadiwiyata.sch.id/wp-content/uploads/2024/05/cropped-logo-SMAGA.png" alt="Logo Sekolah" style="height: 80px;">
        <h2>PEMERINTAH DAERAH KHUSUS IBUKOTA JAKARTA</h2>
        <h3>DINAS PENDIDIKAN</h3>
        <h3>SEKOLAH MENENGAH ATAS NEGERI 3 SUMENEP</h3>
        <p>Jalan Cendana No. 9A, Sumenep 20155<br>Tel/Fax: 061 8246940, e-mail: sma3_sumenep@gmail.com</p>
        <hr>
    </div>

    <p>No: 0425/77/234</p>
    <p>Lamp: -</p>
    <p>Hal: Daftar Kehadiran Siswa</p>
    <br>
    <p>Kepada Yth,<br>Bapak/Ibu Orang Tua/Wali Siswa</p>
    <p>Di Tempat</p>

    <p>Assalamu’alaikum Wr. Wb.<br>
        Berikut kami lampirkan daftar kehadiran siswa SMAN 3 Sumenep:</p>

    <!-- Tabel Kehadiran -->
    <table>
        <thead>
            <tr>
                <th>kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kelas as $kls)
            <tr>
                <td>{{ $kls['nama_kelas'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>

    <p>Demikian surat pemberitahuan ini kami sampaikan. Atas perhatian Bapak/Ibu, kami ucapkan terima kasih.</p>

    <p>Wassalamu'alaikum Wr. Wb.</p>

    <p>Sumenep, 25 Juni 2024<br>Kepala Sekolah SMAN 3 Sumenep</p>
    <p><b>Dra. Hj. Yuliana, Spd.</b><br>NIP. 13096342</p>

</body>

</html>
