@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        @if(Auth::user()->level->level_kode != 'MHS')
        <div class="card-tools">
            <button onclick="modalAction('{{ url('tugaskompen/import') }}')" class="btn btn-info">Import Tugas</button>
            <a href="{{ url('/tugaskompen/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
            <a href="{{ url('/tugaskompen/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF </a>
            <button onclick="modalAction('{{ url('/tugaskompen/create_ajax') }}')" class="btn btn-success"><i class="fa fa-plus"></i>Tambah</button>
        </div>
        @endif
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
                    <label class="col-1 control-labe; col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="level_id" name="level_id" required>
                            <option value="">- Semua -</option>
                            @foreach ($level as $item)
                            @if ($item->level_nama != 'Mahasiswa') {{-- Kondisi pengeculian --}}
                                <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                            @endif
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Level Pembuat</small>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="table_tugaskompen">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Bobot</th>
                    <th>Kuota</th>
                    <th>Status</th>
                    <th>Tgl.dibuat</th>
                    <th>Tgl.ditutup</th>
                    <th>Pembuat</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var dataTugasKompen;

    $(document).ready(function() {
        dataTugasKompen = $('#table_tugaskompen').DataTable({
    serverSide: true,
    ajax: {
        url: "{{ url('tugaskompen/list') }}",
        dataType: "json",
        type: "POST",
        data: function(d) {
            d.level_id = $('#level_id').val();
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
            data: "tugas_nama",
            className: "nama-tugas",
            orderable: true,
            searchable: true
        },
        {
            data: "jenis",
            className: "",
            orderable: false,
            searchable: false
        },
        {
            data: "tugas_bobot",
            className: "",
            orderable: false,
            searchable: false
        },
        {
            data: "kuota",
            className: "",
            orderable: false,
            searchable: false
        },
        {
            data: "tugas_status",
            className: "",
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                switch (data) {
                    case 'O':
                        return '<span class="badge badge-danger">Open</span>';
                    case 'W':
                        return '<span class="badge badge-warning">Working</span>';
                    case 'S':
                        return '<span class="badge badge-primary">Submitted</span>';
                    case 'D':
                        return '<span class="badge badge-success">Done</span>';
                    default:
                        return '<span class="badge badge-secondary">Unknown</span>';
                }
            }
        },
        {
            data: "tugas_tgl_dibuat",
            className: "",
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                return moment(data).format('DD MMMM YYYY HH:mm');
            }
        },
        {
            data: "tugas_tgl_deadline",
            className: "",
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                return moment(data).format('DD MMMM YYYY HH:mm');
            }
        },
        {
            data: "pembuat",
            className: "",
            orderable: false,
            searchable: false
        },
        {
            data: "aksi",
            className: "",
            orderable: false,
            searchable: false,
            width: '160px'
        }
    ]
});

// Reload the DataTable when the filter is changed
$('#level_id').on('change', function() {
    dataTugasKompen.ajax.reload();
});

        });
</script>
@endpush