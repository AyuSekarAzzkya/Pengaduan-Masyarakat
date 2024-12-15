<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Models\Report;
use App\Models\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class ResponseController extends Controller
{
    public function index()
    {
        // Ambil ID provinsi yang terkait dengan staff yang sedang login
        $staffProvince = Auth::user()->staffProvince; // Ambil relasi staffProvince
        $staffProvinceId = $staffProvince->province ?? null; // Ambil ID provinsi (bukan nama provinsi)
        
        // Jika ID provinsi tersedia
        if ($staffProvinceId) {
            // Ambil laporan yang terkait dengan provinsi staff
            $reports = Report::with('user', 'responses')
                ->where('province', $staffProvinceId)  // Filter laporan berdasarkan ID provinsi staff
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Jika tidak ada provinsi yang terkait dengan staff
            $reports = collect(); // Kosongkan laporan jika tidak ada provinsi
        }
        
        // Ambil data lokasi dari API berdasarkan ID
        foreach ($reports as $report) {
            $report->province_name = $this->getLocationName("provinces", $report->province);
            $report->regency_name = $this->getLocationName("regencies/{$report->province}", $report->regency);
            $report->subdistrict_name = $this->getLocationName("districts/{$report->regency}", $report->subdistrict);
            $report->village_name = $this->getLocationName("villages/{$report->subdistrict}", $report->village);
        }
        
        // Kirim data ke view staff.index
        return view('staff.index', compact('reports'));
    }

    public function update(Request $request, $reportId)
    {
        // Validasi action (ON_PROCESS atau REJECT)
        $request->validate([
            'action' => 'required|in:ON_PROCESS,REJECT',
        ]);

        // Cari laporan berdasarkan ID
        $report = Report::findOrFail($reportId);

        // Cek apakah laporan ini sesuai dengan provinsi staff
        if ($report->province != Auth::user()->staffProvince->province) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menangani laporan ini.');
        }

        // Update atau buat response untuk laporan
        $response = Responses::updateOrCreate(
            ['report_id' => $report->id],
            [
                'status' => $request->action,
                'staff_id' => Auth::id(),
            ]
        );

        // Jika action adalah 'ON_PROCESS', arahkan ke halaman detail
        if ($request->action === 'ON_PROCESS') {
            return redirect()->route('staff.detail', $reportId)
                ->with('success', 'Laporan diproses! Tambahkan rincian di halaman detail.');
        }

        // Jika action adalah 'REJECT', kembali ke halaman sebelumnya
        return back()->with('success', 'Laporan berhasil ditolak!');
    }

    // Tampilkan halaman detail
    public function show($reportId)
    {
        // Cari laporan dengan ID yang diberikan
        $report = Report::with('responses', 'user')->findOrFail($reportId);

        // Cek apakah laporan ini sesuai dengan provinsi staff
        if ($report->province != Auth::user()->staffProvince->province) {
            return redirect()->route('staff.index')->with('error', 'Anda tidak memiliki izin untuk melihat laporan ini.');
        }

        // Ambil response terakhir
        $response = $report->responses->last();

        // Ambil nama lokasi
        $report->province_name = $this->getLocationName("provinces", $report->province);
        $report->regency_name = $this->getLocationName("regencies/{$report->province}", $report->regency);
        $report->subdistrict_name = $this->getLocationName("districts/{$report->regency}", $report->subdistrict);
        $report->village_name = $this->getLocationName("villages/{$report->subdistrict}", $report->village);

        // Kirim data ke view detail
        return view('staff.detail', compact('report', 'response'));
    }

    private function getLocationName($endpoint, $id)
    {
        $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/{$endpoint}.json");

        // Cek apakah API responsenya sukses dan data valid
        if ($response->successful()) {
            $location = collect($response->json())->firstWhere('id', $id);
            return $location ? $location['name'] : 'Unknown';
        }

        // Jika gagal, kembalikan status error
        return 'Unknown (API Error)';
    }

    // Simpan rincian proses di halaman detail
    public function storeDetails(Request $request, $reportId)
    {
        $request->validate([
            'details' => 'required|string',
        ]);

        // Ambil laporan
        $report = Report::with('responses')->findOrFail($reportId);

        // Ambil atau buat response
        $response = $report->responses()->firstOrCreate([
            'response_status' => 'ON_PROCESS',
            'staff_id' => auth()->id(),
        ]);

        // Tambahkan riwayat ke progress
        $response->progress()->create([
            'histories' => ['detail' => $request->details],
        ]);

        return redirect()->back()->with('success', 'Riwayat proses berhasil disimpan!');
    }

    public function complete($reportId)
    {
        // Cari laporan beserta responses yang terkait
        $report = Report::with('responses')->findOrFail($reportId);

        // Ambil response terakhir
        $response = $report->responses->last();

        // Pastikan ada response yang terkait dan response_status bukan 'DONE'
        if ($response && $response->response_status !== 'DONE') {
            // Update response_status menjadi 'DONE'
            $response->update(['response_status' => 'DONE']);
        } else {
            // Jika status sudah 'DONE', beri pesan atau logika lain jika perlu
            return redirect()->route('staff.detail', $reportId)->with('info', 'Laporan sudah selesai.');
        }

        // Redirect kembali ke halaman detail dengan pesan sukses
        return redirect()->route('staff.detail', $reportId)->with('success', 'Laporan berhasil ditandai sebagai selesai.');
    }

    public function deleteProgress($reportId, $progressId)
    {
        // Cari laporan dan pastikan ada progress yang terkait
        $report = Report::with('responses')->findOrFail($reportId);
        $response = $report->responses->last(); // Ambil response terakhir

        // Cari progress yang ingin dihapus
        $progress = $response->progress()->findOrFail($progressId);

        // Hapus progress
        $progress->delete();

        // Redirect kembali ke halaman detail dengan pesan sukses
        return redirect()->route('staff.detail', $reportId)->with('success', 'Riwayat berhasil dihapus!');
    }

    public function export(Request $request)
    {
        // Ambil filter berdasarkan provinsi dan rentang tanggal (jika ada)
        $filter_option = $request->filter_option;
        $staffProvince = Auth::user()->staffProvince;
        $staffProvinceId = $staffProvince->province;

        // Logika untuk opsi 'Berdasarkan Provinsi'
        if ($filter_option == 'date_range') {
            $start_date = $request->start_date;
            $end_date = $request->end_date;

            // Validasi input tanggal
            if (!$start_date || !$end_date) {
                return redirect()->back()->with('error', 'Silakan pilih rentang tanggal.');
            }

            // Export data dalam rentang tanggal dan sesuai provinsi staff
            return Excel::download(new ReportsExport($start_date, $end_date, $staffProvinceId), 'reports_filtered.xlsx');
        }

        // Jika opsi 'Semua Data'
        return Excel::download(new ReportsExport(null, null, $staffProvinceId), 'all_reports.xlsx');
    }
}