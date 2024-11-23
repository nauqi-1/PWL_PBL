<form action="{{ url('/tugaskompen/store_ajax') }}" method="POST" id="form-tambah-tugas">
    @csrf
    <div id="modal-tugas" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Tugas Kompensasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nama Tugas -->
                <div class="form-group">
                    <label>Nama Tugas</label>
                    <input type="text" name="tugas_nama" id="tugas_nama" class="form-control" required>
                    <small id="error-tugas_nama" class="error-text form-text text-danger"></small>
                </div>
                <!-- Pembuat -->
                <div class="form-group">
                    <label>Pembuat</label>
                    <select name="tugas_pembuat_id" id="tugas_pembuat_id" class="form-control" required>
                        <option value="">-- Pilih Pembuat --</option>
                    </select>
                    <small id="error-tugas_pembuat_id" class="error-text form-text text-danger"></small>
                </div>
                <!-- Deskripsi -->
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="tugas_desc" id="tugas_desc" class="form-control" rows="3" required></textarea>
                    <small id="error-tugas_desc" class="error-text form-text text-danger"></small>
                </div>
                <!-- Kuota -->
                <div class="form-group">
                    <label>Kuota</label>
                    <input type="number" name="kuota" id="kuota" class="form-control" required>
                    <small id="error-kuota" class="error-text form-text text-danger"></small>
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
    $(document).ready(function () {
        // Inisialisasi Choices.js
        const pembuatData = @json($pembuat);
        const pembuatSelect = new Choices('#tugas_pembuat_id', {
            removeItemButton: true,
            searchEnabled: true,
            placeholderValue: '-- Pilih Pembuat --',
            shouldSort: false
        });

        // Masukkan data pembuat ke dalam Choices
        pembuatSelect.setChoices(
            pembuatData.map(pembuat => ({
                value: pembuat.id,
                label: pembuat.nama
            })),
            'value',
            'label',
            true
        );

        // Validasi dan Submit Form
        $("#form-tambah-tugas").validate({
            rules: {
                tugas_nama: { required: true, maxlength: 255 },
                tugas_desc: { required: true },
                kuota: { required: true, min: 1 },
                tugas_pembuat_id: { required: true }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    beforeSend: function () {
                        $('.error-text').text(''); // Bersihkan error sebelumnya
                    },
                    success: function (response) {
                        if (response.status) {
                            $('#modal-tugas').modal('hide'); // Tutup modal
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataTugas.ajax.reload(); // Refresh DataTable
                        } else {
                            // Tampilkan error validasi dari server
                            if (response.msgField) {
                                $.each(response.msgField, function (prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyimpan data.'
                        });
                    }
                });
                return false; // Jangan submit form secara default
            }
        });
    });
</script>
