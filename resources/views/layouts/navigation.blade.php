<nav x-data="{
    open: false,
    masterDropdown: false,
    transactionDropdown: false,
    isDesktop: window.innerWidth >= 1024,
    profileOpen: false
}" x-init="() => {
    window.addEventListener('resize', () => isDesktop = window.innerWidth >= 1024)
}" class="bg-white border-b border-gray-100 shadow-sm relative z-[1000]">

    <!-- Desktop Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Left Section -->
            <div class="flex items-center space-x-4">
                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="sm:hidden text-gray-600 hover:text-red-600 transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                    </svg>
                </button>

                <!-- Logo -->
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" class="h-9 w-auto" alt="Logo">
                    <span class="text-2xl font-bold text-red-600 ml-2">Ajnira</span>
                </div>

                <!-- Desktop Menu Items -->
                <div class="hidden sm:flex ml-4 space-x-4">
                    <!-- Beranda -->
                    <button @click="window.location.href='{{ route('beranda') }}'" 
                            class="flex items-center px-4 py-2 rounded-md text-gray-600 hover:bg-red-50 transition">
                        Beranda
                    </button>

                    <!-- Master Dropdown -->
                    @can('role-A')
                    <div class="relative" x-data="{ masterOpen: false }" @click.outside="masterOpen = false">
                        <button @click="masterOpen = !masterOpen" 
                                class="flex items-center px-4 py-2 rounded-md text-gray-600 hover:bg-red-50 transition">
                            Master
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                            </svg>
                        </button>
                        <div x-show="masterOpen" x-transition class="absolute mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 z-[1000]">
                            <a href="{{ route('category.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-100 transition">Kategori</a>
                            <a href="{{ route('table.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-100 transition">Meja</a>
                            <a href="{{ route('toping.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-100 transition">Toping</a>
                        </div>
                    </div>

                    <!-- User Interface Dropdown -->
                    <div class="relative" x-data="{ userInterfaceOpen: false }" @click.outside="userInterfaceOpen = false">
                        <button @click="userInterfaceOpen = !userInterfaceOpen" 
                                class="flex items-center px-4 py-2 rounded-md text-gray-600 hover:bg-red-50 transition">
                            User Interface
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                            </svg>
                        </button>
                        <div x-show="userInterfaceOpen" x-transition class="absolute mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 z-[1000]">
                            <a href="{{ route('userInterface.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-100 transition">Kelola User</a>
                        </div>
                    </div>
                    @endcan

                    <!-- Transaksi Dropdown -->
                    @canany(['role-A', 'role-K'])
                    <div class="relative" x-data="{ transactionOpen: false }" @click.outside="transactionOpen = false">
                        <button @click="transactionOpen = !transactionOpen" 
                                class="flex items-center px-4 py-2 rounded-md text-gray-600 hover:bg-red-50 transition">
                            Transaksi
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                            </svg>
                        </button>
                        <div x-show="transactionOpen" x-transition class="absolute mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 z-[1000]">
                            <a href="{{ route('transaction.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-100 transition">Daftar Transaksi</a>
                            <a href="{{ route('payment_providers.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-100 transition">Payment Provider</a>
                        </div>
                    </div>
                    @endcanany

                    @canany(['role-A', 'role-K'])
                    <div class="relative" x-data="{ laporanOpen: false }" @click.outside="laporanOpen = false">
                        <button @click="laporanOpen = !laporanOpen" 
                                class="flex items-center px-4 py-2 rounded-md text-gray-600 hover:bg-red-50 transition">
                            Laporan
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                            </svg>
                        </button>
                        <div x-show="laporanOpen" x-transition class="absolute mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 z-[1000]">
                            <a href="{{ route('transaction.report') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-100 transition">Laporan Transaksi</a>
                            <a href="{{ route('transaction_details.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-100 transition">Detail Penjualan</a>
                        </div>
                    </div>
                    @endcanany
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex items-center space-x-6">
                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">
                    <button @click="profileOpen = !profileOpen" 
                            class="flex items-center space-x-2 text-gray-600 hover:text-red-600 transition">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14.016q2.531 0 5.273 1.102t2.742 2.883v2.016h-16.031v-2.016q0-1.781 2.742-2.883t5.273-1.102zM12 12q-1.641 0-2.813-1.172t-1.172-2.813 1.172-2.813 2.813-1.172 2.813 1.172 1.172 2.813-1.172 2.813-2.813 1.172z"/>
                            </svg>
                        </div>
                        <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                    </button>
                    <div x-show="profileOpen" x-transition class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg border border-gray-100 z-[1000]">
                        <div class="p-4 border-b">
                            <div class="font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="py-2">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center px-4 py-2 hover:bg-red-50 transition">
                                <svg class="w-5 h-5 mr-3 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-4 py-2 hover:bg-red-50 transition">
                                    <svg class="w-5 h-5 mr-3 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar -->
    <div x-show="open" x-transition class="fixed inset-0 bg-black bg-opacity-50 z-40 sm:hidden" @click="open = false"></div>
    <aside x-show="open" x-transition class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg z-50 transform transition-transform sm:hidden">
        <div class="p-4 border-b flex justify-between items-center">
            <span class="text-xl font-bold text-red-600">Ajnira</span>
            <button @click="open = false" class="text-gray-600 hover:text-red-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-4 space-y-4">
            <!-- Mobile Menu Items -->
            <button @click="window.location.href='{{ route('beranda') }}'" 
                    class="w-full px-4 py-3 rounded-md hover:bg-gray-100 transition">
                Beranda
            </button>

            <div class="space-y-2">
                <a href="{{ route('userInterface.index') }}" class="block px-4 py-3 hover:bg-gray-100 transition">Menu</a>
                <a href="{{ route('transaction_details.index') }}" class="block px-4 py-3 hover:bg-gray-100 transition">Keranjang</a>
            </div>

            <!-- Mobile Profile Section -->
            <div class="mt-4 border-t pt-4">
                <div class="px-4 py-3">
                    <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 hover:bg-gray-100 transition">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-100 transition">Logout</button>
                </form>
            </div>
        </div>
    </aside>
</nav>

<!-- Alpine.js Collapse Plugin -->
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.plugin(Collapse);
    });
</script>