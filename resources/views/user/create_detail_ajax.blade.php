<form action="{{ url('/user/store_detail_ajax') }}" method="POST" id="form-tambah">
    @csrf
    <input type="hidden" name="user_id" value="{{ session('user_id') }}">
    <input type="hidden" name="level_id" value="{{ session('level_id') }}">

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(session('level_id') == 1) <!--ADMIN-->                
                <div class="form-group">
                    <label>Nama</label>
                    <input value="" type="text" name="admin_nama" id="admin_nama" class="form-control" required>
                    <small id="error-admin_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Program Studi</label>
                    <input value="" type="text" name="admin_prodi" id="admin_prodi" class="form-control" required>
                    <small id="error-admin_prodi" class="error-text form-text text-danger"></small>
                </div>
                @elseif(session('level_id') == 2) <!--DOSEN-->
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
                <div class="form-group">
                    <label>Program Studi</label>
                    <input value="" type="text" name="dosen_prodi" id="dosen_prodi" class="form-control" required>
                    <small id="error-dosen_prodi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nomor Handphone</label>
                    <input value="" type="text" name="dosen_noHp" id="dosen_noHp" class="form-control" required>
                    <small id="error-dosen_noHp" class="error-text form-text text-danger"></small>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Lanjut</button>
            </div>
        </div>
    </div>
</form>

<script>
    
    $(document).ready(function() {
    $("#form-tambah").validate({
        rules: {
            // Add any validation rules here
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        // Hide the current modal if it's open
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataUser.ajax.reload();

                        // Inject modal content into the page (if necessary)
                        $('#modal-container').html(response.html);  // Assuming modal HTML is returned

                        $('#modal-master').modal('show');
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
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Ada masalah dengan permintaan Anda.'
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
