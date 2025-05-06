<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-white bg-red-600 p-4 rounded-lg">
            🍜 
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistik Utama -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Meja Tersedia -->
                <div class="bg-white p-6 rounded-lg shadow-lg relative">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-red-600 font-medium">Meja Tersedia</p>
                            <p class="text-3xl font-bold text-red-800 mt-2">{{ $availableTables }}</p>
                        </div>
                        <div class="w-20 h-20">
                            <canvas id="availableChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Meja Terisi -->
                <div class="bg-white p-6 rounded-lg shadow-lg relative">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-red-600 font-medium">Meja Terisi</p>
                            <p class="text-3xl font-bold text-red-800 mt-2">{{ $occupiedTables }}</p>
                        </div>
                        <div class="w-20 h-20">
                            <canvas id="occupiedChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Total Meja -->
                <div class="bg-white p-6 rounded-lg shadow-lg relative">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-red-600 font-medium">Total Meja</p>
                            <p class="text-3xl font-bold text-red-800 mt-2">{{ $totalTables }}</p>
                        </div>
                        <div class="w-20 h-20">
                            <canvas id="totalTablesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Total User -->
                <div class="bg-white p-6 rounded-lg shadow-lg relative">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-red-600 font-medium">Total User</p>
                            <p class="text-3xl font-bold text-red-800 mt-2">{{ $totalUsers }}</p>
                        </div>
                        <div class="w-20 h-20">
                            <canvas id="usersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Transaksi -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
                <h3 class="text-lg font-semibold text-red-600 mb-4">Statistik Transaksi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="p-4 bg-red-50 rounded-lg">
                        <p class="text-sm text-red-600">Harian: {{ $dailyTransactions }}</p>
                    </div>
                    <div class="p-4 bg-red-50 rounded-lg">
                        <p class="text-sm text-red-600">Mingguan: {{ $weeklyTransactions }}</p>
                    </div>
                    <div class="p-4 bg-red-50 rounded-lg">
                        <p class="text-sm text-red-600">Bulanan: {{ $monthlyTransactions }}</p>
                    </div>
                </div>
                <canvas id="transactionChart" class="w-full h-64"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Warna Dasar
        const redTheme = {
            available: '#dc2626',
            occupied: '#f87171',
            background: '#fecaca',
            text: '#991b1b'
        };

        // Chart Meja Tersedia
        new Chart(document.getElementById('availableChart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [{{ $availableTables }}, {{ $totalTables - $availableTables }}],
                    backgroundColor: [redTheme.available, redTheme.background],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });

        // Chart Meja Terisi
        new Chart(document.getElementById('occupiedChart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [{{ $occupiedTables }}, {{ $totalTables - $occupiedTables }}],
                    backgroundColor: [redTheme.occupied, redTheme.background],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });

        // Chart Total Meja
        new Chart(document.getElementById('totalTablesChart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [{{ $totalTables }}],
                    backgroundColor: [redTheme.available],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });

        // Chart User
        new Chart(document.getElementById('usersChart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [{{ $totalUsers }}],
                    backgroundColor: [redTheme.available],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });

        // Chart Transaksi
        new Chart(document.getElementById('transactionChart'), {
            type: 'line',
            data: {
                labels: ['Harian', 'Mingguan', 'Bulanan'],
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: [
                        {{ $dailyTransactions }},
                        {{ $weeklyTransactions }},
                        {{ $monthlyTransactions }}
                    ],
                    borderColor: redTheme.available,
                    tension: 0.4,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#fecaca' },
                        ticks: { color: redTheme.text }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: redTheme.text }
                    }
                }
            }
        });
    </script>
</x-app-layout>