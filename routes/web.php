<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/level',[LevelController::class, 'index']);
// Route::get('/kategori',[KategoriController::class, 'index']);
// Route::get('/user', [UserController::class, 'index']);

// //tambahUser
// Route::get('/user/tambah', [UserController::class, 'tambah']);

// //simpanUser
// Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);

// //ubahUser
// Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);

// //ubahSimpan
// Route::put('/user/ubah_simpan/{id}', [UserController::class,'ubah_simpan']);

// //hapusUser
// Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);

//Jobsheet 5 praktikum 3
Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function(){
    Route::get('/', [UserController::class, 'index']);
    Route::post('/list', [UserController::class, 'list']);
    Route::get('/create', [UserController::class, 'create']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/{id}/edit', [UserController::class, 'edit']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});