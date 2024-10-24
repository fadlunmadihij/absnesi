<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            text-align: center;
        }

        .kop {
            margin: 20px auto;
            border-bottom: 2px solid black;
            padding-bottom: 20px;
        }

        .kop h1 {
            font-size: 20px;
            font-weight: bold;
            margin: 5px;
        }

        .kop h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 5px;
        }

        .kop h3 {
            font-size: 18px;
            font-weight: bold;
            margin: 5px;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .kop p {
            font-size: 12px;
            margin: 3px;
        }

        .kop .kode-pos {
            font-size: 10px;
            margin-top: 10px;
            float: right;
            margin-right: 10px;
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
    <table border="0" style="width: 100%; border: 0;">
        <tr>
            <td style="width: 10%;">
                <img src="https://pertanian.jatimprov.go.id/wp-content/uploads/2019/10/logo-provinsi-jawa-timur.png"
                    width="15%" alt="Logo Sekolah">
            </td>
            <td style="text-align: center; ">
                <h3 style="font-family: 'Times New Roman', Times, serif; font-size: 14pt; font-weight: bold;">
                    PEMERINTAH
                    PROVINSI JAWA TIMUR</h3>
                <h3 style="font-family: 'Times New Roman', Times, serif; font-size: 14pt; font-weight: bold;">DINAS
                    PENDIDIKAN</h3>
                <h2
                    style="font-family: 'Bookman Old Style', 'Times New Roman',serif; font-size: 18pt; font-weight: bold;">
                    SEKOLAH
                    MENENGAH ATAS NEGERI 3</h2>
                <h2
                    style="font-family: 'Bookman Old Style','Times New Roman', serif; font-size: 18pt; font-weight: bold;">
                    SUMENEP</h2>
                <p style="font-family: 'Times New Roman', Times, serif; font-size: 12pt;">Jl. Raya Lenteng Batuan -
                    Sumenep Telp. (0328) 6771421 <br>
                    E-mail: <span style="font-style: italic;">sman3sumenep@gmail.com</span></p>
                <h3
                    style="font-family: 'Bookman Old Style', serif; font-size: 14pt; font-weight: bold; letter-spacing: 5px">
                    <u>SUMENEP</u>
                </h3>

            </td>
        </tr>
    </table>
    <div style="position: relative;">
        <p align="right"
            style="font-family: 'Times New Roman', Times, serif; font-size: 12pt;margin: 0; position: absolute; top: 0; right: 0;">
            Kode Pos 69451
        </p>
    </div>

    <hr>
    <p align="center"><b>Data Siswa Rekap Absensi</b></p>
    <p align="center"><b>Per {{ $startDate }} s/d {{ $endDate }}</b></p>
    <p align="left"><b>Kelas <span style="margin-left: 5px;margin-right: 5px">:</span> {{ $kelasName }}</b></p>

    <!-- Tabel Kehadiran -->
    <table border="1">
        <thead>
            <tr>
                <th>No.</th>
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
                <td style="padding: 8px" align="center">{{ $index + 1 }}</td>
                <td style="padding: 8px">{{ $siswa->nama }}</td>
                <td style="padding: 8px">{{ $siswa->hadir_count }}</td>
                <td style="padding: 8px">{{ $siswa->izin_count }}</td>
                <td style="padding: 8px">{{ $siswa->sakit_count }}</td>
                <td style="padding: 8px">{{ $siswa->alpa_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd">
        <p>Sumenep, {{ now()->format('d F Y') }}</p>
        <p><b>Kepala Sekolah SMAN 3 Sumenep</b></p><br><br><br>
        <p><b>Dra. Hj. Yuliana, Spd.</b></p>
        <p>NIP. 13096342</p>
    </div>
</body>

</html>