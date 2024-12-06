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
        {{-- <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-labe; col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="level_id" name="level_id" required>
                            <option value="">- Semua -</option>
                            @foreach ($level as $item)
                            @if ($item->level_nama != 'Mahasiswa') 
                                <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                            @endif
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Level Pembuat</small>
                    </div>
                </div>
            </div>
        </div> --}}
        <table class="table table-bordered table-striped table-hover table-sm" id="table_requesttugas">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tugas</th>
                    <th>Pembuat</th>
                    <th>Mahasiswa</th>
                    <th>Tgl.Request</th>
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

    var dataRequest;

    $(document).ready(function() {
        dataRequest = $('#table_requesttugas').DataTable({
    serverSide: true,
    ajax: {
        url: "{{ url('requesttugas/list') }}",
        dataType: "json",
        type: "POST",
        // data: function(d) {
        //     d.level_id = $('#level_id').val();
        // }
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
            data: "tugas.tugas_nama",
            className: "nama-tugas",
            orderable: true,
            searchable: true
        },
        {
            data: "pembuat",
            className: "",
            orderable: false,
            searchable: false
        },
        {
            data: "mahasiswa.mahasiswa_nama",
            className: "",
            orderable: false,
            searchable: false
        },
        {
            data: "tgl_request",
            className: "",
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                return moment(data).format('DD MMMM YYYY HH:mm');
            }
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
    dataRequest.ajax.reload();
});

        });
</script>
@endpush