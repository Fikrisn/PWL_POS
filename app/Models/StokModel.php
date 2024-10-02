<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class StokModel extends Model
{
    protected $table = 't_stok';        // Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'stok_id';  //Mendefinisikan primary key dari tabel yang digunakan

    protected $fillable = ['stok_id', 'supplier_id', 'barang_id', 'user_id', 'user_id', 'stok_tanggal', 'stok_jumlah']; 
    
    public function user():BelongsTo {
        return $this->belongsTo(UserModel::class);
    }

    public function barang():BelongsTo {
        return $this->belongsTo(BarangModel::class);
    }

    public function supplier():BelongsTo {
        return $this->belongsTo(SupplierModel::class);
    }
}
