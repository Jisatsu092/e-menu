<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name Input -->
        <div class="mb-6">
            <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nama</label>
            <input id="name" type="text" name="name" required autofocus
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition duration-300"
                placeholder="Nama Lengkap">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Email Input -->
        <div class="mb-6">
            <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Alamat Email</label>
            <input type="email" name="email" :value="old('email')" required autofocus oninput="this.value = this.value.toLowerCase()" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition duration-300"
                   placeholder="email@contoh.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Role Dropdown -->
        {{-- <div class="mb-6">
            <label for="role" class="block text-gray-700 text-sm font-semibold mb-2">Peran</label>
            <select name="role" id="role"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-white focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition duration-300">
                <option value="A">Admin</option>
                <option value="K">Kasir</option>
                <option value="U">User</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2 text-red-600 text-sm"/>
        </div> --}}

        <!-- Password Input -->
        <div class="mb-6">
            <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
            <input type="password" name="password" required 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition duration-300"
                               placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Confirm Password Input -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">Konfirmasi
                Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition duration-300"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Register Button -->
        <button type="submit"
            class="w-full bg-red-600 text-white py-3 px-4 rounded-lg hover:bg-red-700 transition duration-300 font-semibold shadow-md transform hover:scale-[1.02]">
            Daftar Sekarang
        </button>

        <!-- Login Link -->
        <div class="text-center mt-6">
            <p class="text-gray-600">Sudah punya akun?
                <a href="{{ route('login') }}" class="text-red-600 hover:text-red-800 font-semibold">
                    Masuk disini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
