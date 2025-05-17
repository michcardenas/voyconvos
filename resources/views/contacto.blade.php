<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - VoyConVoz</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contacto.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <!-- Header con navegación -->
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
                    <li><a href="{{ url('/') }}">Inicio</a></li>
                    <li><a href="#">Buscar viaje</a></li>
                    <li><a href="#">Publicar viaje</a></li>
                    <li><a href="{{ url('/contacto') }}" class="active">Contacto</a></li>
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
        <!-- Hero Section para Contacto -->
        <section class="page-hero">
            <div class="container">
                <h1>Contáctanos</h1>
                <p>Estamos aquí para ayudarte. ¿Tienes preguntas o sugerencias? ¡Escríbenos!</p>
            </div>
        </section>

        <!-- Sección de Contacto -->
        <section class="contact-section">
            <div class="container">
                <div class="contact-container">
                    <div class="contact-info">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3>Ubicación</h3>
                            <p>Av. Circunvalar #123, Bogotá, Colombia</p>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3>Email</h3>
                            <p>contacto@voyconvos.com</p>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h3>Teléfono</h3>
                            <p>+57 300 123 4567</p>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3>Horario</h3>
                            <p>Lunes a Viernes: 8:00 - 18:00</p>
                        </div>
                    </div>

                    <div class="contact-form-container">
                        <h2>Envíanos un mensaje</h2>
                        <form class="contact-form" action="#" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nombre">Nombre completo</label>
                                <input type="text" id="nombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Correo electrónico</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="asunto">Asunto</label>
                                <input type="text" id="asunto" name="asunto" required>
                            </div>
                            <div class="form-group">
                                <label for="mensaje">Mensaje</label>
                                <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="submit-btn">Enviar mensaje</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQs Section -->
        <section class="faq-section">
            <div class="container">
                <h2>Preguntas frecuentes</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>¿Cómo funciona VoyConVos?</h3>
                            <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>VoyConVos conecta a conductores con asientos disponibles y pasajeros que viajan en la misma dirección. Los conductores pueden recuperar costos de viaje y los pasajeros pueden viajar a un precio asequible.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>¿Cómo me registro?</h3>
                            <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Puedes registrarte fácilmente haciendo clic en el botón "Registrarse" en la esquina superior derecha de la página. Solo necesitas tu nombre, correo electrónico y crear una contraseña.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>¿Cómo se realizan los pagos?</h3>
                            <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>VoyConVos ofrece varias opciones de pago seguro, incluyendo tarjetas de crédito/débito y transferencias bancarias. Todos los pagos se procesan de forma segura a través de nuestra plataforma.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>¿Es seguro viajar con desconocidos?</h3>
                            <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Nuestra plataforma incluye sistemas de verificación de identidad, calificaciones y reseñas de usuarios. Además, puedes ver los perfiles de los otros viajeros antes de confirmar el viaje.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
 
    <footer>
        <div class="container">
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

    <!-- Script para el dropdown de usuario -->
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
        
        // Script para las FAQs
        const faqQuestions = document.querySelectorAll('.faq-question');
        
        faqQuestions.forEach(question => {
            question.addEventListener('click', () => {
                const faqItem = question.parentElement;
                const isActive = faqItem.classList.contains('active');
                
                // Cerrar todas las preguntas activas
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                    item.querySelector('.faq-toggle i').className = 'fas fa-plus';
                });
                
                // Si no estaba activa, activarla
                if (!isActive) {
                    faqItem.classList.add('active');
                    question.querySelector('.faq-toggle i').className = 'fas fa-minus';
                }
            });
        });
    });
    </script>
    
    <!-- Script para animaciones y efectos de scroll -->
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>