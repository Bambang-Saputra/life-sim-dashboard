<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="font-pixel text-soil-dark" style="font-size: 12px; letter-spacing: 0.05em;">
            🛡 CONFIRM PASSWORD
        </h2>
        <p class="font-sans text-soil text-sm mt-3">
            Area aman. Konfirmasi password-mu untuk melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')"/>
            <x-text-input id="password" type="password" name="password"
                required autocomplete="current-password" placeholder="Masukkan password"/>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5"/>
        </div>

        <button type="submit" class="btn-primary w-full justify-center py-3">
            Confirm
        </button>
    </form>
</x-guest-layout>
