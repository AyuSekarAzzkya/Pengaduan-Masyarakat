<?php

namespace App\Http\Controllers;

use App\Models\StaffProvince;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class StaffProvinceController extends Controller
{
    // Menampilkan halaman form pembuatan staff
    public function createStaff()
    {
        // Cek apakah user adalah HEAD_STAFF
        if (Auth::user()->role !== 'HEAD_STAFF') {
            return redirect()->route('login')->with('error', 'Akses tidak diizinkan!');
        }

        // Ambil data provinsi dari API
        $provinces = $this->getProvincesFromApi();

        // Cek jika provinsi kosong
        if ($provinces->isEmpty()) {
            return redirect()->back()->with('error', 'Data provinsi tidak dapat diambil.');
        }

        // Kirim data provinsi ke view
        return view('head.create', compact('provinces'));
    }

    // Menyimpan data staff baru
    public function storeStaff(Request $request)
    {
        // Validasi input email dan password
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
    
        // Cek apakah user adalah HEAD_STAFF
        if (Auth::user()->role !== 'HEAD_STAFF') {
            return redirect()->route('login')->with('error', 'Akses tidak diizinkan!');
        }
    
        // Ambil provinsi yang terkait dengan HEAD_STAFF yang sedang login
        $province = Auth::user()->staffProvince->province;
    
        // Buat akun STAFF
        $staff = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'STAFF',
        ]);
    
        // Buat entri provinsi untuk STAFF baru (mengikuti provinsi HEAD_STAFF)
        StaffProvince::create([
            'user_id' => $staff->id,
            'province' => $province, // Simpan provinsi yang diambil dari HEAD_STAFF
        ]);
    
        // Ambil daftar staff setelah berhasil dibuat
        $staffs = User::where('role', 'STAFF')->get(); // Menarik semua data staff
    
        // Redirect ke halaman daftar staff dengan data staff terbaru
        return redirect()->route('head.staff')->with('success', 'Akun STAFF berhasil ditambahkan.')->with('staffs', $staffs);
    }
    

    // Fungsi untuk mengambil data provinsi dari API
    private function getProvincesFromApi()
    {
        // Mengambil data provinsi dari API
        $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');

        // Cek apakah API responsenya sukses dan data valid
        if ($response->successful()) {
            return collect($response->json());
        }

        // Jika gagal, kembalikan array kosong
        Log::error('Gagal mengambil data provinsi: ' . $response->status());
        return collect();
    }

    // Menampilkan daftar staff berdasarkan provinsi
    public function viewStaffByProvince()
    {
        // Cek apakah user adalah HEAD_STAFF
        if (Auth::user()->role !== 'HEAD_STAFF') {
            return redirect()->route('login')->with('error', 'Akses tidak diizinkan!');
        }
    
        // Ambil provinsi dari akun HEAD_STAFF yang sedang login
        $province = Auth::user()->staffProvince->province ?? null;
    
        // Jika provinsi tidak ada, arahkan kembali dengan error
        if (!$province) {
            return redirect()->back()->with('error', 'Provinsi Anda belum terdaftar.');
        }
    
        // Ambil daftar STAFF yang terhubung dengan provinsi yang sama
        $staffs = User::where('role', 'STAFF')
            ->whereHas('staffProvince', function ($query) use ($province) {
                $query->where('province', $province); // Filter berdasarkan provinsi di staffProvince
            })
            ->with('staffProvince') // Menyertakan relasi staffProvince
            ->get();
    
        // Kembalikan data ke view dengan informasi provinsi dan daftar staf
        return view('head.staff', compact('staffs', 'province'));
    }
    

    // Menghapus akun staff
    public function deleteStaff($id)
    {
        // Cari STAFF berdasarkan ID
        $staff = User::findOrFail($id);

        // Periksa apakah STAFF memiliki tanggapan pengaduan
        if ($staff->responses()->exists()) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus STAFF yang memiliki tanggapan pengaduan.');
        }

        // Hapus STAFF
        $staff->delete();

        return redirect()->back()->with('success', 'Akun STAFF berhasil dihapus.');
    }

    // Mereset password STAFF
    public function resetPassword($id)
    {
        // Cari STAFF berdasarkan ID
        $staff = User::findOrFail($id);

        // Ambil 4 karakter pertama dari email
        $newPassword = substr($staff->email, 0, 4);

        // Update password STAFF dengan password baru
        $staff->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()->back()->with('success', 'Password berhasil direset. Password baru: ' . $newPassword);
    }
}