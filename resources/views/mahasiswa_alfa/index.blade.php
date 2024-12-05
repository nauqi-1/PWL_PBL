@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/mahasiswa_alfa/import') }}')" class="btn btn-info">Import Data</button>
            <a href="{{ url('/mahasiswa_alfa/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
            <a href="{{ url('/mahasiswa_alfa/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF </a>
            <button onclick="modalAction('{{ url('/mahasiswa_alfa/create_ajax') }}')" class="btn btn-success"><i class="fa fa-plus"></i>Tambah</button>
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
                        <select class="form-control" id="mahasiswa_id" name="mahasiswa_id">
                            <option value="">- Semua -</option>
                            @foreach($mahasiswa as $item)
                                <option value="{{ $item->mahasiswa_id }}">{{ $item->mahasiswa_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Mahasiswa</small>
                    </div>
                    <div class="col-3">
                        <select class="form-control" id="periode_id" name="periode_id">
                        <option value="">- Semua -</option>
                            @foreach($periode as $item)
                                <option value="{{ $item->periode_id }}">{{ $item->periode }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Periode</small>
                    </div>
                </div>
            </div>
        </div>
        
        <table class="table table-bordered table-striped table-hover table-sm" id="table_mahasiswa_alfa">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Mahasiswa</th>
                    <th>Jumlah Jam Alfa</th>
                    <th>Periode</th>
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
        dataMahasiswaAlfa = $('#table_mahasiswa_alfa').DataTable({
            // serverSide: true, jika ingin menggunakan server side processing
            serverSide: true,
            ajax: {
                "url": "{{ url('mahasiswa_alfa/list') }}",
                "dataType": "json",
                "type": "POST",
                data: function(p) {
                    p.mahasiswa_id = $('#mahasiswa_id').val();
                    p.periode_id = $('#periode_id').val();
                } 
            },
            language: {
                emptyTable: "Data tidak ditemukan."
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
                    data: "mahasiswa.mahasiswa_nama",
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "jumlah_alfa",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "periode.periode",
                    className: "",
                    orderable: false,
                    searchable: true
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

        $('#periode_id').on('change',function() {
            dataMahasiswaAlfa.ajax.reload();
        });
        $('#mahasiswa_id').on('change',function() {
            dataMahasiswaAlfa.ajax.reload();
        })


    });
</script>
@endpush
