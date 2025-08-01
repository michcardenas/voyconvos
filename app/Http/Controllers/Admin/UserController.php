<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\RegistroConductor; // Añadir este import
use Illuminate\Support\Facades\Storage; // Añadir este import
use App\Mail\UniversalMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
  public function index() {
    $users = User::latest()->paginate(10); // 10 usuarios por página, ordenados por más reciente
    return view('admin.users.index', compact('users'));
}

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }
    

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|exists:roles,name',
            'pais' => 'required|string|max:100',
            'ciudad' => 'required|string|max:100',
            'dni' => 'nullable|string|max:20',
            'celular' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        // Guardar imagen si fue cargada
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('usuarios', 'public');
        }
    
        // Encriptar contraseña
        $data['password'] = Hash::make($data['password']);
    
        // Crear usuario
        $user = User::create($data);
    
        // Asignar rol
        $user->assignRole($data['role']);
    
        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

public function edit(User $user)
{
    $roles = Role::all();
    
    // Obtener información del conductor si existe
    $registroConductor = null;
    if ($user->hasRole('conductor')) {
        $registroConductor = RegistroConductor::where('user_id', $user->id)->first();
    }
    
    return view('admin.users.edit', compact('user', 'roles', 'registroConductor'));
}
public function update(Request $request, User $user) 
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'role' => 'required|exists:roles,name',
        'pais' => 'required|string',
        'ciudad' => 'required|string',
        'dni' => 'nullable|string',
        'celular' => 'required|string',
        'verificado' => 'required|boolean',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'dni_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'dni_foto_atras' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // VERIFICAR SI EL USUARIO SERÁ VERIFICADO (cambio de false a true)
    $eraNoVerificado = !$user->verificado; // Estado anterior
    $seraVerificado = $request->verificado; // Estado nuevo
    $acabaDeSerVerificado = $eraNoVerificado && $seraVerificado;

    // Datos básicos del usuario
    $data = $request->only([
        'name', 'email', 'pais', 'ciudad', 'dni', 'celular', 'verificado'
    ]);

    // Manejar foto de perfil
    if ($request->hasFile('foto')) {
        // Eliminar foto anterior si existe
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }
        $data['foto'] = $request->file('foto')->store('fotos', 'public');
    }

    // Manejar DNI frente
    if ($request->hasFile('dni_foto')) {
        // Eliminar foto anterior si existe
        if ($user->dni_foto) {
            Storage::disk('public')->delete($user->dni_foto);
        }
        $data['dni_foto'] = $request->file('dni_foto')->store('dni_fotos', 'public');
    }

    // Manejar DNI atrás
    if ($request->hasFile('dni_foto_atras')) {
        // Eliminar foto anterior si existe
        if ($user->dni_foto_atras) {
            Storage::disk('public')->delete($user->dni_foto_atras);
        }
        $data['dni_foto_atras'] = $request->file('dni_foto_atras')->store('dni_fotos', 'public');
    }

    // Actualizar usuario
    $user->update($data);

    // Actualizar rol
    $user->syncRoles([$request->role]);

    // ENVIAR CORREO SI ACABA DE SER VERIFICADO
    if ($acabaDeSerVerificado) {
        try {
            Mail::to($user->email)->send(new UniversalMail(
                $user,
                '¡Cuenta verificada exitosamente! - VoyConvos',
                "¡Excelentes noticias! Tu cuenta ha sido verificada exitosamente.\n\nYa puedes empezar a utilizar todos nuestros servicios sin restricciones:\n\n\n• Conectar con otros viajeros\n• Acceder a todas las funcionalidades de la plataforma\n• Disfrutar de la experiencia completa de VoyConvos\n\n¡Bienvenido oficialmente a nuestra comunidad de viajeros!\n\nGracias por tu paciencia durante el proceso de verificación.",
                'notificacion'
            ));
            
            // Log para confirmar el envío
            Log::info("Email de verificación enviado a: {$user->email}");
            
        } catch (Exception $e) {
            // Si falla el email, registrar el error pero continuar
            Log::error('Error enviando email de verificación exitosa: ' . $e->getMessage());
        }
    }

    return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente' . ($acabaDeSerVerificado ? '. Se ha notificado al usuario sobre su verificación.' : ''));
}

    
    

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    }

}
