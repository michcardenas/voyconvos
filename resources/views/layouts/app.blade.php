<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VoyConVos - Viajes Compartidos')</title>
    
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
                        <img src="{{ asset('img/usuario.png') }}" alt="Usuario">
                    </a>
                    <div class="dropdown-menu" id="userMenu">
                        <a href="{{ route('login') }}" class="dropdown-item">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="dropdown-item">Registrarse</a>
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
            <div class="footer-contact">
                <div class="footer-contact-text">
                    <h2>¿Necesitas ayuda con algo?</h2>
                    <p>Estamos aquí para responder tus dudas y ayudarte en todo lo que necesites</p>
                </div>
                <a href="{{ url('/contacto') }}" class="footer-contact-btn">
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
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
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

</body>
</html>