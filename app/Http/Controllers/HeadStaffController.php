<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Responses;

use Illuminate\Support\Facades\Auth;

class HeadStaffController extends Controller
{
    public function index()
    {
        // Pastikan hanya data provinsi terkait yang ditampilkan
        $provinceId = Auth::user()->province_id; // Ambil ID provinsi dari user yang login

        // Hitung jumlah pengaduan per bulan untuk provinsi terkait
        $reportsCount = Report::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('province', $provinceId)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Hitung jumlah tanggapan per bulan untuk provinsi terkait
        $responsesCount = Responses::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereHas('report', function ($query) use ($provinceId) {
                $query->where('province', $provinceId);
            })
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Kirim data ke view
        return view('head.index', compact('reportsCount', 'responsesCount'));
    }
}