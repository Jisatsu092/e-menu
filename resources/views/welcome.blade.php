<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Seblak Ajnira - Parasmanan Terenak!</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-auto-scroll@0.5.3/dist/js/splide-extension-auto-scroll.min.js">
    </script>
    <style>
        .splide__slide {
            max-width: 400px !important;
            /* Maksimal lebar card */
        }

        .splide__arrow {
            background: #dc2626 !important;
        }

        .splide__pagination__page.is-active {
            background: #dc2626 !important;
        }
    </style>
</head>

<body class="bg-red-50">
    <!-- Header Navigation -->
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h1 class="text-xl font-bold">Seblak Ajnira</h1>
            </div>

            @auth
                <a href="{{ url('/beranda') }}" class="bg-red-500 hover:bg-red-700 px-4 py-2 rounded-lg transition-colors">
                    Dashboard
                </a>
            @else
                <div class="space-x-4">
                    <a href="{{ route('login') }}" class="hover:text-red-200 transition-colors">Login</a>
                    <a href="{{ route('register') }}"
                        class="bg-red-500 hover:bg-red-700 px-4 py-2 rounded-lg transition-colors">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative h-96 bg-red-800 text-white overflow-hidden flex items-center justify-center">
        <div class="text-center px-4">
            <h2 class="text-4xl font-bold mb-4">
                "Renyahnya Kerupuk, Pedasnya Bumbu<br>
                <span class="text-red-300">Kenangan Manis di Setiap Suapan</span>"
            </h2>
        </div>
    </div>

    <!-- Menu Carousel -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-8 text-red-600">Menu Andalan Kami</h3>

            <div id="seblak-carousel" class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <!-- Repeat 5 items for demo -->
                        <li class="splide__slide px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-sm mx-auto">
                                <img src="/images/a.jpg" class="w-full h-48 object-cover" alt="Seblak Special">
                                <div class="p-4">
                                    <h4 class="font-bold text-xl mb-2">Seblak Special Ajnira</h4>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-2xl font-bold text-red-600">Rp15.000</span>
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                            Pesan Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-sm mx-auto">
                                <img src="/images/a.jpg" class="w-full h-48 object-cover" alt="Seblak Special">
                                <div class="p-4">
                                    <h4 class="font-bold text-xl mb-2">Seblak Special Ajnira</h4>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-2xl font-bold text-red-600">Rp20.000</span>
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                            Pesan Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-sm mx-auto">
                                <img src="/images/a.jpg" class="w-full h-48 object-cover" alt="Seblak Special">
                                <div class="p-4">
                                    <h4 class="font-bold text-xl mb-2">Seblak Special Ajnira</h4>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-2xl font-bold text-red-600">Rp15.000</span>
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                            Pesan Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-sm mx-auto">
                                <img src="/images/a.jpg" class="w-full h-48 object-cover" alt="Seblak Special">
                                <div class="p-4">
                                    <h4 class="font-bold text-xl mb-2">Seblak Special Ajnira</h4>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-2xl font-bold text-red-600">Rp15.000</span>
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                            Pesan Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-sm mx-auto">
                                <img src="/images/a.jpg" class="w-full h-48 object-cover" alt="Seblak Special">
                                <div class="p-4">
                                    <h4 class="font-bold text-xl mb-2">Seblak Special Ajnira</h4>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-2xl font-bold text-red-600">Rp15.000</span>
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                            Pesan Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-sm mx-auto">
                                <img src="/images/a.jpg" class="w-full h-48 object-cover" alt="Seblak Special">
                                <div class="p-4">
                                    <h4 class="font-bold text-xl mb-2">Seblak Special Ajnira</h4>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-2xl font-bold text-red-600">Rp15.000</span>
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                            Pesan Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-sm mx-auto">
                                <img src="/images/a.jpg" class="w-full h-48 object-cover" alt="Seblak Special">
                                <div class="p-4">
                                    <h4 class="font-bold text-xl mb-2">Seblak Special Ajnira</h4>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-2xl font-bold text-red-600">Rp15.000</span>
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                            Pesan Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-sm mx-auto">
                                <img src="/images/a.jpg" class="w-full h-48 object-cover" alt="Seblak Special">
                                <div class="p-4">
                                    <h4 class="font-bold text-xl mb-2">Seblak Special Ajnira</h4>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-2xl font-bold text-red-600">Rp15.000</span>
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                            Pesan Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- Add more items here -->
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Minuman Carousel -->
    <section class="py-16 bg-red-50">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-8 text-red-600">Minuman Segar</h3>

            <div id="minuman-carousel" class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <li class="splide__slide px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-sm mx-auto">
                                <img src="/images/drink.jpg" class="w-full h-48 object-cover" alt="Es Teh Manis">
                                <div class="p-4">
                                    <h4 class="font-bold text-xl mb-2">Es Teh Manis</h4>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-2xl font-bold text-red-600">Rp5.000</span>
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                            Pesan Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- Add more items here -->
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="py-16 bg-red-50">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-8 text-red-600">Kenapa Pilih Kami?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 bg-white rounded-lg shadow">
                    <svg class="w-16 h-16 mx-auto text-red-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <h4 class="text-xl font-bold mt-4">Tempat Nyaman</h4>
                    <p class="mt-2 text-gray-600">Area makan bersih dan cozy dengan nuansa tradisional modern</p>
                </div>
                <!-- Add more facilities -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-red-800 text-white py-12">
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
            <div class="mt-8 pt-8 border-t border-red-700 text-center">
                <p>© 2024 Seblak Ajnira - Parasmanan Terenak se-Jawa Barat</p>
            </div>
        </div>
    </footer>
    <script
        src="https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-auto-scroll@0.5.3/dist/js/splide-extension-auto-scroll.min.js">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seblak Carousel
            new Splide('#seblak-carousel', {
                type: 'loop',
                perPage: 3,
                gap: '1rem',
                pagination: false,
                autoplay: true,
                autoScroll: {
                    speed: 1, // Nilai negatif untuk arah kanan
                },
                breakpoints: {
                    640: {
                        perPage: 1
                    },
                    768: {
                        perPage: 2
                    },
                }
            }).mount();

            // Minuman Carousel
            new Splide('#minuman-carousel', {
                type: 'loop',
                perPage: 3,
                gap: '1rem',
                direction: 'rtl',
                pagination: false,
                autoplay: true,
                autoScroll: {
                    speed: -1, // Nilai negatif untuk arah kanan
                },
                breakpoints: {
                    640: {
                        perPage: 1
                    },
                    768: {
                        perPage: 2
                    },
                }
            }).mount();
        });
    </script>
</body>

</html>
