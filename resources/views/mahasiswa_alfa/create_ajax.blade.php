<form action="{{ url('/mahasiswa_alfa/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Alfa Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Mahasiswa</label>
                    <select name="mahasiswa_id" id="mahasiswa_id" class="form-control" required>
                        <option value="">- Pilih Mahasiswa -</option>
                        @foreach($mahasiswa as $l)
                            <option value="{{ $l->mahasiswa_id }}">{{ $l->mahasiswa_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-mahasiswa_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Periode</label>
                    <select name="periode_id" id="periode_id" class="form-control" required>
                        <option value="">- Pilih Periode -</option>
                        @foreach($periode as $l)
                            <option value="{{ $l->periode_id }}">{{ $l->periode }}</option>
                        @endforeach
                    </select>
                    <small id="error-periode_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jumlah Alfa</label>
                    <input value="0" type="text" name="jumlah_alfa" id="jumlah_alfa" class="form-control" required>
                    <small id="error-jumlah_alfa" class="error-text form-text text-danger"></small>
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
                
                mahasiswa_id: { required: true },
                periode_id: { required: true },
                jumlah_alfa: {required: true, digits:true}

                
            },
            messages : {
                mahasiswa_id : {
                    required: "Kolom ini harus diisi.",
                },
                periode_id: {
                    required: "NIM harus diisi.",
                },
                jumlah_alfa: {
                    required: "Kelas harus diisi.",
                    digits: "Kolom ini hanya menerima angka (0-9)"
                },
                
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
                            dataMahasiswaAlfa.ajax.reload();
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
