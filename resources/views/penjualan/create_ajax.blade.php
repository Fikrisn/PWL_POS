<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Tambah Transaksi Penjualan </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Kasir</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">- Pilih Kasir -</option>
                        @foreach($user as $l)
                            <option value="{{ $l->user_id }}">{{ $l->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Pembeli</label>
                    <input value="" type="text" name="pembeli" id="pembeli" class="form-control" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kode Transaksi</label>
                    <input value="" type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" required>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Transaksi</label>
                    <input value="" type="datetime-local" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control" required>
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                </div>
                <div id="barang-container">
                    <div class="barang-item">
                        <div class="form-group">
                            <label>Pilih Barang</label>
                            <select name="barang_id[]" class="form-control barang-select" required>
                                <option value="">- Pilih Barang -</option>
                                @foreach($barang as $b)
                                    <option value="{{ $b->barang_id }}">{{ $b->barang_nama }}</option>
                                @endforeach
                            </select>
                            <small class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group jumlah-input" style="display: none;">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah[]" class="form-control" min="1" required>
                            <small class="error-text form-text text-danger"></small>
                        </div>
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
        function tambahInputBarang() {
            let newItem = $('.barang-item:first').clone();
            newItem.find('select').val('');
            newItem.find('.jumlah-input').hide();
            newItem.find('input[type="number"]').val('');
            $('#barang-container').append(newItem);
        }

        $(document).on('change', '.barang-select', function() {
            let jumlahInput = $(this).closest('.barang-item').find('.jumlah-input');

            if ($(this).val()) {
                jumlahInput.show();

                // Cek apakah ini adalah input terakhir
                if ($(this).closest('.barang-item').is(':last-child')) {
                    tambahInputBarang();
                }
            } else {
                jumlahInput.hide();
            }
        });
        $("#form-tambah").validate({
            rules: {
                user_id: { required: true, number: true},
                pembeli: { required: true, minlength: 3, maxlength: 255},
                penjualan_kode: { required: true, minlength: 3, maxlength: 255},
                penjualan_tanggal: { required: true}
            },
            submitHandler: function(form) {
                let formData = $(form).serializeArray();
                let filteredData = formData.filter(item => {
                    return !(item.name === 'barang_id[]' && item.value === '') && !(item.name === 'jumlah[]' && item.value === '');
                });
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
                            dataPenjualan.ajax.reload();
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