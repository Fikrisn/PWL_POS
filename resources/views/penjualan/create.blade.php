@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            {{$page->title}}
        </h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <form action="{{ url('penjualan') }}" method="POST" class="form-horizontal" id="penjualanForm">
            @csrf
            <div class="form-group">
                <label class="control-label">Kode Penjualan:</label>
                <input type="text" class="form-control" id="penjualan_kode" name="penjualan_kode">
                @error('penjualan_kode')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="control-label">Pembeli:</label>
                <input type="text" class="form-control" id="pembeli" name="pembeli">
                @error('pembeli')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="control-label">Tanggal:</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal">
                @error('tanggal')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="control-label">User:</label>
                <select class="form-control" id="user" name="user">
                    @foreach($users as $user)
                        <option value="{{ $user->user_id }}">{{ $user->nama }}</option>
                    @endforeach
                </select>
                @error('user')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="control-label">Barang:</label><br>
                <div class="row">
                    @foreach($barang as $barang)
                        <div class="col-md-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input barang-checkbox" type="checkbox" id="barang_{{ $barang->barang_id }}" name="barang[{{ $barang->barang_id }}][id]" value="{{ $barang->barang_id }}">
                                <label class="form-check-label" for="barang_{{ $barang->barang_id }}">
                                    {{ $barang->barang_nama }}
                                </label>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <input type="number" class="form-control barang-jumlah" id="barang_{{ $barang->barang_id }}_jumlah" name="barang[{{ $barang->barang_id }}][jumlah]" min="1" step="1" value="1" data-id="{{ $barang->barang_id }}">
                                <input type="hidden" class="form-control barang-harga" id="barang_{{ $barang->barang_id }}_harga" name="barang[{{ $barang->barang_id }}][harga]" value="{{ $barang->harga_jual }}">
                                <input type="text" class="form-control barang-subtotal" id="barang_{{ $barang->barang_id }}_subtotal" name="barang[{{ $barang->barang_id }}][subtotal]" readonly>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        
            <div class="form-group row">
                <label class="col-1 control-label col-form-label"></label>
                <div class="col-11">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <a class="btn btn-sm btn-default ml-1" href="{{ url('penjualan') }}">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        
        function calculateSubtotal(id) {
            var jumlahInput = $('#barang_' + id + '_jumlah');
            var hargaInput = $('#barang_' + id + '_harga');
            var subtotalField = $('#barang_' + id + '_subtotal');
            var jumlah = parseInt(jumlahInput.val()) || 0;
            var harga = parseFloat(hargaInput.val()) || 0;
            var subtotal = jumlah * harga;
            subtotalField.val(subtotal.toFixed(2)); 
            calculateTotal(); 
        }

        function calculateTotal() {
            var total = 0;
            $('.barang-checkbox:checked').each(function() {
                var id = $(this).val();
                var jumlah = parseInt($('#barang_' + id + '_jumlah').val()) || 0;
                var harga = parseFloat($('#barang_' + id + '_harga').val()) || 0;
                total += jumlah * harga;
            });
            $('#total_harga').val(total.toFixed(2)); 
        }

        // $('.btn-tambah').click(function() {
        //     var id = $(this).data('id');
        //     var jumlahInput = $('#barang_' + id + '_jumlah');
        //     var currentValue = parseInt(jumlahInput.val()) || 0;
        //     jumlahInput.val(currentValue + 1);
        //     calculateSubtotal(id); 
        // });

        // $('.btn-kurang').click(function() {
        //     var id = $(this).data('id');
        //     var jumlahInput = $('#barang_' + id + '_jumlah');
        //     var currentValue = parseInt(jumlahInput.val()) || 0;
        //     if (currentValue > 1) {
        //         jumlahInput.val(currentValue - 1);
        //         calculateSubtotal(id); 
        //     }
        // });

        $('.barang-checkbox').change(function() {
            var id = $(this).val();
            calculateSubtotal(id); 
        });

        $('#penjualanForm').submit(function(event) {
            var checkboxes = $('.barang-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Pilih setidaknya satu barang sebelum menyimpan!');
                event.preventDefault(); 
            }
        });

        calculateTotal();
    });
</script>
@endsection