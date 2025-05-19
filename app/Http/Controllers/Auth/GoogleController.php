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
    if (! $user->hasAnyRole(['admin', 'conductor', 'pasajero'])) {
        $user->assignRole('pasajero');
    }

    Auth::login($user);

    // Si es pasajero o conductor, verificar perfil incompleto
    if ($user->hasAnyRole(['pasajero', 'conductor'])) {
        if (empty($user->pais) || empty($user->celular) || empty($user->foto)) {
            return redirect()->route('profile.edit');
        }

        // ✅ Redirigir al mini panel del pasajero
        return redirect()->route('usuario.panel');
    }

    // Si es admin o soporte, redirigir al dashboard normal
    return redirect()->route('dashboard');
}

    
}
