<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex flex-col items-center mb-8 text-center">
        <img src="{{ asset('images/logo-tegal.png') }}" alt="Logo Kab Tegal" class="h-28 w-auto mb-3 drop-shadow-lg transition-transform duration-300 hover:scale-105">
        <h2 class="text-2xl font-extrabold text-white tracking-wider drop-shadow-md">SISTEM STATISTIK</h2>
        <p class="text-sm font-medium text-blue-200 mt-1 drop-shadow-sm">Layanan Disdukcapil Kabupaten Tegal</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" class="text-white font-medium drop-shadow-sm" />
            <x-text-input id="username" class="block mt-1 w-full bg-white border border-transparent focus:border-blue-400 focus:ring focus:ring-blue-300/50 text-gray-900 placeholder-gray-400 font-semibold rounded-xl shadow-sm transition-all duration-300" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2 text-red-300" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" class="text-white font-medium drop-shadow-sm" />
            
            <div class="relative mt-1">
                <x-text-input id="password" class="block w-full bg-white border border-transparent focus:border-blue-400 focus:ring focus:ring-blue-300/50 text-gray-900 placeholder-gray-400 font-semibold rounded-xl shadow-sm transition-all duration-300 pr-10"
                                ::type="show ? 'text' : 'password'"
                                name="password"
                                required autocomplete="current-password" />
                
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
        </div>

        <!-- Remember Me -->
        <div class="block">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#153e75] shadow-sm focus:ring-[#153e75]/50 bg-white" name="remember">
                <span class="ms-2 text-sm text-white drop-shadow-sm">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-white border border-transparent rounded-xl font-extrabold text-sm text-[#153e75] tracking-widest hover:bg-gray-100 hover:scale-[1.02] focus:bg-white active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-[#153e75] transition-all ease-in-out duration-300 shadow-lg hover:shadow-xl">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>
