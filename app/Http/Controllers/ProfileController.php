<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\RegistroConductor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // 🚫 Si ya tiene un rol asignado, lo redirigimos directamente
        if ($user->hasRole('conductor')) {
            return redirect()->route('dashboard');
        }

        if ($user->hasRole('pasajero')) {
            return redirect()->route('pasajero.dashboard');
        }

        // ✅ Solo entra aquí si no tiene rol aún
        return view('profile.edit', [
            'user' => $user,
        ]);
    }
public function editarUsuario(Request $request): View
{
    $user = $request->user();

    // Si es conductor, cargar ambas tablas y mostrar la vista
    if ($user->hasRole('conductor')) {
        $registro = RegistroConductor::where('user_id', $user->id)->first();

        // Si no tiene registro, puedes crear uno vacío para el formulario
        if (!$registro) {
            $registro = new RegistroConductor([
                'user_id' => $user->id,
                'estado_verificacion' => 'pendiente',
                'estado_registro' => 'incompleto',
            ]);
        }

        return view('conductor.perfil.edit', compact('user', 'registro'));
    }

    // Si es pasajero, podrías hacer lo mismo con otra vista
    if ($user->hasRole('pasajero')) {
        return view('pasajero.perfil.edit', compact('user'));
    }

    // Si es administrador, mostrar su propia edición o ir al CRUD
    if ($user->hasRole('admin')) {
        return view('admin.perfil.edit', compact('user'));
    }

    // Sin rol reconocido
    return view('profile.edit', compact('user'))->with('info', 'Tu perfil no tiene un formulario asignado.');
}
public function actualizarPerfil(Request $request)
{
    $user = $request->user();
    
    // Validación básica
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'dni' => 'nullable|string|max:20',
        'celular' => 'nullable|string|max:20',
        'pais' => 'nullable|string|max:100',
        'ciudad' => 'nullable|string|max:100',
        'foto' => 'nullable|image|max:2048',
        'dni_foto' => 'nullable|image|max:2048',
        'dni_foto_atras' => 'nullable|image|max:2048',
        
        'marca_vehiculo' => 'nullable|string|max:100',
        'modelo_vehiculo' => 'nullable|string|max:100',
        'anio_vehiculo' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
        'numero_puestos' => 'nullable|integer' ,
        'verificar_pasajeros' => 'nullable|boolean',
        'consumo_por_galon' => 'nullable|numeric|min:0|max:100',

        'patente' => 'nullable|string|max:20',
        

        'licencia' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'cedula' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'cedula_verde' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'seguro' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'rto' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'antecedentes' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);
    try {
        // Actualizar datos del usuario
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'dni' => $validated['dni'],
            'celular' => $validated['celular'],
            'pais' => $validated['pais'],
            'ciudad' => $validated['ciudad'],
            'foto' => $user->foto, // Mantener foto actual si no se subió una nueva
            'dni_foto' => $user->dni_foto, // Mantener foto del DNI actual si no se subió una nueva
            'dni_foto_atras' => $user->dni_foto_atras, // Mantener foto del reverso del DNI actual si no se subió una nueva
        ];


        // Manejar foto del DNI (frente)
        if ($request->hasFile('dni_foto')) {
            if ($user->dni_foto && Storage::disk('public')->exists($user->dni_foto)) {
                Storage::disk('public')->delete($user->dni_foto);
            }
            $userData['dni_foto'] = $request->file('dni_foto')->store("conductores/documentos/{$user->id}", 'public');
        }

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $userData['foto'] = $request->file('foto')->store("conductores/documentos/{$user->id}", 'public');
        }

        // Manejar foto del DNI (reverso)
        if ($request->hasFile('dni_foto_atras')) {
            if ($user->dni_foto_atras && Storage::disk('public')->exists($user->dni_foto_atras)) {
                Storage::disk('public')->delete($user->dni_foto_atras);
            }
            $userData['dni_foto_atras'] = $request->file('dni_foto_atras')->store("conductores/documentos/{$user->id}", 'public');
        }


        $user->update($userData);

        // Obtener o crear registro de conductor
        $registro = RegistroConductor::firstOrNew(['user_id' => $user->id]);

        // Actualizar datos del vehículo
        $registro->fill([
            'marca_vehiculo' => $validated['marca_vehiculo'],
            'modelo_vehiculo' => $validated['modelo_vehiculo'],
            'anio_vehiculo' => $validated['anio_vehiculo'],
            'anio_vehiculo' => $validated['anio_vehiculo'],
            'numero_puestos' => $validated['numero_puestos'], 
            'verificar_pasajeros' => $validated['verificar_pasajeros'] ?? 0, // Por defecto 0 si no se envía  
            'consumo_por_galon' => $validated['consumo_por_galon'],
        ]);

        // Manejar documentos
        $documentos = ['licencia', 'cedula', 'cedula_verde'];
        
        foreach ($documentos as $documento) {
            if ($request->hasFile($documento)) {
                // Eliminar archivo anterior
                if ($registro->$documento && Storage::disk('public')->exists($registro->$documento)) {
                    Storage::disk('public')->delete($registro->$documento);
                }
                // Guardar nuevo archivo
                $registro->$documento = $request->file($documento)->store("conductores/documentos/{$user->id}", 'public');
            }
        }

        // Verificar si está completo
        $camposCompletos = $registro->marca_vehiculo && $registro->modelo_vehiculo && 
                          $registro->anio_vehiculo && $registro->patente;
        $docsCompletos = $registro->licencia && $registro->cedula && 
                        $registro->cedula_verde && $registro->seguro;

        $registro->estado_registro = ($camposCompletos && $docsCompletos) ? 'completo' : 'incompleto';
        
        if (!$registro->exists) {
            $registro->user_id = $user->id;
            $registro->estado_verificacion = 'pendiente';
        }

        $registro->save();

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');

    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with('error', 'Error al actualizar el perfil.');
    }
}

