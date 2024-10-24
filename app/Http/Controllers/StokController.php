<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class StokController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Stok',
            'list'=>['Home','Stok']
        ];
        $page = (object)[
            'title' => 'Daftar stok yang terdaftar dalam sistem'
        ];
        $activeMenu = 'stok';
        $supplier = SupplierModel::all();
        $barang = BarangModel::all();
        $user = UserModel::all();
        return view('stok.index',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'supplier'=>$supplier,'barang'=>$barang,'user'=>$user]);
    }

    public function list(Request $request){
        $stok = StokModel::select('stok_id','supplier_id','barang_id','user_id','stok_tanggal','stok_jumlah')
            ->with(['supplier','barang','user']);

        if($request->supplier_id){
            $stok->where('supplier_id', $request->supplier_id);
        } elseif($request->barang_id){
            $stok->where('barang_id', $request->barang_id);
        } elseif($request->user_id){
            $stok->where('user_id', $request->user_id);
        }

        return DataTables::of($stok)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) {
                $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
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
        $supplier = SupplierModel::all();
        $barang = BarangModel::all();
        $user = UserModel::all();
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

        StokModel::create([
            'supplier_id'=>$request->supplier_id,
            'barang_id'=>$request->barang_id,
            'user_id'=>$request->user_id,
            'stok_tanggal'=>$request->stok_tanggal,
            'stok_jumlah'=>$request->stok_jumlah
        ]);

        return redirect('/stok')->with('success','Data stok berhasil disimpan');
    }

    public function show(string $stok_id){
        $stok = StokModel::with('supplier','barang','user')->find($stok_id);
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
        $stok = StokModel::find($stok_id);
        $supplier = SupplierModel::all();
        $barang = BarangModel::all();
        $user = UserModel::all();

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

        $stok = StokModel::find($stok_id);
        $stok->update([
            'supplier_id'=>$request->supplier_id,
            'barang_id'=>$request->barang_id,
            'user_id'=>$request->user_id,
            'stok_tanggal'=>$request->stok_tanggal,
            'stok_jumlah'=>$request->stok_jumlah
        ]);
        return redirect('/stok')->with('success','Data barang berhasil diubah');
    }

    public function create_ajax(){
        $supplier = SupplierModel::all();
        $barang = BarangModel::all();
        $user = UserModel::all();
        return view('stok.create_ajax', ['supplier' => $supplier, 'barang' => $barang, 'user' => $user]);
    }

    public function store_ajax(Request $request){
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id'=>'required|integer',
                'barang_id'=>'required|integer',
                'user_id'=>'required|integer',
                'stok_tanggal'=>'required|date',
                'stok_jumlah'=>'required|integer'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            StokModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil disimpan'
            ]);
        }
        return redirect('/');
    }

    public function show_ajax(string $id) {
        $stok = StokModel::with('supplier','barang','user')->find($id);
        return view('stok.show_ajax', ['stok' => $stok]);
    }
    public function edit_ajax(string $id){
        $stok = StokModel::find($id);
        $supplier = SupplierModel::all();
        $barang = BarangModel::all();
        $user = UserModel::all();

        if ($stok) {
            $stok->stok_tanggal = \Carbon\Carbon::parse($stok->stok_tanggal)->format('Y-m-d');
        }

        return view('stok.edit_ajax', ['stok' => $stok, 'supplier' => $supplier, 'barang' => $barang, 'user' => $user]);
    }

    public function update_ajax(Request $request, $id){
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id'=>'required|integer',
                'barang_id'=>'required|integer',
                'user_id'=>'required|integer',
                'stok_tanggal'=>'required|date',
                'stok_jumlah'=>'required|integer'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $stok = StokModel::find($id);
            if ($stok) {
                $stok->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id) {
        $stok = StokModel::find($id);
        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, string $id) {
        if($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            if($stok) {
                $stok->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function destroy(string $stok_id){
        $check = StokModel::find($stok_id);
        if (!$check) {
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
        }
        try {
            StokModel::destroy($stok_id);
            return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/stok')->with('error', 'Data stok gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function import(){
        return view('stok.import');
    }

    public function import_ajax(Request $request){
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_stok' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_stok');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'supplier_id' => $value['A'],
                            'barang_id' => $value['B'],
                            'user_id' => $value['C'],
                            'stok_tanggal' => $value['D'],
                            'stok_jumlah' => $value['E'],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    StokModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel(){
        $stok = StokModel::select('supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Supplier ID');
        $sheet->setCellValue('C1', 'Barang ID');
        $sheet->setCellValue('D1', 'User ID');
        $sheet->setCellValue('E1', 'Tanggal Stok');
        $sheet->setCellValue('F1', 'Jumlah Stok');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $no = 1;
        $baris = 2;
        foreach ($stok as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->supplier_id);
            $sheet->setCellValue('C' . $baris, $value->barang_id);
            $sheet->setCellValue('D' . $baris, $value->user_id);
            $sheet->setCellValue('E' . $baris, $value->stok_tanggal);
            $sheet->setCellValue('F' . $baris, $value->stok_jumlah);
            $baris++;
            $no++;
        }
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setTitle('Data Stok');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Stok ' . date('Y-m-d H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf(){
        $stok = StokModel::select('supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
        ->get();
        $pdf = Pdf::loadView('stok.export_pdf',['stok'=>$stok]);
        $pdf->setPaper('a4','portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();
        return $pdf->stream('Data Stok '.date('Y-m-d H:i:s').'.pdf');
    }
}