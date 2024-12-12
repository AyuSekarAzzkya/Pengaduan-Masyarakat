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
        // Cek apakah form untuk registrasi yang dikirim
        if ($request->has('register')) {
            // Logika registrasi tanpa field 'name'
            $request->validate([
                'email' => 'required|email|unique:users', // Validasi email
                'password' => 'required|min:6|confirmed', // Validasi password dan konfirmasi
            ]);

            // Membuat user baru tanpa nama
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Login otomatis setelah registrasi
            Auth::login($user);

            return redirect()->route('reports.index')->with('success', 'Pendaftaran berhasil!');
        } else {
            // Logika login
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($request->only('email', 'password'))) {
                return redirect()->route('reports.index');
            }

            return redirect()->route('login')->with('error', 'Email atau password salah.');
        }
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('login')->with('success', 'Anda telah berhasil logout');
    }
}