<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    // Menampilkan form permintaan link untuk reset password
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email'); // Tampilkan halaman form untuk memasukkan email atau nomor telepon
    }

    // Mengirim OTP ke email pengguna
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email_or_phone' => 'required|string',
        ]);

        // Cari user berdasarkan email atau telepon
        $user = User::where('email', $request->email_or_phone)
                    ->orWhere('phone', $request->email_or_phone)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $user->reset_password_token = $otp;
        $user->reset_password_resend_at = Carbon::now()->addMinutes(1);
        $user->reset_password_expired_at = Carbon::now()->addHour();
        $user->save();

        // Kirim email OTP
        try {
            Mail::to($user->email)->send(new ResetPasswordMail($otp));

            // Jika email berhasil dikirim, redirect ke halaman verifikasi OTP
            return redirect()->route('password.verifyOtpForm', ['email' => $user->email]);

        } catch (\Exception $e) {
            // Jika terjadi kesalahan dalam pengiriman email, tampilkan pesan error
            return response()->json(['message' => 'Failed to send OTP email: ' . $e->getMessage()], 500);
        }
    }



    // Menampilkan form untuk verifikasi OTP
    public function showVerifyOtpForm(Request $request)
{
    // Periksa apakah email ada dalam query parameter
    $email = $request->query('email');

    // Kirim view verifikasi OTP dan lewati email ke view
    return view('auth.passwords.verify-otp', compact('email'));
}


    // Memverifikasi OTP dan mengatur ulang password
    public function verifyResetPassword(Request $request)
    {
        // Validasi data yang diperlukan
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
            'password' => 'required|confirmed|min:8', // password confirmation
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user || $user->reset_password_token != $request->code) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        // Cek apakah OTP sudah kedaluwarsa
        if (Carbon::now()->isAfter(Carbon::parse($user->reset_password_expired_at))) {
            return response()->json(['message' => 'OTP has expired'], 400);
        }

        // Log untuk memastikan password berubah
        Log::info("Password for user {$user->email} is being updated.");

        // Update password
        $user->password = Hash::make($request->password);
        $user->reset_password_token = null;
        $user->reset_password_resend_at = null;
        $user->reset_password_expired_at = null;
        $user->save();

        Log::info("Password for user {$user->email} has been updated.");

        return view('auth/login');
    }


    // Fungsi untuk mengirim email reset password dengan OTP
    private function sendResetEmail($user, $otp)
    {
        try {
            // Kirim email dengan OTP
            Mail::to($user->email)->send(new ResetPasswordMail($otp));

            // Jika ada kegagalan pengiriman email
            if (Mail::failures()) {
                return response()->json(['message' => 'Email failed to send'], 500);
            }
        } catch (\Exception $e) {
            // Menangani exception jika pengiriman email gagal
            return response()->json(['message' => 'Failed to send OTP email: ' . $e->getMessage()], 500);
        }
    }
}