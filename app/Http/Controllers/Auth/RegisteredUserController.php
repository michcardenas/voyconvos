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
use Illuminate\View\View;
use App\Services\EmailService;
use App\Mail\BienvenidaUserMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\UniversalMail;

class RegisteredUserController extends Controller
{

        protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'fecha_nacimiento' => ['required', 'date', 'before:-18 years'],
        'perfil' => ['required', 'integer', 'in:0,1,2'],
        'pais' => ['required', 'string', 'max:100'],
        'ciudad' => ['required', 'string', 'max:100'],
        'celular' => ['required', 'string', 'max:20'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'foto' => ['nullable', 'image', 'max:2048'],
    ]);

    $fotoPath = null;
    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('fotos-perfil', 'public');
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'fecha_nacimiento' => $request->fecha_nacimiento,
        'perfil' => $request->perfil,
        'pais' => $request->pais,
        'ciudad' => $request->ciudad,
        'celular' => $request->celular,
        'foto' => $fotoPath,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));
    
    Mail::to($user->email)->send(new UniversalMail(
        $user, 
        'Bienvenido a ' . config('app.name'),
        "Te damos la bienvenida a VoyConVos.\n\nEstamos felices de tenerte con nosotros. 🎉",
        'bienvenida'
    ));

    Auth::login($user);

    // CAMBIO IMPORTANTE: Redirigir a verificación
    return redirect()->route('verificacion.create');
}
}
