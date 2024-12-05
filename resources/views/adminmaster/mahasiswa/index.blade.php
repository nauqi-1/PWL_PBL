@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/mahasiswa/import') }}')" class="btn btn-info">Import Data</button>
            <a href="{{ url('/mahasiswa/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
            <a href="{{ url('/mahasiswa/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF </a>
            <button onclick="modalAction('{{ url('/mahasiswa/create_ajax') }}')" class="btn btn-success"><i class="fa fa-plus"></i>Tambah</button>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
                <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="mahasiswa_kelas" name="mahasiswa_kelas">
                            <option value="">- Semua -</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas }}">{{ $kelas }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kelas</small>
                    </div>
                    <div class="col-3">
                        <select class="form-control" id="mahasiswa_prodi" name="mahasiswa_prodi">
                            <option value="">- Semua -</option>
                            @foreach($prodiList as $prodi)
                                <option value="{{ $prodi }}">{{ $prodi }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Prodi</small>
                    </div>
                </div>
            </div>
        </div>
        
        <table class="table table-bordered table-striped table-hover table-sm" id="table_mahasiswa">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Program Studi</th>
                    <th>Nomor HP</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

@endsection

@push('css')
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var dataMahasiswa;

    $(document).ready(function() {
        dataMahasiswa = $('#table_mahasiswa').DataTable({
            // serverSide: true, jika ingin menggunakan server side processing
            serverSide: true,
            ajax: {
                "url": "{{ url('mahasiswa/list') }}",
                "dataType": "json",
                "type": "POST",
                data: function(p) {
                    p.mahasiswa_prodi = $('#mahasiswa_prodi').val();
                    p.mahasiswa_kelas = $('#mahasiswa_kelas').val();
                } 
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "",
                    width: '5%',
                    orderable: false,
                    searchable: false
                },
                {
                    data: "mahasiswa_nim",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "mahasiswa_nama",
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "mahasiswa_kelas",
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "mahasiswa_prodi",
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "mahasiswa_noHp",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "aksi",
                    className: "",
                    width: '20%',
                    orderable: false,
                    searchable: false
                }
            ]
        });
        $('#mahasiswa_kelas').change(function() {
        dataMahasiswa.draw();
    
        });
        $('#mahasiswa_prodi').change(function() {
            dataMahasiswa.draw();

        });

    });
</script>
@endpush