public function actualizarPerfilPasajero(Request $request)
{
    $user = $request->user();
    // Validación para pasajeros
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'dni' => 'nullable|string|max:20',
        'celular' => 'nullable|string|max:20',
        'pais' => 'nullable|string|max:100',
        'ciudad' => 'nullable|string|max:100',
        'foto' => 'nullable|image|max:2048',
        'dni_foto' => 'nullable|image|max:2048',
        'dni_foto_atras' => 'nullable|image|max:2048',
        'new_password' => 'nullable|string|min:8|confirmed',
        'new_password_confirmation' => 'nullable|string',
    ];

    // Si el usuario tiene contraseña, requerir contraseña actual para cambiarla

    $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'email.required' => 'El email es obligatorio.',
        'email.email' => 'El email debe tener un formato válido.',
        'email.unique' => 'Este email ya está siendo utilizado por otro usuario.',
        'foto.image' => 'La foto debe ser una imagen.',
        'foto.max' => 'La foto no puede superar los 2MB.',
        'dni_foto.image' => 'La foto del DNI debe ser una imagen.',
        'dni_foto.max' => 'La foto del DNI no puede superar los 2MB.',
        'current_password.required_with' => 'Debes ingresar tu contraseña actual para cambiarla.',
        'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        'new_password.confirmed' => 'La confirmación de contraseña no coincide.',
    ];

    $validated = $request->validate($rules, $messages);

    try {
        // Verificar contraseña actual solo si el usuario ya tiene una
        if ($user->password && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
            }
        }

        // Datos a actualizar
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'dni' => $validated['dni'],
            'celular' => $validated['celular'],
            'pais' => $validated['pais'],
            'ciudad' => $validated['ciudad'],
        ];

        // Manejar foto de perfil si se subió
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $userData['foto'] = $request->file('foto')->store('pasajeros/fotos', 'public');
        }

        // Manejar foto del DNI si se subió
        if ($request->hasFile('dni_foto')) {
            // Eliminar foto del DNI anterior
            if ($user->dni_foto && Storage::disk('public')->exists($user->dni_foto)) {
                Storage::disk('public')->delete($user->dni_foto);
            }
            $userData['dni_foto'] = $request->file('dni_foto')->store('pasajeros/documentos', 'public');
        }

        // Manejar foto del reverso del DNI si se subió
        if ($request->hasFile('dni_foto_atras')) {
            // Eliminar reverso anterior
            if ($user->dni_foto_atras && Storage::disk('public')->exists($user->dni_foto_atras)) {
                Storage::disk('public')->delete($user->dni_foto_atras);
            }
            $userData['dni_foto_atras'] = $request->file('dni_foto_atras')->store('pasajeros/documentos', 'public');
        }

        // Establecer o cambiar contraseña
        if ($request->filled('new_password')) {
            $userData['password'] = Hash::make($request->new_password);
        }

        $user->update($userData);

        // Mensaje personalizado según el caso
        $mensaje = 'Perfil actualizado correctamente.';
        
        if ($request->filled('new_password')) {
            if (!$user->password) {
                $mensaje .= ' ¡Contraseña establecida! Ahora puedes iniciar sesión con email y contraseña además de Google.';
            } else {
                $mensaje .= ' Tu contraseña también ha sido cambiada.';
            }
        }

        if ($request->hasFile('dni_foto')) {
            $mensaje .= ' Tu documento DNI ha sido actualizado y será revisado por nuestro equipo.';
        }

        return redirect()->back()->with('success', $mensaje);

    } catch (\Exception $e) {
        \Log::error('Error actualizando perfil de pasajero: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al actualizar el perfil. Por favor, inténtalo nuevamente.');
    }
}

public function editConductor()
{
    $user = auth()->user();
    $registro = \App\Models\RegistroConductor::where('user_id', $user->id)->first();

    if (!$registro) {
        // Si aún no hay registro, puedes crear uno vacío para el formulario
        $registro = new \App\Models\RegistroConductor();
    }

    return view('conductor.perfil.edit', compact('user', 'registro'));
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
