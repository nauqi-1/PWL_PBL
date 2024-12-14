@empty($tugass)
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
            <a href="{{ url('/pengumpulan_tugas') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/pengumpulan_tugas/' . $tugass->tugas_mahasiswa_id . '/denied_ajax') }}" method="PUT" id="form-denied-tugas">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Tolak Tugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi!!!</h5>
                    Apakah Anda ingin menolak tugas ini?
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Judul Tugas:</th>
                        <td class="col-9">{{ $tugass->tugas->tugas_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Mahasiswa:</th>
                        <td class="col-9">{{ $tugass->mahasiswa->mahasiswa_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tenggat:</th>
                        <td class="col-9">{{ $tugass->tugas->tugas_tgl_deadline }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Dikumpulkan:</th>
                        <td class="col-9">{{ $tugass->tanggal_disubmit }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Ya, Tolak</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-denied-tugas").on("submit", function(event) {
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
                        dataPengumpulan.ajax.reload(); // Refresh DataTable
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
