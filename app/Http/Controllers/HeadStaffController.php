<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Responses;
use App\Models\StaffProvince;
use Illuminate\Support\Facades\Auth;

class HeadStaffController extends Controller
{
    public function index()
    {
        // Ambil province berdasarkan relasi di staff_provinces
        $staffProvince = StaffProvince::where('user_id', Auth::id())->first();

        if (!$staffProvince || !$staffProvince->province) {
            return abort(403, 'Province tidak ditemukan untuk user yang login.');
        }

        $province = $staffProvince->province;

        // Total jumlah pengaduan berdasarkan province
        $reportsCount = Report::where('province', $province)->count();

        // Total jumlah tanggapan yang terkait dengan laporan di province tersebut
        $responsesCount = Responses::whereHas('report', function ($query) use ($province) {
            $query->where('province', $province);
        })->count();

        // Kirim data ke view
        return view('head.index', compact('reportsCount', 'responsesCount'));
    }
    }
    
