<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="font-pixel text-soil-dark" style="font-size: 12px; letter-spacing: 0.05em;">
            NEW PASSWORD
        </h2>
        <p class="font-sans text-soil text-sm mt-2">Buat password baru yang aman</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Email')"/>
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)"
                required autofocus autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5"/>
        </div>

        <div>
            <x-input-label for="password" :value="__('New Password')"/>
            <x-text-input id="password" type="password" name="password"
                required autocomplete="new-password" placeholder="Min. 8 karakter"/>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5"/>
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')"/>
            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                required autocomplete="new-password" placeholder="Ulangi password baru"/>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5"/>
        </div>

        <button type="submit" class="btn-primary w-full justify-center py-3">
            Reset Password
        </button>
    </form>
</x-guest-layout>
