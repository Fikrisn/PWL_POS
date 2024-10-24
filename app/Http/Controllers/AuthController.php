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
    // Method untuk menampilkan halaman login
    public function login()
    {
        if (Auth::check()) {
            return redirect('/'); // Pengguna yang sudah login diarahkan ke halaman utama
        }
        return view('auth.login');
    }

    // Method untuk menangani proses login
    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
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

        return redirect('login')->withErrors('Login gagal, silakan coba lagi.');
    }

    // Method untuk menangani logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Logged out successfully', 'redirect' => 'login']);
        }

        return redirect('login')->with('success', 'Anda berhasil logout.');
    }

    // Method untuk menampilkan halaman registrasi
    public function postregister()
    {
        $level = LevelModel::all(); // Mendapatkan daftar level dari database
        return view('auth.register', compact('level'));
    }

    // Method untuk menangani proses registrasi
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'level_id' => 'required|integer|exists:m_level,level_id',
            'username' => 'required|string|min:4|max:20|unique:m_user,username',
            'nama' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Cek jika validasi gagal
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Registrasi Gagal',
                    'errors' => $validator->errors() // Mengirimkan error validasi dalam bentuk JSON
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator) // Mengembalikan error ke form
                ->withInput();
        }

        try {
            // Proses penyimpanan data pengguna ke database
            UserModel::create([
                'level_id' => $request->level_id,
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => Hash::make($request->password), // Hashing password
            ]);

            // Jika menggunakan Ajax, kirimkan response JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Registrasi Berhasil',
                    'redirect' => route('login') // Mengarahkan ke halaman login
                ]);
            }

            // Redirect jika tidak menggunakan Ajax
            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            // Tangani jika terjadi error saat registrasi
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Registrasi Gagal: ' . $e->getMessage() // Menangani error dengan pesan yang lebih jelas
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage());
        }
    }
}
