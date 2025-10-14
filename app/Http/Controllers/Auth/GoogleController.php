<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'password' => bcrypt(Str::random(16)),
            ]
        );

        // Asignar rol por defecto si aún no tiene uno
        if ($user->roles->isEmpty()) {
            $user->assignRole('pasajero');
        }

        Auth::login($user);

        // ✅ Redirigir según el rol
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        // ✅ Todos los demás (conductor, pasajero, etc.) van a hibrido.dashboard
        return redirect()->route('hibrido.dashboard');
    }
}