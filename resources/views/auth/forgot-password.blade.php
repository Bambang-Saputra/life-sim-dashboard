<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="font-pixel text-soil-dark" style="font-size: 12px; letter-spacing: 0.05em;">
            RESET PASSWORD
        </h2>
        <p class="font-sans text-soil text-sm mt-3">
            Lupa password? Tenang. Masukkan email-mu dan kami akan kirim link untuk reset password.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')"/>
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                required autofocus placeholder="your@email.com"/>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5"/>
        </div>

        <button type="submit" class="btn-primary w-full justify-center py-3">
            Kirim Link Reset
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}"
               class="font-sans text-sm text-sky-dark hover:text-sky no-underline transition-colors">
                ← Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>
