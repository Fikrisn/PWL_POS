<?php

namespace App\Http\Controllers;

use App\Models\barangmodel;
use App\Models\stokmodel;
use App\Models\suppliermodel;
use App\Models\usermodel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class stokcontroller extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Stok',
            'list'=>['Home','stok']
        ];
        $page = (object)[
            'title' => 'Daftar stok yang terdaftar dalam sistem'
        ];
        $activeMenu = 'stok';
        $supplier = suppliermodel::all();
        $barang = barangmodel::all();
        $user = usermodel::all();
        return view('stok.index',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'supplier'=>$supplier,'barang'=>$barang,'user'=>$user]);
    }

    public function list(Request $request){
        $stok = stokmodel::select('stok_id','supplier_id','barang_id','user_id','stok_tanggal','stok_jumlah')
        ->with(['supplier','barang','user']);

        if($request->supplier_id){
            $stok->where('supplier_id',$request->supplier_id);
        }elseif($request->barang_id){
            $stok->where('barang_id',$request->barang_id);
        }elseif($request->user_id){
            $stok->where('user_id',$request->user_id);
        }

        return DataTables::of($stok)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/stok/' . $stok->stok_id) . '" class="btn btn-info btnsm">Detail</a> ';
                $btn .= '<a href="' . url('/stok/' . $stok->stok_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/stok/' . $stok->stok_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create(){
        $breadcrumb = (object)[
            'title'=>'Tambah barang',
            'list'=>['Home','stok','tambah']
        ];
        $page = (object)[
            'title'=>'Tambah barang baru'
        ];
        $supplier = suppliermodel::all();
        $barang = barangmodel::all();
        $user = usermodel::all();
        $activeMenu ='stok';
        return view('stok.create',['breadcrumb'=>$breadcrumb,'page'=>$page,'supplier'=>$supplier,'barang'=>$barang,'user'=>$user,'activeMenu'=>$activeMenu]);
    }
    
    public function store(Request $request){
        $request->validate([
            'supplier_id'=>'required|integer',
            'barang_id'=>'required|integer',
            'user_id'=>'required|integer',
            'stok_tanggal'=>'required|date',
            'stok_jumlah'=>'required|integer'
        ]);

        stokmodel::create([
            'supplier_id'=>$request->supplier_id,
            'barang_id'=>$request->barang_id,
            'user_id'=>$request->user_id,
            'stok_tanggal'=>$request->stok_tanggal,
            'stok_jumlah'=>$request->stok_jumlah
        ]);

        return redirect('/stok')->with('success','Data stok berhasil disimpan');
    }

    public function show(string $stok_id){
        $stok = stokmodel::with('supplier','barang','user')->find($stok_id);
        $breadcrumb = (object)[
            'title'=>'Detail Stok',
            'list'=>['Home','stok','detail']
        ];
        $page = (object)[
            'title'=>'Detail data stok'
        ];
        $activeMenu = 'stok';
        return view('stok.show',['breadcrumb'=>$breadcrumb,'page'=>$page,'stok'=>$stok,'activeMenu'=>$activeMenu]);
    }

    public function edit(string $stok_id){
        $stok = stokmodel::find($stok_id);
        $supplier = suppliermodel::all();
        $barang = barangmodel::all();
        $user = usermodel::all();

        $breadcrumb = (object)[
            'title'=>'Edit data stok',
            'list'=>['Home','stok','edit']
        ];
        $page =(object)[
            'title'=>'Edit data stok'
        ];
        $activeMenu = 'stok';
        return view('stok.edit',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'stok'=>$stok,'supplier'=>$supplier,'barang'=>$barang,'user'=>$user]);
    }

    public function update(Request $request, string $stok_id){
        $request->validate([
            'supplier_id'=>'required|integer',
            'barang_id'=>'required|integer',
            'user_id'=>'required|integer',
            'stok_tanggal'=>'required|date',
            'stok_jumlah'=>'required|integer'
        ]);

        $stok = stokmodel::find($stok_id);
        $stok->update([
            'supplier_id'=>$request->supplier_id,
            'barang_id'=>$request->barang_id,
            'user_id'=>$request->user_id,
            'stok_tanggal'=>$request->stok_tanggal,
            'stok_jumlah'=>$request->stok_jumlah
        ]);
        return redirect('/stok')->with('success','Data barang berhasil diubah');
    }
}