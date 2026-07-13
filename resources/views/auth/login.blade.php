<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <div class="mb-6 text-center">
        <h2 class="font-pixel text-soil-dark" style="font-size: 12px; letter-spacing: 0.05em;">
            WELCOME BACK
        </h2>
        <p class="font-sans text-soil text-sm mt-2">Masuk ke dashboard kehidupanmu</p>
    </div>

    @if (config('app.demo'))
        <div class="mb-4 px-3 py-2 bg-corn-light/40 border border-corn/50 text-center" style="border-radius: 4px;">
            <p class="font-sans text-xs text-soil-dark">
                Akun demo: <span class="font-semibold">demo@example.com</span> · <span class="font-semibold">demo1234</span>
            </p>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')"/>
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                required autofocus autocomplete="username"
                placeholder="your@email.com"/>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5"/>
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')"/>
            <x-text-input id="password" type="password" name="password"
                required autocomplete="current-password"
                placeholder="Masukkan password"/>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5"/>
        </div>

        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer gap-2">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-4 h-4 border-2 border-cream-dark text-grass-dark focus:ring-grass focus:ring-offset-0"
                    style="border-radius: 3px;">
                <span class="font-sans text-soil text-sm">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="font-sans text-sm text-sky-dark hover:text-sky no-underline transition-colors">
                    Lupa password?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full justify-center py-3">
                <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Log In
            </button>
        </div>

        <div class="text-center pt-2">
            <span class="font-sans text-soil text-sm">Belum punya akun? </span>
            <a href="{{ route('register') }}"
               class="font-sans font-semibold text-grass-dark hover:text-grass text-sm no-underline transition-colors">
                Daftar di sini →
            </a>
        </div>
    </form>
</x-guest-layout>
