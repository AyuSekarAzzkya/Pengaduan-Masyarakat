<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan halaman login
    public function index()
    {
        return view('login');
    }
    
    // Menangani login dan registrasi
    public function postLoginOrRegistration(Request $request)
    {
        // Validasi data login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    
        // Cek apakah email sudah terdaftar
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            // Jika user tidak ditemukan, buat akun baru
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password), // Enkripsi password
                'role' => 'GUEST', // Default role 'GUEST' untuk user baru
            ]);
        } else {
            // Jika user ditemukan, periksa apakah password benar
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->route('login')->with('error', 'Email atau password salah.');
            }
        }
    
        // Login otomatis setelah registrasi atau login
        Auth::login($user);
    
        // Arahkan ke halaman yang sesuai berdasarkan role pengguna
        if (Auth::user()->role == 'STAFF') {
            return redirect()->route('staff.index')->with('success', 'Selamat datang, Staff!');
        } elseif (Auth::user()->role == 'GUEST') {
            return redirect()->route('reports.index')->with('success', 'Selamat datang!');
        } elseif (Auth::user()->role == 'HEAD_STAFF') {
            return redirect()->route('head.index')->with('success', 'Selamat datang, Head Staff!');
        } else {
            Auth::logout(); // Logout jika role tidak sesuai
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('login')->with('success', 'Anda telah berhasil logout');
    }

    // Menampilkan daftar STAFF berdasarkan provinsi HEAD_STAFF
   
}
