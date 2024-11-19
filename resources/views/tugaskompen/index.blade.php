@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a href="{{ url('/tugaskompen/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
            <a href="{{ url('/tugaskompen/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_tugaskompen">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Bobot</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Tgl dibuat</th>
                    <th>Deadline</th>
                    <th>Pembuat</th>
                    <th>Progres</th>
                    <th>Jenis</th>
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

    var dataTugasKompen;

    $(document).ready(function() {
        dataTugasKompen = $('#table_tugaskompen').DataTable({
            // serverSide: true, jika ingin menggunakan server side processing
            serverSide: true,
            ajax: {
                "url": "{{ url('tugaskompen/list') }}",
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
                    data: "tugas_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "tugas_desc",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "tugas_bobot",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "tugas_file",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "tugas_status",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "tugas_tgl_dibuat",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "tugas_tgl_deadline",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "pembuat",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "tugas_progress",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "tugas_jenis",
                    className: "",
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
</script>
@endpush
