<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Título dinámico --}}
    @if(isset($metadatos) && $metadatos)
        <title>{{ $metadatos->meta_title }}</title>
    @else
        <title>VoyConVos - Viajes Compartidos Seguros</title>
    @endif

    {{-- Meta Description --}}
    @if(isset($metadatos) && $metadatos)
        <meta name="description" content="{{ $metadatos->meta_description }}">
    @else
        <meta name="description" content="Encuentra viajes compartidos seguros y económicos en Colombia.">
    @endif

    {{-- Meta Keywords --}}
    @if(isset($metadatos) && $metadatos && $metadatos->meta_keywords)
        <meta name="keywords" content="{{ $metadatos->meta_keywords }}">
    @else
        <meta name="keywords" content="viajes compartidos, carpooling, transporte Colombia, VoyConVos">
    @endif

    {{-- Canonical URL --}}
    @if(isset($metadatos) && $metadatos && $metadatos->canonical_url)
        <link rel="canonical" href="{{ $metadatos->canonical_url }}">
    @else
        <link rel="canonical" href="{{ url()->current() }}">
    @endif

    {{-- Meta Robots --}}
    @if(isset($metadatos) && $metadatos && $metadatos->meta_robots)
        <meta name="robots" content="{{ $metadatos->meta_robots }}">
    @else
        <meta name="robots" content="index, follow">
    @endif

    {{-- Meta tags extra --}}
    @if(isset($metadatos) && $metadatos && $metadatos->extra_meta)
        {!! $metadatos->extra_meta !!}
    @else
        <meta property="og:title" content="VoyConVos - Viajes Compartidos">
        <meta property="og:description" content="Encuentra viajes compartidos seguros y económicos">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
    @endif    
    
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contacto.css') }}">
    
    @stack('styles')

    <link href="{{ asset('css/header-footer.css') }}" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .desktop-user {
            display: flex;
            align-items: center;
        }

        .dropdown {
            position: relative;
        }

        /* Foto grande con BORDE DE COLOR */
        .profile-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            text-decoration: none;
            transition: transform 0.2s;
            cursor: pointer;
            overflow: hidden;
        }

        /* BORDE VERDE - VERIFICADO */
        .profile-icon.verified {
            border: 4px solid #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        /* BORDE NARANJA - SIN VERIFICAR */
        .profile-icon.unverified {
            border: 4px solid #f59e0b;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
        }

        .profile-icon:hover {
            transform: scale(1.05);
        }

        .profile-icon img {
            width: 100%;
            height: 100%;
            object-fit: scale-down;
            border-radius: 50%;
        }

        /* Dropdown simple */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 8px 0;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s;
            margin-top: 8px;
            z-index: 1000;
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-info {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .user-name {
            font-weight: 600;
            color: #1f4e79;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .user-status {
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 12px;
            font-weight: 500;
        }

        .user-status.verified {
            background: #d1fae5;
            color: #059669;
        }

        .user-status.unverified {
            background: #fef3c7;
            color: #d97706;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            color: #334155;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
        }

        .dropdown-item:hover {
            background: #f8fafc;
        }

        .dropdown-item i {
            width: 18px;
            margin-right: 8px;
            font-size: 14px;
        }

        /* Móvil - BORDE DE COLOR */
        .mobile-user-avatar {
            text-align: center;
            margin-bottom: 20px;
        }

        .mobile-avatar-wrapper {
            display: inline-block;
            border-radius: 50%;
            padding: 4px;
        }

        /* Borde verde - verificado móvil */
        .mobile-avatar-wrapper.verified {
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        /* Borde naranja - sin verificar móvil */
        .mobile-avatar-wrapper.unverified {
            background: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
        }

        .mobile-user-avatar img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
        }

        .mobile-user-name {
            margin-top: 8px;
            font-weight: 600;
            color: #1f4e79;
        }

        .mobile-user-status {
            font-size: 13px;
            margin-top: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: 500;
        }

        .mobile-user-status.verified {
            background: #d1fae5;
            color: #059669;
        }

        .mobile-user-status.unverified {
            background: #fef3c7;
            color: #d97706;
        }
    </style>
</head>
<body>
    {{-- HEADER --}}
    <header id="navbar" class="navbar">
        <div class="container header-container">
            <!-- Logo -->
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="VoyConVos" class="logo-image">
                    <img src="{{ asset('img/letras-logo.png') }}" alt="VoyConVos" class="logo-text">
                </a>
            </div>

            <!-- Navegación Desktop -->
            <nav class="desktop-nav">
                <ul>
                    <li><a href="{{ url('/') }}">Inicio</a></li>
                    <li><a href="{{ route('sobre-nosotros') }}">Nosotros</a></li>
                    <li><a href="{{ route('pasajero.viajes.disponibles') }}">Viajes Disponibles</a></li>
                    @auth
                        <li><a href="{{ route('pasajero.dashboard') }}">Mis Viajes</a></li>
                    @endauth
                    <li><a href="{{ url('/contacto') }}">Contáctanos</a></li>
                    <li><a href="{{ route('como-funciona') }}">Cómo funciona</a></li>
                </ul>
            </nav>

            <!-- Usuario Desktop con BORDE DE COLOR -->
            <div class="desktop-user">
                <div class="dropdown">
                    @auth
                        <a href="#" class="profile-icon {{ Auth::user()->verificado ? 'verified' : 'unverified' }}" id="userDropdown">
                            @if(Auth::user()->foto)
                                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="{{ Auth::user()->name }}">
                            @else
                                <img src="{{ asset('img/usuario.png') }}" alt="Usuario">
                            @endif
                        </a>
                    @else
                        <a href="#" class="profile-icon" id="userDropdown" style="border: 3px solid #e2e8f0;">
                            <img src="{{ asset('img/usuario.png') }}" alt="Usuario">
                        </a>
                    @endauth
                    
                    <div class="dropdown-menu" id="userMenu">
                        @auth
                            <div class="user-info">
                                <div class="user-name">{{ Auth::user()->name }}</div>
                                @if(Auth::user()->verificado)
                                    <span class="user-status verified">
                                        <i class="fas fa-check-circle"></i> Verificado
                                    </span>
                                @else
                                    <span class="user-status unverified">
                                        <i class="fas fa-exclamation-circle"></i> Sin verificar
                                    </span>
                                @endif
                            </div>
                            
                            @if(Auth::user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard Admin
                                </a>
                            @else
                                <a href="{{ route('hibrido.dashboard') }}" class="dropdown-item">
                                    <i class="fas fa-th-large"></i>
                                    Mi Dashboard
                                </a>
                                <a href="{{ route('pasajero.dashboard') }}" class="dropdown-item">
                                    <i class="fas fa-suitcase-rolling"></i>
                                    Mis Viajes
                                </a>
                            @endif

                            <a href="" class="dropdown-item">
                                <i class="fas fa-user-edit"></i>
                                Mi Perfil
                            </a>
                            
                            @if(!Auth::user()->verificado)
                                <a href="{{ route('verificacion.create') }}" class="dropdown-item" style="color: #f59e0b;">
                                    <i class="fas fa-shield-alt"></i>
                                    Verificar Cuenta
                                </a>
                            @endif
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; color: #ef4444;">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Cerrar Sesión
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="dropdown-item">
                                <i class="fas fa-sign-in-alt"></i>
                                Iniciar sesión
                            </a>
                            <a href="{{ route('register') }}" class="dropdown-item">
                                <i class="fas fa-user-plus"></i>
                                Registrarse
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Botón Hamburguesa -->
            <button class="hamburger-btn" id="hamburgerBtn" aria-label="Menú">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </header>

    {{-- OVERLAY MÓVIL --}}
    <div class="mobile-overlay" id="mobileOverlay"></div>

    {{-- MENÚ MÓVIL --}}
    <nav class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <div class="logo-mobile">
                <img src="{{ asset('img/logo.png') }}" alt="VoyConVos" class="logo-image-mobile">
                <img src="{{ asset('img/letras-logo.png') }}" alt="VoyConVos" class="logo-text-mobile">
            </div>
            <button class="close-btn" id="closeBtn" aria-label="Cerrar menú">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mobile-nav">
            <ul class="mobile-nav-list">
                <li class="mobile-nav-item">
                    <a href="{{ url('/') }}" class="mobile-nav-link">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('sobre-nosotros') }}" class="mobile-nav-link">
                        <i class="fas fa-users"></i>
                        <span>Nosotros</span>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('pasajero.viajes.disponibles') }}" class="mobile-nav-link">
                        <i class="fas fa-route"></i>
                        <span>Viajes Disponibles</span>
                    </a>
                </li>
                @auth
                    <li class="mobile-nav-item">
                        <a href="{{ route('pasajero.dashboard') }}" class="mobile-nav-link">
                            <i class="fas fa-suitcase-rolling"></i>
                            <span>Mis Viajes</span>
                        </a>
                    </li>
                @endauth
                <li class="mobile-nav-item">
                    <a href="{{ url('/contacto') }}" class="mobile-nav-link">
                        <i class="fas fa-envelope"></i>
                        <span>Contáctanos</span>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('como-funciona') }}" class="mobile-nav-link">
                        <i class="fas fa-question-circle"></i>
                        <span>Cómo funciona</span>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('faq.index') }}" class="mobile-nav-link">
                        <i class="fas fa-question"></i>
                        <span>FAQ</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Usuario móvil con BORDE DE COLOR -->
        <div class="mobile-user-section">
            <div class="mobile-user-avatar">
                @auth
                    <div class="mobile-avatar-wrapper {{ Auth::user()->verificado ? 'verified' : 'unverified' }}">
                        @if(Auth::user()->foto)
                            <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="{{ Auth::user()->name }}">
                        @else
                            <img src="{{ asset('img/usuario.png') }}" alt="Usuario">
                        @endif
                    </div>
                    
                    <p class="mobile-user-name">{{ Auth::user()->name }}</p>
                    
                    @if(Auth::user()->verificado)
                        <span class="mobile-user-status verified">
                            <i class="fas fa-check-circle"></i> Verificado
                        </span>
                    @else
                        <span class="mobile-user-status unverified">
                            <i class="fas fa-exclamation-circle"></i> Sin verificar
                        </span>
                    @endif
                @else
                    <div class="mobile-avatar-wrapper" style="background: #e2e8f0;">
                        <img src="{{ asset('img/usuario.png') }}" alt="Usuario">
                    </div>
                @endauth
            </div>
            
            <div class="mobile-user-actions">
                @auth
                    @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="mobile-auth-btn login-btn">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard Admin</span>
                        </a>
                    @else
                        <a href="{{ route('hibrido.dashboard') }}" class="mobile-auth-btn login-btn">
                            <i class="fas fa-th-large"></i>
                            <span>Mi Dashboard</span>
                        </a>
                    @endif
                    
                    @if(!Auth::user()->verificado)
                        <a href="{{ route('verificacion.create') }}" class="mobile-auth-btn" style="background: #f59e0b; color: white; border: none;">
                            <i class="fas fa-shield-alt"></i>
                            <span>Verificar Cuenta</span>
                        </a>
                    @endif
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mobile-auth-btn register-btn" style="background: #ef4444; border: none;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mobile-auth-btn login-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Iniciar sesión</span>
                    </a>
                    <a href="{{ route('register') }}" class="mobile-auth-btn register-btn">
                        <i class="fas fa-user-plus"></i>
                        <span>Registrarse</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- CONTENIDO PRINCIPAL --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer>
        <div class="container">
            <div class="footer-contact" style="display: flex; justify-content: space-between; align-items: center; background-color: #245c7d; padding: 58px; border-radius: 32px; color: white; flex-wrap: wrap; gap: 20px;">
                <div class="footer-contact-text" style="flex: 1; min-width: 200px;">
                    <h2 style="margin: 0 0 10px 0; font-size: 24px; font-weight: 600;">¿Necesitas ayuda con algo?</h2>
                    <p style="margin: 0; font-size: 16px;">Estamos aquí para responder tus dudas</p>
                </div>
                <a href="{{ url('/contacto') }}" class="footer-contact-btn" style="text-decoration: none; background-color: #3399ff; color: white; padding: 12px 20px; border-radius: 30px; font-weight: bold; display: flex; align-items: center; gap: 10px;">
                    <span>Contactar ahora</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="footer-divider"></div>

            <div class="footer-columns">
                <div class="footer-column">
                    <h3>VoyConVos</h3>
                    <ul>
                        <li><a href="{{ route('sobre-nosotros') }}">Sobre nosotros</a></li>
                        <li><a href="{{ route('como-funciona') }}">Cómo funciona</a></li>
                        <li><a href="{{ url('/contacto') }}">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Información</h3>
                    <ul>
                        <li><a href="{{ route('faq.index') }}">Preguntas frecuentes</a></li>
                        <li><a href="{{ route('terminos.index') }}">Términos y condiciones</a></li>
                        <li><a href="{{ route('politicas.index') }}">Política de privacidad</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Síguenos</h3>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/share/1EyRxy45Wy/?mibextid=wwXIfr" target="_blank">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://www.threads.com/@voyconvos.app" target="_blank">
                            <img src="{{ asset('img/threads-foo.png') }}" alt="Threads" style="width: 35px; height: 35px;">
                        </a>
                        <a href="https://www.instagram.com/voyconvos.app?igsh=MWRjemFtaG04bzY4Yg==" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 VoyConVos. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/header-footer.js') }}"></script>
    <script>
        const userDropdown = document.getElementById('userDropdown');
        const userMenu = document.getElementById('userMenu');

        userDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            userMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.remove('show');
            }
        });
    </script>
</body>
</html>