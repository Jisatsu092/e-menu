<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white bg-blue-600 p-4 rounded-lg shadow">
            {{ __('LAPORAN TRANSAKSI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-blue-600">
                <div class="p-6">
                    <!-- Filter Section -->
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                            <div class="flex items-center space-x-2">
                                <label for="start_date" class="text-blue-600">Dari Tanggal</label>
                                <input type="date" id="start_date" name="start_date"
                                    class="bg-white border-2 border-blue-600 text-blue-600 rounded-lg p-2"
                                    value="{{ request('start', $minDate ?? now()->format('Y-m-d')) }}">
                            </div>
                            <div class="flex items-center space-x-2">
                                <label for="end_date" class="text-blue-600">Sampai Tanggal</label>
                                <input type="date" id="end_date" name="end_date"
                                    class="bg-white border-2 border-blue-600 text-blue-600 rounded-lg p-2"
                                    value="{{ request('end', $maxDate ?? now()->format('Y-m-d')) }}">
                            </div>
                            <button onclick="applyDateFilter()"
                                class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg shadow-md">
                                Filter
                            </button>
                            <button onclick="printAllTransactions()"
                                class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white shadow-md">
                                🖨️ Print Semua
                            </button>
                        </div>
                        <div class="w-full md:w-auto">
                            <input type="text" id="search" placeholder="Cari transaksi..."
                                class="bg-white border-2 border-blue-600 text-blue-600 rounded-lg p-2 w-full"
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-900">
                            <thead class="text-xs text-white uppercase bg-blue-600">
                                <tr>
                                    <th class="px-6 py-3">No</th>
                                    <th class="px-6 py-3">User</th>
                                    <th class="px-6 py-3">Meja</th>
                                    <th class="px-6 py-3">Total</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr class="bg-white border-b hover:bg-blue-50">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $transaction->user->name ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $transaction->table->number ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-3 py-1 rounded-full text-white 
                                                {{ $transaction->status == 'paid' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button onclick="printTransaction('{{ $transaction->id }}')"
                                                class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-md text-white shadow">
                                                🖨️ Print
                                            </button>
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

    <!-- Print Template -->
    <div id="print-template" class="hidden">
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                #print-section,
                #print-section * {
                    visibility: visible;
                }

                #print-section {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                .print-container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 40px;
                    font-family: 'Arial', sans-serif;
                }

                .print-header {
                    text-align: center;
                    margin-bottom: 30px;
                }

                .print-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }

                .print-table th,
                .print-table td {
                    border: 1px solid #ddd;
                    padding: 12px;
                    text-align: left;
                }
            }
        </style>
        <div id="print-section"></div>
    </div>

    <script>
        function applyDateFilter() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const search = document.getElementById('search').value;

            const url = new URL(window.location.href);
            url.searchParams.set('start', startDate);
            url.searchParams.set('end', endDate);
            url.searchParams.set('search', search);

            window.location.href = url.toString();
        }

        function printTransaction(id) {
            fetch(`/transaction/${id}/print`)
                .then(response => response.text())
                .then(html => {
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write(html);
                    printWindow.document.close();
                    printWindow.print();
                });
        }

        function printAllTransactions() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            const params = new URLSearchParams({
                start: startDate,
                end: endDate
            });

            window.open(`/transaction/print-all?${params.toString()}`, '_blank');
        }

        document.getElementById('search').addEventListener('input', function() {
            const search = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const user = row.cells[1].textContent.toLowerCase();
                const table = row.cells[2].textContent.toLowerCase();
                const total = row.cells[3].textContent.toLowerCase();

                if (user.includes(search) || table.includes(search) || total.includes(search)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>
