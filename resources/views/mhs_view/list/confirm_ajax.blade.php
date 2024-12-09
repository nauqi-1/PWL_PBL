@empty($tugas)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5> Data yang Anda cari tidak ditemukan
            </div>
            <a href="{{ url('/mhs_listtugas') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/mhs_listtugas/' . $tugas->tugas_id . '/request_ajax') }}" method="POST" id="form-request-tugas">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Request Tugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5> Apakah Anda ingin melakukan request pada tugas ini?
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Judul Tugas:</th>
                        <td class="col-9">{{ $tugas->tugas_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bobot Jam:</th>
                        <td class="col-9">{{ $tugas->tugas_bobot }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kuota Mahasiswa:</th>
                        <td class="col-9">{{ $tugas->kuota }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Deskripsi:</th>
                        <td class="col-9">{{ $tugas->tugas_desc }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Ya, Request</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-request-tugas").on("submit", function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr("action"),
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        tableTugasKompen.ajax.reload(); // Refresh DataTable
                        tableStatusRequest.ajax.reload(); // Refresh DataTable
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
