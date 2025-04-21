<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menu Toping</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            .modal-overlay {
                z-index: 50;
            }
            .modal-content {
                z-index: 51;
            }
        </style>
    </head>

    <body class="bg-gray-100" x-data="{
        cart: [],
        isCartOpen: false,
        showModal: false,
        userName: '{{ Auth::user()->name }}',
        tables: {!! $tables->toJson() !!},
        selectedTable: null,
        
        addToCart(toping) {
            if(toping.stock < 1) return;
            
            const existing = this.cart.find(item => item.id === toping.id);
            if(existing) {
                existing.quantity++;
            } else {
                this.cart.push({...toping, quantity: 1});
            }
        },
        
        removeFromCart(index) {
            this.cart.splice(index, 1);
        },
        
        get totalItems() {
            return this.cart.reduce((total, item) => total + item.quantity, 0);
        },
        
        get totalPrice() {
            return this.cart.reduce((total, item) => total + item.price * item.quantity, 0);
        },
        
        submitCheckout() {
            if (!this.selectedTable) {
                alert('Pilih meja terlebih dahulu');
                return;
            }
            
            const data = {
                user_id: {{ Auth::id() }},
                table_id: this.selectedTable,
                toppings: this.cart.map(item => ({
                    id: item.id,
                    name: item.name,
                    price: item.price,
                    quantity: item.quantity
                })),
                total_price: this.totalPrice
            };

            fetch('/transactions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    this.cart = [];
                    this.selectedTable = null;
                    this.showModal = false;
                    alert('Transaksi berhasil!');
                }
            });
        }
    }">
        <!-- Checkout Modal -->
        <div x-show="showModal" class="fixed inset-0 overflow-y-auto modal-overlay">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div @click="showModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 modal-content">
                    <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                        <button @click="showModal = false" type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Checkout</h3>
                            <div class="mt-4">
                                <form @submit.prevent="submitCheckout">
                                    <div class="mb-4">
                                        <label class="block text-gray-700">Nama Pengguna</label>
                                        <input type="text" :value="userName" disabled class="w-full p-2 border rounded">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700">Nomor Meja</label>
                                        <select x-model="selectedTable" class="w-full p-2 border rounded">
                                            <option value="">Pilih Meja</option>
                                            <template x-for="table in tables" :key="table.id">
                                                <option :value="table.id" x-text="'Meja ' + table.number"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700">Topings</label>
                                        <ul class="list-disc pl-5">
                                            <template x-for="item in cart" :key="item.id">
                                                <li class="flex justify-between">
                                                    <span x-text="item.name"></span>
                                                    <span>x<span x-text="item.quantity"></span></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700">Total Harga</label>
                                        <p class="font-bold text-xl">Rp <span x-text="totalPrice.toLocaleString('id-ID')"></span></p>
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button @click="showModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                            Batal
                                        </button>
                                        <button type="submit" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:mt-0 sm:w-auto">
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto p-6" :class="{ 'overflow-hidden': showModal }">
            <!-- Search and Cart Section -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div class="w-full md:w-2/3 bg-gray-800 rounded-full px-6 py-3 flex items-center">
                    <svg class="w-6 h-6 text-white mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" placeholder="Cari toping..." class="w-full bg-transparent text-white focus:outline-none">
                </div>
                
                <div class="flex items-center gap-4 relative">
                    <button @click="isCartOpen = !isCartOpen" class="bg-gray-800 text-white p-3 rounded-full hover:bg-gray-700 relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span x-show="totalItems > 0" x-text="totalItems" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs"></span>
                    </button>

                    <!-- Cart Dropdown -->
                    <div x-show="isCartOpen" @click.away="isCartOpen = false" class="absolute top-12 right-0 w-72 bg-white rounded-lg shadow-xl z-50">
                        <div class="p-4">
                            <h3 class="text-lg font-bold mb-4">Keranjang</h3>
                            <template x-if="cart.length === 0">
                                <p class="text-gray-500">Keranjang kosong</p>
                            </template>
                            <template x-for="(item, index) in cart" :key="item.id">
                                <div class="flex items-center justify-between py-2 border-b">
                                    <div>
                                        <p x-text="item.name" class="font-medium"></p>
                                        <p class="text-sm text-gray-500">
                                            <span x-text="item.quantity"></span>x 
                                            Rp<span x-text="new Intl.NumberFormat('id-ID').format(item.price)"></span>
                                        </p>
                                    </div>
                                    <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-700">
                                        ✕
                                    </button>
                                </div>
                            </template>
                            <template x-if="cart.length > 0">
                                <div class="p-4 border-t">
                                    <button @click="isCartOpen = false; showModal = true" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                                        Checkout
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach($topings as $toping)
                <div class="bg-gray-800 rounded-xl p-4 shadow-lg transition-all duration-300 
                    {{ $toping->stock < 1 ? 'opacity-50 cursor-not-allowed' : 'hover:transform hover:scale-105' }}"
                    :class="{ 'opacity-50 cursor-not-allowed': {{ $toping->stock }} < 1 }">
                    
                    <!-- Image Skeleton -->
                    <div class="w-full h-48 rounded-lg mb-4 overflow-hidden">
                        @if($toping->image)
                            <img src="{{ asset('storage/' . $toping->image) }}" alt="{{ $toping->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-300 animate-pulse"></div>
                        @endif
                    </div>

                    <div class="text-white">
                        <h3 class="text-xl font-bold mb-2">{{ $toping->name }}</h3>
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-gray-400">Rp {{ number_format($toping->price, 0, ',', '.') }}</p>
                            <span class="text-sm px-2 py-1 rounded-full 
                                      {{ $toping->stock > 0 ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $toping->stock > 0 ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>
                        <button 
                            @click="addToCart({{ json_encode($toping) }})"
                            class="w-full py-2 rounded-lg transition-colors
                                   {{ $toping->stock < 1 ? 'bg-gray-600 cursor-not-allowed' : 'bg-gray-700 hover:bg-gray-600' }}"
                            :disabled="{{ $toping->stock < 1 ? 'true' : 'false' }}">
                            Tambah
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </body>
    </html>
</x-app-layout>