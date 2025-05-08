<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Seblak Ajnira - Parasmanan Terenak!</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-50">
    <!-- Header Navigation -->
    <nav class="bg-orange-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h1 class="text-xl font-bold">Seblak Ajnira</h1>
            </div>
            
            @auth
                <a href="{{ url('/beranda') }}" class="bg-orange-500 hover:bg-orange-700 px-4 py-2 rounded-lg transition-colors">
                    Dashboard
                </a>
            @else
                <div class="space-x-4">
                    <a href="{{ route('login') }}" class="hover:text-orange-200 transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-700 px-4 py-2 rounded-lg transition-colors">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </nav>

    <!-- Hero Section with Fade Carousel -->
    <div x-data="{ activeSlide: 0, slides: ['/images/a.jpg', '/images/a.jpg', '/images/a.jpg'] }" 
         class="relative h-96 overflow-hidden">
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="activeSlide === index" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0">
                <img :src="slide" class="w-full h-full object-cover" alt="Hero image">
                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                    <h2 class="text-4xl text-white font-bold text-center">
                        "Renyahnya Kerupuk, Pedasnya Bumbu<br> 
                        <span class="text-orange-400">Kenangan Manis di Setiap Suapan</span>"
                    </h2>
                </div>
            </div>
        </template>
        
        <!-- Carousel Indicators -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <template x-for="(_, index) in slides" :key="index">
                <button @click="activeSlide = index" 
                        :class="{ 'bg-orange-500': activeSlide === index, 'bg-white': activeSlide !== index }"
                        class="w-3 h-3 rounded-full transition-colors"></button>
            </template>
        </div>
    </div>

    <!-- Menu Carousel -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-8 text-orange-600">Menu Andalan Kami</h3>
            
            <div x-data="{ activeMenu: 0 }" class="relative overflow-hidden">
                <div class="flex transition-transform duration-500 ease-in-out"
                     :style="`transform: translateX(-${activeMenu * 100}%)`">
                    <!-- Menu Items -->
                    <div class="w-full flex-shrink-0 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Repeat this block for each menu item -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <img src="/images/a.jpg" class="w-full h-48 object-cover" alt="Seblak Special">
                            <div class="p-4">
                                <h4 class="font-bold text-xl mb-2">Seblak Special Ajnira</h4>
                                <p class="text-gray-600">
                                    Kombinasi kerupuk pedas + ceker + bakso + telur + sosis + 
                                    <span class="text-orange-500">+5 topping pilihan</span>
                                </p>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-2xl font-bold text-orange-600">Rp15.000</span>
                                    <button class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                                        Pesan Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <img src="/images/a.jpg" class="w-full h-48 object-cover" alt="Seblak Special">
                            <div class="p-4">
                                <h4 class="font-bold text-xl mb-2">Seblak Special Ajnira</h4>
                                <p class="text-gray-600">
                                    Kombinasi kerupuk pedas + ceker + bakso + telur + sosis + 
                                    <span class="text-orange-500">+5 topping pilihan</span>
                                </p>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-2xl font-bold text-orange-600">Rp15.000</span>
                                    <button class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                                        Pesan Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <img src="/images/a.jpg" class="w-full h-48 object-cover" alt="Seblak Special">
                            <div class="p-4">
                                <h4 class="font-bold text-xl mb-2">Seblak Special Ajnira</h4>
                                <p class="text-gray-600">
                                    Kombinasi kerupuk pedas + ceker + bakso + telur + sosis + 
                                    <span class="text-orange-500">+5 topping pilihan</span>
                                </p>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-2xl font-bold text-orange-600">Rp15.000</span>
                                    <button class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                                        Pesan Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Add more menu items here -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="py-16 bg-orange-50">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-8 text-orange-600">Kenapa Pilih Kami?</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <svg class="w-16 h-16 mx-auto text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <h4 class="text-xl font-bold mt-4">Tempat Nyaman</h4>
                    <p class="mt-2">Area makan bersih dan cozy dengan nuansa tradisional modern</p>
                </div>
                
                <!-- Add more facilities here -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-orange-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h5 class="text-xl font-bold mb-4">Seblak Ajnira</h5>
                    <p>Jalan Rasa Pedas No. 45<br>Kota Kuliner, Jawa Barat</p>
                </div>
                
                <div>
                    <h5 class="text-xl font-bold mb-4">Kontak Kami</h5>
                    <p>📞 (022) 1234 5678<br>📧 seblak@ajnira.com</p>
                </div>
            </div>
            
            <div class="mt-8 pt-8 border-t border-orange-700 text-center">
                <p>© 2024 Seblak Ajnira - Parasmanan Terenak se-Jawa Barat</p>
            </div>
        </div>
    </footer>
</body>
</html>