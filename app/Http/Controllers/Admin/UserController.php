<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
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
        // Solo mostrar roles permitidos para asignar (evitar admin/soporte)
        $roles = Role::all();
    
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|exists:roles,name',
            'pais' => 'required|string|max:100',
            'ciudad' => 'required|string|max:100',
            'dni' => 'required|string|max:20',
            'celular' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        // Foto (manejo aparte)
        if ($request->hasFile('foto')) {
            if ($user->foto && \Storage::disk('public')->exists($user->foto)) {
                \Storage::disk('public')->delete($user->foto);
            }
    
            $data['foto'] = $request->file('foto')->store('usuarios', 'public');
        }
    
        // Asignar datos uno a uno (más seguro que update($data) cuando hay conflictos)
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->pais = $data['pais'];
        $user->ciudad = $data['ciudad'];
        $user->dni = $data['dni'];
        $user->celular = $data['celular'];
        if (isset($data['foto'])) {
            $user->foto = $data['foto'];
        }
    
        $user->save();
    
        // Roles
        $user->syncRoles([$data['role']]);
    
        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado con éxito.');
    }
    
    
    

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    }

}
