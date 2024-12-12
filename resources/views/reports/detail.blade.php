@extends('layouts.app')

@section('page-title', 'Detail Laporan') 
@section('breadcrumb', 'Detail Laporan')

@section('content')
<div class="container mt-5">
    <h2>Detail Laporan</h2>

    <!-- Card Laporan Detail -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="row no-gutters">
                    <!-- Gambar Laporan -->
                    <div class="col-md-4 mt-3">
                        <img src="/storage/{{ $report->image }}" class="d-block w-100" alt="Report Image"
                            style="height: 300px; object-fit: cover;">
                    </div>

                    <!-- Deskripsi Laporan -->
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $report->description }}</h5>
                            <p class="card-text"><small>By: {{ $report->user->email }}</small></p>
                            <p class="card-text">{{ $report->details }}</p> <!-- Detail Laporan -->
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Jumlah View -->
                                <div><i class="fas fa-eye"></i> {{ $report->views_count }}</div>

                                <!-- Jumlah Vote -->
                                <div><i class="fas fa-heart"></i> {{ count(json_decode($report->voting)) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card untuk Komentar -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <!-- Form Komentar -->
            <form action="{{ route('reports.comment', $report->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <textarea name="comment" class="form-control" rows="6" placeholder="Tulis komentar..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Kirim Komentar</button>
            </form>

            <!-- Daftar Komentar -->
            <div class="mt-4">
                <h6>Daftar Komentar:</h6>
                @foreach($report->comments as $comment)
                    <div class="card mb-2">
                        <div class="card-body">
                            <p>{{ $comment->user->email }} berkata:</p>
                            <p>{{ $comment->comment }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
