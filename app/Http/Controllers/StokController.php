<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title'=>'Daftar Stok',
            'list'=>['Home','stok']
        ];
        $page =(object)[
            'title'=>'Daftar stok yang terdaftar dalam sistem'
        ];
        $activeMenu ='stok';
        $stok = StokModel::all();
        return view('stok.index',['breadcrumb'=>$breadcrumb,'page'=>$page,'stok'=>$stok, 'activeMenu' =>$activeMenu]);
    }

    public function list(Request $request){
        $stok = StokModel::select('barang_id','supplier_nama','stok_jumlah','username');
        if($request->supplier_id){
            $stok->where('stok_id',$request->stok_id);
        }
        return DataTables::of($stok)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/stok/' . $stok->stok_id) . '" class="btn btn-info btnsm">Detail</a> ';
                $btn .= '<a href="' . url('/stok/' . $stok->sstok_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
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
            'title'=>'Tambah stok',
            'list'=>['Home','stok','tambah']
        ];
        $page = (object)[
            'title'=>'Tambah stok baru'
        ];
        $activeMenu = 'stok';
        $stok = StokModel::all();
        return view('stok.create',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'stok'=>$stok]);
    }

    public function store(Request $request){
        $request->validate([
            'supplier_kode'=>'required|string|min:3|max:5|unique:m_supplier,supplier_kode',
            'supplier_nama'=>'required|string|max:100',
            'supplier_alamat'=>'required|string|max:100'
        ]);
        StokModel::create([
            'supplier_kode'=>$request->supplier_kode,
            'supplier_nama'=>$request->supplier_nama,
            'supplier_alamat'=>$request->supplier_alamat,
        ]);
        return redirect('/supplier')->with('success','Data kategori berhasil disimpan');
    }

    public function show(string $supplier_id){
        $supplier = StokModel::find($supplier_id);
        $breadcrumb = (object)[
            'title'=>'Detail supplier',
            'list'=>['Home','supplier','detail']
        ];
        $page = (object)[
            'title'=>'Detail supplier'
        ];
        $activeMenu = 'supplier';
        return view('supplier.show',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'supplier'=>$supplier]);
    }

    public function edit(string $supplier_id){
        $supplier = StokModel::find($supplier_id);
        $breadcrumb = (object)[
            'title'=>'Edit supplier',
            'list'=>['Home','supplier','edit']
        ];
        $page = (object)[
            'title' => 'Edit supplier'
        ];
        $activeMenu = 'supplier';
        return view('supplier.edit',['breadcrumb'=>$breadcrumb,'page'=>$page,'supplier'=>$supplier,'activeMenu'=>$activeMenu]);
    }

    public function update(Request $request, string $supplier_id){
        $request->validate([
            'supplier_kode'=>'required|string|min:3|max:5|unique:m_supplier,supplier_kode',
            'supplier_nama'=>'required|string|max:100',
            'supplier_alamat'=>'required|string|max:100'
        ]);
        $supplier = StokModel::find($supplier_id);
        $supplier->update([
            'supplier_kode'=>$request->supplier_kode,
            'supplier_nama'=>$request->supplier_nama,
            'supplier_alamat'=>$request->supplier_alamat
        ]);
        return redirect('/supplier')->with('success','Data supplier berhasil diperbarui');
    }

    public function destroy(string $supplier_id){
        $check = StokModel::find($supplier_id);
        if (!$check) {
            return redirect('/supplier')->with('error', 'Data level tidak ditemukan');
        }
        try {
            StokModel::destroy($supplier_id);
            return redirect('/supplier')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/supplier')->with('error', 'Data level gagal dhapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}