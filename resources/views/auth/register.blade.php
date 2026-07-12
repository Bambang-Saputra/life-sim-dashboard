<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="font-pixel text-soil-dark" style="font-size: 12px; letter-spacing: 0.05em;">
            JOIN THE FARM
        </h2>
        <p class="font-sans text-soil text-sm mt-2">Buat akun baru untuk mulai petualanganmu</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')"/>
            <x-text-input id="name" type="text" name="name" :value="old('name')"
                required autofocus autocomplete="name"
                placeholder="e.g. Bambang"/>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5"/>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')"/>
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                required autocomplete="username"
                placeholder="your@email.com"/>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5"/>
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')"/>
            <x-text-input id="password" type="password" name="password"
                required autocomplete="new-password"
                placeholder="Min. 8 karakter"/>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5"/>
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')"/>
            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                required autocomplete="new-password"
                placeholder="Ulangi password"/>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5"/>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full justify-center py-3">
                <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M19 8v6M22 11h-6"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="8.5" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                </svg>
                Create Account
            </button>
        </div>

        <div class="text-center pt-2">
            <span class="font-sans text-soil text-sm">Sudah punya akun? </span>
            <a href="{{ route('login') }}"
               class="font-sans font-semibold text-grass-dark hover:text-grass text-sm no-underline transition-colors">
                Login di sini →
            </a>
        </div>
    </form>
</x-guest-layout>
