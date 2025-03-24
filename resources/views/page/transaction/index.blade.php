<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white bg-blue-600 p-4 rounded-lg shadow">
            {{ __('MANAJEMEN TRANSAKSI') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-blue-600">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <x-show-entries :route="route('category.index')" :search="request()->search" class="w-full md:w-auto"></x-show-entries>
                        <h3 class="text-lg font-medium text-blue-600">DAFTAR TRANSAKSI</h3>
                        <button type="button" onclick="toggleModal('createTransactionModal')"
                            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg shadow-md">
                            + Transaksi Baru
                        </button>
                    </div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-900">
                            <thead class="text-xs text-white uppercase bg-blue-600">
                                <tr>
                                    <th class="px-6 py-3">#</th>
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
                                        <td class="px-6 py-4 font-bold text-blue-600">
                                            {{ $transaction->table->number }}
                                        </td>
                                        <td class="px-6 py-4">Rp {{ number_format($transaction->total_price, 2) }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'paid' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                ];
                                            @endphp
                                            <span
                                                class="px-3 py-1.5 text-sm font-semibold rounded-full {{ $statusColors[$transaction->status] }}">
                                                {{ strtoupper($transaction->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 space-x-2">
                                            <button data-id="{{ $transaction->id }}"
                                                data-table_id="{{ $transaction->table_id }}"
                                                data-total_price="{{ $transaction->total_price }}"
                                                data-status="{{ $transaction->status }}"
                                                onclick="editTransactionModal(this)"
                                                class="bg-amber-500 hover:bg-amber-600 px-4 py-2 rounded-md text-white shadow">
                                                ✏️ Edit
                                            </button>
                                            <button onclick="confirmDelete('{{ $transaction->id }}')"
                                                class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md text-white shadow">
                                                🗑️ Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-blue-600">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Transaksi -->
    <div id="createTransactionModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg shadow-2xl mx-4 md:mx-auto md:w-1/2 border-2 border-blue-600">
            <div class="flex items-start justify-between p-6 border-b-2 border-blue-600">
                <h3 class="text-xl font-semibold text-blue-600">➕ Tambah Transaksi Baru</h3>
                <button type="button" onclick="toggleModal('createTransactionModal')"
                    class="text-blue-600 hover:text-blue-800 text-2xl">
                    &times;
                </button>
            </div>
            <form id="createTransactionForm" action="{{ route('transaction.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="table_id" class="block mb-2 text-sm font-medium text-blue-600">Pilih Meja</label>
                        <select name="table_id" id="table_id_create"
                            class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required>
                            <option value="">Pilih Meja</option>
                            @foreach ($availableTables as $table)
                                {{-- Pastikan variabel ini ada --}}
                                <option value="{{ $table->id }}">{{ $table->number }} -
                                    {{ strtoupper($table->status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="total_price" class="block mb-2 text-sm font-medium text-blue-600">Total
                            Harga</label>
                        <input type="number" name="total_price" id="total_price_create"
                            class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="0.00" step="0.01" required>
                    </div>
                    <div>
                        <label for="status" class="block mb-2 text-sm font-medium text-blue-600">Status</label>
                        <select name="status" id="status_create"
                            class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md">
                        💾 Simpan
                    </button>
                    <button type="button" onclick="toggleModal('createTransactionModal')"
                        class="text-blue-600 bg-white hover:bg-blue-50 border-2 border-blue-600 rounded-lg text-sm font-medium px-5 py-2.5">
                        ✖️ Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Transaksi -->
    <div id="editTransactionModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg shadow-2xl mx-4 md:mx-auto md:w-1/2 border-2 border-blue-600">
            <div class="flex items-start justify-between p-6 border-b-2 border-blue-600">
                <h3 class="text-xl font-semibold text-blue-600" id="edit_title">✏️ Update Transaksi</h3>
                <button type="button" onclick="toggleModal('editTransactionModal')"
                    class="text-blue-600 hover:text-blue-800 text-2xl">
                    &times;
                </button>
            </div>
            <form id="editTransactionForm" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="table_id_edit" class="block mb-2 text-sm font-medium text-blue-600">Pilih
                            Meja</label>
                            <select 
                            name="table_id" 
                            id="table_id_edit"
                            class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required
                        >
                            <option value="">Pilih Meja</option>
                            @foreach($tables as $table) {{-- Pastikan variabel ini ada --}}
                                <option value="{{ $table->id }}">{{ $table->number }} - {{ strtoupper($table->status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="total_price_edit" class="block mb-2 text-sm font-medium text-blue-600">Total
                            Harga</label>
                        <input type="number" name="total_price" id="total_price_edit"
                            class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="0.00" step="0.01" required>
                    </div>
                    <div>
                        <label for="status_edit" class="block mb-2 text-sm font-medium text-blue-600">Status</label>
                        <select name="status" id="status_edit"
                            class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md">
                        💾 Update
                    </button>
                    <button type="button" onclick="toggleModal('editTransactionModal')"
                        class="text-blue-600 bg-white hover:bg-blue-50 border-2 border-blue-600 rounded-lg text-sm font-medium px-5 py-2.5">
                        ✖️ Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi Modal
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        // Fungsi Edit Modal
        function editTransactionModal(button) {
            const id = button.dataset.id;
            const tableId = button.dataset.table_id;
            const totalPrice = button.dataset.total_price;
            const status = button.dataset.status;

            const form = document.getElementById('editTransactionForm');
            form.action = `/transaction/${id}`;

            document.getElementById('table_id_edit').value = tableId;
            document.getElementById('total_price_edit').value = totalPrice;
            document.getElementById('status_edit').value = status;

            document.getElementById('edit_title').innerText = `✏️ UPDATE TRANSAKSI #${id}`;
            toggleModal('editTransactionModal');
        }

        // Validasi Form Create
        document.getElementById('createTransactionForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const tableId = document.getElementById('table_id_create').value;
            const totalPrice = document.getElementById('total_price_create').value;
            const status = document.getElementById('status_create').value;

            // Validasi meja tidak boleh kosong
            if (!tableId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Silakan pilih meja terlebih dahulu!',
                    confirmButtonColor: '#1d4ed8'
                });
                return;
            }

            // Konfirmasi pembuatan transaksi
            Swal.fire({
                title: 'Buat Transaksi Baru?',
                html: `
                    <div class="text-left">
                        <p>Meja: <strong>${document.getElementById('table_id_create').options[document.getElementById('table_id_create').selectedIndex].text}</strong></p>
                        <p>Total: <strong>Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}</strong></p>
                        <p>Status: <strong>${status.toUpperCase()}</strong></p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1d4ed8',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Buat!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Validasi Form Edit
        document.getElementById('editTransactionForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const id = this.action.split('/').pop();
            const tableId = document.getElementById('table_id_edit').value;
            const totalPrice = document.getElementById('total_price_edit').value;
            const status = document.getElementById('status_edit').value;

            // Validasi meja tidak boleh kosong
            if (!tableId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Silakan pilih meja terlebih dahulu!',
                    confirmButtonColor: '#1d4ed8'
                });
                return;
            }

            // Konfirmasi perubahan
            Swal.fire({
                title: 'Update Transaksi?',
                html: `
                    <div class="text-left">
                        <p>Meja: <strong>${document.getElementById('table_id_edit').options[document.getElementById('table_id_edit').selectedIndex].text}</strong></p>
                        <p>Total: <strong>Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}</strong></p>
                        <p>Status: <strong>${status.toUpperCase()}</strong></p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1d4ed8',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Konfirmasi Hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Transaksi?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/transaction/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: 'Transaksi berhasil dihapus',
                                icon: 'success',
                                confirmButtonColor: '#1d4ed8',
                                timer: 2000
                            }).then(() => location.reload());
                        }
                    });
                }
            });
        }
    </script>
</x-app-layout>
