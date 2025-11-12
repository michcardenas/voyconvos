<x-guest-layout>
    <div class="login-container">
        <div class="login-card">
            <!-- Panel izquierdo - Formulario -->
            <div class="login-form-panel">
                <!-- Logo de VoyConVos -->
                <div class="logo-container">
                    <img src="{{ asset('img/voyconvos-logo.png') }}" alt="VoyConVos" class="logo-image">
                </div>
                
                <h1 class="login-title">Iniciar Sesión</h1>
                
                <!-- Mostrar mensajes de error -->
                @if (session('error'))
                    <div class="alert-error">
                        {{ session('error') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required placeholder="Correo Electrónico" />
                        @error('email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <input id="password" class="form-input" type="password" name="password" required placeholder="Contraseña" />
                        @error('password')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                    
                    <div class="form-checkbox">
                        <input id="remember_me" type="checkbox" name="remember">
                        <label for="remember_me">Recordarme</label>
                    </div>
                    
                    <button type="submit" class="login-button">
                        INICIAR SESIÓN
                    </button>
                    
                    <!-- Separador -->
                    <div class="separator">
                        <span>ó continúa con</span>
                    </div>
                    
                    <!-- Botón de Google -->
                    <a href="{{ route('login.google') }}" class="google-btn">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="google-icon" alt="Google logo">
                        <span>Iniciar sesión con Google</span>
                    </a>
                    
                    <!-- Botón de Apple -->
                    <a href="{{ route('login.apple') }}" class="apple-btn">
                        <svg class="apple-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                        </svg>
                        <span>Iniciar sesión con Apple</span>
                    </a>
                </form>
            </div>
            
            <!-- Panel derecho - Bienvenida -->
            <div class="welcome-panel">
                <h2 class="welcome-title">¡Hola, Amigo!</h2>
                <p class="welcome-text">Regístrate con tus datos personales para usar todas las funciones del sitio</p>
                
                <a href="{{ route('register') }}" class="register-button">
                    REGISTRARSE
                </a>
            </div>
        </div>
    </div>
    
    <style>
        :root {
            --color-principal: #1F4E79;
            --color-azul-claro: #DDF2FE;
            --color-neutro-oscuro: #3A3A3A;
            --color-complementario: #4CAF50;
            --color-fondo-base: #FCFCFD;
        }
        
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: var(--color-fondo-base);
        }
        
        .min-h-screen {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        main, x-app-layout, x-guest-layout {
            display: block;
            height: 100%;
            width: 100%;
        }
        
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }
        
        .login-card {
            width: 100%;
            max-width: 800px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
        }
        
        .login-form-panel {
            flex: 1;
            padding: 2.5rem;
        }
        
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .logo-image {
            height: 80px;
            width: auto;
        }
        
        .login-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
            color: var(--color-principal);
        }
        
        /* Alerta de error */
        .alert-error {
            background-color: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 0.75rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #E2E8F0;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--color-principal);
            box-shadow: 0 0 0 2px rgba(31, 78, 121, 0.1);
        }
        
        .form-checkbox {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .form-checkbox input {
            margin-right: 0.5rem;
        }
        
        .forgot-link {
            display: block;
            font-size: 0.8rem;
            color: var(--color-principal);
            text-decoration: none;
            margin-bottom: 1rem;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        .login-button {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--color-principal);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        
        .login-button:hover {
            background-color: #173d61;
        }
        
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #94A3B8;
            font-size: 0.85rem;
        }
        
        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #E2E8F0;
        }
        
        .separator::before {
            margin-right: 0.5rem;
        }
        
        .separator::after {
            margin-left: 0.5rem;
        }
        
        /* Botón de Google */
        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.75rem;
            background-color: white;
            border: 1px solid #E2E8F0;
            border-radius: 5px;
            color: #4B5563;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-bottom: 0.75rem;
        }
        
        .google-btn:hover {
            background-color: #f8f9fa;
        }
        
        .google-icon {
            width: 20px;
            height: 20px;
        }
        
        /* Botón de Apple */
        .apple-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.75rem;
            background-color: #000;
            border: 1px solid #000;
            border-radius: 5px;
            color: white;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .apple-btn:hover {
            background-color: #1a1a1a;
        }
        
        .apple-icon {
            width: 20px;
            height: 20px;
        }
        
        .welcome-panel {
            background-color: var(--color-principal);
            color: white;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            width: 40%;
        }
        
        .welcome-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .welcome-text {
            font-size: 0.9rem;
            margin-bottom: 2rem;
            max-width: 220px;
        }
        
        .register-button {
            background-color: transparent;
            color: white;
            border: 2px solid white;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 0.8rem;
            text-decoration: none;
        }
        
        .register-button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                max-width: 400px;
            }
            
            .welcome-panel {
                width: 100%;
                padding: 2rem;
            }
            
            .login-form-panel {
                padding: 2rem;
            }
        }
    </style>
</x-guest-layout>