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
public function index(Request $request)
{
    // Obtener parámetros de filtro
    $ordenar = $request->get('ordenar', 'created_at');
    $rol = $request->get('rol');
    $verificado = $request->get('verificado');
    
    // Construir la consulta con filtros
    $query = User::query();
    
    // Filtro por rol
    if ($rol) {
        $query->whereHas('roles', function($q) use ($rol) {
            $q->where('name', $rol);
        });
    }
    
    // Filtro por estado de verificación
    if ($verificado !== null && $verificado !== '') {
        $query->where('verificado', $verificado);
    }
    
    // Aplicar ordenamiento
    if ($ordenar === 'updated_at') {
        $query->latest('updated_at');
    } else {
        $query->latest('created_at');
    }
    
    // Paginar manteniendo los parámetros de consulta
    $users = $query->paginate(10)->withQueryString();
    
    return view('admin.users.index', compact('users'));
}
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }
    

    public function store(Request $request)
{
    // Validaciones básicas
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

    // Validaciones adicionales si es conductor
    if ($request->role === 'conductor') {
        $conductorData = $request->validate([
            'marca_vehiculo' => 'required|string|max:255',
            'modelo_vehiculo' => 'required|string|max:255',
            'anio_vehiculo' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'patente' => 'required|string|max:20',
            'numero_puestos' => 'required|integer|min:1|max:50',
            'consumo_por_galon' => 'nullable|numeric|min:0',
            'verificar_pasajeros' => 'nullable|boolean',
            'estado_verificacion' => 'nullable|in:pendiente,en_revision,aprobado,rechazado',
            'estado_registro' => 'nullable|in:activo,inactivo,suspendido',
            // Documentos
            'licencia' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'cedula' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'cedula_verde' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'seguro' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'rto' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'antecedentes' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
    }

    // Guardar imagen del usuario si fue cargada
    if ($request->hasFile('foto')) {
        $data['foto'] = $request->file('foto')->store('usuarios', 'public');
    }

    // Encriptar contraseña
    $data['password'] = Hash::make($data['password']);

    // Crear usuario
    $user = User::create($data);

    // Asignar rol
    $user->assignRole($data['role']);

    // Si es conductor, crear registro adicional
    if ($request->role === 'conductor') {
        $registroConductor = new \App\Models\RegistroConductor();
        $registroConductor->user_id = $user->id;
        
        // Datos del vehículo
        $registroConductor->marca_vehiculo = $request->marca_vehiculo;
        $registroConductor->modelo_vehiculo = $request->modelo_vehiculo;
        $registroConductor->anio_vehiculo = $request->anio_vehiculo;
        $registroConductor->patente = $request->patente;
        $registroConductor->numero_puestos = $request->numero_puestos;
        $registroConductor->consumo_por_galon = $request->consumo_por_galon;
        $registroConductor->verificar_pasajeros = $request->has('verificar_pasajeros') ? 1 : 0;
        
        // Estados
        $registroConductor->estado_verificacion = $request->estado_verificacion ?? 'pendiente';
        $registroConductor->estado_registro = $request->estado_registro ?? 'activo';

        // Guardar documentos
        if ($request->hasFile('licencia')) {
            $registroConductor->licencia = $request->file('licencia')->store('conductores/licencias', 'public');
        }
        
        if ($request->hasFile('cedula')) {
            $registroConductor->cedula = $request->file('cedula')->store('conductores/cedulas', 'public');
        }
        
        if ($request->hasFile('cedula_verde')) {
            $registroConductor->cedula_verde = $request->file('cedula_verde')->store('conductores/cedulas_verdes', 'public');
        }
        
        if ($request->hasFile('seguro')) {
            $registroConductor->seguro = $request->file('seguro')->store('conductores/seguros', 'public');
        }
        
        if ($request->hasFile('rto')) {
            $registroConductor->rto = $request->file('rto')->store('conductores/rto', 'public');
        }
        
        if ($request->hasFile('antecedentes')) {
            $registroConductor->antecedentes = $request->file('antecedentes')->store('conductores/antecedentes', 'public');
        }

        $registroConductor->save();
    }

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
                "Hola {$user->name}! 👋\n\n¡Buenísimas noticias! ✅\n\nTu cuenta ya está verificada y lista para usar sin restricciones.\n\nA partir de ahora podés:\n\t•\tConectarte con otros viajeros reales\n\t•\tUsar todas las funciones de la app sin límites\n\t•\tVivir la experiencia completa de VoyConVos\n\n🚗 ¡Bienvenida oficialmente a esta comunidad que viaja distinto!\nGracias por tu paciencia en el proceso de verificación.\n\nNos encanta tenerte a bordo.\nEl equipo de VoyConVos",
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
