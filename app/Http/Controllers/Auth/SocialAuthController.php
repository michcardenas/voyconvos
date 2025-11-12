<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // Google Login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user, true);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Error al iniciar sesión con Google');
        }
    }

    // Apple Login
    public function redirectToApple()
    {
        $clientId = config('services.apple.client_id');
        $redirectUri = config('services.apple.redirect');
        $state = Str::random(40);
        session(['apple_state' => $state]);

        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code id_token',
            'response_mode' => 'form_post',
            'scope' => 'name email',
            'state' => $state,
        ];

        $url = 'https://appleid.apple.com/auth/authorize?' . http_build_query($params);
        
        return redirect($url);
    }

    public function handleAppleCallback(Request $request)
    {
        try {
            // Verificar state para prevenir CSRF
            if ($request->state !== session('apple_state')) {
                throw new \Exception('Invalid state parameter');
            }

            // Obtener el id_token de Apple
            $idToken = $request->id_token;
            
            if (!$idToken) {
                throw new \Exception('No id_token received from Apple');
            }

            // Decodificar el JWT (Apple ya lo firmó, solo necesitamos leer los claims)
            $tokenParts = explode('.', $idToken);
            
            if (count($tokenParts) !== 3) {
                throw new \Exception('Invalid token format');
            }

            // Decodificar el payload (segunda parte del JWT)
            $payload = $tokenParts[1];
            // Añadir padding si es necesario
            $remainder = strlen($payload) % 4;
            if ($remainder) {
                $payload .= str_repeat('=', 4 - $remainder);
            }
            
            $claims = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

            if (!$claims) {
                throw new \Exception('Could not decode token claims');
            }

            // Extraer información del usuario
            $appleId = $claims['sub'] ?? null;
            $email = $claims['email'] ?? null;
            $emailVerified = $claims['email_verified'] ?? false;
            
            if (!$appleId || !$email) {
                throw new \Exception('Missing required user information from Apple');
            }

            // El nombre solo viene en el primer login (cuando el usuario autoriza por primera vez)
            $name = 'Usuario de Apple';
            
            if ($request->has('user')) {
                $userData = json_decode($request->user, true);
                if (isset($userData['name'])) {
                    $firstName = $userData['name']['firstName'] ?? '';
                    $lastName = $userData['name']['lastName'] ?? '';
                    $name = trim($firstName . ' ' . $lastName);
                    
                    if (empty($name)) {
                        $name = 'Usuario de Apple';
                    }
                }
            }

            // Buscar usuario existente por apple_id o email
            $user = User::where('apple_id', $appleId)
                ->orWhere('email', $email)
                ->first();

            if ($user) {
                // Si el usuario existe pero no tiene apple_id, agregarlo
                if (!$user->apple_id) {
                    $user->apple_id = $appleId;
                }
                
                // Si el usuario ya existe, no sobreescribir el nombre a menos que esté vacío
                if (empty($user->name) || $user->name === 'Usuario de Apple') {
                    $user->name = $name;
                }
                
                $user->save();
            } else {
                // Crear nuevo usuario
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'apple_id' => $appleId,
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => $emailVerified ? now() : null,
                ]);
            }

            // Iniciar sesión
            Auth::login($user, true);

            // Limpiar el state de la sesión
            session()->forget('apple_state');

            // Verificar si el usuario está completamente registrado
            // Campos requeridos: fecha_nacimiento, pais, ciudad, celular
            $camposIncompletos = !$user->fecha_nacimiento ||
                                 !$user->pais ||
                                 !$user->ciudad ||
                                 !$user->celular;

            if ($camposIncompletos) {
                // Usuario necesita completar su registro
                return redirect()->route('register')
                    ->with('info', 'Por favor completa tu registro para continuar.');
            }

            // Usuario ya está completamente registrado
            return redirect()->route('hibrido.dashboard');
            
        } catch (\Exception $e) {
            \Log::error('Apple login error: ' . $e->getMessage());
            \Log::error('Apple login trace: ' . $e->getTraceAsString());
            
            return redirect()->route('login')
                ->with('error', 'Error al iniciar sesión con Apple. Por favor intenta de nuevo.');
        }
    }
}