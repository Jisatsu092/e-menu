<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white bg-red-600 p-4 rounded-lg shadow">
            {{ __('MANAJEMEN MEJA') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-red-600">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <x-show-entries :route="route('table.index')" :search="request()->search" class="w-full md:w-auto" />
                        <h3 class="text-lg font-medium text-red-600">DATA MEJA RESTORAN</h3>
                        <button type="button" onclick="createTable()"
                            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md">
                            + Tambah Meja
                        </button>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-900">
                            <thead class="text-xs text-white uppercase bg-red-600">
                                <tr>
                                    <th scope="col" class="px-6 py-3">NO</th>
                                    <th scope="col" class="px-6 py-3">NOMOR MEJA</th>
                                    <th scope="col" class="px-6 py-3">STATUS</th>
                                    <th scope="col" class="px-6 py-3">AKSI</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @foreach ($tables as $table)
                                    <tr id="row-{{ $table->id }}" class="bg-white border-b hover:bg-red-50">
                                        <td class="px-6 py-4 font-semibold">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 font-bold text-red-600">{{ $table->number }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-3 py-1.5 text-sm font-semibold rounded-full 
                                                {{ $table->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ strtoupper($table->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 space-x-2">
                                            <!-- Tombol Edit -->
                                            <button
                                                onclick="openEditModal('{{ $table->id }}', '{{ $table->number }}', '{{ $table->status }}')"
                                                class="bg-amber-500 hover:bg-amber-600 px-4 py-2 rounded-md text-sm text-white shadow">
                                                ✏️ Edit
                                            </button>

                                            <!-- Tombol Set Available (Baru) -->
                                            <button onclick="setAvailable('{{ $table->id }}')"
                                                class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-md text-sm text-white shadow">
                                                ✅ Available
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <button onclick="deleteTable('{{ $table->id }}')"
                                                class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md text-sm text-white shadow">
                                                🗑️ Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-red-600">
                        {{ $tables->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-xl font-bold text-red-600 mb-4">Edit Meja</h3>
            <form id="editForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editId" name="id">
                <div class="mb-4">
                    <label class="block text-red-600 mb-2">Nomor Meja</label>
                    <input type="text" id="editNumber" name="number"
                        class="w-full p-2 border-2 border-red-600 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-red-600 mb-2">Status</label>
                    <select id="editStatus" name="status" class="w-full p-2 border-2 border-red-600 rounded">
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                    </select>
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Tambah Meja Otomatis
        async function createTable() {
            try {
                const response = await fetch("{{ route('table.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        auto_generate: true
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    const newRow = `
                        <tr id="row-${data.table.id}" class="bg-white border-b hover:bg-red-50">
                            <td class="px-6 py-4 font-semibold">${document.querySelectorAll('#tableBody tr').length + 1}</td>
                            <td class="px-6 py-4 font-bold text-red-600">${data.table.number}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1.5 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    AVAILABLE
                                </span>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <button onclick="openEditModal('${data.table.id}', '${data.table.number}', 'available')"
                                    class="bg-amber-500 hover:bg-amber-600 px-4 py-2 rounded-md text-sm text-white shadow">
                                    ✏️ Edit
                                </button>
                                <button onclick="setAvailable('{{ $table->id }}')"
                                    class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-md text-sm text-white shadow">
                                    ✅ Available
                                </button>
                                <button onclick="deleteTable('${data.table.id}')"
                                    class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md text-sm text-white shadow">
                                    🗑️ Hapus
                                </button>
                            </td>
                        </tr>
                    `;
                    document.getElementById('tableBody').insertAdjacentHTML('beforeend', newRow);

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Meja baru ditambahkan',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message
                });
            }
        }

        // Set Status Available
        async function setAvailable(id) {
            try {
                const response = await fetch(`/table/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status: 'available' // Hanya kirim status
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    const row = document.getElementById(`row-${id}`);
                    const statusBadge = row.querySelector('span');
                    statusBadge.textContent = 'AVAILABLE';
                    statusBadge.className =
                        'px-3 py-1.5 text-sm font-semibold rounded-full bg-green-100 text-green-800';

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Status meja diubah menjadi AVAILABLE',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message
                });
            }
        }

        // Edit Meja
        function openEditModal(id, number, status) {
            document.getElementById('editId').value = id;
            document.getElementById('editNumber').value = number;
            document.getElementById('editStatus').value = status;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        document.getElementById('editForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            try {
                const response = await fetch(`/table/${document.getElementById('editId').value}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        number: document.getElementById('editNumber').value,
                        status: document.getElementById('editStatus').value
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    const row = document.getElementById(`row-${data.table.id}`);
                    row.querySelector('td:nth-child(2)').textContent = data.table.number;
                    const statusBadge = row.querySelector('span');
                    statusBadge.textContent = data.table.status.toUpperCase();
                    statusBadge.className =
                        `px-3 py-1.5 text-sm font-semibold rounded-full 
                        ${data.table.status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;

                    closeEditModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data meja diperbarui',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message
                });
            }
        });

        // Hapus Meja
        async function deleteTable(id) {
            Swal.fire({
                title: 'Hapus Meja?',
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/table/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content
                            }
                        });

                        if (response.ok) {
                            document.getElementById(`row-${id}`).remove();
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Meja berhasil dihapus',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            throw new Error('Gagal menghapus meja');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: error.message
                        });
                    }
                }
            });
        }
    </script>
</x-app-layout>
