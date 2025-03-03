<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('images/logo.png') }}" class="h-9 w-auto" alt="Logo">
                        <span class="text-2xl font-le-vin-bold text-red-600">Ajnira</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Menu Items with Hover Effect -->
                    <div class="relative group">
                        <button class="flex items-center px-3 py-2 rounded-md hover:bg-red-500/10 transition-colors duration-300">
                            <span class="text-gray-600">Master</span>
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <!-- Dropdown Menu -->
                        <div class="absolute hidden group-hover:block mt-2 w-48 bg-white rounded-md shadow-lg py-1 transition-all duration-300 origin-top transform opacity-0 group-hover:opacity-100">
                            <x-dropdown-link :href="route('category.index')" class="hover:bg-red-50">
                                {{ __('Kategori') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('table.index')" class="hover:bg-red-50">
                                {{ __('Meja') }}
                            </x-dropdown-link>
                        </div>
                    </div>

                    <!-- Repeat similar structure for other menu items -->
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex items-center space-x-4">
                <!-- Profile Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-1 hover:bg-red-500/10 rounded-full p-1 transition-colors duration-300">
                            <div class="relative">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-gray-600">{{ Auth::user()->name }}</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b">
                            <div class="font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-red-50">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <!-- Logout Form -->
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <!-- Responsive Menu (mobile) -->
</nav>

<style>
/* Tambahkan di CSS Anda */
@font-face {
    font-family: 'Le Vin Bold';
    src: url('../fonts/le-vin-bold.ttf') format('truetype');
}

.font-le-vin-bold {
    font-family: 'Le Vin Bold', sans-serif;
}

/* Transition efek untuk dropdown */
.dropdown-enter-active, .dropdown-leave-active {
    transition: all 0.3s ease;
}

.dropdown-enter-from, .dropdown-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>