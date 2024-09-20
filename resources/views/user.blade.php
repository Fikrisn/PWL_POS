<!DOCTYPE html>
<html>
    <head>
        <title>
            Data User
        </title>
    </head>
    <body>
        <h1>
            Data User
        </h1>
        <a href="{{url('/user/tambah')}}"> Tambah User</a>
        <table border="1" cellpadding="2" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nama</th>
                <th>ID Level Pengguna</th>
                <th>Kode Level</th>
                <th>Nama Level</th>
                <th>Aksi</th>
            </tr>
            @foreach ($data as $d)
                <tr>
                    <td>{{ $d->user_id }}</td>
                    <td>{{ $d->username }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->level_id }}</td>
                    <td>{{ $d->level->level_kode }}</td>
                    <td>{{ $d->level->level_nama }}</td>
                    <td><a href="{{ url('/user/ubah/' . $d->user_id) }}">Ubah</a> | <a href="{{url('/user/hapus/' .$d->user_id)}}">Hapus</a></td>
                </tr>
            @endforeach
        </table>
    </body>
</html>

{{-- praktikum 2.3 --}}

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User</title>
    <style>
        .data-container {
            border: 1px solid blue;
            width: 200px;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <div class="data-container">
        <h1>Data User</h1>
        <table>
            <tr>
                <th>Jumlah Pengguna</th>
            </tr>
            <tr>
                <td>{{ $data }}</td>
            </tr>
        </table>
    </div>
</body>
</html> --}}
