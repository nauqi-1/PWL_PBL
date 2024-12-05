<form action="{{ url('/tendik/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Tendik</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="form-group">
                    <label>NIP</label>
                    <input value="" type="text" name="tendik_nip" id="tendik_nip" class="form-control" required>
                    <small id="error-tendik_nip" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input value="" type="text" name="tendik_nama" id="tendik_nama" class="form-control" required>
                    <small id="error-tendik_nama" class="error-text form-text text-danger"></small>
                </div>
                <!--div class="form-group ">
                    <label>Program Studi</label>
                    <input value="" type="text" name="tendik_prodi" id="tendik_prodi" class="form-control" required>
                    <small id="error-tendik_prodi" class="error-text form-text text-danger"></small>
                </div-->
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input value="" type="text" name="tendik_noHp" id="tendik_noHp" class="form-control" required>
                    <small id="error-tendik_noHp" class="error-text form-text text-danger"></small>
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
                
                tendik_nama: { required: true, maxlength: 100 },
                tendik_nip: { required: true, maxlength: 50, digits:true },
                //tendik_prodi: { required: true, minlength: 2, maxlength: 10 },
                tendik_noHp: { required: true, maxlength: 20, digits:true },
                username: {required: true, maxlength: 100 },
                password: {required: true, minlength: 6, maxlength: 100 },

                
            },
            messages: 
            {tendik_nama : {
                    required: "Nama harus diisi.",
                    maxlength: "Data yang diisi tidak melebihi 100 karakter."
                },
                tendik_nip: {
                    required: "NIP harus diisi.",
                    maxlength: "NIP maksimal 50 karakter.",
                    digits: "NIM harus berupa angka saja (0-9)."
                },
                tendik_noHp: {
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
                            dataTendik.ajax.reload();
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
