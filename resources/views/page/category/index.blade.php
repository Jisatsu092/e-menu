<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white bg-red-600 p-4 rounded-lg shadow">
            {{ __('MANAJEMEN KATEGORI') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-red-600 p-4">
                <div class="p-6">
                    <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                        <x-show-entries :route="route('category.index')" :search="request()->search" class="w-full md:w-auto"></x-show-entries>
                        <h3 class="text-md md:text-lg font-medium text-red-600">DATA KATEGORI MENU</h3>
                        <button 
                            type="button" 
                            onclick="toggleModal('createCategoryModal')"
                            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs md:text-sm px-4 py-2 md:px-5 md:py-2.5 shadow-md"
                        >
                            + Tambah Kategori
                        </button>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-xs md:text-sm text-left text-gray-900">
                            <thead class="text-xs text-white uppercase bg-red-600">
                                <tr>
                                    <th scope="col" class="px-4 py-2 md:px-6 md:py-3">NO</th>
                                    <th scope="col" class="px-4 py-2 md:px-6 md:py-3">NAMA KATEGORI</th>
                                    <th scope="col" class="px-4 py-2 md:px-6 md:py-3">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = $categories->firstItem();
                                @endphp
                                @forelse ($categories as $category)
                                    <tr class="bg-white border-b hover:bg-red-50">
                                        <td class="px-4 py-2 md:px-6 md:py-4 font-semibold">{{ $no++ }}</td>
                                        <td class="px-4 py-2 md:px-6 md:py-4 font-bold text-red-600">{{ $category->name }}</td>
                                        <td class="px-4 py-2 md:px-6 md:py-4 space-x-2 flex flex-wrap">
                                            <button 
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                onclick="editCategoryModal(this)"
                                                class="bg-amber-500 hover:bg-amber-600 px-2 py-1 md:px-4 md:py-2 rounded-md text-xs md:text-sm text-white shadow"
                                            >
                                                ✏️ Edit
                                            </button>
                                            <form action="{{ route('category.destroy', $category->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit"
                                                    class="bg-red-500 hover:bg-red-600 px-2 py-1 md:px-4 md:py-2 rounded-md text-xs md:text-sm text-white shadow"
                                                >
                                                    🗑️ Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 md:px-6 md:py-4 text-center text-gray-500">
                                            Tidak ada data kategori
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-red-600 text-xs md:text-sm">
                        {{ $categories->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div id="createCategoryModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg shadow-2xl mx-4 md:mx-auto md:w-1/3 border-2 border-red-600">
            <div class="flex items-start justify-between p-6 border-b-2 border-red-600">
                <h3 class="text-xl font-semibold text-red-600">🆕 Tambah Kategori Baru</h3>
                <button 
                    type="button" 
                    onclick="toggleModal('createCategoryModal')"
                    class="text-red-600 hover:text-red-800 text-2xl"
                >
                    &times;
                </button>
            </div>
            <form id="createForm" action="{{ route('category.store') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-6">
                    <label for="name_create" class="block mb-2 text-sm font-medium text-red-600">Nama Kategori</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name_create"
                        class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                        placeholder="Contoh: Makanan"
                        required
                        value="{{ old('name') }}"
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
                        onclick="toggleModal('createCategoryModal')"
                        class="text-red-600 bg-white hover:bg-red-50 border-2 border-red-600 rounded-lg text-sm font-medium px-5 py-2.5"
                    >
                        ✖ Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div id="editCategoryModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg shadow-2xl mx-4 md:mx-auto md:w-1/3 border-2 border-red-600">
            <div class="flex items-start justify-between p-6 border-b-2 border-red-600">
                <h3 class="text-xl font-semibold text-red-600" id="title_edit">✏️ Update Kategori</h3>
                <button 
                    type="button" 
                    onclick="toggleModal('editCategoryModal')"
                    class="text-red-600 hover:text-red-800 text-2xl"
                >
                    &times;
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label for="name_edit" class="block mb-2 text-sm font-medium text-red-600">Nama Kategori</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name_edit"
                        class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                        required
                        value="{{ old('name') }}"
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
                        onclick="toggleModal('editCategoryModal')"
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
            document.getElementById(modalId).classList.toggle('hidden');
        }
    
        function editCategoryModal(button) {
            const id = button.dataset.id;
            const name = button.dataset.name;
            const form = document.getElementById('editForm');
            
            form.action = `/category/${id}`;
            document.getElementById('name_edit').value = name;
            document.getElementById('title_edit').innerText = `✏️ UPDATE ${name}`;
            toggleModal('editCategoryModal');
        }
    
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const name = this.closest('tr').querySelector('td:nth-child(2)').textContent;
                
                Swal.fire({
                    title: 'Hapus Kategori?',
                    html: `Yakin ingin menghapus <b>"${name}"</b>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#dc2626',
                timer: 3000
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: `@foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                       @endforeach`,
                confirmButtonColor: '#dc2626'
            });
        @endif
    </script>
</x-app-layout>