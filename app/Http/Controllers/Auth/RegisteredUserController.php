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
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));

        Mail::to($user->email)->send(new UniversalMail(
        $user, 
        'Bienvenido a ' . config('app.name'),
        "Te damos la bienvenida a VoyConvos.\n\nEstamos felices de tenerte con nosotros. 🎉\n\n¡Gracias por registrarte en nuestra plataforma!",
        'bienvenida'
    ));

    Auth::login($user);

    return redirect(route('perfil.editar.usuario', absolute: false));
}
}
