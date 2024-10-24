<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanDetailModel extends Model
{
    use HasFactory;

    protected $table = 't_penjualan_detail'; // Mendefinisikan nama tabel
    protected $primaryKey = 'detail_id'; // Mendefinisikan primary key

    protected $fillable = ['penjualan_id', 'barang_id', 'harga', 'jumlah'];
    
    public function penjualan_detail(): BelongsTo{
        return $this->belongsTo(PenjualanModel::class, 'penjualan_id', 'penjualan_id');
    }

    public function barang(): BelongsTo{
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }
}