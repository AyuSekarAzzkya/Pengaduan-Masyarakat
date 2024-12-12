@extends('layouts.app')
@section('title', 'Article Report')

@section('page-title', 'Article Report')
@section('breadcrumb', 'Article Report')

@section('content')

    <div class="container mt-2">
        <h2>Daftar Pengaduan</h2>
        <div class="row">
            <!-- Left Section: Dropdown dan Search Button -->
            <div class="col-md-8 border-end"> <!-- Wider section -->
                <div class="form-group mb-2">
                    <label for="province">Pilih Provinsi</label>
                    <div class="input-group">
                        <!-- Province Dropdown -->
                        <select id="province" class="form-control">
                            <option value="">-- Pilih Provinsi --</option>
                            <!-- Provinsi akan dimuat dari API eksternal -->
                        </select>
                        <!-- Search Button -->
                        <div class="input-group-append">
                            <button id="searchBtn" class="btn btn-primary" type="button">Cari</button>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="row" id="report-container">
                            <!-- Data laporan akan ditampilkan di sini -->
                            @foreach ($reports as $report)
                                <div class="col-md-12 mb-2">
                                    <div class="card shadow-sm">
                                        <div class="row no-gutters">
                                            <!-- Gambar Laporan -->
                                            <div class="col-md-4 mt-1">
                                                <img src="/storage/{{ $report->image }}" class="d-block w-100"
                                                    alt="Report Image"
                                                    style="height: 200px; object-fit: cover; border-radius: 4%;">
                                            </div>
                                            <!-- Deskripsi Laporan -->
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <a href="{{ route('reports.show', $report->id) }}"
                                                            style="text-decoration: none; color: inherit;">
                                                            {{ substr($report->description, 0, 120) }}
                                                        </a>
                                                    </h5>
                                                    <p class="card-text"><small>By: {{ $report->user->email }}</small></p>

                                                    <!-- Informasi: View dan Vote count dengan flex -->
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <!-- Jumlah View -->
                                                        <div>
                                                            <i class="fas fa-eye"></i> {{ $report->views_count }}
                                                        </div>

                                                        <!-- Jumlah Vote -->
                                                        <div>
                                                            <i class="fas fa-heart"></i>
                                                            <span id="voteCount-{{ $report->id }}">
                                                                {{ count(json_decode($report->voting)) }}
                                                            </span>
                                                        </div>

                                                        <!-- Tombol vote dengan icon hati di ujung kanan -->
                                                        <form action="{{ route('reports.vote', $report->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" id="likeButton-{{ $report->id }}"
                                                                class="btn btn-link">
                                                                <i class="fa-regular fa-heart"
                                                                    id="heartIcon-{{ $report->id }}"
                                                                    style="font-size: 20px;"></i>
                                                                <br>Vote
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Section: Card yang Ditempatkan di Samping, remains unchanged -->
            <div class="col-md-4 d-flex justify-content-center">
                <div class="card shadow-sm fixed-card" style="width: 22rem;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Informasi Tambahan</h5>
                        <p class="card-text" style="text-align: left;">
                            1. Pengaduan bisa dibuat hanya jika Anda telah membuat akun sebelumnya. <br>
                            2. Keseluruhan data pada pengaduan bernilai BENAR dan DAPAT DIPERTANGGUNG JAWABKAN. <br>
                            3. Seluruh bagian data perlu diisi. <br>
                            4. Pengaduan Anda akan ditanggapi dalam 2x24 Jam. <br>
                            5. Periksa tanggapan Kami, pada Dashboard setelah Anda Login. <br>
                            6. Pembuatan pengaduan dapat dilakukan pada halaman dibawah ini. <br>
                        </p>
                        <button class="btn btn-info">
                            <a href="{{ route('reports.create') }}" style="color: black; text-decoration: none;">Buat Pengaduan</a>
                        </button>
                    </div>
                </div>
            </div>            
        </div>

        <!-- Section untuk Menampilkan Data Laporan -->

    </div>

    <hr>

    <!-- Include Font Awesome for Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Script for loading provinces and fetching reports -->
    <script type="text/javascript">
        $(document).ready(function() {
            // Load provinces dynamically from external API
            $.ajax({
                url: 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
                type: 'GET',
                success: function(response) {
                    $.each(response, function(index, province) {
                        $('#province').append(`
                            <option value="${province.id}">${province.name}</option>
                        `);
                    });
                },
                error: function() {
                    alert('Gagal memuat data provinsi');
                }
            });
    
            // Fetch reports dynamically when the search button is clicked
            $('#searchBtn').click(function() {
                console.log("Tombol Cari diklik");
                var provinceId = $('#province').val();
    
                if (!provinceId) {
                    alert("Pilih provinsi terlebih dahulu");
                    return;
                }
    
                $.ajax({
                    url: '{{ route('reports.index') }}',  // Gunakan route Laravel untuk permintaan
                    type: 'GET',
                    data: {
                        province: provinceId,
                        ajax: true
                    },
                    success: function(response) {
                        console.log(response); // Menampilkan response di console untuk debugging
                        if (response.reports && response.reports.length > 0) {
                            renderReports(response.reports);
                        } else {
                            $('#report-container').html('<p class="text-center">Tidak ada laporan ditemukan.</p>');
                        }
                    },
                    error: function() {
                        alert('Gagal mengambil data laporan');
                    }
                });
            });
    
            // Function to render reports dynamically
            function renderReports(reports) {
                var reportsHtml = '';
                $.each(reports, function(index, report) {
                    reportsHtml += `
                        <div class="col-md-12 mb-2">
                            <div class="card shadow-sm">
                                <div class="row no-gutters">
                                    <div class="col-md-4 mt-4">
                                        <img src="/storage/${report.image}" class="d-block w-100" alt="Report Image" style="height: 180px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a href="{{ route('reports.show', '') }}/${report.id}" style="text-decoration: none; color: inherit;">
                                                    ${report.description.substring(0, 120)}
                                                </a>
                                            </h5>   
                                            <p class="card-text"><small>By: ${report.user.email}</small></p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-eye"></i> ${report.views_count}
                                                </div>
                                                <div>
                                                    <i class="fas fa-heart"></i>
                                                    <span id="voteCount-${report.id}">
                                                        ${countVotes(report.voting)}
                                                    </span>
                                                </div>
                                                <form action="/reports/vote/${report.id}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link">
                                                        <i class="fa-regular fa-heart" style="font-size: 20px;"></i>
                                                        <br>Vote
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
    
                $('#report-container').html(reportsHtml);
            }
    
            // Function to count votes from JSON
            function countVotes(voting) {
                return voting ? voting.length : 0; // Safely count votes if available
            }
        });
    </script>
    
    

@endsection
