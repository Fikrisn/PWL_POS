<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    // Menampilkan halaman profil
    public function index()
    {
        $user = UserModel::findOrFail(Auth::id());
        $breadcrumb = (object) [
            'title' => 'Data Profil',
            'list' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Profil', 'url' => url('/profil')]
            ]
        ];
        $activeMenu = 'profil';
        return view('profil', compact('user', 'breadcrumb', 'activeMenu'));
    }

    // Metode untuk update profil pengguna
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama' => 'required|string|max:100',
            'old_password' => 'nullable|string',
            'password' => 'nullable|min:5',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg', // Validasi untuk gambar
        ]);

        // Temukan user berdasarkan id
        $user = UserModel::find($id);

        // Update username dan nama
        $user->username = $request->username;
        $user->nama = $request->nama;

        // Jika user mengisi password lama, periksa validitasnya
        if ($request->filled('old_password')) {
            if (Hash::check($request->old_password, $user->password)) {
                // Jika password lama valid, update password baru
                $user->password = Hash::make($request->password);
            } else {
                return back()
                    ->withErrors(['old_password' => __('Password lama tidak sesuai')])
                    ->withInput();
            }
        }

        // Jika ada file gambar yang di-upload
        if ($request->hasFile('profile_image')) {
            // Hapus gambar lama jika ada
            if ($user->profile_image && Storage::exists('public/photos/' . $user->profile_image)) {
                Storage::delete('public/photos/' . $user->profile_image);
            }

            // Simpan gambar baru dan update ke database
            $fileName = time() . '.' . $request->profile_image->extension();
            $request->profile_image->storeAs('public/photos', $fileName);
            $user->profile_image = $fileName;
        }

        // Simpan perubahan ke database
        $user->save();

        // Kembalikan ke halaman profil dengan pesan sukses
        return back()->with('status', 'Profil berhasil diperbarui');
    }

    // Fungsi upload gambar profile yang diakses via ajax
    public function uploadProfileImage(Request $request)
    {
        // Validasi upload file gambar
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = UserModel::find(Auth::id());

        // Hapus gambar lama jika ada
        if ($user->profile_image && Storage::exists('public/photos/' . $user->profile_image)) {
            Storage::delete('public/photos/' . $user->profile_image);
        }

        // Simpan gambar baru
        $fileName = time() . '.' . $request->profile_image->extension();
        $request->profile_image->storeAs('public/photos', $fileName);

        // Update field profile_image pada user
        $user->profile_image = $fileName;
        $user->save();

        // Kembalikan respon JSON
        return response()->json(['success' => true, 'file_name' => $fileName]);
    }

    // Middleware untuk memastikan pengguna sudah login
    public function __construct()
    {
        $this->middleware('auth');
    }
}
