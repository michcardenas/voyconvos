<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Actualiza datos validados
        $user->fill($request->validated());

        // Si cambia el email, lo desverifica
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // 🔄 Actualiza el rol
        if ($request->has('role')) {
            $user->syncRoles([$request->input('role')]);
        }

        // Redirige si el nuevo rol es conductor
        if ($request->input('role') === 'conductor') {
            return redirect()->route('conductor.registro.form');
        }

        // ✅ Redirige al dashboard del pasajero si es pasajero
        if ($request->input('role') === 'pasajero') {
            return redirect()->route('pasajero.dashboard');
        }

        // Por defecto vuelve al perfil
        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
