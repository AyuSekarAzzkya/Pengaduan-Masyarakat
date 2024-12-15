@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Grafik Pengaduan</h2>
    <div class="card shadow p-4">
        <canvas id="barChart" width="400" height="200"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Data total pengaduan dan tanggapan
        const reportsData = @json($reportsCount);
        const responsesData = @json($responsesCount);

        // Konfigurasi Chart.js
        const ctx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jumlah Pengaduan', 'Jumlah Tanggapan'], // Label sumbu X
                datasets: [
                    {
                        label: 'Data Statistik',
                        data: [reportsData, responsesData], // Data jumlah total
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)', // Biru lebih solid
                            'rgba(75, 192, 192, 0.8)'  // Hijau lebih solid
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1,
                        hoverBackgroundColor: [
                            'rgba(54, 162, 235, 1)', // Biru lebih terang saat hover
                            'rgba(75, 192, 192, 1)'  // Hijau lebih terang saat hover
                        ],
                        barPercentage: 0.7, // Lebar bar
                        categoryPercentage: 0.6, // Ruang antar bar
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#333',
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#ddd',
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        displayColors: false,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false, // Hilangkan garis grid horizontal
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#555'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(200, 200, 200, 0.3)' // Grid abu-abu tipis
                        },
                        title: {
                            display: true,
                            text: 'Jumlah',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#555'
                        },
                        ticks: {
                            stepSize: 1, // Langkah skala y
                            font: {
                                size: 12
                            },
                            color: '#555'
                        }
                    }
                },
                animation: {
                    duration: 2000, // Efek animasi saat load
                    easing: 'easeOutBounce'
                }
            }
        });
    });
</script>
@endsection
