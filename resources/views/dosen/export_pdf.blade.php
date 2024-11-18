<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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

        td, th {
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

        .border-all, .border-all th, .border-all td {
            border: 1px solid;
        }
        .bg-success {
            background-color: lightgreen;
            color: black;
        }

        .bg-danger {
            background-color: red;
            color: black;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center mb-1">
                <img src="{{ public_path('images/polinema-logo.png') }}" width="150" height="110">
            </td>
            
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">
                    KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI
                </span>
                <span class="text-center d-block font-13 font-bold mb-1">
                    POLITEKNIK NEGERI MALANG
                </span>
                <span class="text-center d-block font-10">
                    Jl. Soekarno-Hatta No. 9 Malang 65141
                </span>
                <span class="text-center d-block font-10">
                    Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420
                </span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h3 class="text-center">LAPORAN DATA MAHASISWA</h3>

    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Username</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Program Studi</th>
                <th>Nomor Handphone</th>
                <th>Jumlah Jam Alfa Terbayar</th>
                <th>Jumlah Jam Alfa Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswa as $m)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $m->user->username ?? '-' }}</td>
                <td>{{ $m->mahasiswa_nim }}</td>
                <td>{{ $m->mahasiswa_nama }}</td>
                <td>{{ $m->mahasiswa_kelas }}</td>
                <td>{{ $m->mahasiswa_prodi }}</td>
                <td>{{ $m->mahasiswa_noHp }}</td>
                <td>{{ $m->mahasiswa_alfa_sisa }}</td>
                <td>{{ $m->mahasiswa_alfa_total }}</td>
                <td class="{{ $m->mahasiswa_alfa_sisa < $m->mahasiswa_alfa_total ? 'bg-danger text-black' : 'bg-success text-black' }}">
                    @if ($m->mahasiswa_alfa_sisa < $m->mahasiswa_alfa_total) 
                        BELUM LUNAS
                    @else 
                        LUNAS
                    @endif
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
