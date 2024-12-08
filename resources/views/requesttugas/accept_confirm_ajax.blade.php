{{-- resources/views/requesttugas/accept_confirm_ajax.blade.php --}}
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
                Data yang Anda cari tidak ditemukan
            </div>
            <a href="{{ url('/mhs_listtugas') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/requesttugas/' . $requests->id_request . '/accept_ajax') }}" method="PUT" id="form-accept-request">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Request Tugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi!!!</h5>
                    Apakah Anda ingin menerima request tugas ini?
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Judul Tugas:</th>
                        <td class="col-9">{{ $requests->tugas->tugas_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Mahasiswa:</th>
                        <td class="col-9">{{ $requests->mahasiswa->mahasiswa_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIM:</th>
                        <td class="col-9">{{ $requests->mahasiswa->mahasiswa_nim }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Deskripsi Tugas:</th>
                        <td class="col-9">{{ $requests->tugas->tugas_desc }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Ya, Terima</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-accept-request").on("submit", function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr("action"),
                type: "PUT",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataRequest.ajax.reload(); // Refresh DataTable
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                }
            });
        });
    });
</script>
@endempty
