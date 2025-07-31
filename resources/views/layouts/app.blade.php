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
    <meta name="description" content="Encuentra viajes compartidos seguros y económicos en Colombia. Conecta con conductores y pasajeros de confianza.">
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

{{-- Meta tags extra (Open Graph, Twitter, etc.) --}}
@if(isset($metadatos) && $metadatos && $metadatos->extra_meta)
    {!! $metadatos->extra_meta !!}
@else
    <meta property="og:title" content="VoyConVos - Viajes Compartidos">
    <meta property="og:description" content="Encuentra viajes compartidos seguros y económicos en Colombia">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://voyconvos.com/images/og-home.jpg">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="VoyConVos - Viajes Compartidos">
@endif    
    
    <!-- CSS en orden correcto -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    
    <!-- Tus CSS existentes -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contacto.css') }}">
    
    <!-- CSS adicional por página -->
    @stack('styles')
    
    <!-- CSS del header y footer (DEBE IR AL FINAL) -->
    <link href="{{ asset('css/header-footer.css') }}" rel="stylesheet">
     <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* User Dropdown Styles */
        .desktop-user {
            display: flex;
            align-items: center;
        }

        .dropdown {
            position: relative;
        }

        .profile-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1f4e79 0%, #4CAF50 100%);
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .profile-icon img {
            width: 24px;
            height: 24px;
            filter: brightness(0) invert(1);
        }

        .profile-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 8px 0;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            margin-top: 8px;
            border: 1px solid #e2e8f0;
            z-index: 1000;
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: block;
            padding: 12px 20px;
            color: #334155;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea10, #764ba210);
            color: #667eea;
            border-left-color: #667eea;
        }

        /* Demo styles */
        .demo-container {
            max-width: 400px;
            margin: 50px auto;
            text-align: center;
        }

        .demo-title {
            margin-bottom: 30px;
            color: #334155;
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
                <li><a href="{{ url('/contacto') }}">Contáctanos</a></li>
                <li><a href="{{ route('como-funciona') }}">Cómo funciona</a></li>
            </ul>
        </nav>

        <!-- Usuario Desktop -->
        <div class="desktop-user">
            <div class="dropdown">
                 <a href="#" class="profile-icon" id="userDropdown">
            @auth
                <img src="{{ asset('img/usuario.png') }}" alt="Usuario">
            @else
                <img src="{{ asset('img/usuario.png') }}" alt="Usuario">
            @endauth
        </a>
                
                <div class="dropdown-menu" id="userMenu">
                    @auth
                        <!-- Dashboard según el rol -->
                        @if(Auth::user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">Dashboard Admin</a>
                        @elseif(Auth::user()->hasRole('conductor'))
                            <a href="{{ route('dashboard') }}" class="dropdown-item">Mi Dashboard</a>
                        @elseif(Auth::user()->hasRole('pasajero'))
                            <a href="{{ route('pasajero.dashboard') }}" class="dropdown-item">Mi Panel</a>
                        @endif
                        
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                                Cerrar Sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="dropdown-item">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="dropdown-item">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Botón Hamburguesa (Solo Móvil) -->
        <button class="hamburger-btn" id="hamburgerBtn" aria-label="Menú" aria-expanded="false">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
    </div>
</header>

    {{-- OVERLAY MÓVIL --}}
    <div class="mobile-overlay" id="mobileOverlay"></div>

    {{-- MENÚ MÓVIL --}}
    <nav class="mobile-menu" id="mobileMenu" aria-hidden="true">
        <!-- Header del menú móvil -->
        <div class="mobile-menu-header">
            <div class="logo-mobile">
                <img src="{{ asset('img/logo.png') }}" alt="VoyConVos" class="logo-image-mobile">
                <img src="{{ asset('img/letras-logo.png') }}" alt="VoyConVos" class="logo-text-mobile">
            </div>
            <button class="close-btn" id="closeBtn" aria-label="Cerrar menú">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Navegación móvil -->
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

        <!-- Sección de usuario móvil -->
        <div class="mobile-user-section">
            <div class="mobile-user-avatar">
                <img src="{{ asset('img/usuario.png') }}" alt="Usuario">
            </div>
            <div class="mobile-user-actions">
                <a href="{{ route('login') }}" class="mobile-auth-btn login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Iniciar sesión</span>
                </a>
                <a href="{{ route('register') }}" class="mobile-auth-btn register-btn">
                    <i class="fas fa-user-plus"></i>
                    <span>Registrarse</span>
                </a>
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
                <p style="margin: 0; font-size: 16px;">Estamos aquí para responder tus dudas y ayudarte en todo lo que necesites</p>
            </div>
            <a href="{{ url('/contacto') }}" class="footer-contact-btn" style="text-decoration: none; background-color: #3399ff; color: white; padding: 12px 20px; border-radius: 30px; font-weight: bold; display: flex; align-items: center; gap: 10px; white-space: nowrap;">
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
                        <a href="https://www.facebook.com/share/1EyRxy45Wy/?mibextid=wwXIfr
" target="_blank">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://www.threads.com/@voyconvos.arg?igshid=NTc4MTIwNjQ2YQ==" target="_blank">
                            <img src="{{ asset('img/threads-foo.png') }}"
                                alt="Threads"
                                title="Threads"
                                style="width: 35px; height: 35px; vertical-align: middle;">
                        </a>
                        <a href="https://www.instagram.com/voyconvos.arg?igsh=dXl0NmJxMTk3Mndx&utm_source=qr
" target="_blank">
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

    {{-- JS: Archivo externo --}}
    <script src="{{ asset('js/header-footer.js') }}"></script>
 <script>
        // Elementos del DOM
        const userDropdown = document.getElementById('userDropdown');
        const userMenu = document.getElementById('userMenu');

        // Toggle dropdown de usuario
        userDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isVisible = userMenu.classList.contains('show');
            
            // Cerrar dropdown si está abierto, abrirlo si está cerrado
            if (isVisible) {
                userMenu.classList.remove('show');
                userDropdown.setAttribute('aria-expanded', 'false');
            } else {
                userMenu.classList.add('show');
                userDropdown.setAttribute('aria-expanded', 'true');
            }
        });

        // Cerrar dropdown cuando se hace clic fuera
        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.remove('show');
                userDropdown.setAttribute('aria-expanded', 'false');
            }
        });

        // Soporte para teclado (accesibilidad)
        userDropdown.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });

        // Cerrar con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                userMenu.classList.remove('show');
                userDropdown.setAttribute('aria-expanded', 'false');
            }
        });
    </script>
</body>
</html>   