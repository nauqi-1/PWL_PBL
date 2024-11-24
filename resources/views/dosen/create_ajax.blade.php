<form action="{{ url('/dosen/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="form-group">
                    <label>NIP</label>
                    <input value="" type="text" name="dosen_nip" id="dosen_nip" class="form-control" required>
                    <small id="error-dosen_nip" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input value="" type="text" name="dosen_nama" id="dosen_nama" class="form-control" required>
                    <small id="error-dosen_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group ">
                    <label>Program Studi</label>
                    <input value="" type="text" name="dosen_prodi" id="dosen_prodi" class="form-control" required>
                    <small id="error-dosen_prodi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input value="" type="text" name="dosen_noHp" id="dosen_noHp" class="form-control" required>
                    <small id="error-dosen_noHp" class="error-text form-text text-danger"></small>
                </div>
                <hr>
                <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Username</label>
                    <input value="" type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group col-md-6">
                    <label>Password</label>
                    <input value="" type="password" name="password" id="password" class="form-control" required>
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>
                </div>
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
        $("#form-tambah").validate({
            rules: {
                
                dosen_nama: { required: true, maxlength: 100 },
                dosen_nip: { required: true, maxlength: 50, digits:true },
                dosen_prodi: { required: true, minlength: 2, maxlength: 10 },
                dosen_noHp: { required: true, minlength: 10, maxlength: 13, digits:true },
                username: {required: true, maxlength: 100 },
                password: {required: true, minlength: 6, maxlength: 100 },

                
            },
            messages: 
            {dosen_nama : {
                    required: "Nama harus diisi.",
                    maxlength: "Data yang diisi tidak melebihi 100 karakter."
                },
                dosen_nip: {
                    required: "NIP harus diisi.",
                    maxlength: "NIP maksimal 50 karakter.",
                    digits: "NIM harus berupa angka saja (0-9)."
                },
                dosen_prodi: {
                    required: "Program studi harus diisi.",
                    minlength: "Program studi minimal 2 karakter.",
                    maxlength: "Program studi maksimal 10 karakter."
                },
                dosen_noHp: {
                    required: "Nomor HP harus diisi.",
                    minlength: "Nomor HP minimal 10 karakter.",
                    maxlength: "Nomor HP maksimal 13 karakter.",
                    digits: "Nomor HP harus berupa angka saja."
                },
                
                username: {
                    required: "Username harus diisi.",
                    maxlength: "Username maksimal 100 karakter."
                },
                password: {
                    required: "Password harus diisi.",
                    minlength: "Password minimal 6 karakter.",
                    maxlength: "Password maksimal 100 karakter."
                }},
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
                            dataDosen.ajax.reload();
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
