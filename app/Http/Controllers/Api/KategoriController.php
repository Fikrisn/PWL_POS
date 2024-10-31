<?php

namespace App\Http\Controllers\Api;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = KategoriModel::all();
        return response()->json($kategoris);
    }

    public function store(Request $request)
    {
        $kategori = KategoriModel::create($request->all());
        return response()->json($kategori, 201);
    }

    public function show(KategoriModel $kategori)
    {
        return response()->json($kategori);
    }

    public function update(Request $request, KategoriModel $kategori)
    {
        $kategori->update($request->all());
        return response()->json($kategori, 200);
    }

    public function destroy(KategoriModel $kategori)
    {
        $kategori->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus'
        ]);
    }
}