@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard Head Staff</h2>

    <canvas id="barChart" width="400" height="200"></canvas>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Data untuk grafik
        const reportsData = @json(array_values($reportsCount));
        const responsesData = @json(array_values($responsesCount));
        const labels = @json(array_keys($reportsCount)); // Bulan

        // Konfigurasi Chart.js
        const ctx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels.map(month => `Bulan ${month}`), // Label bulan
                datasets: [
                    {
                        label: 'Jumlah Pengaduan',
                        data: reportsData,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)', // Warna biru
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Jumlah Tanggapan',
                        data: responsesData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)', // Warna hijau
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
