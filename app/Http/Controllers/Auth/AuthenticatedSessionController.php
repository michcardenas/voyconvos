<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request): RedirectResponse 
{
    $request->authenticate();
    $request->session()->regenerate();
    
    $user = Auth::user();
    
    // Si no tiene ningún rol asignado aún, redirige a editar perfil
    if ($user->roles->isEmpty()) {
        return redirect()->route('profile.edit');
    }
    
    // Asignar rol por defecto si aún no tiene uno de los definidos
    if (! $user->hasAnyRole(['admin', 'conductor', 'pasajero'])) {
        $user->assignRole('pasajero');
    }
    
    // ✅ CONDUCTOR - Dashboard principal
    if ($user->hasRole('conductor')) {
        if (empty($user->pais) || empty($user->celular) || empty($user->foto)) {
            return redirect()->route('profile.edit');
        }
        
        return redirect()->route('dashboard'); // 🚗 Solo para conductores
    }
    
    // ✅ PASAJERO - Mini panel específico
    if ($user->hasRole('pasajero')) {
        if (empty($user->pais) || empty($user->celular) || empty($user->foto)) {
            return redirect()->route('profile.edit');
        }
        
        return redirect()->route('pasajero.dashboard'); // 👤 Solo para pasajeros
    }
    
    // ✅ ADMIN - Panel administrativo
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard'); // 👑 Solo para admin
    }
    
    // Fallback - por si acaso (no debería llegar aquí)
    return redirect()->route('profile.edit');
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
