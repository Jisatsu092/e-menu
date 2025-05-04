<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex items-center justify-center bg-red-50 px-4 py-12">
        <!-- Form Container -->
        <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-red-600 py-6 px-8 text-white">
                <div class="flex items-center justify-center mb-4">
                    <div class="bg-red-700 p-3 rounded-full mr-3">
                        <x-application-logo class="w-8 h-8 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold">Warung Seblak Ajnira</h2>
                </div>
                <p class="text-center text-red-100">Silakan masuk ke akun Anda</p>
            </div>

            <!-- Form Section -->
            <div class="p-8">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>