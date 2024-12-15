<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{
    public $start_date;
    public $end_date;
    public $province_id;

    public function __construct($start_date = null, $end_date = null, $province_id = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->province_id = $province_id;
    }

    public function collection()
    {
        // Query untuk mengambil data report dan relasi yang dibutuhkan
        $query = Report::with([
            'responses',         // Relasi untuk mengambil status
            'responses.progress', // Relasi ke ResponseProgress (Riwayat Tanggapan)
            'staffProvince',     // Relasi untuk Identitas Staff
            'user'               // Relasi ke pelapor (user)
        ])->where('province', $this->province_id);

        // Filter berdasarkan tanggal jika ada
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date, $this->end_date]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Email Pelapor',
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Desa',
            'Deskripsi',
            'Tipe',
            'Tanggal Dibuat',
            'Status',
            'URL Gambar',
            'Jumlah Voting',
            'Identitas Staff',
            'Riwayat Tanggapan',
        ];
    }

    public function map($report): array
    {
        // Ambil status dari relasi responses (response pertama)
        $status = $report->responses->first() ? $report->responses->first()->status : 'Tidak Ada Status';

        // Ambil riwayat tanggapan dari ResponseProgress
        $responsesProgress = $report->responses->flatMap(function ($response) {
            return $response->progress->pluck('histories');
        })->implode(', ') ?: 'Belum Ada Tanggapan';

        // Ambil identitas staff dari relasi StaffProvince (user yang login)
        $staffIdentity = $report->staffProvince ? $report->staffProvince->name : 'Tidak Ada Staff';

        // Ambil jumlah voting, jika JSON hitung jumlah item
        $votesCount = is_array($report->voting) ? count($report->voting) : 0;

        return [
            $report->id,
            $report->user ? $report->user->email : 'Tidak Ada Email', // Email pelapor
            $this->getLocationName("provinces", $report->province), // Provinsi
            $this->getLocationName("regencies/{$report->province}", $report->regency), // Kabupaten
            $this->getLocationName("districts/{$report->regency}", $report->subdistrict), // Kecamatan
            $this->getLocationName("villages/{$report->subdistrict}", $report->village), // Desa
            $report->description ?? 'Tidak Ada Deskripsi', // Deskripsi
            $report->type ?? 'Tidak Ada Tipe', // Tipe
            $report->created_at ? $report->created_at->format('d F Y') : 'Tidak Ada Tanggal', // Tanggal Dibuat
            $status, // Status
            $report->image ?? 'Tidak Ada Gambar', // URL Gambar
            $votesCount, // Jumlah Voting
            $staffIdentity, // Identitas Staff
            $responsesProgress, // Riwayat Tanggapan
        ];
    }

    private function getLocationName($endpoint, $id)
    {
        // Caching API wilayah untuk mengurangi pemanggilan berulang
        $cacheKey = "{$endpoint}_{$id}";
        $locationName = Cache::get($cacheKey);

        if (!$locationName) {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/{$endpoint}.json");

            if ($response->successful()) {
                $location = collect($response->json())->firstWhere('id', $id);
                $locationName = $location ? $location['name'] : 'Tidak Diketahui';
                Cache::put($cacheKey, $locationName, now()->addMinutes(10));
            } else {
                $locationName = 'Tidak Diketahui (API Error)';
            }
        }

        return $locationName;
    }
}
