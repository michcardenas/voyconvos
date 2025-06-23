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

    // Si no tiene ningún rol asignado aún, redirige a editar perfil
    if ($user->roles->isEmpty()) {
        Auth::login($user);
        return redirect()->route('profile.edit');
    }

    // Asignar rol por defecto si aún no tiene uno de los definidos
    if (! $user->hasAnyRole(['admin', 'conductor', 'pasajero'])) {
        $user->assignRole('pasajero');
    }

    Auth::login($user);

    // Si es conductor y su perfil está incompleto
    if ($user->hasRole('conductor')) {
        if (empty($user->pais) || empty($user->celular) || empty($user->foto)) {
            return redirect()->route('profile.edit');
        }

        return redirect()->route('dashboard'); // ✅ dashboard general
    }

    // Si es pasajero y su perfil está incompleto
    if ($user->hasRole('pasajero')) {
        if (empty($user->pais) || empty($user->celular) || empty($user->foto)) {
            return redirect()->route('profile.edit');
        }

        return redirect()->route('pasajero.dashboard'); // ✅ mini panel pasajero
    }

    // Si es admin u otro rol
return redirect()->route('admin.dashboard');
}




    
}
