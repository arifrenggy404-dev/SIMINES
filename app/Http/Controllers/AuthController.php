<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'required|string|unique:users',
            'alamat' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => $request->password, // Password otomatis di-hash oleh model User (casts 'hashed')
            'peran' => 'pelanggan',
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Simulasi OTP dianggap terverifikasi untuk registrasi baru agar langsung bisa belanja
        $request->session()->put('otp_verified', true);

        return redirect()->route('produk.index')->with('success', 'Selamat datang, ' . $user->nama . '! Akun Anda berhasil dibuat dan Anda telah masuk otomatis.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // Bisa Email atau No HP
            'password' => 'required',
        ]);

        // Cek apakah login menggunakan email atau no_hp
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'no_hp';

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if ($user->peran === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Generate OTP Dinamis (Bab VI 6.4.1)
            $otp = rand(1000, 9999);
            $request->session()->put('otp_code', $otp);
            $request->session()->put('otp_verified', false);

            // Simulasi pengiriman SMS dengan flash message
            return redirect()->route('otp.verify')->with('info', 'SIMULASI SMS: Kode OTP Anda adalah ' . $otp);
        }

        return back()->with('error', 'Kredensial (Email/No HP atau Password) salah.');
    }

    public function showOtp() { return view('auth.otp'); }

    public function verifyOtp(Request $request)
    {
        $sessionOtp = $request->session()->get('otp_code');

        if ($request->otp == $sessionOtp) {
            $request->session()->put('otp_verified', true);
            $request->session()->forget('otp_code');
            return redirect()->route('produk.index')->with('success', 'Login berhasil dengan OTP!');
        }
        return back()->with('error', 'Kode OTP salah. Silakan cek simulasi SMS di atas.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        // Simulasi pengiriman link
        return back()->with('success', 'Link pemulihan telah dikirim ke email Anda! (Simulasi: Silakan hubungi admin untuk reset manual di project UAS ini)');
    }
}
