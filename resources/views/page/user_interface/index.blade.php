<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ajnira Ramen - Pemesanan</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>
        <style>
            .animate-bounce-in {
                animation: bounceIn 0.3s ease;
            }
            @keyframes bounceIn {
                0% { transform: scale(0.9); opacity: 0; }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); opacity: 1; }
            }
        </style>
    </head>
    <body class="bg-gray-100">
        <!-- Modal Konfirmasi Pesanan -->
        <div id="checkoutModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-lg animate-bounce-in mx-auto">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-xl font-bold text-red-500">Konfirmasi Pesanan</h3>
                    <button onclick="closeCheckoutModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <form id="checkoutForm" onsubmit="processPayment(event)">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                            <input type="text" value="{{ Auth::user()->name }}" disabled 
                                   class="mt-1 block w-full rounded-md bg-gray-100 p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Meja</label>
                            <select id="tableNumber" required
                                class="mt-1 block w-full rounded-md border p-2">
                                <option value="">Pilih Meja</option>
                                @foreach($tables as $table)
                                <option value="{{ $table->id }}">Meja {{ $table->number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ukuran Mangkuk</label>
                            <select id="bowlSize" required
                                class="mt-1 block w-full rounded-md border p-2">
                                <option value="">Pilih Ukuran</option>
                                <option value="small">Kecil</option>
                                <option value="medium">Sedang</option>
                                <option value="large">Besar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tingkat Kepedasan</label>
                            <select id="spicinessLevel" required
                                class="mt-1 block w-full rounded-md border p-2">
                                <option value="">Pilih Level</option>
                                <option value="mild">Ringan</option>
                                <option value="medium">Sedang</option>
                                <option value="hot">Pedas</option>
                                <option value="extreme">Ekstrem</option>
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
                            <button type="submit" 
                                class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                Bayar Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Konfirmasi Pembayaran -->
        <div id="paymentConfirmationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-lg animate-bounce-in">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-xl font-bold text-red-500">Konfirmasi Pembayaran</h3>
                    <button onclick="closePaymentModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <div class="space-y-4">
                    <div id="qrCodeContainer" class="flex justify-center mb-4"></div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Upload Bukti Pembayaran</label>
                        <input type="file" id="paymentProof" accept="image/*" 
                               class="mt-1 block w-full rounded-md border p-2">
                    </div>
                    <button onclick="submitPayment()" 
                            class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600">
                        Konfirmasi Pembayaran
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Pesanan Berhasil -->
        <div id="orderConfirmationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-lg animate-bounce-in">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-xl font-bold text-red-500">Pesanan Berhasil</h3>
                    <button onclick="closeOrderModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <div class="space-y-4">
                    <div>
                        <p>Nama: <span id="customerName" class="font-bold"></span></p>
                        <p>Tanggal: <span id="orderDate"></span></p>
                        <p>Status: <span id="orderStatus" class="font-bold text-green-500"></span></p>
                    </div>
                    <button onclick="window.print()" 
                            class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                        Cetak Pesanan
                    </button>
                </div>
            </div>
        </div>

        <div class="container mx-auto p-6">
            <!-- Header dengan Keranjang -->
            <div class="flex justify-between items-center mb-12">
                <div class="text-left">
                    <h1 class="text-4xl font-bold text-red-500 mb-2">🍜 Ajnira Ramen</h1>
                    <p class="text-gray-600">Pilih topping favorit Anda</p>
                </div>
                <div class="relative mr-8">
                    <button id="cartButton" onclick="toggleCart()" 
                            class="bg-red-500 text-white p-4 rounded-full shadow-lg relative hover:bg-red-600">
                        🛒
                        <span id="cartBadge" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">0</span>
                    </button>
                    <div id="cartDropdown" class="hidden mt-2 w-72 bg-white rounded-lg shadow-xl absolute right-0">
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold">Keranjang Kamu</h3>
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

            <!-- Daftar Topping -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($topings as $toping)
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="relative h-48 rounded-lg overflow-hidden mb-4">
                        @if($toping->image)
                            <img src="{{ asset('storage/' . $toping->image) }}" alt="{{ $toping->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 animate-pulse"></div>
                        @endif
                        <div class="absolute bottom-2 right-2 px-3 py-1 bg-black bg-opacity-50 text-white rounded-full text-sm">
                            Stok: <span id="stock-{{ $toping->id }}">{{ $toping->stock }}</span>
                        </div>
                    </div>
                    <div class="text-gray-800">
                        <h3 class="text-xl font-bold mb-2">{{ $toping->name }}</h3>
                        <div class="flex justify-between items-center">
                            <p class="text-2xl font-bold text-red-500">
                                Rp{{ number_format($toping->price, 0, ',', '.') }}
                            </p>
                            <div class="flex items-center space-x-2">
                                <button onclick="updateQuantity('{{ $toping->id }}', -1, {{ $toping->price }})" 
                                        class="bg-gray-200 text-gray-700 px-3 py-1 rounded-lg hover:bg-gray-300">-</button>
                                <span id="qty-{{ $toping->id }}" class="px-3">0</span>
                                <button onclick="updateQuantity('{{ $toping->id }}', 1, {{ $toping->price }})" 
                                        class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">+</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <script>
            let cart = [];
            let cartVisible = false;

            // Fungsi Keranjang
            function updateCartDisplay() {
                document.getElementById('cartBadge').textContent = cart.reduce((acc, item) => acc + item.quantity, 0);
                const cartItems = document.getElementById('cartItems');
                cartItems.innerHTML = cart.map(item => `
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium">${item.name}</p>
                            <p class="text-sm text-gray-500">
                                ${item.quantity}x Rp${item.price.toLocaleString('id-ID')}
                            </p>
                        </div>
                        <button onclick="removeItem('${item.id}')" class="text-red-500 hover:text-red-700">
                            ✕
                        </button>
                    </div>
                `).join('');
                const total = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
                document.getElementById('cartTotal').textContent = `Rp${total.toLocaleString('id-ID')}`;
                cart.forEach(item => {
                    document.getElementById(`qty-${item.id}`).textContent = item.quantity;
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
                        name: document.querySelector(`#qty-${id}`).parentElement.parentElement.parentElement.querySelector('h3').textContent,
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
                updateCartDisplay();
            }

            function toggleCart() {
                cartVisible = !cartVisible;
                document.getElementById('cartDropdown').classList.toggle('hidden', !cartVisible);
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
            }

            // Proses Pembayaran
            async function processPayment(e) {
                e.preventDefault();
                const tableNumber = document.getElementById('tableNumber').value;
                const bowlSize = document.getElementById('bowlSize').value;
                const spicinessLevel = document.getElementById('spicinessLevel').value;
                
                if (!tableNumber || !bowlSize || !spicinessLevel) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Tidak Lengkap',
                        text: 'Harap lengkapi semua field yang diperlukan!'
                    });
                    return;
                }

                const orderData = {
                    table_id: tableNumber,
                    bowl_size: bowlSize,
                    spiciness_level: spicinessLevel,
                    total_price: cart.reduce((acc, item) => acc + (item.price * item.quantity), 0),
                    status: 'pending'
                };

                sessionStorage.setItem('pendingOrder', JSON.stringify(orderData));
                generateQRCode(orderData.total_price);
                closeCheckoutModal();
                document.getElementById('paymentConfirmationModal').classList.remove('hidden');
            }

            function generateQRCode(amount) {
                const qrCodeContainer = document.getElementById('qrCodeContainer');
                qrCodeContainer.innerHTML = '';
                new QRCode(qrCodeContainer, {
                    text: `https://payment.example.com?amount=${amount}`,
                    width: 200,
                    height: 200
                });
            }

            async function submitPayment() {
                const paymentProof = document.getElementById('paymentProof').files[0];
                const orderData = JSON.parse(sessionStorage.getItem('pendingOrder'));
                
                if (!paymentProof) {
                    Swal.fire('Error!', 'Harap upload bukti pembayaran', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('payment_proof', paymentProof);
                formData.append('order_data', JSON.stringify(orderData));
                
                try {
                    const response = await fetch('/confirm-payment', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        document.getElementById('customerName').textContent = "{{ Auth::user()->name }}";
                        document.getElementById('orderDate').textContent = new Date().toLocaleString();
                        document.getElementById('orderStatus').textContent = 'PAID';
                        document.getElementById('orderConfirmationModal').classList.remove('hidden');
                        closePaymentModal();
                        clearCart();
                    }
                } catch (error) {
                    Swal.fire('Error!', 'Gagal mengupload bukti pembayaran', 'error');
                }
            }
        </script>
    </body>
    </html>
</x-app-layout>