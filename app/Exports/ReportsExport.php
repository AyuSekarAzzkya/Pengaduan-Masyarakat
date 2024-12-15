<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{
    public $start_date;
    public $end_date;

    public function __construct($start_date = null, $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        // Jika ada rentang tanggal, ambil data dalam rentang tersebut
        if ($this->start_date && $this->end_date) {
            return Report::whereBetween('created_at', [$this->start_date, $this->end_date])
                ->with(['responses', 'user']) // Eager load relasi
                ->get();
        }

        // Jika tidak ada filter, ambil semua data
        return Report::with(['responses', 'user'])->get();
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
        return [
            $report->id,
            $report->user ? $report->user->email : 'Tidak Ada Email', // Email pelapor
            $report->province_name ?? 'Tidak Ada Provinsi',
            $report->regency_name ?? 'Tidak Ada Kabupaten',
            $report->subdistrict_name ?? 'Tidak Ada Kecamatan',
            $report->village_name ?? 'Tidak Ada Desa',
            $report->description ?? 'Tidak Ada Deskripsi',
            $report->type ?? 'Tidak Ada Tipe',
            $report->created_at ? $report->created_at->format('d F Y') : 'Tidak Ada Tanggal',
            $report->status ?? 'Tidak Ada Status',
            $report->image_url ?? 'Tidak Ada Gambar',
            $report->votes_count ?? 0, // Jumlah voting
            $report->user ? $report->user->name : 'Tidak Ada Staff', // Identitas Staff dari user
            $report->responses->pluck('content')->implode(', ') ?: 'Belum Ada Tanggapan', // Riwayat tanggapan
        ];
    }
}
