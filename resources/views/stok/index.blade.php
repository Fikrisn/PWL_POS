@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('stok/create') }}">Tambah</a>
                
            </div>
        </div>
        <div class="card-body">
            @if (@session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter </label>
                        <div class="col-3">
                            <select class="form-control" id="supplier_id" name="supplier_id" required>
                                <option value="">- Semua -</option>
                                @foreach ($supplier as $item)
                                    <option value="{{ $item->supplier_id }}">{{ $item->supplier_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Supplier Barang</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama supplier</th>
                        <th>Nama Barang</th>
                        <th>Nama Pekerja</th>
                        <th>Tanggal stok </th>
                        <th>Jumlah stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
    <script>
        $(document).ready(function() {
            var dataUser = $('#table_user').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('stok/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.supplier_id = $('#supplier_id').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "supplier.supplier_nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "barang.barang_nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "user.nama",
                        className: "",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "stok_tanggal",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "stok_jumlah",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]

            });
            $('#supplier_id').on('change', function() {
                dataUser.ajax.reload();
            })
        });
    </script>
@endpush