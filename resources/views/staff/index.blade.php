@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Daftar Pengaduan User</h1>
        <div style="margin-bottom: 10px">
            <!-- Button Export -->
            <!-- Button Export -->
            <button type="button" class="btn btn-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Export ke Excel
            </button>
            <div class="dropdown-menu">
                <button class="dropdown-item" id="exportAllButton">Semua Data</button>
                <button class="dropdown-item" data-toggle="modal" data-target="#dateModal">Berdasarkan Tanggal</button>
            </div>

            <!-- Modal untuk Memilih Tanggal -->
            <div class="modal fade" id="dateModal" tabindex="-1" role="dialog" aria-labelledby="dateModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="dateModalLabel">Pilih Tanggal</h5>
                        </div>
                        <form action="{{ route('staff.export') }}" method="get">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="selected_date">Tanggal:</label>
                                    <input type="date" name="selected_date" id="selected_date" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Export</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <!-- Script untuk Aksi Tombol -->
        <script>
            // Jika tombol "Semua Data" ditekan
            document.getElementById('exportAllButton').addEventListener('click', function() {
                window.location.href = "{{ route('staff.export', ['filter_option' => 'all']) }}";
            });
        </script>


        <!-- Tabel Laporan -->
        <table class="table table-bordered table-striped">


            <thead class="thead-dark">
                <tr>
                    <th style="width: 100px;">Gambar</th>
                    <th>User</th>
                    <th>Lokasi & Tanggal</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Jumlah Vote</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <!-- Gambar -->
                        <td class="text-center">
                            @if ($report->image)
                                <img src="{{ asset('storage/' . $report->image) }}" class="rounded-circle" width="70"
                                    height="70" alt="Report Image">
                            @else
                                <span>Tidak ada gambar</span>
                            @endif
                        </td>

                        <!-- User Email -->
                        <td>
                            <small>{{ $report->user->email ?? 'User tidak ditemukan' }}</small>
                        </td>

                        <!-- Lokasi & Tanggal -->
                        <td>
                            <p>{{ $report->village_name }}, {{ $report->subdistrict_name }}, {{ $report->regency_name }},
                                {{ $report->province_name }}</p>
                            <small>{{ $report->created_at->format('d M Y') }}</small>
                        </td>

                        <!-- Deskripsi -->
                        <td>{{ $report->description }}</td>

                        <!-- Jumlah Vote -->
                        <td class="text-center">
                            {{ count(json_decode($report->voting ?? '[]', true)) }}
                        </td>

                        <!-- Aksi -->
                        <td>
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target="#actionModal-{{ $report->id }}">
                                Tindak Lanjut
                            </button>
                        </td>
                        <!-- Modal -->
                        <div class="modal fade" id="actionModal-{{ $report->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Tindak Lanjut Pengaduan</h5>
                                    </div>
                                    <form action="{{ route('staff.action', $report->id) }}" method="POST">
                                        <!-- Correct route here -->
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="action">Tanggapan</label>
                                                <select name="action" id="action" class="form-control" required>
                                                    <option value="ON_PROCESS">Proses Penyelesaian/Perbaikan</option>
                                                    <option value="REJECT">Tolak</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Buat</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada laporan tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
