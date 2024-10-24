@empty($penjualan)
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
                    <h5><i class="icon fas fa-ban"></i>Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Transaki Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Data Transaksi Penjualan</h5>
                    Berikut adalah detail dari data transaksi penjualan
                </div>
                <table class="table table-sm table-bordered table-stripped">
                    <tr>
                        <th class="text-right col-3">Nama Kasir : </th>
                        <td class="col-9">{{ $penjualan->user->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Pembeli : </th>
                        <td class="col-9">{{ $penjualan->pembeli }} </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kode Transaksi : </th>
                        <td class="col-9">{{ $penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Transaksi : </th>
                        <td class="col-9">{{ $penjualan->penjualan_tanggal }}</td>
                    </tr>
                </table>
                <table class="table table-sm table-bordered table-stripped">
                    <tr>
                        <th class="text-center">Nama Barang </th>
                        <th class="text-center">Harga Barang </th>
                        <th class="text-center">Jumlah Barang </th>
                    </tr>
                    @foreach ($penjualan_detail as $item)
                    <tr>
                        <td class="text-center">{{ $item->barang->barang_nama }} </td>
                        <td class="text-center">Rp {{ number_format($item->harga, 0, ',','.') }} </td>
                        <td class="text-center">{{ $item->jumlah }} </td>
                    </tr>
                    @endforeach
                    <tr>
                        <th class="text-center"> Total Harga </th>
                        <td class="text-center" colspan="2" >Rp {{ number_format($total_harga, 0,',','.') }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@endempty