<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Warung Seblak Ajnira - Pemesanan</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <style>
            /* Tambahkan di bagian style */
            #orderConfirmationModal {
                z-index: 1002;
                /* Lebih tinggi dari floating cart */
            }

            @media (max-width: 768px) {
                #orderConfirmationModal {
                    align-items: flex-start;
                    padding-top: 20%;
                }

                #orderConfirmationModal>div {
                    width: 95vw;
                    margin: 0 auto;
                    max-height: 80vh;
                }
            }

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
                max-width: 280px;
                top: 100%;
                left: auto;
                right: 0;
                transform-origin: top right;
                margin-top: 4px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                z-index: 50;
            }


            @media (max-width: 768px) {
                .mobile-cart-panel {
                    /* Lebar dinamis sesuai konten, dengan padding */
                    width: 90vw !important;
                    left: 5vw !important;
                    right: 5vw !important;
                    max-width: 100vw !important;
                    transform-origin: top left;
                    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
                }

                /* Scroll vertikal jika konten terlalu panjang */
                .mobile-cart-panel .overflow-y-auto {
                    max-height: 65vh;
                }

                /* Tombol Checkout di mobile */
                .mobile-cart-panel button[type="button"] {
                    width: 100%;
                    font-size: 0.9rem;
                    padding: 0.6rem;
                }
            }

            #cartDropdown {
                transition: all 0.3s ease;
            }

            @media (max-width: 768px) {
                #cartDropdown {
                    transform: translateY(0);
                    bottom: 1rem;
                    left: 2.5vw;
                    right: 2.5vw;
                    width: 95vw;
                    z-index: 1001;
                    border-radius: 1rem;
                }
            }

            .horizontal-scroll::-webkit-scrollbar {
                display: none;
            }

            .mobile-card {
                min-width: 80vw;
            }

            #mobileCartDropdown {
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
                max-height: 80vh;
                bottom: 80px;
                left: 1rem;
                right: 1rem;
                width: auto;
            }

            #mobileCartItems::-webkit-scrollbar {
                width: 4px;
            }

            #mobileCartItems::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            #mobileCartItems::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }

            @media (min-width: 768px) {
                #mobileCartDropdown {
                    display: none !important;
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

            .cart-badge {
                @extend .absolute;
                -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs;
            }

            .cart-item-details {
                @extend .flex;
                justify-between items-center mb-3;
            }

            .cart-item-card {
                @apply flex-shrink-0 min-w-[120px] max-w-xs;
            }

            .cart-item-qty {
                @extend .text-xs;
                text-gray-500;
            }

            .cart-item-price {
                @extend .font-bold;
                text-red-500;
            }

            #imageModal {
                backdrop-filter: blur(5px);
            }

            #imageModal img {
                box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
            }

            #imagePreview {
                transition: transform 0.3s ease;
            }

            #imagePreview:hover {
                transform: scale(1.02);
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
                            <select id="tableNumber" required class="mt-1 block w-full rounded-md border p-2"
                                onchange="checkTableStatus(this.value)">
                                <option value="">Pilih Meja</option>
                                @foreach ($tables as $table)
                                    <option value="{{ $table->id }}" data-status="{{ $table->status }}"
                                        {{ $table->status === 'occupied' ? 'disabled' : '' }}>
                                        Meja {{ $table->number }} -
                                        {{ $table->status === 'occupied' ? ' (Terisi)' : ' (Tersedia)' }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="tableStatusMessage" class="text-sm mt-1"></div>
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
        <!-- Modal Konfirmasi Pembayaran -->
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
                                class="mt-1 block w-full rounded-md border p-2 hidden" onchange="previewImage(event)">
                            <label for="paymentProof" class="cursor-pointer">
                                <div
                                    class="border-2 border-dashed rounded-md p-4 text-center mt-2 hover:bg-gray-50 transition-colors">
                                    <span class="text-blue-500">Klik untuk Upload Bukti Transfer</span>
                                </div>
                            </label>
                            <div id="imagePreviewContainer" class="mt-3 hidden">
                                <div class="relative group">
                                    <img id="imagePreview" class="max-h-40 rounded-lg shadow-sm cursor-zoom-in"
                                        onclick="showImageModal(this.src)">
                                    <div
                                        class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick="removeImagePreview()"
                                            class="bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Klik gambar untuk memperbesar</p>
                            </div>
                        </div>
                    </div>
                    <button onclick="submitPayment()"
                        class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600 transition-colors">
                        Konfirmasi Pembayaran
                    </button>
                </div>
            </div>
        </div>

        <div id="imageModal"
            class="fixed inset-0 z-50 hidden bg-black bg-opacity-90 flex items-center justify-center">
            <div class="max-w-4xl max-h-[90vh]">
                <img id="modalImage" class="max-h-[80vh] rounded-lg">
                <button onclick="closeImageModal()"
                    class="absolute top-4 right-4 text-white text-3xl hover:text-gray-200">
                    &times;
                </button>
            </div>
        </div>
        <!-- Modal Pesanan Berhasil -->
        <div id="orderConfirmationModal"
            class="fixed inset-0 z-[1002] hidden flex items-center justify-center bg-black bg-opacity-50">
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
        <div class="fixed top-16 right-4 z-[1001]">
            <div class="fixed bottom-4 right-4 z-50 md:hidden">
                <button id="mobileCartButton" onclick="toggleMobileCart()"
                    class="bg-red-500 text-white p-4 rounded-full shadow-xl relative hover:bg-red-600 transition-transform transform hover:scale-110">
                    🛒
                    <span id="mobileCartBadge"
                        class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">0</span>
                </button>
            </div>
            <div id="mobileCartDropdown"
                class="fixed bottom-20 right-4 left-4 z-50 hidden bg-white rounded-xl shadow-2xl transition-all duration-300 origin-bottom-right transform">
                <div class="p-4 max-h-[70vh] flex flex-col">
                    <div class="flex justify-between items-center mb-4 pb-2 border-b">
                        <h3 class="text-lg font-bold">Keranjang Belanja</h3>
                        <div class="flex items-center space-x-4">
                            <button onclick="clearCart()" class="text-red-500 text-sm">Hapus Semua</button>
                            <button onclick="toggleMobileCart()" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div id="mobileCartItems" class="flex-1 overflow-y-auto space-y-3 mb-4"></div>

                    <div class="pt-4 border-t">
                        <div class="flex justify-between mb-3">
                            <span class="font-medium">Total Item:</span>
                            <span id="mobileCartItemTotal" class="font-bold text-red-500">0 item</span>
                        </div>
                        <div class="flex justify-between mb-4">
                            <span class="font-medium">Total Harga:</span>
                            <span id="mobileCartTotal" class="font-bold text-red-500">Rp0</span>
                        </div>
                        <button onclick="openCheckoutModal()"
                            class="w-full bg-red-500 text-white py-3 rounded-xl hover:bg-red-600 transition-colors font-medium">
                            Checkout Sekarang
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
                        class="bg-red-500 text-white p-2 md:p-3 rounded-full shadow-lg relative hover:bg-red-600">
                        🛒
                        <span id="cartBadge"
                            class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">0</span>
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
                                    <span class="font-medium">Total Item:</span>
                                    <span id="cartItemTotal" class="font-bold text-red-500">0 item</span>
                                </div>
                                <div class="flex justify-between mb-3">
                                    <span class="font-medium">Total Harga:</span>
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
                <div class="flex-1 overflow-x-auto scroll-hide horizontal-scroll">
                    <div class="flex space-x-3 pb-3">
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

                // Event listener untuk close dropdown dengan penanganan null
                document.addEventListener('click', function(event) {
                const cartButton = document.getElementById('mobileCartButton');
                const cartDropdown = document.getElementById('mobileCartDropdown');
                
                if (!cartButton.contains(event.target) && !cartDropdown.contains(event.target)) {
                    cartDropdown.classList.add('hidden');
                }

                    // Untuk mobile
                    const mobilePanel = document.getElementById('mobileCartPanel');
                    const mobileButton = document.getElementById('mobileCartButton');

                    if (mobilePanel && mobileButton) {
                        if (!mobilePanel.contains(event.target) && !mobileButton.contains(event.target)) {
                            mobilePanel.classList.add('hidden');
                            mobilePanel.classList.remove('animate-bounce-in');
                        }
                    }
                });
                window.toggleCart = function() {
                    const dropdown = document.getElementById('cartDropdown');
                    if (window.innerWidth >= 768) {
                        dropdown.classList.toggle('hidden');
                    }
                };

            });


            function closeOrderModal() {
                document.getElementById('orderConfirmationModal').classList.add('hidden');
                sessionStorage.removeItem('pendingOrderStatus');
            }

            let cart = [];
            let cartVisible = false;
            const paymentProviders = @json($paymentProviders->where('is_active', true));

            // Fungsi Keranjang
            function updateCartDisplay() {
                const totalQty = cart.reduce((acc, item) => acc + item.quantity, 0);
                const totalPrice = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);

                // Update Desktop
                document.getElementById('cartBadge').textContent = totalQty;
                document.getElementById('cartTotal').textContent = `Rp${totalPrice.toLocaleString('id-ID')}`;
                document.getElementById('cartItemTotal').textContent = `${totalQty} item`;

                // Update Mobile
                document.getElementById('mobileCartBadge').textContent = totalQty;
                document.getElementById('mobileCartTotal').textContent = `Rp${totalPrice.toLocaleString('id-ID')}`;
                document.getElementById('mobileCartItemTotal').textContent = `${totalQty} item`;

                // Update Items
                const mobileItems = document.getElementById('mobileCartItems');
                const desktopItems = document.getElementById('cartItems');

                mobileItems.innerHTML = cart.map(item => `
                    <div class="flex justify-between items-start bg-gray-50 rounded-lg p-3">
                        <div class="flex-1">
                            <p class="font-medium text-sm">${item.name}</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <button onclick="updateQuantity('${item.id}', -1, ${item.price})" 
                                    class="bg-gray-200 text-gray-700 px-2 py-1 rounded-lg text-xs">
                                    −
                                </button>
                                <span class="text-sm font-medium">${item.quantity}</span>
                                <button onclick="updateQuantity('${item.id}', 1, ${item.price})" 
                                    class="bg-red-500 text-white px-2 py-1 rounded-lg text-xs">
                                    +
                                </button>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-red-500">
                                Rp${(item.price * item.quantity).toLocaleString('id-ID')}
                            </p>
                            <button onclick="removeItem('${item.id}')" 
                                class="text-gray-400 hover:text-red-500 text-xs mt-1">
                                Hapus
                            </button>
                        </div>
                    </div>
                `).join('');
            }

            document.addEventListener('click', function(event) {
                    // Untuk desktop
                    const cartDropdown = document.getElementById('cartDropdown');
                    const cartButton = document.querySelector('.hidden.md\\:block .relative button');

                    if (cartDropdown && cartButton) {
                        if (!cartDropdown.contains(event.target) && !cartButton.contains(event.target)) {
                            cartDropdown.classList.add('hidden');
                        }
                    }

                // Mobile cart
                if (mobilePanel && mobileButton && !mobilePanel.contains(event.target) && !mobileButton.contains(event
                        .target)) {
                    mobilePanel.classList.add('hidden');
                    mobilePanel.classList.remove('animate-bounce-in');
                }

                // Sembunyikan dropdown saat klik tombol checkout
                const checkoutButton = document.querySelector('[onclick="openCheckoutModal()"]');
                if (checkoutButton && event.target.closest('[onclick="openCheckoutModal()"]')) {
                    if (cartDropdown) cartDropdown.classList.add('hidden');
                    if (mobilePanel) mobilePanel.classList.add('hidden');
                }
            });

            window.filterByCategory = function(categoryId) {
                // Update active class
                document.querySelectorAll('.category-btn').forEach(btn => {
                    btn.classList.remove('active', 'bg-red-500', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-600');
                });

                // Cari tombol yang sesuai dengan kategori
                const activeBtn = [...document.querySelectorAll('.category-btn')]
                    .find(btn => btn.textContent.trim() ===
                        (categoryId === 'all' ? 'Semua Topping' :
                            document.querySelector(`[onclick="filterByCategory('${categoryId}')"]`)?.textContent?.trim()));

                if (activeBtn) {
                    activeBtn.classList.remove('bg-gray-100', 'text-gray-600');
                    activeBtn.classList.add('active', 'bg-red-500', 'text-white');
                }

                // Filter items
                const allItems = document.querySelectorAll('[data-category-id]');
                allItems.forEach(item => {
                    if (categoryId === 'all' || item.dataset.categoryId === categoryId) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            };


            window.updateQuantity = function(id, change, price) {
                const itemIndex = cart.findIndex(item => item.id === id);
                const stockElement = document.getElementById(`stock-${id}`);
                if (!stockElement) return;

                let currentStock = parseInt(stockElement.textContent);

                if (itemIndex > -1) {
                    const newQty = cart[itemIndex].quantity + change;

                    // Pastikan qty tidak bisa negatif
                    if (newQty < 0) return;

                    if (newQty === 0) {
                        // Hapus item jika quantity mencapai 0
                        stockElement.textContent = currentStock + cart[itemIndex].quantity;
                        cart.splice(itemIndex, 1);
                        document.getElementById(`qty-${id}`).textContent = 0; // Pastikan indikator 0
                    } else {
                        if (newQty > currentStock) {
                            Swal.fire('Stok tidak cukup!', '', 'warning');
                            return;
                        }
                        cart[itemIndex].quantity = newQty;
                        stockElement.textContent = currentStock - change;
                        const qtyDisplay = document.getElementById(`qty-${id}`);
                        if (qtyDisplay) qtyDisplay.textContent = newQty;
                    }
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
                    const qtyDisplay = document.getElementById(`qty-${id}`);
                    if (qtyDisplay) qtyDisplay.textContent = 1;
                }

                updateCartDisplay();
            };

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
                const dropdown = document.getElementById('mobileCartDropdown');
                dropdown.classList.toggle('hidden');
                dropdown.classList.toggle('animate-bounce-in');

                if (!dropdown.classList.contains('hidden')) {
                    // Tutup keyboard jika terbuka
                    document.activeElement.blur();
                    // Scroll ke bawah jika perlu
                    window.scrollTo({
                        top: document.body.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            }

            // Fungsi Modal
            function openCheckoutModal() {
                showModal('checkoutModal');
                if (cart.length === 0) {
                    Swal.fire('Keranjang kosong!', 'Silakan tambahkan item terlebih dahulu', 'warning');
                    return;
                }


                document.getElementById('orderItems').innerHTML = cart.map(item => `
                    <div class="flex justify-between">
                        <span>${item.name} (Qty: ${item.quantity})</span>
                        <span>Rp${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                    </div>
                `).join('');

                const total = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
                document.getElementById('modalTotal').textContent = `Rp${total.toLocaleString('id-ID')}`;
                document.getElementById('cartDropdown').classList.add('hidden');
                document.getElementById('mobileCartPanel').classList.add('hidden');
                document.getElementById('checkoutModal').classList.remove('hidden');
                document.getElementById('mobileCartDropdown').classList.add('hidden');

                refreshTableStatus();

            }

            function closeCheckoutModal() {
                document.getElementById('checkoutModal').classList.add('hidden');
            }

            function closePaymentModal() {
                document.getElementById('paymentConfirmationModal').classList.add('hidden');
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
                const bowlSize = document.getElementById('bowlSize').value;

                if (!tableNumber || !spicinessLevel || !bowlSize) {
                    Swal.fire('Error!', 'Harap lengkapi semua field!', 'error');
                    return;
                }

                const orderData = {
                    table_id: tableNumber,
                    spiciness_level: spicinessLevel,
                    bowl_size: bowlSize,
                    items: cart,
                    total_price: cart.reduce((acc, item) => acc + (item.price * item.quantity), 0)
                };

                sessionStorage.setItem('pendingOrder', JSON.stringify(orderData));

                // Tampilkan item di modal pembayaran
                const paymentOrderItems = document.getElementById('paymentOrderItems');
                paymentOrderItems.innerHTML = cart.map(item => `
                    <div class="flex justify-between">
                        <span>${item.name} (Qty: ${item.quantity})</span>
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

                // Validasi tipe file
                if (!paymentProof.type.startsWith('image/')) {
                    Swal.fire('Error!', 'File harus berupa gambar', 'error');
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

            function checkTableStatus(tableId) {
                const selectedOption = document.querySelector(`#tableNumber option[value="${tableId}"]`);
                const statusMessage = document.getElementById('tableStatusMessage');

                if (selectedOption) {
                    if (selectedOption.dataset.status === 'occupied') {
                        statusMessage.innerHTML = '<span class="text-red-500">⛔ Meja sedang digunakan!</span>';
                        document.getElementById('tableNumber').value = '';
                    } else {
                        statusMessage.innerHTML = '<span class="text-green-500">✅ Meja tersedia</span>';
                    }
                }
            }

            async function refreshTableStatus() {
                try {
                    const response = await fetch('/tables');
                    const tables = await response.json();

                    const select = document.getElementById('tableNumber');
                    const currentValue = select.value;

                    select.innerHTML = '<option value="">Pilih Meja</option>';
                    tables.forEach(table => {
                        const option = new Option(
                            `Meja ${table.number} - ${table.status === 'occupied' ? '(Terisi)' : '(Tersedia)'}`,
                            table.id
                        );
                        option.dataset.status = table.status;
                        option.disabled = table.status === 'occupied';
                        select.appendChild(option);
                    });

                    select.value = currentValue;

                    // Force re-check status jika nilai masih ada
                    if (currentValue) checkTableStatus(currentValue);
                } catch (error) {
                    console.error('Gagal memperbarui status meja:', error);
                }

                setInterval(() => {
                    if (!document.getElementById('checkoutModal').classList.contains('hidden')) {
                        refreshTableStatus();
                    }
                }, 5000);
            }

            // Perbaikan fungsi showOrderModal
            function showOrderModal(status, transactionId) {
                showModal('orderConfirmationModal');
                try {
                    const modal = document.getElementById('orderConfirmationModal');
                    if (!modal) return;

                    // Gunakan class代替直接 memanipulasi style
                    modal.classList.remove('hidden');
                    modal.classList.add('show');

                    if (!modal) {
                        throw new Error('Modal element not found');
                    }

                    const statusElement = modal.querySelector('.status-text');
                    const printBtn = modal.querySelector('#printButton');

                    if (!statusElement || !printBtn) {
                        throw new Error('Modal elements not found');
                    }

                    statusElement.textContent = status;
                    statusElement.className = `font-bold ${status === 'proses' ? 'text-green-500' : 'text-yellow-500'}`;
                    printBtn.style.display = status === 'proses' ? 'block' : 'none';
                    printBtn.dataset.transactionId = transactionId;

                    // Tampilkan modal dengan benar
                    modal.classList.remove('hidden');
                    modal.style.display = 'flex'; // Tambahkan ini untuk browser tertentu

                    // Scroll ke atas untuk mobile
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                } catch (error) {
                    console.error('Error showing modal:', error);
                }
            }

            function printOrder() {
                try {
                    const transactionId = document.getElementById('printButton').dataset.transactionId;
                    if (!transactionId) throw new Error('ID Transaksi tidak valid');

                    fetch(`/transaksi/print/${transactionId}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Gagal memuat data cetakan');
                            return response.text();
                        })
                        .then(html => {
                            const printWindow = window.open('', '_blank');

                            if (!printWindow || printWindow.closed) {
                                throw new Error('Pop-up diblokir. Izinkan pop-up untuk mencetak');
                            }

                            printWindow.document.write(html);
                            printWindow.document.close();

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
                const printButton = document.getElementById('printButton');

                if (statusElement && printButton) {
                    statusElement.textContent = status;
                    statusElement.className = `font-bold ${status === 'proses' ? 'text-green-500' : 'text-yellow-500'}`;
                    printButton.style.display = status === 'proses' ? 'block' : 'none';
                    printButton.dataset.transactionId = transactionId;

                    // Tampilkan modal dengan menghapus kelas 'hidden'
                    modal.classList.remove('hidden');
                }
            }

            // function printOrder() {
            //     const transactionId = document.getElementById('printButton').dataset.transactionId;
            //     window.open(`/print/${transactionId}`, '_blank');
            // }

            function showModal(modalId) {
                document.getElementById(modalId).classList.remove('hidden');
                document.getElementById('mobileCartDropdown').classList.add('hidden');
            }
            function checkPendingOrder() {
                try {
                    const pendingOrderStr = sessionStorage.getItem('pendingOrderStatus');
                    if (!pendingOrderStr) return;

                    const pendingOrder = JSON.parse(pendingOrderStr);
                    if (!pendingOrder || !pendingOrder.showModal) return;

                    const oneHourAgo = Date.now() - 3600000; // 1 jam dalam milidetik
                    if (pendingOrder.timestamp > oneHourAgo) {
                        showPersistedOrderModal(pendingOrder.status, pendingOrder.transactionId);
                    } else {
                        sessionStorage.removeItem('pendingOrderStatus');
                    }
                } catch (error) {
                    console.error('Error checking pending order:', error);
                    sessionStorage.removeItem('pendingOrderStatus');
                }
            }
            // Preview image upload
            function previewImage(event) {
                const input = event.target;
                const container = document.getElementById('imagePreviewContainer');
                const preview = document.getElementById('imagePreview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        container.classList.remove('hidden');
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Show image in modal
            function showImageModal(src) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                modalImage.src = src;
                modal.classList.remove('hidden');
            }

            // Close image modal
            function closeImageModal() {
                document.getElementById('imageModal').classList.add('hidden');
            }

            // Remove image preview
            function removeImagePreview() {
                document.getElementById('paymentProof').value = '';
                document.getElementById('imagePreviewContainer').classList.add('hidden');
                document.getElementById('imagePreview').src = '';
            }
        </script>
    </body>

    </html>
</x-app-layout>
