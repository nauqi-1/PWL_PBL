@empty($tugasMahasiswa)
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
    <form action="{{ url('/mhs_kumpultugas/' . $tugasMahasiswa->tugas_mahasiswa_id . '/submit_ajax') }}" method="POST" id="form-submit-tugas"enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"id="exampleModalLabel">Detail Tugas Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Nama Tugas:</label>
                            <p>{{ $tugasMahasiswa->tugas->tugas_nama }}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Dibuat:</label>
                            <p>{{ $tugasMahasiswa->tugas->tugas_tgl_dibuat }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Jenis Tugas:</label>
                            <p>{{ $tugasMahasiswa->tugas->jenis->jenis_nama }}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Ditutup:</label>
                            <p>{{ $tugasMahasiswa->tugas->tugas_tgl_deadline }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jawaban File:</label>
                        <input type="file" name="file_path" id="file_path" class="form-control">
                        <small id="error-file_path" class="error-text form-text text-danger"></small>

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
            $("#form-submit-tugas").validate({
                rules: {
                    file_path: {
                        required: false,
                        extension: "pdf|doc|docx|xls|xlsx|ppt|pptx|jpg|jpeg|png|gif|mp4|avi|mkv|txt|zip"
                    }
                },
                messages: {
                    file_path: {
                        extension: "File harus memiliki format yang valid (PDF, DOCX, JPG, PNG, dll)."
                    }
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
                                tableTugas.ajax.reload(); // Reload data table jika digunakan
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
