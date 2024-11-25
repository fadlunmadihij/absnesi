<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Menampilkan halaman register
    public function register()
    {
        return view('auth/register');
    }

    // Menyimpan data registrasi pengguna
    public function registerSave(Request $request)
    {
        // Validasi data input register
        Validator::make($request->all(), [
            'name' => 'required', // Nama wajib diisi
            'email' => 'required|email', // Email wajib diisi dan harus format email
            'password' => 'required|confirmed', // Password wajib diisi dan harus terkonfirmasi (password_confirmation harus cocok)
            'phone' => 'nullable|string|max:20', // Opsional (nullable)
            'address' => 'nullable|string|max:255',
        ])->validate();

        // Membuat user baru dengan level "Admin"
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password sebelum menyimpan
            'level' => 'Admin'
        ]);
        // Redirect ke halaman login setelah berhasil register
        return redirect()->route('login');
    }
// Menampilkan halaman login
    public function login()
    {
        return view('auth/login');
    }

    public function loginAction(Request $request)
    {
        // Validasi input login
        Validator::make($request->all(), [
            'email' => 'required|email', // Email wajib diisi dan harus format email
            'password' => 'required'  // Password wajib diisi
        ])->validate();

        // Melakukan proses autentikasi user
        // Jika autentikasi gagal, tampilkan pesan kesalahan menggunakan ValidationException
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed') // Menggunakan pesan default dari laravel untuk kesalahan autentikasi
            ]);
        }

        // Regenerasi sesi untuk keamanan setelah login berhasil
        $request->session()->regenerate();

        // Redirect ke halaman dashboard setelah berhasil login
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        // Logout user dari guard 'web'
        Auth::guard('web')->logout();

        // Menghapus sesi pengguna untuk memastikan logout sepenuhnya
        $request->session()->invalidate();

        // Redirect ke halaman utama setelah logout
        return redirect('/');
    }

    public function profile()
    {
        // Menampilkan halaman profil user
        return view('auth/profile');
    }

    // Method untuk mengupdate profil user
    public function updateProfile(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20', // Wajib diisi
            'address' => 'required|string|max:255', // Wajib diisi
        ], [
            'phone.required' => 'Phone masih belum di isi.',
            'address.required' => 'alamat masih belum di isi.',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Debugging
        if (!$user) {
            return back()->withErrors(['message' => 'User not authenticated']);
        }

        // Pastikan $user adalah instance dari App\Models\User
        if (!($user instanceof User)) {
            return back()->withErrors(['message' => 'Invalid user model']);
        }

        // Update data user
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;

        // Simpan data
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        // Validasi input password
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama harus diisi.',
            'new_password.required' => 'Password baru harus diisi.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'new_password.min' => 'Password baru harus minimal 8 karakter.',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Periksa apakah password lama cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // Update password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

    return redirect()->route('profile')->with('success', 'Password berhasil diubah.');
    }

}