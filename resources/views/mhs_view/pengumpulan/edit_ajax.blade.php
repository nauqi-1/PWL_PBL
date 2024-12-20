@empty($tugas)
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
                <a href="{{ url('/mhs_kumpultugas') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/mhs_kumpultugas/' . $tugas->tugas_mahasiswa_id . '/update_progress') }}" method="POST" id="form-edit-tugas">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Progress Tugas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">Nama Tugas :</th>
                            <td class="col-9">{{ $tugas->tugas->tugas_nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Deskripsi :</th>
                            <td class="col-9">{{ $tugas->tugas->tugas_desc }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Progres Tugas :</th>
                            <td class="col-9">
                                <input type="number" min="0" max="100" value="{{ $tugas->progress }}" class="form-control" name="progress" id="progressInput" />
                                <span id="progressValue">{{ $tugas->progress }}%</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Deskripsi Progres :</th>
                                <td class="col-9">
                                    <textarea 
                                        class="form-control" 
                                        name="progress_deskripsi" 
                                        id="progressDeskripsiInput" 
                                        rows="3" 
                                        placeholder="Masukkan deskripsi progres">{{ $tugas->progress_deskripsi }}</textarea>
                                </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-edit-tugas").validate({
                rules: {
                    progres: { 
                        required: true, 
                        min: 0, 
                        max: 100 
                    },
                    progress_deskripsi: { required: true, maxlength: 255 }

                },
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
                                tablePengumpulan.ajax.reload(); // Reload data table jika digunakan
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
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan pada server!'
                            });
                        }
                    });
                    return false; 
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
