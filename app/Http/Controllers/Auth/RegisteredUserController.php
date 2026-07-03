<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Aplikasi ini personal & datanya global (tanpa user_id per baris):
     * akun kedua akan bisa melihat dan mengubah seluruh data pemilik.
     * Karena itu registrasi otomatis TERKUNCI setelah akun pertama dibuat.
     */
    private function registrationLocked(): bool
    {
        return User::query()->exists();
    }

    /**
     * Display the registration view.
     */
    public function create(): View|RedirectResponse
    {
        if ($this->registrationLocked()) {
            return redirect()
                ->route('login')
                ->with('status', 'Registrasi ditutup — dashboard ini milik pribadi. Silakan login.');
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if ($this->registrationLocked()) {
            throw ValidationException::withMessages([
                'email' => 'Registrasi ditutup — dashboard ini milik pribadi.',
            ]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
