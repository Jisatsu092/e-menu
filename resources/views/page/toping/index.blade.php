<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white bg-red-600 p-4 rounded-lg shadow">
            {{ __('MANAJEMEN TOPING') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-red-600 p-4">
                <div class="p-6">
                    <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                        <x-show-entries :route="route('toping.index')" :search="request()->search" class="w-full md:w-auto"></x-show-entries>
                        <h3 class="text-md md:text-lg font-medium text-red-600">DAFTAR TOPING</h3>
                        <button 
                            type="button" 
                            onclick="toggleModal('createTopingModal')"
                            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs md:text-sm px-4 py-2 md:px-5 md:py-2.5 shadow-md"
                        >
                            + Tambah Toping
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
                                    <th scope="col" class="px-4 py-2 md:px-6 md:py-3">NAMA</th>
                                    <th scope="col" class="px-4 py-2 md:px-6 md:py-3">KATEGORI</th>
                                    <th scope="col" class="px-4 py-2 md:px-6 md:py-3">HARGA</th>
                                    <th scope="col" class="px-4 py-2 md:px-6 md:py-3">STOK</th>
                                    <th scope="col" class="px-4 py-2 md:px-6 md:py-3">GAMBAR</th>
                                    <th scope="col" class="px-4 py-2 md:px-6 md:py-3">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = $topings->firstItem();
                                @endphp
                                @foreach ($topings as $toping)
                                    <tr class="bg-white border-b hover:bg-red-50">
                                        <td class="px-4 py-2 md:px-6 md:py-4 font-semibold">{{ $no++ }}</td>
                                        <td class="px-4 py-2 md:px-6 md:py-4 font-bold text-red-600">{{ $toping->name }}</td>
                                        <td class="px-4 py-2 md:px-6 md:py-4">{{ $toping->category->name ?? '-' }}</td>
                                        <td class="px-4 py-2 md:px-6 md:py-4">@currency($toping->price)</td>
                                        <td class="px-4 py-2 md:px-6 md:py-4">{{ $toping->stock }}</td>
                                        <td class="px-4 py-2 md:px-6 md:py-4">
                                            @if($toping->image)
                                                <img src="{{ asset('storage/' . $toping->image) }}" class="w-16 h-16 object-cover rounded-lg shadow" alt="Gambar Toping">
                                            @else
                                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 md:px-6 md:py-4 space-x-2 flex flex-wrap">
                                            <button 
                                                data-id="{{ $toping->id }}"
                                                data-name="{{ $toping->name }}"
                                                data-category="{{ $toping->category_id }}"
                                                data-price="{{ $toping->price }}"
                                                data-stock="{{ $toping->stock }}"
                                                data-image="{{ $toping->image }}"
                                                onclick="editTopingModal(this)"
                                                class="bg-amber-500 hover:bg-amber-600 px-2 py-1 md:px-4 md:py-2 rounded-md text-xs md:text-sm text-white shadow"
                                            >
                                                ✏️ Edit
                                            </button>
                                            <form action="{{ route('toping.destroy', $toping->id) }}" method="POST" class="delete-form">
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-red-600 text-xs md:text-sm">
                        {{ $topings->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Toping -->
    <div id="createTopingModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg shadow-2xl mx-4 md:mx-auto md:w-1/2 border-2 border-red-600">
            <div class="flex items-start justify-between p-6 border-b-2 border-red-600">
                <h3 class="text-xl font-semibold text-red-600">🆕 Tambah Toping Baru</h3>
                <button 
                    type="button" 
                    onclick="toggleModal('createTopingModal')"
                    class="text-red-600 hover:text-red-800 text-2xl"
                >
                    &times;
                </button>
            </div>
            <form id="createForm" action="{{ route('toping.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="name_create" class="block mb-2 text-sm font-medium text-red-600">Nama Toping</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name_create"
                            class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                            required
                            value="{{ old('name') }}"
                        >
                    </div>
                    
                    <div class="mb-4">
                        <label for="category_create" class="block mb-2 text-sm font-medium text-red-600">Kategori</label>
                        <select 
                            name="category_id" 
                            id="category_create"
                            class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                            required
                        >
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="price_create" class="block mb-2 text-sm font-medium text-red-600">Harga</label>
                        <input 
                            type="number" 
                            name="price" 
                            id="price_create"
                            class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                            min="0"
                            required
                            value="{{ old('price') }}"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="stock_create" class="block mb-2 text-sm font-medium text-red-600">Stok</label>
                        <input 
                            type="number" 
                            name="stock" 
                            id="stock_create"
                            class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                            min="0"
                            required
                            value="{{ old('stock') }}"
                        >
                    </div>

                    <div class="mb-4 md:col-span-2">
                        <label for="image_create" class="block mb-2 text-sm font-medium text-red-600">Gambar</label>
                        <div class="flex items-center gap-4">
                            <div id="imagePreviewCreate" class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden hidden">
                                <img class="w-full h-full object-cover" alt="Preview Gambar">
                            </div>
                            <input 
                                type="file" 
                                name="image" 
                                id="image_create"
                                class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                                accept="image/*"
                                onchange="showImagePreview(event, 'create')"
                            >
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-4">
                    <button 
                        type="submit"
                        class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md"
                    >
                        💾 Simpan
                    </button>
                    <button 
                        type="button"
                        onclick="toggleModal('createTopingModal')"
                        class="text-red-600 bg-white hover:bg-red-50 border-2 border-red-600 rounded-lg text-sm font-medium px-5 py-2.5"
                    >
                        ✖ Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Toping -->
    <div id="editTopingModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg shadow-2xl mx-4 md:mx-auto md:w-1/2 border-2 border-red-600">
            <div class="flex items-start justify-between p-6 border-b-2 border-red-600">
                <h3 class="text-xl font-semibold text-red-600" id="title_edit">✏️ Update Toping</h3>
                <button 
                    type="button" 
                    onclick="toggleModal('editTopingModal')"
                    class="text-red-600 hover:text-red-800 text-2xl"
                >
                    &times;
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="name_edit" class="block mb-2 text-sm font-medium text-red-600">Nama Toping</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name_edit"
                            class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                            required
                        >
                    </div>
                    
                    <div class="mb-4">
                        <label for="category_edit" class="block mb-2 text-sm font-medium text-red-600">Kategori</label>
                        <select 
                            name="category_id" 
                            id="category_edit"
                            class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                            required
                        >
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="price_edit" class="block mb-2 text-sm font-medium text-red-600">Harga</label>
                        <input 
                            type="number" 
                            name="price" 
                            id="price_edit"
                            class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                            min="0"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="stock_edit" class="block mb-2 text-sm font-medium text-red-600">Stok</label>
                        <input 
                            type="number" 
                            name="stock" 
                            id="stock_edit"
                            class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                            min="0"
                            required
                        >
                    </div>

                    <div class="mb-4 md:col-span-2">
                        <label for="image_edit" class="block mb-2 text-sm font-medium text-red-600">Gambar</label>
                        <div class="flex items-center gap-4">
                            <div id="currentImage" class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden">
                                <img class="w-full h-full object-cover" alt="Gambar Saat Ini">
                            </div>
                            <div id="imagePreviewEdit" class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden hidden">
                                <img class="w-full h-full object-cover" alt="Preview Gambar Baru">
                            </div>
                            <input 
                                type="file" 
                                name="image" 
                                id="image_edit"
                                class="bg-white border-2 border-red-600 text-red-600 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                                accept="image/*"
                                onchange="showImagePreview(event, 'edit')"
                            >
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-4">
                    <button 
                        type="submit"
                        class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md"
                    >
                        💾 Simpan
                    </button>
                    <button 
                        type="button"
                        onclick="toggleModal('editTopingModal')"
                        class="text-red-600 bg-white hover:bg-red-50 border-2 border-red-600 rounded-lg text-sm font-medium px-5 py-2.5"
                    >
                        ✖ Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi Toggle Modal
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
            
            // Reset preview saat modal ditutup
            if(modalId === 'createTopingModal') {
                document.getElementById('imagePreviewCreate').classList.add('hidden');
                document.getElementById('image_create').value = '';
            }
        }

        // Fungsi Preview Gambar
        function showImagePreview(event, type) {
            const input = event.target;
            const previewId = type === 'create' ? 'imagePreviewCreate' : 'imagePreviewEdit';
            const preview = document.getElementById(previewId);
            const image = preview.querySelector('img');
            
            if(input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    image.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Fungsi Edit Modal
        function editTopingModal(button) {
            const id = button.dataset.id;
            const form = document.getElementById('editForm');
            const currentImage = document.getElementById('currentImage').querySelector('img');
            
            form.action = `/toping/${id}`;
            document.getElementById('name_edit').value = button.dataset.name;
            document.getElementById('category_edit').value = button.dataset.category;
            document.getElementById('price_edit').value = button.dataset.price;
            document.getElementById('stock_edit').value = button.dataset.stock;

            // Set gambar saat ini
            if(button.dataset.image) {
                currentImage.src = "{{ asset('storage') }}/" + button.dataset.image;
            } else {
                currentImage.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23CBD5E0"><path d="M4 5h16v12H4z"/><path d="M12 9a3 3 0 100 6 3 3 0 000-6z"/></svg>';
            }

            document.getElementById('title_edit').innerText = `✏️ Update ${button.dataset.name}`;
            toggleModal('editTopingModal');
        }

        // Konfirmasi Hapus
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const name = this.closest('tr').querySelector('td:nth-child(2)').textContent;
                
                Swal.fire({
                    title: 'Hapus Toping?',
                    html: `Yakin ingin menghapus <b>"${name}"</b>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if(result.isConfirmed) this.submit();
                });
            });
        });

        // Notifikasi
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#dc2626',
                timer: 3000
            });
        @endif

        @if(session('error_message'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error_message') }}',
                confirmButtonColor: '#dc2626',
                timer: 5000
            });
        @endif
    </script>
</x-app-layout>