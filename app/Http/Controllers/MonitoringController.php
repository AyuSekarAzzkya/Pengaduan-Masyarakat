<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Http;

class MonitoringController extends Controller
{
    public function index()
{
    // Ambil semua laporan
    $reports = Report::all();

    // Loop setiap laporan untuk mengambil data lokasi terkait dari API
    foreach ($reports as $report) {
        // Ambil data lokasi provinsi, kabupaten, kecamatan, desa menggunakan API eksternal
        $province = $this->getLocationName('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json', $report->province_id);
        $regency = $this->getLocationName("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$report->province_id}.json", $report->regency_id);
        $subdistrict = $this->getLocationName("https://www.emsifa.com/api-wilayah-indonesia/api/subdistricts/{$report->regency_id}.json", $report->subdistrict_id);
        $village = $this->getLocationName("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$report->subdistrict_id}.json", $report->village_id);

        // Menambahkan nama lokasi ke laporan
        $report->province_name = $province;
        $report->regency_name = $regency;
        $report->subdistrict_name = $subdistrict;
        $report->village_name = $village;
    }

    return view('reports.monitoring', compact('reports'));
}

// Fungsi untuk mengambil nama lokasi dari API
private function getLocationName($url, $id)
{
    // Ambil data dari API menggunakan Http request
    $response = Http::get($url);

    // Pastikan respons berhasil
    if ($response->successful()) {
        $data = $response->json();

        // Cari lokasi berdasarkan ID yang diterima
        foreach ($data as $item) {
            if ($item['id'] == $id) {
                return $item['name']; // Kembalikan nama lokasi
            }
        }
    }

    return 'Unknown'; // Kembalikan 'Unknown' jika tidak ditemukan
}
    
}
