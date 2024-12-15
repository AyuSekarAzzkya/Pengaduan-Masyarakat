@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Detail Laporan</h1>

        <!-- Informasi Laporan -->
        <div class="card mb-4 shadow-sm">
            <div class="row no-gutters">
                <!-- Gambar -->
                <div class="col-md-4 text-center p-3">
                    @if ($report->image)
                        <img src="{{ asset('storage/' . $report->image) }}" class="img-fluid rounded shadow"
                            alt="Report Image">
                    @else
                        <p class="text-muted">Tidak ada gambar</p>
                    @endif
                </div>

                <!-- Informasi Detail -->
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Laporan dari:
                            <span class="text-primary">{{ $report->user->email ?? 'User tidak ditemukan' }}</span>
                        </h5>
                        <p class="card-text"><strong>Deskripsi:</strong> {{ $report->description }}</p>

                        <!-- Alamat -->
                        <p class="card-text">
                            <strong>Lokasi:</strong> {{ $report->village_name }}, {{ $report->subdistrict_name }},
                            {{ $report->regency_name }}, {{ $report->province_name }}
                        </p>

                        <!-- Status -->
                        <p class="card-text">
                            <strong>Status:</strong>
                            @if ($response)
                                @if ($response->response_status === 'ON_PROCESS')
                                    <span class="badge badge-success p-2" style="background-color: blue">ON PROCESS</span>
                                @elseif($response->response_status === 'REJECT')
                                    <span class="badge badge-danger p-2" style="background-color: red">REJECTED</span>
                                @elseif($response->response_status === 'DONE')
                                    <span class="badge badge-secondary p-2" style="background-color: green">SELESAI</span>
                                @else
                                    <span class="badge badge-secondary p-2">{{ $response->response_status }}</span>
                                @endif
                            @else
                                <span class="badge badge-secondary p-2">Belum Diproses</span>
                            @endif
                        </p>

                        <!-- Riwayat -->
                        <p class="card-text">
                            <strong>Riwayat:</strong> <br>
                            @if ($response && $response->progress->isNotEmpty())
                                <!-- Menggunakan titik progress -->
                                <div class="progress-dots">
                                    @foreach ($response->progress as $progress)
                                        <div class="progress-step">
                                            <span class="dot"></span>
                                            <div class="progress-content">
                                                <!-- Menampilkan Riwayat -->
                                                @if (isset($progress->histories['detail']))
                                                    {{ $progress->histories['detail'] }}
                                                @else
                                                    Tidak ada rincian yang tersedia.
                                                @endif

                                                <!-- Tanggal -->
                                                <div class="text-muted small">
                                                    @if ($progress->created_at)
                                                        ({{ $progress->created_at->format('d F Y H:i') }})
                                                    @else
                                                        (Tanggal tidak tersedia)
                                                    @endif
                                                </div>

                                                <!-- Tombol Hapus Riwayat -->
                                                @if ($response->response_status !== 'DONE')
                                                    <!-- Hanya tampil jika status bukan 'Selesai' -->
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#deleteModal{{ $progress->id }}">
                                                        Hapus
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>Belum ada riwayat</p>
                            @endif
                        </p>

                        <!-- Tanggal Laporan -->
                        <p class="card-text text-muted">
                            <small>Tanggal Laporan: {{ $report->created_at->format('d F Y') }}</small>
                        </p>

                        <!-- Tombol Selesai -->
                        @if ($response && $response->response_status !== 'DONE')
                            <form action="{{ route('staff.complete', $report->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success mt-3">Selesaikan Laporan</button>
                            </form>
                        @endif

                        <!-- Tombol Kembali -->
                        <a href="{{ route('staff.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Tambah Riwayat -->
        @if ($response && $response->response_status !== 'DONE')
            <!-- Hanya tampil jika status bukan 'Selesai' -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Tambahkan Riwayat</h5>
                    <form action="{{ route('staff.detail.store', $report->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="details">Riwayat</label>
                            <textarea name="details" id="details" rows="4" class="form-control" placeholder="Masukkan riwayat proses..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Riwayat</button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Modal Konfirmasi Hapus -->
        @foreach ($response->progress as $progress)
            <div class="modal fade" id="deleteModal{{ $progress->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Riwayat</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus riwayat ini?
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('staff.deleteProgress', ['reportId' => $report->id, 'progressId' => $progress->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tambahkan CSS untuk Progress Dots -->
    <style>
        .progress-dots {
            position: relative;
            padding-left: 20px;
        }

        .progress-step {
            position: relative;
            margin-bottom: 20px;
        }

        .progress-step .dot {
            position: absolute;
            left: -20px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #007bff;
        }

        .progress-step .progress-content {
            margin-left: 10px;
        }
    </style>
@endsection
