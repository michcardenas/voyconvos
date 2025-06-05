<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VoyConVos - Viajes Compartidos')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contacto.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

    {{-- HEADER --}}
    <header id="navbar" class="navbar">
        <div class="container header-container">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="VoyConVos" class="logo-image">
                    <img src="{{ asset('img/letras-logo.png') }}" alt="VoyConVos" class="logo-text">
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Coche compartido</a></li>
                @role('admin')
                    <li>  
                    <a href="{{ route('admin.users.index') }}" class="text-gray-700 hover:text-indigo-600">
                        Gestión de Usuarios
                    </a>
                </li>
                <li>  
                    <a href="{{ route('configuracion.index') }}" class="text-gray-700 hover:text-indigo-600">
                        Configuración
                    </a>
                </li>
                @endrole
                </ul>
            
            </nav>
 <div class="user-profile">
    <div class="dropdown">
        <a href="#" class="profile-icon" id="userDropdown">
            @auth
                <img src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('img/usuario.png') }}" alt="Usuario">
            @else
                <img src="{{ asset('img/usuario.png') }}" alt="Usuario">
            @endauth
        </a>
        
        <div class="dropdown-menu" id="userMenu">
            @auth
                <a href="{{ route('perfil.editar.usuario') }}" class="dropdown-item">Editar perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Cerrar sesión</button>
                </form>
            @endauth
            
            @guest
                <a href="{{ route('login') }}" class="dropdown-item">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="dropdown-item">Registrarse</a>
            @endguest
        </div>
    </div>
</div>

        </div>
    </header>

    {{-- CONTENIDO PRINCIPAL --}}
<main style="padding-top: 100px;">
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
                        <li><a href="#">Sobre nosotros</a></li>
                        <li><a href="#">Cómo funciona</a></li>
                        <li><a href="{{ url('/contacto') }}">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Información</h3>
                    <ul>
                        <li><a href="#">Preguntas frecuentes</a></li>
                        <li><a href="#">Términos y condiciones</a></li>
                        <li><a href="#">Política de privacidad</a></li>
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

    {{-- JS: Menú de usuario --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userDropdown = document.getElementById('userDropdown');
            const userMenu = document.getElementById('userMenu');

            userDropdown.addEventListener('click', function(e) {
                e.preventDefault();
                userMenu.classList.toggle('show');
            });

            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target) && !userMenu.contains(e.target)) {
                    userMenu.classList.remove('show');
                }
            });
        });

        
    </script>

    {{-- Tu script de efectos --}}
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   @yield('scripts') {{-- Esta línea es crucial --}}


</body>
</html>
