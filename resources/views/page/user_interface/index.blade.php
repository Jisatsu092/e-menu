<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Warung Seblak Ajnira - Pemesanan</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            .scroll-hide::-webkit-scrollbar {
                display: none;
            }

            .horizontal-scroll {
                scroll-snap-type: x mandatory;
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
            }

            .mobile-card {
                min-width: 85vw;
                scroll-snap-align: start;
            }

            .mobile-cart-panel {
                transform: translateY(20%);
                right: 1rem;
                bottom: calc(100% + 10px);
                max-height: 60vh;
            }

            @media (min-width: 768px) {
                .mobile-card {
                    min-width: auto;
                }

                .toping-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 1rem;
                }
            }

            @keyframes bounceIn {
                0% {
                    transform: scale(0.9);
                    opacity: 0;
                }

                50% {
                    transform: scale(1.05);
                }

                100% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            .animate-bounce-in {
                animation: bounceIn 0.3s ease;
            }

            .payment-detail {
                transition: all 0.2s ease;
            }

            .copy-number:hover {
                background-color: #f3f4f6;
                cursor: pointer;
            }

            .modal-scrollable {
                scrollbar-width: none;
                -ms-overflow-style: none;
            }

            .modal-scrollable::-webkit-scrollbar {
                display: none;
            }

            .category-btn {
                transition: all 0.2s ease;
                flex-shrink: 0;
            }

            .category-btn.active {
                background-color: #ef4444;
                color: white;
            }
        </style>
    </head>

    <body class="bg-gray-100">
        <!-- Modal Konfirmasi Pesanan -->
        <div id="checkoutModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md animate-bounce-in mx-4">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-xl font-bold text-red-500">Konfirmasi Pesanan</h3>
                    <button onclick="closeCheckoutModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <form id="checkoutForm" onsubmit="processPayment(event)">
                    @csrf
                    <div class="max-h-[70vh] overflow-y-auto modal-scrollable space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                            <input type="text" value="{{ Auth::user()->name }}" disabled
                                class="mt-1 block w-full rounded-md bg-gray-100 p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Meja</label>
                            <select id="tableNumber" required class="mt-1 block w-full rounded-md border p-2">
                                <option value="">Pilih Meja</option>
                                @foreach ($tables as $table)
                                    <option value="{{ $table->id }}">Meja {{ $table->number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Level Pedas</label>
                            <select id="spicinessLevel" required class="mt-1 block w-full rounded-md border p-2">
                                <option value="">Pilih Level</option>
                                <option value="mild">Pedas Level 1</option>
                                <option value="medium">Pedas Level 2</option>
                                <option value="hot">Pedas Level 3</option>
                                <option value="extreme">Pedas Level 4</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ukuran Mangkok</label>
                            <select id="bowlSize" required class="mt-1 block w-full rounded-md border p-2">
                                <option value="">Pilih Ukuran</option>
                                <option value="small">Kecil</option>
                                <option value="medium">Sedang</option>
                                <option value="large">Besar</option>
                            </select>
                        </div>
                        <div class="border rounded-lg p-3">
                            <h4 class="font-medium mb-2">Detail Pesanan:</h4>
                            <div id="orderItems" class="space-y-2"></div>
                            <div class="mt-3 pt-2 border-t">
                                <p class="flex justify-between font-bold">
                                    <span>Total:</span>
                                    <span id="modalTotal" class="text-red-500">Rp0</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 mt-4">
                            <button type="button" onclick="closeCheckoutModal()"
                                class="px-4 py-2 border rounded-md hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                Bayar Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Konfirmasi Pembayaran (Diperbaiki) -->
        <div id="paymentConfirmationModal"
            class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md animate-bounce-in mx-4">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-xl font-bold text-red-500">Pembayaran</h3>
                    <button onclick="closePaymentModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <div class="space-y-4 max-h-[70vh] modal-scrollable overflow-y-auto">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <select id="paymentMethod" onchange="showPaymentDetails()"
                            class="mt-1 block w-full rounded-md border p-2">
                            <option value="">Pilih Pembayaran</option>
                            @foreach ($paymentProviders->where('is_active', true) as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="paymentDetails" class="hidden space-y-3">
                        <div class="flex items-center space-x-3">
                            <img id="providerLogo" src="" class="w-12 h-12 object-contain rounded-lg">
                            <div>
                                <p class="font-bold" id="providerName"></p>
                                <p class="text-sm text-gray-500" id="providerType"></p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Nomor Rekening</p>
                                <div class="copy-number bg-gray-50 rounded-lg p-2" onclick="copyToClipboard(this)">
                                    <span class="font-mono text-gray-800" id="accountNumber"></span>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Atas Nama</p>
                                <p class="font-medium text-gray-800" id="accountName"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Petunjuk Pembayaran</p>
                                <p class="text-sm text-gray-800" id="instructions"></p>
                            </div>
                        </div>
                    </div>
                    <div class="border-t pt-4">
                        <div class="border rounded-lg p-3">
                            <h4 class="font-medium mb-2">Detail Pesanan:</h4>
                            <div id="paymentOrderItems" class="space-y-2 max-h-40 modal-scrollable overflow-y-auto">
                            </div>
                            <!-- Item pesanan muncul di sini -->
                            <div class="mt-3 pt-2 border-t">
                                <p class="flex justify-between font-bold">
                                    <span>Total:</span>
                                    <span id="paymentTotal" class="text-red-500">Rp0</span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Upload Bukti Transfer</label>
                            <input type="file" id="paymentProof" accept="image/*"
                                class="mt-1 block w-full rounded-md border p-2">
                        </div>
                    </div>
                    <button onclick="submitPayment()"
                        class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600 transition-colors">
                        Konfirmasi Pembayaran
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Pesanan Berhasil -->
        <div id="orderConfirmationModal"
            class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md animate-bounce-in mx-4">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-xl font-bold text-red-500">Pesanan Berhasil</h3>
                    <button onclick="closeOrderModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <div class="space-y-4 max-h-[70vh] modal-scrollable overflow-y-auto">
                    <div class="space-y-2">
                        <p>Nama: <span class="font-bold">{{ Auth::user()->name }}</span></p>
                        <p>Tanggal: <span id="orderDate">{{ now()->format('d/m/Y H:i') }}</span></p>
                        <p>Status: <span class="font-bold status-text"></span></p>
                    </div>
                    <button id="printButton" style="display: none;" onclick="printOrder()"
                        class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                        Cetak Pesanan
                    </button>
                </div>
            </div>
        </div>

        <!-- Floating Mobile Cart -->
        <div class="md:hidden fixed bottom-20 right-4 z-[1001]">
            <button id="mobileCartButton" onclick="toggleMobileCart()"
                class="bg-red-500 text-white p-3 rounded-full shadow-lg relative 
                                   hover:bg-red-600 transition-transform transform hover:scale-110">
                🛒
                <span id="mobileCartBadge"
                    class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full 
                                     w-6 h-6 flex items-center justify-center text-xs">0</span>
            </button>

            <div id="mobileCartPanel"
                class="hidden absolute mobile-cart-panel w-72 bg-white rounded-lg 
                                shadow-xl transition-all duration-300 origin-bottom-right animate-bounce-in">
                <div class="p-4 overflow-y-auto max-h-[60vh]">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Keranjang</h3>
                        <button onclick="toggleMobileCart()" class="text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                    </div>
                    <div id="mobileCartItems" class="space-y-3"></div>
                    <div class="mt-4 pt-4 border-t">
                        <div class="flex justify-between mb-3">
                            <span class="font-medium">Total:</span>
                            <span id="mobileCartTotal" class="font-bold text-red-500">Rp0</span>
                        </div>
                        <button onclick="openCheckoutModal()"
                            class="w-full bg-red-500 text-white py-2 rounded-md 
                                               hover:bg-red-600">
                            Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="container mx-auto p-4">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-4 md:mb-8">
                <div class="text-left">
                    <h1 class="text-2xl md:text-3xl font-bold text-red-500 mb-1">🍲 Warung Seblak Ajnira</h1>
                    <p class="text-sm md:text-base text-gray-600">Pilih topping seblak favoritmu</p>
                </div>

                <!-- Desktop Cart -->
                <div class="hidden md:block relative">
                    <button onclick="toggleCart()"
                        class="bg-red-500 text-white p-2 md:p-3 rounded-full shadow-lg relative 
                                   hover:bg-red-600">
                        🛒
                        <span id="cartBadge"
                            class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full 
                                     w-6 h-6 flex items-center justify-center text-xs">0</span>
                    </button>

                    <div id="cartDropdown"
                        class="hidden mt-2 w-72 bg-white rounded-lg shadow-xl absolute right-0 z-[1001]">
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold">Keranjang</h3>
                                <button onclick="clearCart()" class="text-red-500 text-sm">Hapus Semua</button>
                            </div>
                            <div id="cartItems" class="space-y-3 max-h-60 overflow-y-auto"></div>
                            <div class="mt-4 pt-4 border-t">
                                <div class="flex justify-between mb-3">
                                    <span class="font-medium">Total:</span>
                                    <span id="cartTotal" class="font-bold text-red-500">Rp0</span>
                                </div>
                                <button onclick="openCheckoutModal()"
                                    class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600">
                                    Checkout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Categories Scroll -->
            <div class="md:hidden mb-4">
                <div class="flex space-x-3 overflow-x-auto scroll-hide pb-3 horizontal-scroll">
                    <button onclick="filterByCategory('all')"
                        class="category-btn active px-4 py-2 bg-red-500 text-white rounded-full whitespace-nowrap">
                        Semua Topping
                    </button>
                    @foreach ($categories as $category)
                        <button onclick="filterByCategory('{{ $category->id }}')"
                            class="category-btn px-4 py-2 bg-gray-100 text-gray-600 rounded-full whitespace-nowrap">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Desktop Categories -->
            <div class="hidden md:flex mb-6 space-x-3">
                <button onclick="filterByCategory('all')"
                    class="category-btn active px-4 py-2 bg-red-500 text-white rounded-md">
                    Semua Topping
                </button>
                @foreach ($categories as $category)
                    <button onclick="filterByCategory('{{ $category->id }}')"
                        class="category-btn px-4 py-2 bg-gray-100 text-gray-600 rounded-md">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <!-- Mobile Cart Scroll -->
            <div class="md:hidden mb-6">
                <div id="mobileCart" class="flex overflow-x-auto scroll-hide gap-3 pb-3 horizontal-scroll">
                    <!-- Item cart akan ditambahkan via JavaScript -->
                </div>
            </div>

            <!-- Topping Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach ($topings as $toping)
                    <div class="bg-white rounded-xl p-2 md:p-3 shadow-md hover:shadow-lg transition-transform transform hover:scale-[1.02]"
                        data-category-id="{{ $toping->category_id }}">
                        <div class="relative h-28 md:h-36 rounded-lg overflow-hidden mb-2">
                            @if ($toping->image)
                                <img src="{{ asset('storage/' . $toping->image) }}" alt="{{ $toping->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 animate-pulse"></div>
                            @endif
                            <div
                                class="absolute bottom-1 right-1 px-2 py-0.5 bg-black bg-opacity-50 text-white rounded-full text-xs">
                                Stok: <span id="stock-{{ $toping->id }}">{{ $toping->stock }}</span>
                            </div>
                        </div>
                        <div class="text-gray-800 px-1">
                            <h3 class="text-sm md:text-base font-bold mb-1 truncate">{{ $toping->name }}</h3>
                            <div class="flex justify-between items-center">
                                <p class="text-base md:text-lg font-bold text-red-500">
                                    Rp{{ number_format($toping->price, 0, ',', '.') }}
                                </p>
                                <div class="flex items-center space-x-2">
                                    <button onclick="updateQuantity('{{ $toping->id }}', -1, {{ $toping->price }})"
                                        class="bg-gray-200 text-gray-700 px-2 py-1 rounded-md hover:bg-gray-300 text-xs md:text-sm">-</button>
                                    <span id="qty-{{ $toping->id }}" class="px-1 text-sm">0</span>
                                    <button onclick="updateQuantity('{{ $toping->id }}', 1, {{ $toping->price }})"
                                        class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 text-xs md:text-sm">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                checkPendingOrder();
            });

            function closeOrderModal() {
                document.getElementById('orderConfirmationModal').classList.add('hidden');
                sessionStorage.removeItem('pendingOrderStatus');
            }

            // function printOrder() {
            //     const transactionId = document.getElementById('printButton').dataset.transactionId;
            //     window.open(`/print/${transactionId}`, '_blank');
            //     sessionStorage.removeItem('pendingOrderStatus');
            // }


            let cart = [];
            let cartVisible = false;
            const paymentProviders = @json($paymentProviders->where('is_active', true));

            // Fungsi Keranjang
            function updateCartDisplay() {
                const total = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);

                // Desktop
                document.getElementById('cartBadge').textContent = cart.length;
                document.getElementById('cartTotal').textContent = `Rp${total.toLocaleString('id-ID')}`;

                // Mobile
                document.getElementById('mobileCartBadge').textContent = cart.length;
                document.getElementById('mobileCartTotal').textContent = `Rp${total.toLocaleString('id-ID')}`;

                // Update Items
                const mobileItems = document.getElementById('mobileCartItems');
                const desktopItems = document.getElementById('cartItems');

                const cartItemHTML = cart.map(item => `
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium text-sm">${item.name}</p>
                            <p class="text-xs text-gray-500">
                                ${item.quantity}x Rp${item.price.toLocaleString('id-ID')}
                            </p>
                        </div>
                        <button onclick="removeItem('${item.id}')" 
                                class="text-red-500 hover:text-red-700">
                            ✕
                        </button>
                    </div>
                `).join('');

                mobileItems.innerHTML = cartItemHTML;
                desktopItems.innerHTML = cartItemHTML;
            }

            document.addEventListener('click', function(event) {
                // Untuk desktop
                const cartDropdown = document.getElementById('cartDropdown');
                const cartButton = document.querySelector('.hidden.md\\:block .relative button');

                if (cartDropdown && !cartDropdown.contains(event.target) &&
                    !cartButton.contains(event.target)) {
                    cartDropdown.classList.add('hidden');
                }

                // Untuk mobile
                const mobilePanel = document.getElementById('mobileCartPanel');
                const mobileButton = document.getElementById('mobileCartButton');

                if (mobilePanel && !mobilePanel.contains(event.target) &&
                    !mobileButton.contains(event.target)) {
                    mobilePanel.classList.add('hidden');
                }
            });

            // Fungsi Filter Kategori
            function filterByCategory(categoryId) {
                // Update active class
                document.querySelectorAll('.category-btn').forEach(btn => {
                    btn.classList.remove('active', 'bg-red-500', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-600');
                });

                event.target.classList.add('active', 'bg-red-500', 'text-white');
                event.target.classList.remove('bg-gray-100', 'text-gray-600');

                // Filter items
                const allItems = document.querySelectorAll('[data-category-id]');
                allItems.forEach(item => {
                    if (categoryId === 'all' || item.dataset.categoryId === categoryId) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            }

            function updateQuantity(id, change, price) {
                const itemIndex = cart.findIndex(item => item.id === id);
                const stockElement = document.getElementById(`stock-${id}`);
                let currentStock = parseInt(stockElement.textContent);

                if (itemIndex > -1) {
                    const newQty = cart[itemIndex].quantity + change;
                    if (newQty < 0) return;
                    if (newQty > currentStock) {
                        Swal.fire('Stok tidak cukup!', '', 'warning');
                        return;
                    }
                    cart[itemIndex].quantity = newQty;
                    stockElement.textContent = currentStock - change;
                } else if (change === 1) {
                    if (currentStock < 1) {
                        Swal.fire('Stok habis!', '', 'warning');
                        return;
                    }
                    cart.push({
                        id: id,
                        name: document.querySelector(`#qty-${id}`).parentElement.parentElement.parentElement
                            .querySelector('h3').textContent,
                        price: price,
                        quantity: 1
                    });
                    stockElement.textContent = currentStock - 1;
                }
                updateCartDisplay();
            }

            function removeItem(id) {
                const itemIndex = cart.findIndex(item => item.id === id);
                const removedItem = cart[itemIndex];
                const stockElement = document.getElementById(`stock-${id}`);
                if (stockElement) {
                    stockElement.textContent = parseInt(stockElement.textContent) + removedItem.quantity;
                }
                cart = cart.filter(item => item.id !== id);
                document.getElementById(`qty-${id}`).textContent = 0;
                updateCartDisplay();
            }

            function clearCart() {
                cart.forEach(item => {
                    const stockElement = document.getElementById(`stock-${item.id}`);
                    if (stockElement) {
                        stockElement.textContent = parseInt(stockElement.textContent) + item.quantity;
                    }
                });
                cart = [];
                document.querySelectorAll('[id^="qty-"]').forEach(el => el.textContent = 0);
                document.getElementById('mobileCart').innerHTML = '';
                updateCartDisplay();
            }

            function toggleCart() {
                if (window.innerWidth >= 768) {
                    const dropdown = document.getElementById('cartDropdown');
                    dropdown.classList.toggle('hidden');
                }
            }

            function toggleMobileCart() {
                const panel = document.getElementById('mobileCartPanel');
                panel.classList.toggle('hidden');
                panel.classList.toggle('animate-bounce-in');
            }

            // Fungsi Modal
            function openCheckoutModal() {
                if (cart.length === 0) {
                    Swal.fire('Keranjang kosong!', 'Silakan tambahkan item terlebih dahulu', 'warning');
                    return;
                }
                document.getElementById('orderItems').innerHTML = cart.map(item => `
                    <div class="flex justify-between">
                        <span>${item.name} (${item.quantity}x)</span>
                        <span>Rp${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                    </div>
                `).join('');
                const total = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
                document.getElementById('modalTotal').textContent = `Rp${total.toLocaleString('id-ID')}`;
                document.getElementById('checkoutModal').classList.remove('hidden');
            }

            function closeCheckoutModal() {
                document.getElementById('checkoutModal').classList.add('hidden');
            }

            function closePaymentModal() {
                document.getElementById('paymentConfirmationModal').classList.add('hidden');
            }

            function closeOrderModal() {
                document.getElementById('orderConfirmationModal').classList.add('hidden');
                sessionStorage.removeItem('pendingOrderStatus');
            }

            // Fungsi Copy
            function copyToClipboard(element) {
                const text = element.querySelector('span').innerText;
                try {
                    navigator.clipboard.writeText(text).then(() => {
                        element.style.backgroundColor = '#DCFCE7';
                        setTimeout(() => {
                            element.style.backgroundColor = '#F3F4F6';
                        }, 1000);
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersalin!',
                            text: 'Nomor rekening berhasil disalin',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    });
                } catch (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal menyalin',
                        text: 'Browser tidak mendukung clipboard API',
                    });
                }
            }

            // Fungsi Tampilkan Detail Pembayaran
            function showPaymentDetails() {
                const providerId = document.getElementById('paymentMethod').value;
                const provider = paymentProviders.find(p => p.id == providerId);
                const paymentDetails = document.getElementById('paymentDetails');

                if (provider) {
                    paymentDetails.classList.remove('hidden');
                    document.getElementById('providerLogo').src = provider.logo ?
                        "{{ asset('storage/') }}/" + provider.logo : '';
                    document.getElementById('providerName').textContent = provider.name;
                    document.getElementById('providerType').textContent = provider.type;
                    document.getElementById('accountNumber').textContent = provider.account_number;
                    document.getElementById('accountName').textContent = provider.account_name;
                    document.getElementById('instructions').textContent = provider.instructions;
                } else {
                    paymentDetails.classList.add('hidden');
                }
            }

            async function processPayment(e) {
                e.preventDefault();
                const tableNumber = document.getElementById('tableNumber').value;
                const spicinessLevel = document.getElementById('spicinessLevel').value;
                const bowlSize = document.getElementById('bowlSize').value; // Tambahkan ini

                if (!tableNumber || !spicinessLevel || !bowlSize) {
                    Swal.fire('Error!', 'Harap lengkapi semua field!', 'error');
                    return;
                }

                const orderData = {
                    table_id: tableNumber,
                    spiciness_level: spicinessLevel,
                    bowl_size: bowlSize, // Tambahkan ini
                    items: cart,
                    total_price: cart.reduce((acc, item) => acc + (item.price * item.quantity), 0)
                };
                sessionStorage.setItem('pendingOrder', JSON.stringify(orderData));

                // Tampilkan item di modal pembayaran
                const paymentOrderItems = document.getElementById('paymentOrderItems');
                paymentOrderItems.innerHTML = cart.map(item => `
                    <div class="flex justify-between">
                        <span>${item.name} (${item.quantity}x)</span>
                        <span>Rp${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                    </div>
                `).join('');

                // Update total harga
                document.getElementById('paymentTotal').textContent =
                    `Rp${orderData.total_price.toLocaleString('id-ID')}`;

                // Buka modal pembayaran
                closeCheckoutModal();
                document.getElementById('paymentConfirmationModal').classList.remove('hidden');
            }

            window.addEventListener('beforeunload', function(e) {
                const modal = document.getElementById('orderConfirmationModal');

                if (modal && !modal.classList.contains('hidden')) {
                    const transactionId = document.getElementById('printButton')?.dataset.transactionId;
                    const status = document.querySelector('.status-text')?.textContent;

                    if (transactionId && status) {
                        sessionStorage.setItem('pendingOrderStatus', JSON.stringify({
                            showModal: true,
                            transactionId: transactionId,
                            status: status,
                            timestamp: Date.now()
                        }));
                    }
                }
            });

            async function submitPayment() {
                const paymentProof = document.getElementById('paymentProof').files[0];
                const providerId = document.getElementById('paymentMethod').value;
                const orderData = JSON.parse(sessionStorage.getItem('pendingOrder'));

                if (!providerId) {
                    Swal.fire('Error!', 'Harap pilih metode pembayaran', 'error');
                    return;
                }
                if (!paymentProof) {
                    Swal.fire('Error!', 'Harap upload bukti pembayaran', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('payment_proof', paymentProof);
                formData.append('provider_id', providerId);
                formData.append('order_data', JSON.stringify({
                    table_id: orderData.table_id,
                    spiciness_level: orderData.spiciness_level,
                    bowl_size: orderData.bowl_size,
                    total_price: orderData.total_price,
                    items: cart.map(item => ({
                        id: item.id,
                        quantity: item.quantity
                    }))
                }));

                try {
                    const response = await fetch('/confirm-payment', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        const transactionData = {
                            showModal: true,
                            transactionId: result.transactionId,
                            status: result.status,
                            timestamp: Date.now()
                        };

                        sessionStorage.setItem('pendingOrderStatus', JSON.stringify(transactionData));
                        showOrderModal(result.status, result.transactionId);

                        closePaymentModal();
                        clearCart();
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    console.error('Payment error:', error);
                    Swal.fire('Error!', error.message, 'error');
                }
            }

            function showOrderModal(status, transactionId) {
                try {
                    const statusElement = document.querySelector('.status-text');
                    const printBtn = document.getElementById('printButton');
                    const modal = document.getElementById('orderConfirmationModal');

                    if (!statusElement || !printBtn || !modal) {
                        throw new Error('Modal elements not found');
                    }

                    statusElement.textContent = status;
                    statusElement.className = `font-bold ${status === 'proses' ? 'text-green-500' : 'text-yellow-500'}`;
                    printBtn.style.display = status === 'proses' ? 'block' : 'none';
                    printBtn.dataset.transactionId = transactionId;
                    modal.classList.remove('hidden');

                } catch (error) {
                    console.error('Error showing modal:', error);
                    sessionStorage.removeItem('pendingOrderStatus');
                }
            }

            function printOrder() {
                try {
                    const transactionId = document.getElementById('printButton').dataset.transactionId;
                    if (!transactionId) throw new Error('ID Transaksi tidak valid');

                    // Gabungkan pendekatan dari report yang berhasil
                    fetch(`/transaksi/print/${transactionId}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Gagal memuat data cetakan');
                            return response.text();
                        })
                        .then(html => {
                            const printWindow = window.open('', '_blank');

                            // Handle pop-up blocker
                            if (!printWindow || printWindow.closed) {
                                throw new Error('Pop-up diblokir. Izinkan pop-up untuk mencetak');
                            }

                            printWindow.document.write(html);
                            printWindow.document.close();

                            // Trigger print setelah konten siap
                            setTimeout(() => {
                                printWindow.print();
                                printWindow.onafterprint = () => {
                                    sessionStorage.removeItem('pendingOrderStatus');
                                    printWindow.close();
                                };
                            }, 500);
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Mencetak',
                                html: `Silakan coba lagi atau <a href="/transaksi/print/${transactionId}" 
                           target="_blank" class="text-blue-500">buka halaman cetak</a>`,
                            });
                        });

                } catch (error) {
                    console.error('Print error:', error);
                    Swal.fire('Error!', error.message, 'error');
                }
            }

            function showPersistedOrderModal(status, transactionId) {
                const modal = document.getElementById('orderConfirmationModal');
                if (!modal) return;

                const statusElement = modal.querySelector('.status-text');
                const printButton = modal.querySelector('#printButton');

                statusElement.textContent = status;
                statusElement.className = `font-bold ${status === 'proses' ? 'text-green-500' : 'text-yellow-500'}`;

                printButton.style.display = status === 'proses' ? 'block' : 'none';
                printButton.dataset.transactionId = transactionId;

                modal.classList.remove('hidden');
            }

            // function printOrder() {
            //     const transactionId = document.getElementById('printButton').dataset.transactionId;
            //     window.open(`/print/${transactionId}`, '_blank');
            // }

            function checkPendingOrder() {
                const pendingOrder = JSON.parse(sessionStorage.getItem('pendingOrderStatus'));

                if (pendingOrder && pendingOrder.showModal) {
                    const oneHourAgo = Date.now() - 3600000;

                    if (pendingOrder.timestamp > oneHourAgo) {
                        fetch(`/check-status/${pendingOrder.transactionId}`)
                            .then(response => {
                                if (!response.ok) throw new Error('Gagal memuat status');
                                return response.json();
                            })
                            .then(data => {
                                showPersistedOrderModal(data.status, pendingOrder.transactionId);
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                sessionStorage.removeItem('pendingOrderStatus');
                            });
                    } else {
                        sessionStorage.removeItem('pendingOrderStatus');
                    }
                }
            }
        </script>
    </body>

    </html>
</x-app-layout>
