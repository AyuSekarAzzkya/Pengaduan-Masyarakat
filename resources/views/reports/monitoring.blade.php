@extends('layouts.app')

@section('title', 'Monitoring Pengaduan')
@section('page-title', 'Monitoring Laporan')
@section('breadcrumb', 'Monitoring Laporan')

@section('styles')
    <style>
        /* Style untuk tab */
        .nav-tabs .nav-link {
            position: relative;
            color: #000 !important;
            border: none;
            font-size: 16px;
        }

        /* Garis bawah tab dengan transisi smooth */
        .nav-tabs .nav-link.active,
        .nav-tabs .nav-link:hover {
            color: #FF6600;
            /* Warna oranye */
            transition: color 0.3s ease;
        }

        .nav-tabs .nav-link.active::after,
        .nav-tabs .nav-link:hover::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #FF6600;
            /* Warna oranye */
            transition: all 0.3s ease-in-out;
        }

        .nav-tabs .nav-link.active::after {
            width: 100%;
        }

        /* Card style */
        .card-custom {
            margin-bottom: 1rem;
            text-align: left;
            border-radius: 10px;
            border: 1px solid #FF6600;
            /* Border oranye */
            background-color: #fff;
            /* Warna latar belakang */
        }

        .card-custom .card-header {
            background-color: #FF6600;
            /* Header oranye */
            color: white;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Ikon buka/tutup */
        .card-custom .card-header .toggle-icon {
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        .card-custom .card-body {
            display: none;
            /* Awalnya disembunyikan */
        }

        .card-custom.active .card-body {
            display: block;
            /* Ditampilkan jika card aktif */
        }

        .card-custom.active .card-header .toggle-icon {
            transform: rotate(180deg);
            /* Ikon berputar saat card terbuka */
        }

        /* Tab Content Style */
        .tab-content {
            padding: 20px 0;
        }

        .tab-pane img {
            max-height: 200px;
            object-fit: cover;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="data-tab" data-toggle="tab" href="#data" role="tab" aria-controls="data"
                    aria-selected="true">Data</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="gambar-tab" data-toggle="tab" href="#gambar" role="tab" aria-controls="gambar"
                    aria-selected="false">Gambar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="status-tab" data-toggle="tab" href="#status" role="tab" aria-controls="status"
                    aria-selected="false">Status</a>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="myTabContent">
            <!-- Tab 1: Data -->
            <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="data-tab">
                <div class="mt-3">
                    @foreach ($reports as $report)
                        <div class="card card-custom">
                            <div class="card-header" data-toggle="collapse" data-target="#collapse{{ $report->id }}"
                                aria-expanded="false" aria-controls="collapse{{ $report->id }}">
                                <strong>Tanggal: </strong>{{ $report->created_at->format('d F Y') }}
                                <i class="fas fa-chevron-down toggle-icon"></i> <!-- Ikon buka/tutup -->
                            </div>
                            <div id="collapse{{ $report->id }}" class="card-body">
                                <p><strong>Type : </strong>{{ $report->type }}</p>
                                <p><strong>Deskripsi: </strong>{{ $report->description }}</p>
                                <p><strong>Lokasi : </strong>{{ $report->village_name }}, {{ $report->subdistrict_name }},
                                    {{ $report->regency_name }}, {{ $report->province_name }} </p>

                                <!-- Tombol Hapus (Jika status PENDING atau REJECT) -->
                                @if ($report->status == 'pending' || $report->status == 'reject')
                                    <form action="{{ route('reports.destroy', $report->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @endif
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
                            <div class="card-header" data-toggle="collapse" data-target="#collapseImage{{ $report->id }}"
                                aria-expanded="false" aria-controls="collapseImage{{ $report->id }}">
                                Gambar Pengaduan
                                <i class="fas fa-chevron-down toggle-icon"></i> <!-- Ikon buka/tutup -->
                            </div>
                            <div id="collapseImage{{ $report->id }}" class="card-body">
                                <img src="{{ asset('storage/' . $report->image) }}" alt="Gambar Pengaduan"
                                    class="img-fluid mx-auto d-block"
                                    style="width: 300px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab 3: Status -->

            <!-- Tab 3: Status -->
            <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
                <div class="mt-3">
                    <h5><strong>Status Pengaduan</strong></h5>
                    @foreach ($reports as $report)
                        <div class="card card-custom">
                            <!-- Header untuk collapse -->
                            <div class="card-header" data-toggle="collapse"
                                data-target="#collapseStatus{{ $report->id }}" aria-expanded="false"
                                aria-controls="collapseStatus{{ $report->id }}">
                                Status Pengaduan - {{ $report->created_at->format('d F Y') }}
                                <i class="fas fa-chevron-down toggle-icon"></i> <!-- Ikon buka/tutup -->
                            </div>

                            <!-- Card Body -->
                            <div id="collapseStatus{{ $report->id }}" class="collapse">
                                <div class="card-body">
                                    <!-- Status Respons Staff -->
                                    <p><strong>Status Respon:</strong>
                                        @if ($report->responses->isNotEmpty())
                                            {{ $report->responses->first()->response_status }}
                                        @else
                                            <span class="text-muted">Belum ada respons</span>
                                        @endif
                                    </p>

                                    <!-- Riwayat Proses -->
                                    <p><strong>Riwayat Proses:</strong></p>

                                    @if ($report->responses->isNotEmpty())
                                        @foreach ($report->responses as $response)
                                            @if ($response->progress->isNotEmpty())
                                                <ul>
                                                    @foreach ($response->progress as $prog)
                                                        <li>
                                                            {{-- Check if $prog->histories is an array and convert it to a string --}}
                                                            {{ is_array($prog->histories) ? implode(', ', $prog->histories) : $prog->histories ?? 'Detail proses tidak tersedia' }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted">Belum ada riwayat proses.</p>
                                            @endif
                                        @endforeach
                                    @else
                                        <p class="text-muted">Belum ada respons.</p>
                                    @endif

                                    <!-- Tombol Hapus (jika status REJECT, PENDING, atau Belum ada respons) -->
                                    @if ($report->status == 'REJECT' || $report->status == 'PENDING' || $report->responses->isEmpty())
                                        <form action="{{ route('reports.destroy', $report->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Script untuk membuka dan menutup card
        $(document).ready(function() {
            $('.card-header').on('click', function() {
                var card = $(this).closest('.card-custom');
                card.toggleClass('active');
            });
        });
    </script>
@endsection
