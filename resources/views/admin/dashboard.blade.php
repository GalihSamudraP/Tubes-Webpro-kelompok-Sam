@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>

        {{-- Sales Chart (Dominant) --}}
        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik Penjualan (7 Hari Terakhir)</h3>
            <div class="relative h-72 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-sm font-medium text-gray-500">Total Penjualan</h3>
                <p class="text-2xl font-bold text-gray-900" id="stat-sales">Rp
                    {{ number_format($stats['total_sales'] * 1000, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-sm font-medium text-gray-500">Total Pesanan</h3>
                <p class="text-2xl font-bold text-gray-900" id="stat-orders">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-sm font-medium text-gray-500">Total Menu</h3>
                <p class="text-2xl font-bold text-gray-900" id="stat-menu">{{ $stats['total_products'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-sm font-medium text-gray-500">Total Pengguna</h3>
                <p class="text-2xl font-bold text-gray-900" id="stat-users">{{ $stats['total_users'] }}</p>
            </div>
        </div>

        {{-- Action Links --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.menu.index') }}"
                class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">☕ Kelola Menu</h5>
                <p class="font-normal text-gray-700">Tambah, edit, atau hapus menu makanan dan minuman.</p>
            </a>
            <a href="{{ route('admin.promos.index') }}"
                class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">🏷️ Kelola Promo</h5>
                <p class="font-normal text-gray-700">Buat kode promo baru dan atur diskon.</p>
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">👥 Kelola Akun</h5>
                <p class="font-normal text-gray-700">Lihat semua pengguna yang terdaftar.</p>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        let salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: {!! json_encode($chartValues) !!},
                    borderColor: '#D97706', // Amber-600
                    backgroundColor: 'rgba(217, 119, 6, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    maximumSignificantDigits: 3
                                }).format(value);
                            }
                        }
                    }
                }
            }
        });

        // Auto Update Logic (Polling)
        function updateStats() {
            fetch('{{ route('admin.stats') }}')
                .then(response => response.json())
                .then(data => {
                    // Update Stats
                    document.getElementById('stat-sales').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data.stats.total_sales * 1000).replace(',00', '');
                    document.getElementById('stat-orders').innerText = data.stats.total_orders;
                    document.getElementById('stat-menu').innerText = data.stats.total_products;
                    document.getElementById('stat-users').innerText = data.stats.total_users;

                    // Update Chart
                    salesChart.data.labels = data.chart.labels;
                    salesChart.data.datasets[0].data = data.chart.values;
                    salesChart.update();
                })
                .catch(error => console.error('Error fetching stats:', error));
        }

        // Poll every 5 seconds
        setInterval(updateStats, 5000);
    </script>
@endsection