<x-app-layout>
    <style>
        /* Hapus panah default pada details */
        summary::-webkit-details-marker {
            display: none;
        }

        summary {
            list-style: none;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Daftar Detail Transaksi</h2>
                        <div>
                            <a href="{{ route('transaction_details.report') }}"
                                onclick="window.open(this.href, 'newwindow', 'width=800,height=600'); return false;"
                                class="no-print bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mr-2">
                                Print Report
                            </a>
                            @can('role-A')
                                <form action="{{ route('transaction_details.destroyAll') }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua detail transaksi?')"
                                    class="no-print inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                        Clear All
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <!-- Desktop View -->
                    <div class="hidden md:block">
                        <div class="border rounded-lg overflow-hidden">
                            <!-- Table Header -->
                            <div class="grid grid-cols-12 gap-4 bg-gray-100 px-4 py-3 font-semibold">
                                <div class="col-span-6">Transaksi</div>
                                <div class="col-span-2">Jumlah Item</div>
                                <div class="col-span-3">Total Transaksi</div>
                                <div class="col-span-1"></div>
                            </div>

                            @foreach ($details->groupBy('transaction_id') as $transactionId => $transactionDetails)
                                @php
                                    $total = $transactionDetails->sum('subtotal');
                                    $transactionCode = optional($transactionDetails->first()->transaction)->code ?? '-';
                                @endphp
                                <details class="group border-t">
                                    <summary
                                        class="grid grid-cols-12 gap-4 items-center px-4 py-3 cursor-pointer hover:bg-gray-50">
                                        <div class="col-span-6 font-medium">#{{ $transactionCode }}</div>
                                        <div class="col-span-2">{{ $transactionDetails->count() }} Item</div>
                                        <div class="col-span-3">Rp{{ number_format($total, 0, ',', '.') }}</div>
                                        <div class="col-span-1 text-right">
                                            <span
                                                class="transform transition-transform duration-300 group-open:-rotate-180">▼</span>
                                        </div>
                                    </summary>

                                    <!-- Sub-table -->
                                    <div class="ml-8 mr-4 my-2">
                                        <div class="grid grid-cols-12 gap-4 bg-gray-50 px-4 py-2 font-medium">
                                            <div class="col-span-6">Toping</div>
                                            <div class="col-span-3">Qty</div>
                                            <div class="col-span-3">Subtotal</div>
                                        </div>

                                        @foreach ($transactionDetails as $detail)
                                            <div class="grid grid-cols-12 gap-4 px-4 py-2 border-t hover:bg-gray-50">
                                                <div class="col-span-6">{{ $detail->toping->name ?? '-' }}</div>
                                                <div class="col-span-3">{{ $detail->quantity }}</div>
                                                <div class="col-span-3">
                                                    Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    </div>

                    <!-- Mobile View -->
                    <div class="md:hidden space-y-3">
                        @foreach ($details->groupBy('transaction_id') as $transactionId => $transactionDetails)
                            @php
                                $total = $transactionDetails->sum('subtotal');
                                $transactionCode = optional($transactionDetails->first()->transaction)->code ?? '-';
                            @endphp
                            <details class="border rounded-lg bg-white">
                                <summary class="p-4 cursor-pointer">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-semibold">#{{ $transactionCode }}</div>
                                            <div class="text-sm text-gray-600 mt-1">
                                                {{ $transactionDetails->count() }} Item •
                                                Rp{{ number_format($total, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <span class="text-blue-600 underline">Detail</span>
                                    </div>
                                </summary>

                                <div class="px-4 pb-4 space-y-3 border-t">
                                    @foreach ($transactionDetails as $detail)
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-sm"><span class="font-medium">Toping:</span>
                                                {{ $detail->toping->name ?? '-' }}</p>
                                            <p class="text-sm"><span class="font-medium">Qty:</span>
                                                {{ $detail->quantity }}</p>
                                            <p class="text-sm font-semibold text-green-600">
                                                Subtotal: Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $details->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
