<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-4">Daftar Detail Transaksi</h2>

                    <!-- Desktop Table -->
                    <div class="hidden md:block">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">ID</th>
                                    <th class="px-4 py-2">Transaksi</th>
                                    <th class="px-4 py-2">Toping</th>
                                    <th class="px-4 py-2">Qty</th>
                                    <th class="px-4 py-2">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($details as $detail)
                                <tr>
                                    <td class="border px-4 py-2">{{ $detail->id }}</td>
                                    <td class="border px-4 py-2">
                                        #{{ $detail->transaction->code ?? '-' }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        {{ $detail->toping->name ?? '-' }}
                                    </td>
                                    <td class="border px-4 py-2">{{ $detail->quantity }}</td>
                                    <td class="border px-4 py-2">
                                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-4">
                        @foreach($details as $detail)
                        <div class="bg-white p-4 rounded-lg shadow border">
                            <div class="flex justify-between items-center border-b pb-2">
                                <div class="font-semibold text-gray-600">ID: {{ $detail->id }}</div>
                                <span class="text-sm bg-blue-100 px-2 py-1 rounded">
                                    #{{ $detail->transaction->code ?? '-' }}
                                </span>
                            </div>
                            <div class="mt-2 space-y-1">
                                <p class="text-sm">
                                    <span class="font-medium">Toping:</span> 
                                    {{ $detail->toping->name ?? '-' }}
                                </p>
                                <p class="text-sm">
                                    <span class="font-medium">Qty:</span> 
                                    {{ $detail->quantity }}
                                </p>
                                <p class="text-sm font-semibold text-green-600">
                                    Subtotal: Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $details->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>