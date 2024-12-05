@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/periode/import') }}')" class="btn btn-info">Import Periode</button>
            <a href="{{ url('/periode/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
            <a href="{{ url('/periode/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF </a>
            <button onclick="modalAction('{{ url('/periode/create_ajax') }}')" class="btn btn-success"><i class="fa fa-plus"></i> Tambah</button>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_periode">
            <thead>
                <tr>
                    <th>ID</th>
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

    var dataPeriode;

    $(document).ready(function() {
        dataPeriode = $('#table_periode').DataTable({
            // serverSide: true, jika ingin menggunakan server side processing
            serverSide: true,
            ajax: {
                "url": "{{ url('periode/list') }}",
                "dataType": "json",
                "type": "POST",
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
                    data: "periode",
                    className: "",
                    orderable: true,
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
    });
</script>
@endpush
