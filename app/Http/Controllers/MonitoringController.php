<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Http;

class MonitoringController extends Controller
{
    public function index()
{
    $reports = Report::with('responses.progress')
        ->select('id', 'description', 'province', 'regency', 'subdistrict', 'village', 'type', 'image', 'created_at')
        ->orderBy('created_at', 'desc')
        ->get();

    // Ambil data lokasi dari API berdasarkan ID
    foreach ($reports as $report) {
        // Ambil nama provinsi
        $provinceResponse = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json");
        $report->province_name = $provinceResponse->successful()
            ? collect($provinceResponse->json())->firstWhere('id', $report->province)['name'] ?? 'Unknown'
            : 'Unknown (API Error)';

        // Ambil nama kabupaten
        $regencyResponse = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$report->province}.json");
        $report->regency_name = $regencyResponse->successful()
            ? collect($regencyResponse->json())->firstWhere('id', $report->regency)['name'] ?? 'Unknown'
            : 'Unknown (API Error)';

        // Ambil nama kecamatan
        $subdistrictResponse = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$report->regency}.json");
        $report->subdistrict_name = $subdistrictResponse->successful()
            ? collect($subdistrictResponse->json())->firstWhere('id', $report->subdistrict)['name'] ?? 'Unknown'
            : 'Unknown (API Error)';

        // Ambil nama desa
        $villageResponse = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$report->subdistrict}.json");
        $report->village_name = $villageResponse->successful()
            ? collect($villageResponse->json())->firstWhere('id', $report->village)['name'] ?? 'Unknown'
            : 'Unknown (API Error)';
    }

    return view('reports.monitoring', compact('reports'));
}

public function destroy(Report $report)
{
    // Jika respons kosong, berarti laporan belum ada respons
    if ($report->responses->isEmpty()) {
        // Jika status laporan adalah 'pending' atau 'reject', maka laporan bisa dihapus
        if ($report->status == 'pending' || $report->status == 'reject') {
            $report->delete();
            return redirect()->route('reports.index')->with('success', 'Laporan berhasil dihapus.');
        } else {
            // Jika status bukan 'pending' atau 'reject', tampilkan pesan error
            return redirect()->route('reports.index')->with('error', 'Laporan ini tidak dapat dihapus karena statusnya sudah diproses.');
        }
    }

    // Jika sudah ada respons, tidak bisa dihapus
    return redirect()->route('reports.index')->with('error', 'Laporan ini tidak dapat dihapus karena sudah ada respons.');
}

}
