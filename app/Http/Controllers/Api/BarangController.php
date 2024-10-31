<?php

namespace App\Http\Controllers\Api;

use App\Models\BarangModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = BarangModel::all();
        return response()->json($barangs);
    }

    public function store(Request $request)
    {
        $barang = BarangModel::create($request->all());
        return response()->json($barang, 201);
    }

    public function show(BarangModel $barang)
    {
        return response()->json($barang);
    }

    public function update(Request $request, BarangModel $barang)
    {
        $barang->update($request->all());
        return response()->json($barang, 200);
    }

    public function destroy(BarangModel $barang)
    {
        $barang->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus'
        ]);
    }
}
