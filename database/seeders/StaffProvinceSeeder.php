<?php

namespace Database\Seeders;

use App\Models\StaffProvince;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class StaffProvinceSeeder extends Seeder
{
    public function run()
    {
        // // Ambil data provinsi dari API
        // $provinces = $this->getProvincesFromApi();

        // // Pastikan ada provinsi yang diambil dari API
        // if ($provinces->isEmpty()) {
        //     $this->command->error('Tidak ada data provinsi yang ditemukan.');
        //     return;
        // }

        // // Tentukan ID provinsi yang ingin digunakan, misalnya ID untuk Jawa Barat adalah 32
        // $selectedProvinceId = 32; // ID untuk Jawa Barat, ganti dengan ID yang sesuai

        // // Cari provinsi berdasarkan ID
        // $selectedProvince = $provinces->firstWhere('id', $selectedProvinceId);

        // if (!$selectedProvince) {
        //     $this->command->error('Provinsi dengan ID ' . $selectedProvinceId . ' tidak ditemukan.');
        //     return;
        // }

        // // Buat akun HEAD_STAFF
        // $headStaff = User::create([
        //     'email' => 'head_jabarss@gmail.com', // Email yang telah dikustom
        //     'password' => Hash::make('123456'),
        //     'role' => 'HEAD_STAFF',
        // ]);
        
        // // Assign provinsi untuk HEAD_STAFF, simpan ID provinsi, bukan nama
        // StaffProvince::create([
        //     'user_id' => $headStaff->id,
        //     'province' => $selectedProvince['id'],  // Menyimpan ID provinsi, bukan nama
        // ]);


        // Ambil data provinsi dari API
        $provinces = $this->getProvincesFromApi();

        // Pastikan ada provinsi yang diambil dari API
        if ($provinces->isEmpty()) {
            $this->command->error('Tidak ada data provinsi yang ditemukan.');
            return;
        }

        // Tentukan ID provinsi yang ingin digunakan, misalnya ID untuk Jawa Barat adalah 32
        $selectedProvinceId = 11; // ID untuk Jawa Barat, ganti dengan ID yang sesuai

        // Cari provinsi berdasarkan ID
        $selectedProvince = $provinces->firstWhere('id', $selectedProvinceId);

        if (!$selectedProvince) {
            $this->command->error('Provinsi dengan ID ' . $selectedProvinceId . ' tidak ditemukan.');
            return;
        }

        // Buat akun HEAD_STAFF
        $headStaff = User::create([
            'email' => 'head_aceh@gmail.com', // Email yang telah dikustom
            'password' => Hash::make('123456'),
            'role' => 'HEAD_STAFF',
        ]);
        
        // Assign provinsi untuk HEAD_STAFF, simpan ID provinsi, bukan nama
        StaffProvince::create([
            'user_id' => $headStaff->id,
            'province' => $selectedProvince['id'],  // Menyimpan ID provinsi, bukan nama
        ]);
    }

    /**
     * Fungsi untuk mengambil data provinsi dari API
     */
    private function getProvincesFromApi()
    {
        // Mengambil data provinsi dari API
        $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');

        // Cek apakah API responsenya sukses dan data valid
        if ($response->successful()) {
            return collect($response->json());
        }

        // Jika gagal, kembalikan array kosong
        return collect();
    }
}