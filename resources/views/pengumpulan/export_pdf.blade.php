<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 4px 3px;
        }

        th {
            text-align: left;
        }

        .d-block {
            display: block;
        }

        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .p-1 {
            padding: 5px 1px 5px 1px;
        }

        .font-10 {
            font-size: 10pt;
        }

        .font-11 {
            font-size: 11pt;
        }

        .font-12 {
            font-size: 12pt;
        }

        .font-13 {
            font-size: 13pt;
        }

        .border-bottom-header {
            border-bottom: 1px solid;
        }

        .border-all,
        .border-all th,
        .border-all td {
            border: 1px solid;
        }
    </style>
</head>
<body>
    <div class="document">
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center mb-1">
                <img src="{{ public_path('images/polinema-logo.png') }}" width="150" height="110">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN
                    PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI
                    MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang
                    65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-
                    105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

        <div class="content">
        <h3>BERITA ACARA KOMPENSASI PRESENSI</h3>
        <div class="field"><strong>Nama Pengajar    :</strong> {{ $data->pengajar_nama }}</div>
        <div class="field"><strong>NIP              :</strong> {{ $data->pengajar_nip }}</div>
        <p>Memberikan rekomendasi kepada:</p>
        <div class="field"><strong>Nama Mahasiswa   :</strong> {{ $data->mahasiswa_nama }}</div>
        <div class="field"><strong>NIM              :</strong> {{ $data->mahasiswa_nim }}</div>
        <div class="field"><strong>Kelas            :</strong> {{$data->mahasiswa_prodi}} {{ $data->mahasiswa_kelas }}</div>
        <div class="field"><strong>Pekerjaan        :</strong> {{ $data->tugas_nama }}</div>
        <div class="field"><strong>Jumlah Jam       :</strong> {{ $data->tugas_bobot }} Jam</div>

        <p>Malang, {{ $data->current_date }}</p>
    </div>

    <div class="footer">
        <p>Ka. Program Studi</p>
        <br><br>
        @if($data->mahasiswa_prodi == 'SIB')
        <p><strong>Hendra Pradibta, S.E., M.Sc.</strong></p>
        <p>NIP: 19835212000641003</p>
        @elseif($data->mahasiswa_prodi == 'TI')
        <p><strong>Ely Setyo Astuti, ST., MT., Dr.</strong></p>
        <p>NIP: 197605152009122001</p>
        @elseif($data->mahasiswa_prodi == 'PPLS')
        <p><strong>Pramana Yoga Saputra, S.Kom., MMT.</strong></p>
        <p>NIP: 198805042015041001</p>
        @endif
    </div>
    
</body>
</html>
