@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/admin/import') }}')" class="btn btn-info">Import Data</button>
            <a href="{{ url('/admin/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
            <a href="{{ url('/admin/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF </a>
            <button onclick="modalAction('{{ url('/admin/create_ajax') }}')" class="btn btn-success"><i class="fa fa-plus"></i>Tambah</button>
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
                        <select class="form-control" id="admin_prodi" name="admin_prodi">
                            <option value="">- Semua -</option>
                            @foreach($prodiList as $prodi)
                                <option value="{{ $prodi }}">{{ $prodi }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Program Studi</small>
                    </div>
                </div>
            </div>
        </div>
        
        <table class="table table-bordered table-striped table-hover table-sm" id="table_admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
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

    var dataAdmin;

    $(document).ready(function() {
        dataAdmin = $('#table_admin').DataTable({
            // serverSide: true, jika ingin menggunakan server side processing
            serverSide: true,
            ajax: {
                "url": "{{ url('admin/list') }}",
                "dataType": "json",
                "type": "POST",
                data: function(p) {
                    p.admin_prodi = $('#admin_prodi').val();
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
                    data: "admin_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "admin_prodi",
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "admin_noHp",
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
        $('#admin_prodi').change(function() {
            dataAdmin.draw();

        });

    });
</script>
@endpush
