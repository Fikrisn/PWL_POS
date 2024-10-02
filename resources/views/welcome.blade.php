@extends('layouts.template')

@section('content')
    <div class="card bg-light shadow-sm">
        <div class="card-header">
            <h3 class="card-title text-uppercase">Halo Apakabar!!!</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body p-4">
            <p class="lead">Selamat datang semua, ini adalah halaman utama dari aplikasi ini.</p>
            <canvas id="sales-chart" width="400" height="200"></canvas>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById('sales-chart').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [{
                            label: 'Sales',
                            data: [100, 200, 255, 432, 612, 100, 167, 109, 276, 300, 400, 500],
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Marketing',
                            data: [100, 167, 109, 276, 300, 400, 500, 100, 200, 255, 432, 612],
                            backgroundColor: 'rgba(255, 99, 132, 1)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 3
                        }, {
                            label: 'Stock',
                            data: [ 300, 400, 500, 100, 100, 167, 109, 276, 200, 255, 432, 612],
                            backgroundColor: 'rgba(black)',
                            borderColor: 'rgba(blue)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
@endsection