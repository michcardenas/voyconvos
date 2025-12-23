<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VoyConVos - Viajes Compartidos')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contacto.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Estilos del navbar */
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            padding: 1rem 0;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 51px;
            margin-right: -5px;
        }

        nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 2rem;
        }

        nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        /* Estilos del dropdown - ESTOS SON LOS IMPORTANTES */
        .user-profile {
            position: relative;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.25s ease;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            box-shadow: 0 8px 12px rgba(0,0,0,0.1);
            z-index: 1000;
            min-width: 200px;
            border-radius: 8px;
            padding: 0.5rem 0;
            pointer-events: none;
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            color: #333;
            text-decoration: none;
            display: block;
            transition: background-color 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #f3f4f6;
            color: #333;
            text-decoration: none;
        }

        .profile-icon img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid #ccc;
            transition: box-shadow 0.3s ease;
        }

        .profile-icon img:hover {
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.2);
        }

        /* Estilos adicionales para el demo */
        main {
            padding-top: 120px;
            /* min-height: 500px; */
            text-align: center;
        }
    </style>
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
                    <!-- <li><a href="#">Coche compartido</a></li> -->
                    <li>
                        <a href="{{ route('pasajero.viajes.disponibles') }}" class="text-gray-700 hover:text-indigo-600">
                            Viajes Disponibles
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pasajero.dashboard') }}" class="text-gray-700 hover:text-indigo-600">
                            Mis Viajes
                        </a>
                    </li>
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
                {{-- Editar perfil unificado --}}
                <a href="{{ route('perfil.editar.usuario') }}" class="dropdown-item">
                    <i class="fas fa-user-edit"></i> Editar perfil
                </a>

                {{-- Dashboard --}}
                <a href="{{ route('hibrido.dashboard') }}" class="dropdown-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>

                {{-- Cerrar sesión --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                    </button>
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
            <div class="container-int">
                <div class="footer-contact-int">
                    <div class="footer-contact-text-int">
                        <h2>¿Necesitas ayuda con algo?</h2>
                        <p>Estamos aquí para responder tus dudas y ayudarte en todo lo que necesites</p>
                    </div>
                    <a href="{{ url('/contacto') }}" class="footer-contact-btn-int">
                        <span>Contactar ahora</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="footer-divider-int"></div>

                <div class="footer-columns-int">
                    <div class="footer-column-int">
                        <h3>VoyConVos</h3>
                        <ul>
                            <li><a href="{{ route('sobre-nosotros') }}">Sobre nosotros</a></li>
                            <li><a href="{{ route('como-funciona') }}">Cómo funciona</a></li>
                            <li><a href="{{ url('/contacto') }}">Contacto</a></li>
                        </ul>
                    </div>
                    <div class="footer-column-int">
                        <h3>Información</h3>
                        <ul>
                            <li><a href="{{ route('faq.index') }}">Preguntas frecuentes</a></li>
                            <li><a href="{{ route('terminos.index') }}">Términos y condiciones</a></li>
                            <li><a href="{{ route('politicas.index') }}">Política de privacidad</a></li>
                        </ul>
                    </div>
                    <div class="footer-column-int">
                        <h3>Síguenos</h3>
                        <div class="social-icons-int">
                            <a href="https://www.facebook.com/share/1EyRxy45Wy/?mibextid=wwXIfr
" target="_blank">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="https://www.threads.com/@voyconvos.app" target="_blank">
                                <img src="{{ asset('img/threads-foo.png') }}"
                                    alt="Threads"
                                    title="Threads"
                                    style="width: 35px; height: 35px; vertical-align: middle;">
                            </a>
                            <a href="https://www.instagram.com/voyconvos.app?igsh=MWRjemFtaG04bzY4Yg==" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom-int">
                    <p>&copy; 2025 VoyConVos. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- JS: Menú de usuario --}}
<script>
        document.addEventListener('DOMContentLoaded', function() {
            // COMENTÉ LA LÍNEA QUE CAUSABA EL ERROR
            // calcularCosto(); 
            
            
            const userDropdown = document.getElementById('userDropdown');
            const userMenu = document.getElementById('userMenu');

            // Verificar que los elementos existen antes de agregar eventos
            if (userDropdown && userMenu) {
                userDropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    userMenu.classList.toggle('show');
                    console.log('Dropdown clicked!'); // Para debug
                });

                // Cerrar dropdown al hacer clic fuera
                document.addEventListener('click', function(e) {
                    if (!userDropdown.contains(e.target) && !userMenu.contains(e.target)) {
                        userMenu.classList.remove('show');
                    }
                });
            } else {
                console.error('Elementos del dropdown no encontrados');
            }
        });


    </script>


</body>
</html>
