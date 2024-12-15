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
        // Ambil semua laporan dengan relasi user dan response
        $reports = Report::with('user', 'responses')->orderBy('created_at', 'desc')->get();

        // Ambil data lokasi dari API berdasarkan ID
        foreach ($reports as $report) {
            // Ambil nama provinsi
            $report->province_name = $this->getLocationName("provinces", $report->province);

            // Ambil nama kabupaten
            $report->regency_name = $this->getLocationName("regencies/{$report->province}", $report->regency);

            // Ambil nama kecamatan
            $report->subdistrict_name = $this->getLocationName("districts/{$report->regency}", $report->subdistrict);

            // Ambil nama desa
            $report->village_name = $this->getLocationName("villages/{$report->subdistrict}", $report->village);
        }

        // Kirim data ke view staff.index
        return view('staff.index', compact('reports'));
    }

    public function update(Request $request, $reportId)
    {
        // Validate the action (ON_PROCESS or REJECT)
        $request->validate([
            'action' => 'required|in:ON_PROCESS,REJECT',
        ]);

        // Find the report
        $report = Report::findOrFail($reportId);

        // Update or create a response for the report
        $response = Responses::updateOrCreate(
            ['report_id' => $report->id],
            [
                'status' => $request->action,
                'staff_id' => Auth::id(),
            ]
        );

        // If the action is 'ON_PROCESS', redirect to the detail page
        if ($request->action === 'ON_PROCESS') {
            return redirect()->route('staff.detail', $reportId)
                ->with('success', 'Laporan diproses! Tambahkan rincian di halaman detail.');
        }

        // If the action is 'REJECT', simply go back to the previous page with a success message
        return back()->with('success', 'Laporan berhasil ditolak!');
    }



    // Tampilkan halaman detail
    public function show($reportId)
    {
        // Ambil laporan beserta responses dan user
        $report = Report::with('responses', 'user')->findOrFail($reportId);

        // Ambil response terakhir
        $response = $report->responses->last(); // atau first() tergantung data yang diinginkan

        // Ambil nama lokasi (jika diperlukan)
        $report->province_name = $this->getLocationName("provinces", $report->province);
        $report->regency_name = $this->getLocationName("regencies/{$report->province}", $report->regency);
        $report->subdistrict_name = $this->getLocationName("districts/{$report->regency}", $report->subdistrict);
        $report->village_name = $this->getLocationName("villages/{$report->subdistrict}", $report->village);

        // Kirim data report dan response ke view
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
        $filter_option = $request->filter_option;

        // Logika untuk opsi 'Berdasarkan Tanggal'
        if ($filter_option == 'date_range') {
            $start_date = $request->start_date;
            $end_date = $request->end_date;

            // Validasi input tanggal
            if (!$start_date || !$end_date) {
                return redirect()->back()->with('error', 'Silakan pilih rentang tanggal.');
            }

            // Export data dalam rentang tanggal
            return Excel::download(new ReportsExport($start_date, $end_date), 'reports_filtered.xlsx');
        }

        // Jika opsi 'Semua Data'
        return Excel::download(new ReportsExport(), 'all_reports.xlsx');
    }
}
