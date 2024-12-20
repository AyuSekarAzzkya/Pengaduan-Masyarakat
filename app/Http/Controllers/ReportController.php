<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $province = $request->query('province');
        
        // Ambil laporan berdasarkan provinsi jika ada
        $reports = Report::when($province, function($query, $province) {
            return $query->where('province', $province);  // Gunakan 'province' sesuai dengan kolom di database
        })->with('user')  // Memuat relasi 'user' jika diperlukan
        ->latest()
        ->get();
        
        // Cek jika permintaan adalah AJAX
        if ($request->ajax()) {
            return response()->json([
                'reports' => $reports
            ]);
        }
        
        // Jika bukan AJAX, kembalikan data laporan untuk view biasa
        return view('reports.index', compact('reports'));
    }
    
    
    public function search(Request $request)
    {
        if ($request->ajax()) {
            $provinceId = $request->input('province');
        
            // Pastikan parameter province valid
            if (!$provinceId) {
                return response()->json(['reports' => []]);
            }
        
            // Ambil laporan berdasarkan 'province' (bukan 'province_id')
            $reports = Report::where('province', $provinceId)  // Gantilah 'province' jika kolomnya memang bernama itu
                ->with('user') // Relasi untuk mengambil email user
                ->latest()
                ->get();
        
            // Kirim data dalam format JSON
            return response()->json(['reports' => $reports]);
        }
        
        return response()->json(['error' => 'Invalid request'], 400);
    }
    
    public function show($id)
    {
        $report = Report::findOrFail($id);
    
        // Increment viewers
        $report->increment('viewers');
    
        return view('reports.detail', compact('report'));
    }


    public function comment(Request $request, $id)
    {
        // Validasi form input komentar
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // Temukan laporan berdasarkan ID
        $report = Report::findOrFail($id);

        // Menyimpan komentar dengan ID user yang sedang login
        $report->comments()->create([
            'user_id' => auth()->id(),  // Mengambil ID pengguna yang sedang login
            'comment' => $request->comment,
        ]);

        // Redirect ke halaman detail laporan dengan flash message
        return redirect()->route('reports.show', $id)->with('success', 'Komentar berhasil ditambahkan!');
    }

    // Method untuk mendapatkan daftar provinsi dari database atau   
    public function getProvinces()
    {
        // Mengambil data provinsi dari API atau database
        // Jika mengambil dari database, sesuaikan query-nya
        $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');

        return collect($response->json());  // Return the provinces as a collection
        return Report::select('province')->distinct()->get();
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'type' => 'required|in:KEJAHATAN,PEMBANGUNAN,SOSIAL',
            'province' => 'required|string',
            'regency' => 'required|string',
            'subdistrict' => 'required|string',
            'village' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'statement' => 'required|boolean',
        ]);

        // Store image and report data
        $imagePath = $request->file('image')->store('images/reports', 'public');
        Report::create([
            'user_id' => Auth::id(),
            'description' => $request->description,
            'type' => $request->type,
            'province' => $request->province,
            'regency' => $request->regency,
            'subdistrict' => $request->subdistrict,
            'village' => $request->village,
            'voting' => json_encode([]),
            'viewers' => 0,  // Kolom viewers
            'image' => $imagePath,
            'statement' => $request->statement,
        ]);

        return redirect()->route('reports.create')->with('success', 'Laporan berhasil dikirim!');
    }


    public function votes($reportId)
    {
        // Temukan report berdasarkan ID
        $report = Report::findOrFail($reportId);
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login

        // Ambil data voting yang disimpan (dalam format JSON atau array)
        $voting = json_decode($report->voting, true);

        // Cek apakah pengguna sudah memberi vote
        if (!in_array($user->id, $voting)) {
            // Jika belum, beri vote
            $voting[] = $user->id;
        } else {
            // Jika sudah, batalkan vote
            $voting = array_diff($voting, [$user->id]);
        }

        // Simpan kembali data voting ke database
        $report->voting = json_encode($voting);
        $report->save();

        // Redirect kembali tanpa pesan
        return redirect()->back();
    }
}
