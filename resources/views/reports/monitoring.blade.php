@extends('layouts.app')

@section('title', 'Monitoring Pengaduan')
@section('page-title', 'Monitoring Laporan')
@section('breadcrumb', 'Monitoring Laporan')

@section('content')
<div class="container">
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="data-tab" data-toggle="tab" href="#data" role="tab" aria-controls="data" aria-selected="true">Data</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="gambar-tab" data-toggle="tab" href="#gambar" role="tab" aria-controls="gambar" aria-selected="false">Gambar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="status-tab" data-toggle="tab" href="#status" role="tab" aria-controls="status" aria-selected="false">Status</a>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="myTabContent">
        <!-- Tab 1: Data -->
        <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="data-tab">
            <div class="mt-3">
                <h5><strong>Data Pengaduan</strong></h5>
                @foreach ($reports as $report)
                    <div class="card card-custom">
                        <div class="card-body">
                            <p><strong>Tanggal: </strong>{{ $report->created_at->format('d F Y') }}</p>
                            <p><strong>Deskripsi: </strong>{{ $report->description }}</p>
                            <p><strong>Lokasi: </strong>
                                {{ $report->province_name }}, 
                                {{ $report->regency_name }}, 
                                {{ $report->subdistrict_name }}, 
                                {{ $report->village_name }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tab 2: Gambar -->
        <div class="tab-pane fade" id="gambar" role="tabpanel" aria-labelledby="gambar-tab">
            <div class="mt-3">
                <h5><strong>Gambar Pengaduan</strong></h5>
                @foreach ($reports as $report)
                    <div class="card card-custom">
                        <div class="card-body">
                            <img src="{{ asset('storage/' . $report->image) }}" class="w-100 mb-3" alt="Gambar Pengaduan">                        
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tab 3: Status -->
        <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
            <div class="mt-3">
                <h5><strong>Status Pengaduan</strong></h5>
                @foreach ($reports as $report)
                    <div class="card card-custom">
                        <div class="card-body">
                            <p><strong>Status: </strong>
                                @if ($report->status == 'done')
                                    <span class="badge badge-success">DONE</span>
                                @else
                                    <span class="badge badge-warning">PENDING</span>
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
    