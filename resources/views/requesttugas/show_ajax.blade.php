@empty($requests)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data request yang anda cari tidak ditemukan
            </div>
            <a href="{{ url('/requesttugas') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/requesttugas/' . $requests->request_id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Request Tugas Kompen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Nama Tugas :</th>
                        <td class="col-9">{{ $requests->requests_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Deskripsi :</th>
                        <td class="col-9">{{ $requests->requests_desc }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenis requests :</th>
                        <td class="col-9">{{ $requests->jenis->jenis_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bobot :</th>
                        <td class="col-9">{{ $requests->requests_bobot }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kuota :</th>
                        <td class="col-9">{{ $requests->kuota }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Status :</th>
                        <td class="col-9">
                            @switch($requests->requests_status)
                                @case('O')
                                    <span class="badge badge-danger">Open</span>
                                    @break
                                @case('W')
                                    <span class="badge badge-warning">Working</span>
                                    @break
                                @case('S')
                                    <span class="badge badge-primary">Submitted</span>
                                    @break
                                @case('D')
                                    <span class="badge badge-success">Done</span>
                                    @break
                                @default
                                    <span class="badge badge-secondary">Unknown</span>
                            @endswitch
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="text-right col-3">Tanggal Dibuat :</th>
                        <td class="col-9">{{ \Carbon\Carbon::parse($requests->requests_tgl_dibuat)->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Deadline :</th>
                        <td class="col-9">{{ \Carbon\Carbon::parse($requests->requests_tgl_deadline)->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Pembuat :</th>
                        <td class="col-9">{{ $requests->user->nama_pembuat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">File requests :</th>
                            <td class="col-9">
                                @if ($requests->requests_file)
                                    <a href="{{ url('storage/' . $requests->requests_file) }}" target="_blank">
                                        {{ basename($requests->requests_file) }}
                                    </a>
                                @else
                                    Tidak ada file
                                @endif
                            </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function() {
    $("#form-delete").validate({
        rules: {},
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        datarequestsKompen.ajax.reload();
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endempty
