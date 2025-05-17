<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoyConVoz - Viajes Compartidos</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <!-- Header con navegación fija y efecto de scroll -->
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
                </ul>
            </nav>
            <div class="user-profile">
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
        </div>
    </header>
 
    <main>
        <!-- Hero Section con imagen de fondo y animación -->
        <section class="hero">
            <div class="hero-background"></div>
            <div class="container hero-content">
                <div class="hero-text">
                    <h1>Comparte tu viaje en auto</h1>
                    <p>Ahorra dinero y conecta con otras personas</p>
                    <div class="hero-buttons">
                        <a href="#" class="btn btn-primary">Buscar viaje</a>
                        <a href="#" class="btn btn-outline">Publicar viaje</a>
                    </div>
                </div>

                <div class="search-box">
                    <div class="route-inputs">
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" placeholder="Origen" value="">
                            <button class="switch-btn">⇄</button>
                        </div>
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" placeholder="Destino" value="">
                        </div>
                    </div>
                    
                    <div class="passengers">
                        <span class="person-icon"><i class="fas fa-user"></i></span>
                        <span>2 pasajeros</span>
                    </div>
                    
                    <div class="savings">
                        <h2>Ahorra hasta <span class="highlight">$ 100</span> en cada viaje.</h2>
                    </div>
                    
                    <button class="publish-trip-btn">Publica un viaje</button>
                    <a href="#" class="como-funciona">Cómo funciona <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <!-- <div class="car-illustration">
                <img src="{{ asset('img/undraw_vintage_q09n.png') }}" alt="Coche compartido">
            </div> -->
        </section>

        <!-- Features Section (Nueva) -->
        <section class="features">
            <div class="container">
                <h2>¿Por qué elegir VoyConVoz?</h2>
                <div class="feature-cards">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h3>Ahorra en cada viaje</h3>
                        <p>Comparte los gastos de gasolina y peajes con otros viajeros</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Conoce nuevas personas</h3>
                        <p>Conecta con gente que comparte tu ruta e intereses</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>Cuida el medio ambiente</h3>
                        <p>Reduce la contaminación compartiendo vehículo</p>
                    </div>
                </div>
            </div>
        </section>
 
        <section class="slogan">
            <div class="container">
                <h2>Conduce. Comparte. Ahorra.</h2>
                <a href="#" class="btn btn-primary">Publica un viaje</a>
            </div>
        </section>
    </main>
 
    <footer>
    <div class="container">
        <!-- Sección de contacto en el footer -->
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
                <h3>VoyConVoz</h3>
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
            <p>&copy; 2025 VoyConVoz. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

    <!-- Script original para el dropdown -->
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
    
    <!-- Nuevo script para animaciones y efectos de scroll -->
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>