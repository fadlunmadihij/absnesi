<?php

namespace App\Http\Controllers;

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
            'password' => 'required|confirmed' // Password wajib diisi dan harus terkonfirmasi (password_confirmation harus cocok)
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
}
