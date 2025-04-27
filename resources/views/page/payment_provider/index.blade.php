<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white bg-blue-600 p-4 rounded-lg shadow">
            {{ __('MANAJEMEN PAYMENT PROVIDER') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-blue-600">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <x-show-entries :route="route('payment_providers.index')" :search="request()->search" class="w-full md:w-auto" />
                        <h3 class="text-lg font-medium text-blue-600">DAFTAR PAYMENT PROVIDER</h3>
                        <button onclick="toggleModal('createProviderModal')"
                            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg shadow-md">
                            + Provider Baru
                        </button>
                    </div>
                    
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-900">
                            <thead class="text-xs text-white uppercase bg-blue-600">
                                <tr>
                                    <th class="px-6 py-3">#</th>
                                    <th class="px-6 py-3">Nama</th>
                                    <th class="px-6 py-3">Nomor Rekening</th>
                                    <th class="px-6 py-3">Tipe</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($providers as $provider)
                                <tr class="bg-white border-b hover:bg-blue-50">
                                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 font-bold text-blue-600">
                                        <div class="flex items-center gap-2">
                                            @if($provider->logo)
                                            <img src="{{ asset('storage/'.$provider->logo) }}" class="w-8 h-8 rounded-full">
                                            @endif
                                            {{ $provider->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">{{ $provider->account_number }}</td>
                                    <td class="px-6 py-4">{{ ucfirst($provider->type) }}</td>
                                    <td class="px-6 py-4">
                                        <button onclick="toggleStatus('{{ $provider->id }}')"
                                            class="relative inline-flex items-center w-10 h-6 transition-colors duration-300 ease-in-out rounded-full cursor-pointer
                                            {{ $provider->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                                            <span class="sr-only">Toggle Status</span>
                                            <span class="absolute left-0 w-6 h-6 transition-transform duration-300 ease-in-out transform bg-white rounded-full shadow-md
                                                {{ $provider->is_active ? 'translate-x-4' : 'translate-x-0' }}">
                                            </span>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 space-x-2">
                                        <button onclick="editProviderModal(this)"
                                            data-id="{{ $provider->id }}"
                                            data-name="{{ $provider->name }}"
                                            data-account_number="{{ $provider->account_number }}"
                                            data-account_name="{{ $provider->account_name }}"
                                            data-type="{{ $provider->type }}"
                                            data-instructions="{{ $provider->instructions }}"
                                            class="bg-amber-500 hover:bg-amber-600 px-4 py-2 rounded-md text-white shadow">
                                            ✏️ Edit
                                        </button>
                                        
                                        <button onclick="confirmDelete('{{ $provider->id }}')"
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
                        {{ $providers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Provider --}}
    <div id="createProviderModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg shadow-2xl mx-4 md:mx-auto md:w-1/2 border-2 border-blue-600">
            <div class="flex items-start justify-between p-6 border-b-2 border-blue-600">
                <h3 class="text-xl font-semibold text-blue-600">➕ Tambah Provider Baru</h3>
                <button onclick="toggleModal('createProviderModal')"
                    class="text-blue-600 hover:text-blue-800 text-2xl">&times;</button>
            </div>
            <form id="createProviderForm" action="{{ route('payment_providers.store') }}" method="POST" class="p-6" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name_create" class="block mb-2 text-sm font-medium text-blue-600">Nama Provider</label>
                        <input type="text" name="name" id="name_create"
                            class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Contoh: Dana" required>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="account_number_create" class="block mb-2 text-sm font-medium text-blue-600">Nomor Rekening</label>
                            <input type="text" name="account_number" id="account_number_create"
                                class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Contoh: 081234567890" required>
                        </div>
                        
                        <div>
                            <label for="type_create" class="block mb-2 text-sm font-medium text-blue-600">Tipe</label>
                            <select name="type" id="type_create"
                                class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required>
                                <option value="e-wallet">E-Wallet</option>
                                <option value="bank">Bank</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="account_name_create" class="block mb-2 text-sm font-medium text-blue-600">Nama Akun</label>
                        <input type="text" name="account_name" id="account_name_create"
                            class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Contoh: Ajnira Ramen" required>
                    </div>
                    
                    <div>
                        <label for="instructions_create" class="block mb-2 text-sm font-medium text-blue-600">Instruksi Pembayaran</label>
                        <textarea name="instructions" id="instructions_create" rows="3"
                            class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Tambahkan instruksi pembayaran..."></textarea>
                    </div>
                    
                    <div>
                        <label for="logo_create" class="block mb-2 text-sm font-medium text-blue-600">Logo Provider</label>
                        <input type="file" name="logo" id="logo_create"
                            class="block w-full text-sm text-blue-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            accept="image/*">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md">
                        💾 Simpan
                    </button>
                    <button type="button" onclick="toggleModal('createProviderModal')"
                        class="text-blue-600 bg-white hover:bg-blue-50 border-2 border-blue-600 rounded-lg text-sm font-medium px-5 py-2.5">
                        ✖️ Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Provider --}}
    <div id="editProviderModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg shadow-2xl mx-4 md:mx-auto md:w-1/2 border-2 border-blue-600">
            <div class="flex items-start justify-between p-6 border-b-2 border-blue-600">
                <h3 class="text-xl font-semibold text-blue-600" id="edit_title">✏️ Update Provider</h3>
                <button onclick="toggleModal('editProviderModal')"
                    class="text-blue-600 hover:text-blue-800 text-2xl">&times;</button>
            </div>
            <form id="editProviderForm" method="POST" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name_edit" class="block mb-2 text-sm font-medium text-blue-600">Nama Provider</label>
                        <input type="text" name="name" id="name_edit"
                            class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="account_number_edit" class="block mb-2 text-sm font-medium text-blue-600">Nomor Rekening</label>
                            <input type="text" name="account_number" id="account_number_edit"
                                class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required>
                        </div>
                        
                        <div>
                            <label for="type_edit" class="block mb-2 text-sm font-medium text-blue-600">Tipe</label>
                            <select name="type" id="type_edit"
                                class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required>
                                <option value="e-wallet">E-Wallet</option>
                                <option value="bank">Bank</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="account_name_edit" class="block mb-2 text-sm font-medium text-blue-600">Nama Akun</label>
                        <input type="text" name="account_name" id="account_name_edit"
                            class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required>
                    </div>
                    
                    <div>
                        <label for="instructions_edit" class="block mb-2 text-sm font-medium text-blue-600">Instruksi Pembayaran</label>
                        <textarea name="instructions" id="instructions_edit" rows="3"
                            class="bg-white border-2 border-blue-600 text-blue-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
                    </div>
                    
                    <div>
                        <label class="block mb-2 text-sm font-medium text-blue-600">Logo Saat Ini</label>
                        <div id="current_logo_container" class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center">
                            <img id="current_logo" class="max-w-full max-h-full">
                        </div>
                        <input type="file" name="logo" id="logo_edit"
                            class="block w-full mt-2 text-sm text-blue-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            accept="image/*">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md">
                        💾 Update
                    </button>
                    <button type="button" onclick="toggleModal('editProviderModal')"
                        class="text-blue-600 bg-white hover:bg-blue-50 border-2 border-blue-600 rounded-lg text-sm font-medium px-5 py-2.5">
                        ✖️ Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi Toggle Modal
        function toggleModal(modalId) {
            document.getElementById(modalId).classList.toggle('hidden');
        }

        // Fungsi Edit Provider
        function editProviderModal(button) {
            const id = button.dataset.id;
            const form = document.getElementById('editProviderForm');
            form.action = `/payment_providers/${id}`;

            // Isi data ke form
            document.getElementById('name_edit').value = button.dataset.name;
            document.getElementById('account_number_edit').value = button.dataset.account_number;
            document.getElementById('account_name_edit').value = button.dataset.account_name;
            document.getElementById('type_edit').value = button.dataset.type;
            document.getElementById('instructions_edit').value = button.dataset.instructions;

            // Tampilkan logo saat ini
            const logoContainer = document.getElementById('current_logo_container');
            const currentLogo = document.getElementById('current_logo');
            const logoPath = button.dataset.logo;
            
            if(logoPath) {
                currentLogo.src = `{{ asset('storage/') }}/${logoPath}`;
                logoContainer.classList.remove('hidden');
            } else {
                logoContainer.classList.add('hidden');
            }

            document.getElementById('edit_title').innerText = `✏️ UPDATE PROVIDER #${id}`;
            toggleModal('editProviderModal');
        }

        // Fungsi Toggle Status
        async function toggleStatus(id) {
            const response = await fetch(`/payment_providers/${id}/toggle-status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });

            if(response.ok) {
                location.reload();
            }
        }

        // Validasi Form Create
        document.getElementById('createProviderForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const name = document.getElementById('name_create').value;
            const accountNumber = document.getElementById('account_number_create').value;

            if(!name || !accountNumber) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Nama Provider dan Nomor Rekening wajib diisi!'
                });
                return;
            }

            Swal.fire({
                title: 'Buat Provider Baru?',
                html: `
                    <div class="text-left">
                        <p>Nama: <strong>${name}</strong></p>
                        <p>Nomor Rekening: <strong>${accountNumber}</strong></p>
                        <p>Tipe: <strong>${document.getElementById('type_create').value.toUpperCase()}</strong></p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Buat!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });

        // Konfirmasi Hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Provider?',
                text: "Semua transaksi terkait akan tetap tersimpan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/payment_providers/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                Swal.fire('Terhapus!', 'Provider berhasil dihapus', 'success')
                                    .then(() => location.reload());
                            }
                        });
                }
            });
        }
    </script>
</x-app-layout>