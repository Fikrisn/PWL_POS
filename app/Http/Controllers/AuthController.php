<?php
namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/'); // Pengguna yang sudah login diarahkan ke halaman utama
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return response()->json([
                'status' => true,
                'message' => 'Login Berhasil',
                'redirect' => url('/') // Arahkan pengguna setelah login sukses
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Login Gagal'
        ], 401); // Tambahkan status 401 untuk login yang gagal
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }

    public function postregister()
    {
        $level = LevelModel::all(); // Mendapatkan daftar level dari database
        return view('auth.register', compact('level'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level_id' => 'required|integer|exists:m_level,level_id',
            'username' => 'required|string|min:4|max:20|unique:m_user,username',
            'nama' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Registrasi Gagal',
                'errors' => $validator->errors() // Mengirimkan error validasi dalam bentuk JSON
            ], 422);
        }

        try {
            UserModel::create([
                'level_id' => $request->level_id,
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => Hash::make($request->password), // Hashing password
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registrasi Berhasil',
                'redirect' => url('/login') // Mengarahkan ke halaman login
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Registrasi Gagal: ' . $e->getMessage() // Menangani error dengan pesan yang lebih jelas
            ], 500);
        }
    }
}