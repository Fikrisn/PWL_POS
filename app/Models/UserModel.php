<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UserModel extends Authenticatable implements JWTSubject
{
//     public function getJWTIdentifier() {
//         return $this->getKey();
//     }
    
//     public function getJWTCustomClaims() {
//         return [];
//     }

//     protected $table = 'm_user';        // Mendefinisikan nama tabel yang digunakan oleh model ini
//     protected $primaryKey = 'user_id';  //Mendefinisikan primary key dari tabel yang digunakan

//     protected $fillable = ['level_id', 'profile_image', 'username', 'nama', 'password'];

//     protected $hidden = ['password']; 
//     protected $casts = ['password' => 'hashed'];

//     public function level():BelongsTo {
//         return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
//     }

//     public function geRoleName(): string {
//         return $this->level->level_nama;
//     }

//     public function hasRole($role): bool {
//         return $this->level->level_kode == $role;
//     }

//     public function getRole() {
//         return $this->level->level_kode;
//     }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['level_id', 'image', 'username', 'nama', 'password'];

    public function level(): BelongsTo {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    protected function image():Attribute {
        return Attribute::make(
            get: fn($image) => url('/storage/posts/'. $image)
        );
    }

}

