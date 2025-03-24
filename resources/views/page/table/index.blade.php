<x-app-layout>
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
                        <x-show-entries :route="route('category.index')" :search="request()->search" class="w-full md:w-auto"></x-show-entries>
                        <h3 class="text-lg font-medium text-red-600">DATA MEJA RESTORAN</h3>
                        <button 
                            type="button" 
                            onclick="toggleModal('createTableModal')"
                            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md"
                        >
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
                            <tbody>
                                @foreach ($tables as $key => $table)
                                    <tr class="bg-white border-b hover:bg-red-50">
                                        <td class="px-6 py-4 font-semibold">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 font-bold text-red-600">{{ $table->number }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1.5 text-sm font-semibold rounded-full 
                                                {{ $table->status === 'available' ? 
                                                    'bg-green-100 text-green-800' : 
                                                    'bg-red-100 text-red-800' }}">
                                                {{ strtoupper($table->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 space-x-2">
                                            <button 
                                                data-id="{{ $table->id }}"
                                                data-number="{{ $table->number }}"
                                                data-status="{{ $table->status }}"
                                                onclick="editTableModal(this)"
                                                class="bg-amber-500 hover:bg-amber-600 px-4 py-2 rounded-md text-sm text-white shadow"
                                            >
                                                ✏️ Edit
                                            </button>
                                            <button 
                                                onclick="tableDelete('{{ $table->id }}', '{{ $table->number }}')"
                                                class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md text-sm text-white shadow"
                                            >
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

    <!-- Modal Tambah Meja -->
    <div id="createTableModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg shadow-2xl mx-4 md:mx-auto md:w-1/3 border-2 border-red-600">
            <div class="flex items-start justify-between p-6 border-b-2 border-red-600">
                <h3 class="text-xl font-semibold text-red-600">🆕 Tambah Meja Baru</h3>
                <button 
                    type="button" 
                    onclick="toggleModal('createTableModal')"
                    class="text-red-600 hover:text-red-800 text-2xl"
                >
                    &times;
                </button>
            </div>
            <form id="createForm" action="{{ route('table.store') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-6">
                    <label for="number_create" class="block mb-2 text-sm font-medium text-red-600">Nomor Meja</label>
                    <input 
                        type="text" 
                        name="number" 
                        id="number_create"
                        class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                        placeholder="MEJA-01"
                        required
                    >
                </div>
                <div class="flex justify-end space-x-4">
                    <button 
                        type="submit"
                        class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md"
                    >
                        💾 Simpan
                    </button>
                    <button 
                        type="button"
                        onclick="toggleModal('createTableModal')"
                        class="text-red-600 bg-white hover:bg-red-50 border-2 border-red-600 rounded-lg text-sm font-medium px-5 py-2.5"
                    >
                        ✖ Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Meja -->
    <div id="editTableModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg shadow-2xl mx-4 md:mx-auto md:w-1/3 border-2 border-red-600">
            <div class="flex items-start justify-between p-6 border-b-2 border-red-600">
                <h3 class="text-xl font-semibold text-red-600" id="title_edit">✏️ Update Meja</h3>
                <button 
                    type="button" 
                    onclick="toggleModal('editTableModal')"
                    class="text-red-600 hover:text-red-800 text-2xl"
                >
                    &times;
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    <label for="number_edit" class="block mb-2 text-sm font-medium text-red-600">Nomor Meja</label>
                    <input 
                        type="text" 
                        name="number" 
                        id="number_edit"
                        class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                        required
                    >
                </div>
                <div class="mb-6">
                    <label for="status_edit" class="block mb-2 text-sm font-medium text-red-600">Status</label>
                    <select 
                        name="status" 
                        id="status_edit"
                        class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                    >
                        <option value="available" class="text-green-600">AVAILABLE</option>
                        <option value="occupied" class="text-red-600">OCCUPIED</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-4">
                    <button 
                        type="submit"
                        class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md"
                    >
                        💾 Simpan
                    </button>
                    <button 
                        type="button"
                        onclick="toggleModal('editTableModal')"
                        class="text-red-600 bg-white hover:bg-red-50 border-2 border-red-600 rounded-lg text-sm font-medium px-5 py-2.5"
                    >
                        ✖ Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        function editTableModal(button) {
            const id = button.dataset.id;
            const number = button.dataset.number;
            const status = button.dataset.status;

            document.getElementById('editForm').action = `/table/${id}`;
            document.getElementById('number_edit').value = number;
            document.getElementById('status_edit').value = status;
            document.getElementById('title_edit').innerText = `✏️ UPDATE ${number}`;
            toggleModal('editTableModal');
        }

        // Validasi Create Form
        document.getElementById('createForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const numberInput = document.getElementById('number_create');
            const number = numberInput.value.trim();

            if (!number) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Nomor meja tidak boleh kosong!',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            const response = await fetch(`/table/check-number/${encodeURIComponent(number)}`);
            const data = await response.json();

            if (data.exists) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplikat!',
                    text: `Meja ${number} sudah terdaftar!`,
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            Swal.fire({
                title: `Tambahkan Meja ${number}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tambahkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Validasi Edit Form
        document.getElementById('editForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const number = document.getElementById('number_edit').value.trim();
            const id = this.action.split('/').pop();

            const response = await fetch(`/table/check-number/${encodeURIComponent(number)}?id=${id}`);
            const data = await response.json();

            if (data.exists) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplikat!',
                    text: `Meja ${number} sudah terdaftar!`,
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            Swal.fire({
                title: `Update Meja ${number}?`,
                text: 'Pastikan data sudah benar',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
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
        function tableDelete(id, number) {
            Swal.fire({
                title: `Hapus Meja ${number}?`,
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/table/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: `Meja ${number} berhasil dihapus`,
                                icon: 'success',
                                confirmButtonColor: '#dc2626',
                                timer: 2000
                            }).then(() => location.reload());
                        }
                    });
                }
            });
        }
    </script>
</x-app-layout>