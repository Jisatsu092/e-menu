<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Input -->
        <div class="mb-6">
            <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Alamat Email</label>
            <input type="email" name="email" :value="old('email')" required autofocus
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition duration-300"
                placeholder="email@contoh.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Password Input -->
        <div class="mb-6">
            <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
            <input type="password" name="password" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition duration-300"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Opsi Tambahan -->
        <div class="flex items-center justify-between mb-8">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-red-600 text-sm hover:text-red-800">
                    Lupa Password?
                </a>
            @endif
        </div>

        <!-- Tombol Login -->
        <button type="submit"
            class="w-full bg-red-600 text-white py-3 px-4 rounded-lg hover:bg-red-700 transition duration-300 font-semibold shadow-md transform hover:scale-[1.02]">
            Masuk Sekarang
        </button>

        <!-- Link Registrasi -->
        <div class="text-center mt-6">
            <p class="text-gray-600">Belum punya akun?
                <a href="{{ route('register') }}" class="text-red-600 hover:text-red-800 font-semibold">
                    Daftar disini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
