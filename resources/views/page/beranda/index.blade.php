<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Warung Seblak') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Grid Statistik Utama -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Card Meja Tersedia -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="text-gray-500 text-sm mb-2">Meja Tersedia</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ $mejaKosong }}</div>
                    <div class="charts-css column show-labels mt-3">
                        <div style="--size: calc({{ $mejaKosong }} / {{ $mejaKosong + $mejaTerisi }}); --color: #4f46e5"></div>
                    </div>
                </div>

                <!-- Card Meja Terisi -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="text-gray-500 text-sm mb-2">Meja Terisi</div>
                    <div class="text-2xl font-bold text-red-600">{{ $mejaTerisi }}</div>
                    <div class="charts-css column show-labels mt-3">
                        <div style="--size: calc({{ $mejaTerisi }} / {{ $mejaKosong + $mejaTerisi }}); --color: #ef4444"></div>
                    </div>
                </div>

                <!-- Card Total User -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="text-gray-500 text-sm mb-2">Total User</div>
                    <div class="text-2xl font-bold text-emerald-600">{{ $totalUser }}</div>
                    <div class="charts-css area show-labels mt-3">
                        <div style="--size: calc({{ $activeUser }} / {{ $totalUser }}); --color: #10b981"></div>
                    </div>
                </div>

                <!-- Card User Aktif -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="text-gray-500 text-sm mb-2">User Aktif</div>
                    <div class="text-2xl font-bold text-amber-600">{{ $activeUser }}</div>
                    <div class="charts-css area show-labels mt-3">
                        <div style="--size: calc({{ $activeUser }} / {{ $totalUser }}); --color: #f59e0b"></div>
                    </div>
                </div>
            </div>

            <!-- Section Pendapatan -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
                <!-- Pendapatan Harian -->
                <div class="bg-white shadow-sm sm:rounded-lg p-4 hover:shadow-md transition-shadow">
                    <h3 class="font-semibold text-lg mb-2 text-gray-700">Pendapatan Harian</h3>
                    <div class="text-2xl font-bold text-emerald-600">
                        Rp {{ number_format($dailyRevenue, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Pendapatan Mingguan -->
                <div class="bg-white shadow-sm sm:rounded-lg p-4 hover:shadow-md transition-shadow">
                    <h3 class="font-semibold text-lg mb-2 text-gray-700">Pendapatan Mingguan</h3>
                    <div class="text-2xl font-bold text-blue-600">
                        Rp {{ number_format($weeklyRevenue, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Pendapatan Bulanan -->
                <div class="bg-white shadow-sm sm:rounded-lg p-4 hover:shadow-md transition-shadow">
                    <h3 class="font-semibold text-lg mb-2 text-gray-700">Pendapatan Bulanan</h3>
                    <div class="text-2xl font-bold text-purple-600">
                        Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Section Grafik dan Tabel -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Grafik Pendapatan -->
                <div class="bg-white shadow-sm sm:rounded-lg p-4 hover:shadow-md transition-shadow">
                    <h3 class="font-semibold text-lg mb-4 text-gray-700">Pendapatan 7 Hari Terakhir</h3>
                    <div class="charts-css line show-labels show-primary-axis">
                        @foreach($weeklyRevenueChart as $data)
                        <div style="--size: calc({{ $data->total }} / {{ $weeklyRevenueChart->max('total') }}); --color: #3b82f6"
                             data-label="{{ date('d M', strtotime($data->date)) }}"></div>
                        @endforeach
                    </div>
                    <div class="mt-3 text-sm text-gray-500 text-center">
                        <span class="mr-2">&#9679; Hari</span>
                        <span class="mr-2">&#9679; Total Pendapatan</span>
                    </div>
                </div>

                <!-- Tabel Toping Terlaris -->
                <div class="bg-white shadow-sm sm:rounded-lg p-4 hover:shadow-md transition-shadow">
                    <h3 class="font-semibold text-lg mb-4 text-gray-700">Toping Terlaris</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="text-left border-b-2 border-gray-200">
                                    <th class="pb-3 text-gray-700">Nama Toping</th>
                                    <th class="pb-3 text-gray-700">Terjual</th>
                                    <th class="pb-3 text-gray-700">Market Share</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topToppings as $topping)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 text-gray-600">{{ $topping->name }}</td>
                                    <td class="py-3 font-medium text-blue-600">{{ $topping->transactions_count }}</td>
                                    <td class="py-3">
                                        <div class="charts-css bar show-labels">
                                            <div style="--size: calc({{ $topping->transactions_count }} / {{ $topToppings->sum('transactions_count') }}); --color: #f59e0b">
                                                <span class="data-value">{{ number_format(($topping->transactions_count / $topToppings->sum('transactions_count')) * 100, 1) }}%</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
    <style>
        .charts-css {
            height: 100px;
            margin: 1rem 0;
        }
        .charts-css.bar {
            height: 30px;
        }
        .charts-css.column::before,
        .charts-css.line::before {
            content: attr(data-label);
            display: block;
            text-align: center;
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }
        .data-value {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.8rem;
            color: #374151;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Script untuk update real-time (jika diperlukan)
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(() => {
                window.livewire.emit('refreshDashboard');
            }, 30000); // Refresh setiap 30 detik
        });
    </script>
    @endpush
</x-app-layout>