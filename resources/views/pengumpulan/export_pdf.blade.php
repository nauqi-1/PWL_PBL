<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .document {
            width: 80%;
            margin: auto;
            background: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header img {
            height: 60px;
            float: left;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
        }

        .header h2 {
            font-size: 16px;
            margin: 5px 0;
        }

        .header p {
            font-size: 14px;
        }

        .content {
            font-size: 14px;
            line-height: 1.6;
        }

        .content .field {
            display: flex;
            margin-bottom: 10px;
        }

        .content .field label {
            width: 150px;
            font-weight: bold;
        }

        .content .field span {
            flex: 1;
        }

        .footer {
            margin-top: 20px;
        }

        .footer .signature {
            float: right;
            text-align: center;
            margin-top: 30px;
        }

        .footer .signature p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="document">
        <div class="header">
            <img src="logo.png" alt="Logo">
            <h1>KEMENTRIAN PENDIDIKAN, KEBUDAYAAN, RISET DAN TEKNOLOGI</h1>
            @if($data->mahasiswa_prodi == 'SIB')
            <h2>Program Studi Sistem Informasi Bisnis</h2>
            @elseif ($data->mahasiswa_prodi == 'TI')
            <h2>Program Studi Teknik Informatika</h2>
            @elseif ($data->mahasiswa_prodi == 'PPLS')
            <h2>Program Studi Pengembangan Piranti Lunak Situs</h2>
            @endif
            <p>Politeknik Negeri Malang</p>
            <p>Telp. 0341-404424</p>
        </div>

        <div class="content">
        <h4><strong>BERITA ACARA KOMPENSASI PRESENSI</strong></h4>
        <p>Memberikan rekomendasi kepada:</p>
        <div class="field"><strong>Nama Mahasiswa:</strong> {{ $data->mahasiswa_nama }}</div>
        <div class="field"><strong>NIM:</strong> {{ $data->mahasiswa_nim }}</div>
        <div class="field"><strong>Kelas:</strong> {{$data->mahasiswa_prodi}} {{ $data->mahasiswa_kelas }}</div>
        <div class="field"><strong>Pekerjaan:</strong> {{ $data->tugas_nama }}</div>
        <div class="field"><strong>Jumlah Jam:</strong> {{ $data->tugas_bobot }} Jam</div>

        <p>Malang, {{ $data->current_date }}</p>
    </div>

    <div class="footer">
        <p>Ka. Program Studi</p>
        <br><br>
        @if($data->mahasiswa_prodi == 'SIB')
        <p><strong>Hendra Pradibta, S.E., M.Sc.</strong></p>
        <p>NIP: 19835212000641003</p>
    </div>
    
</body>
</html>
