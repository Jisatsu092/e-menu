<nav x-data="{ 
    open: false, 
    masterDropdown: false, 
    transactionDropdown: false, 
    topingDropdown: false, 
    isDesktop: window.innerWidth >= 1024,
    profileOpen: false
}" 
x-init="() => { 
    window.addEventListener('resize', () => isDesktop = window.innerWidth >= 1024) 
}" 
class="bg-white border-b border-gray-100 shadow-sm">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Left Section: Logo & Desktop Menu -->
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

                <!-- Desktop Navigation -->
                <div class="hidden sm:flex ml-4 space-x-4">
                    <!-- Beranda Button untuk Admin dan User -->
                    @can('role-A')
                    <button @click="window.location.href='{{ route('beranda') }}'" 
                        class="flex items-center px-3 py-2 rounded-md hover:bg-red-500/10 transition-colors duration-300">
                        <span class="text-gray-600">Beranda</span>
                    </button>
                    @endcan
                    
                    @can('role-U')
                    <button @click="window.location.href='{{ route('beranda') }}'" 
                        class="flex items-center px-3 py-2 rounded-md hover:bg-red-500/10 transition-colors duration-300">
                        <span class="text-gray-600">Beranda</span>
                    </button>
                    @endcan

                    @can('role-A')
                    <!-- Master Dropdown Hanya untuk Admin -->
                    <div class="relative" x-data="{ masterOpen: false }" @click.outside="masterOpen = false">
                        <button @click="masterOpen = !masterOpen" 
                            class="flex items-center px-3 py-2 rounded-md hover:bg-red-500/10 transition-colors duration-300">
                            <span class="text-gray-600">Master</span>
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="masterOpen" x-transition 
                            class="absolute mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-100">
                            <a href="{{ route('category.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50">Kategori</a>
                            <a href="{{ route('table.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50">Meja</a>
                        </div>
                    </div>

                    <!-- Transaksi Dropdown untuk Admin -->
                    <div class="relative" x-data="{ transactionOpen: false }" @click.outside="transactionOpen = false">
                        <button @click="transactionOpen = !transactionOpen" 
                            class="flex items-center px-3 py-2 rounded-md hover:bg-red-500/10 transition-colors duration-300">
                            <span class="text-gray-600">Transaksi</span>
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="transactionOpen" x-transition 
                            class="absolute mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-100">
                            <a href="{{ route('transaction.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50">Transaksi</a>
                        </div>
                    </div>

                    <div class="relative" x-data="{ topingOpen: false }" @click.outside="topingOpen = false">
                        <button @click="topingOpen = !topingOpen" 
                            class="flex items-center px-3 py-2 rounded-md hover:bg-red-500/10 transition-colors duration-300">
                            <span class="text-gray-600">Toping</span>
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="topingOpen" x-transition 
                            class="absolute mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-100">
                            <a href="{{ route('toping.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50">Toping</a>
                        </div>
                    </div>
                    @endcan

                    <!-- Transaksi untuk Kasir -->
                    @can('role-K')
                    <div class="relative" x-data="{ transactionOpen: false }" @click.outside="transactionOpen = false">
                        <button @click="transactionOpen = !transactionOpen" 
                            class="flex items-center px-3 py-2 rounded-md hover:bg-red-500/10 transition-colors duration-300">
                            <span class="text-gray-600">Transaksi</span>
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="transactionOpen" x-transition 
                            class="absolute mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-100">
                            <a href="{{ route('transaction.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50">Transaksi</a>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>

            <!-- Right Section: Profile & Notifications -->
            <div class="flex items-center space-x-4">
                <!-- Profile Dropdown -->
                <div class="relative">
                    <button @click="profileOpen = !profileOpen" 
                            class="flex items-center text-gray-600 hover:text-red-600 transition">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14.016q2.531 0 5.273 1.102t2.742 2.883v2.016h-16.031v-2.016q0-1.781 2.742-2.883t5.273-1.102zM12 12q-1.641 0-2.813-1.172t-1.172-2.813 1.172-2.813 2.813-1.172 2.813 1.172 1.172 2.813-1.172 2.813-2.813 1.172z"/>
                            </svg>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen" x-transition 
                        @click.outside="profileOpen = false"
                        class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg border border-gray-100 z-50">
                        
                        <!-- Profile Header -->
                        <div class="p-4 border-b">
                            <div class="font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('profile.edit') }}" 
                                class="flex items-center px-4 py-2 hover:bg-red-50 transition">
                                <svg class="w-5 h-5 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profile
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                    class="flex items-center w-full px-4 py-2 hover:bg-red-50 transition">
                                    <svg class="w-5 h-5 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
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
    <aside x-show="open" x-transition 
        class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg z-50 transform transition-transform sm:hidden">
        
        <div class="p-4 border-b flex justify-between items-center">
            <span class="text-xl font-bold text-red-600">Ajnira</span>
            <button @click="open = false" class="text-gray-600 hover:text-red-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-4 space-y-2">
            <!-- Beranda untuk Admin dan User -->
            @can('role-A')
            <button @click="window.location.href='{{ route('beranda') }}'" 
                class="w-full text-left px-4 py-2 rounded-md hover:bg-gray-100">
                Beranda
            </button>
            @endcan
            
            @can('role-U')
            <button @click="window.location.href='{{ route('beranda') }}'" 
                class="w-full text-left px-4 py-2 rounded-md hover:bg-gray-100">
                Beranda
            </button>
            @endcan

            @can('role-A')
            <!-- Master Menu Mobile -->
            <div x-data="{ mobileMasterOpen: false }" class="relative">
                <button @click="mobileMasterOpen = !mobileMasterOpen" 
                    class="w-full text-left px-4 py-2 rounded-md hover:bg-gray-100 flex justify-between items-center">
                    Master
                    <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': mobileMasterOpen}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div x-show="mobileMasterOpen" x-collapse class="ml-4">
                    <a href="{{ route('category.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Kategori</a>
                    <a href="{{ route('table.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Meja</a>
                </div>
            </div>

            <!-- Transaksi Mobile untuk Admin -->
            <div x-data="{ mobileTransactionOpen: false }" class="relative">
                <button @click="mobileTransactionOpen = !mobileTransactionOpen" 
                    class="w-full text-left px-4 py-2 rounded-md hover:bg-gray-100 flex justify-between items-center">
                    Transaksi
                    <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': mobileTransactionOpen}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div x-show="mobileTransactionOpen" x-collapse class="ml-4">
                    <a href="{{ route('transaction.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Transaksi</a>
                </div>
            </div>
            @endcan

            @can('role-K')
            <!-- Transaksi Mobile untuk Kasir -->
            <div x-data="{ mobileTransactionOpen: false }" class="relative">
                <button @click="mobileTransactionOpen = !mobileTransactionOpen" 
                    class="w-full text-left px-4 py-2 rounded-md hover:bg-gray-100 flex justify-between items-center">
                    Transaksi
                    <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': mobileTransactionOpen}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div x-show="mobileTransactionOpen" x-collapse class="ml-4">
                    <a href="{{ route('transaction.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Transaksi</a>
                </div>
            </div>
            @endcan
        </div>
    </aside>
</nav>