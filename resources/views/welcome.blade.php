@extends('layouts.app')

@section('title', 'Inicio')

@section('content')


{{-- HERO --}}
<section class="hero position-relative">
    <div class="hero-background"></div>

    <div class="container hero-content position-relative">
        <div class="hero-text">
            <h1>{{ \App\Models\Contenido::get('hero', 'h1') }}</h1>
            <h2>{{ \App\Models\Contenido::get('hero', 'h2') }}</h2>
            <div class="hero-buttons">
                <a href="#" class="btn btn-primary" id="buscarBtn">
                    {{ \App\Models\Contenido::get('hero', 'btn_buscar') }}
                </a>
                <a href="#" class="btn btn-primary" id="publicarBtn">
                    {{ \App\Models\Contenido::get('hero', 'btn_publicar') }}
                </a>
            </div>
        </div>

        <div class="search-box">
            <form id="searchForm" class="search-form">
                <div class="route-inputs">
                    {{-- Campo Origen --}}
                    <div class="input-group">
                     
                        <select id="origen" name="origen" required>
                            <option value="">Selecciona origen</option>
                            <option value="buenos-aires">Buenos Aires</option>
                            <option value="cordoba">Córdoba</option>
                            <option value="rosario">Rosario</option>
                            <option value="mendoza">Mendoza</option>
                            <option value="la-plata">La Plata</option>
                            <option value="mar-del-plata">Mar del Plata</option>
                        </select>
                    </div>

                    {{-- Botón Intercambiar --}}
                    <div style="text-align: center; margin: 10px 0;">
                        <button type="button" class="switch-btn" id="switchBtn">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </div>

                    {{-- Campo Destino --}}
                    <div class="input-group">
                     
                        <select id="destino" name="destino" required>
                            <option value="">Selecciona destino</option>
                            <option value="buenos-aires">Buenos Aires</option>
                            <option value="cordoba">Córdoba</option>
                            <option value="rosario">Rosario</option>
                            <option value="mendoza">Mendoza</option>
                            <option value="la-plata">La Plata</option>
                            <option value="mar-del-plata">Mar del Plata</option>
                        </select>
                    </div>

                    {{-- Campo Fecha --}}
                    <div class="input-group">
                        
                        <input type="date" id="fecha" name="fecha" required min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                {{-- Selector de Pasajeros --}}
                <div class="passengers">
                    <div class="input-group passengers-group">
                       
                        <select id="pasajeros" name="pasajeros" required>
                            <option value="1">1 pasajero</option>
                            <option value="2">2 pasajeros</option>
                            <option value="3">3 pasajeros</option>
                            <option value="4">4 pasajeros</option>
                            <option value="5">5 pasajeros</option>
                            <option value="6">6 pasajeros</option>
                        </select>
                    </div>
                </div>

                {{-- Botón de Búsqueda --}}
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                    Buscar viajes
                </button>
            </form>

            {{-- Información de ahorro --}}
            <div class="savings">
                <h3>{{ \App\Models\Contenido::get('hero', 'ahorro_texto') }}
                    <span class="highlight">{{ \App\Models\Contenido::get('hero', 'ahorro_valor') }}</span>
                    {{ \App\Models\Contenido::get('hero', 'ahorro_sufijo') }}
                </h3>
            </div>

            {{-- Botón Publicar Viaje --}}
            <button type="button" class="publish-trip-btn" id="publishBtn">
                {{ \App\Models\Contenido::get('hero', 'btn_publicar_main') }}
            </button>

            {{-- Enlace Cómo Funciona --}}
            <a href="#como-funciona" class="como-funciona">
                {{ \App\Models\Contenido::get('hero', 'como_funciona') }} <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>


{{-- VIAJES DISPONIBLES SECTION --}}
<section class="viajes-disponibles">
    <div class="container">
        <div class="section-header">
            <h2>Viajes Disponibles</h2>
            <p>Encuentra tu viaje ideal entre las principales ciudades</p>
        </div>

        <div class="carousel-container">
            <div class="viajes-carousel" id="viajesCarousel">
                {{-- Viaje 1 --}}
                <div class="viaje-card">
                    <div class="route">
                        <div class="cities">
                            <span class="from">Buenos Aires</span>
                            <i class="fas fa-arrow-right"></i>
                            <span class="to">Córdoba</span>
                        </div>
                        <div class="time">7h 30min</div>
                    </div>
                    
                    <div class="driver">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&crop=face" alt="Carlos">
                        <div class="info">
                            <h4>Carlos M.</h4>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>4.8</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="details">
                        <div class="item">
                            <i class="fas fa-calendar"></i>
                            <span>Hoy 15:30</span>
                        </div>
                        <div class="item">
                            <i class="fas fa-users"></i>
                            <span>2 lugares</span>
                        </div>
                        <div class="price">
                            <span class="amount">$4,500</span>
                            <small>por persona</small>
                        </div>
                    </div>
                    
                    <button class="reserve-btn" onclick="goToLogin('Buenos Aires', 'Córdoba')">
                        Reservar
                    </button>
                </div>

                {{-- Viaje 2 --}}
                <div class="viaje-card">
                    <div class="route">
                        <div class="cities">
                            <span class="from">Rosario</span>
                            <i class="fas fa-arrow-right"></i>
                            <span class="to">Mendoza</span>
                        </div>
                        <div class="time">5h 45min</div>
                    </div>
                    
                    <div class="driver">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b8fb?w=80&h=80&fit=crop&crop=face" alt="María">
                        <div class="info">
                            <h4>María G.</h4>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>4.9</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="details">
                        <div class="item">
                            <i class="fas fa-calendar"></i>
                            <span>Hoy 18:00</span>
                        </div>
                        <div class="item">
                            <i class="fas fa-users"></i>
                            <span>3 lugares</span>
                        </div>
                        <div class="price">
                            <span class="amount">$3,800</span>
                            <small>por persona</small>
                        </div>
                    </div>
                    
                    <button class="reserve-btn" onclick="goToLogin('Rosario', 'Mendoza')">
                        Reservar
                    </button>
                </div>

                {{-- Viaje 3 --}}
                <div class="viaje-card">
                    <div class="route">
                        <div class="cities">
                            <span class="from">Córdoba</span>
                            <i class="fas fa-arrow-right"></i>
                            <span class="to">Mar del Plata</span>
                        </div>
                        <div class="time">6h 15min</div>
                    </div>
                    
                    <div class="driver">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80&h=80&fit=crop&crop=face" alt="Diego">
                        <div class="info">
                            <h4>Diego F.</h4>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>4.7</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="details">
                        <div class="item">
                            <i class="fas fa-calendar"></i>
                            <span>Mañana 08:00</span>
                        </div>
                        <div class="item">
                            <i class="fas fa-users"></i>
                            <span>1 lugar</span>
                        </div>
                        <div class="price">
                            <span class="amount">$5,200</span>
                            <small>por persona</small>
                        </div>
                    </div>
                    
                    <button class="reserve-btn" onclick="goToLogin('Córdoba', 'Mar del Plata')">
                        Reservar
                    </button>
                </div>

                {{-- Viaje 4 --}}
                <div class="viaje-card">
                    <div class="route">
                        <div class="cities">
                            <span class="from">La Plata</span>
                            <i class="fas fa-arrow-right"></i>
                            <span class="to">Buenos Aires</span>
                        </div>
                        <div class="time">1h 20min</div>
                    </div>
                    
                    <div class="driver">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=80&h=80&fit=crop&crop=face" alt="Alejandro">
                        <div class="info">
                            <h4>Alejandro R.</h4>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>5.0</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="details">
                        <div class="item">
                            <i class="fas fa-calendar"></i>
                            <span>Hoy 20:30</span>
                        </div>
                        <div class="item">
                            <i class="fas fa-users"></i>
                            <span>4 lugares</span>
                        </div>
                        <div class="price">
                            <span class="amount">$1,200</span>
                            <small>por persona</small>
                        </div>
                    </div>
                    
                    <button class="reserve-btn" onclick="goToLogin('La Plata', 'Buenos Aires')">
                        Reservar
                    </button>
                </div>
            </div>

            {{-- Controles --}}
            <div class="carousel-controls">
                <button class="carousel-btn" id="prevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="carousel-dots" id="carouselDots"></div>
                <button class="carousel-btn" id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        {{-- Call to Action --}}
    <div class="cta-section">
    <h3>¿Buscas más opciones?</h3>
    <button class="cta-btn" onclick="goToLogin()">
        Ver Todos los Viajes
    </button>
</div>
    </div>
</section>


{{-- FEATURES --}}
<section class="features">
    <div class="container">
        <h2>{{ \App\Models\Contenido::get('features', 'titulo') }}</h2>

        <div class="feature-cards">
            @for ($i = 1; $i <= 3; $i++)
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas {{ \App\Models\Contenido::get('features', 'feature_' . $i . '_icon') }}"></i>
                    </div>
                    <h3>{{ \App\Models\Contenido::get('features', 'feature_' . $i . '_titulo') }}</h3>
                    <p>{{ \App\Models\Contenido::get('features', 'feature_' . $i . '_texto') }}</p>
                </div>
            @endfor
        </div>
    </div>
</section>

{{-- SLOGAN --}}
<section class="slogan">
    <div class="container">
        <h2>{{ \App\Models\Contenido::get('slogan', 'titulo') }}</h2>
        <a href="#" class="btn btn-primary"">{{ \App\Models\Contenido::get('slogan', 'boton') }}</a>
    </div>
</section>


{{-- CONTACT SECTION --}}
<section class="contact-section">
    <div class="container">
        <div class="contact-content">
            {{-- Información de contacto --}}
            <div class="contact-info">
                <h2>¿Tienes alguna pregunta?</h2>
                <p>Estamos aquí para ayudarte. Contáctanos y te responderemos lo antes posible.</p>
                
                <div class="contact-methods">
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="method-info">
                            <h4>Email</h4>
                            <p>contacto@voyconvos.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="method-info">
                            <h4>Teléfono</h4>
                            <p>+57 1 234 5678</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="method-info">
                            <h4>Horario</h4>
                            <p>Lun - Vie: 8:00 AM - 6:00 PM</p>
                        </div>
                    </div>
                </div>
                
                <div class="social-links">
                    <h4>Síguenos</h4>
                    <div class="social-icons">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            {{-- Formulario de contacto --}}
            <div class="contact-form-container">
                <div class="form-header">
                    <h3>Envíanos un mensaje</h3>
                    <p>Completa el formulario y nos pondremos en contacto contigo</p>
                </div>
                
                <form class="contact-form" id="contactForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <div class="input-wrapper">
                                <input type="text" id="nombre" name="nombre" required>
                                <label for="nombre" class="floating-label">Nombre completo</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="input-wrapper">
                                <input type="email" id="email" name="email" required>
                                <label for="email" class="floating-label">Correo electrónico</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="input-wrapper">
                                <input type="tel" id="telefono" name="telefono">
                                <label for="telefono" class="floating-label">Teléfono (opcional)</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="input-wrapper select-wrapper">
                                <select id="asunto" name="asunto" required>
                                    <option value="">Selecciona un tema</option>
                                    <option value="soporte">Soporte técnico</option>
                                    <option value="sugerencia">Sugerencias</option>
                                    <option value="colaboracion">Colaboraciones</option>
                                    <option value="otro">Otro</option>
                                </select>
                                <label for="asunto" class="floating-label">Asunto</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <div class="input-wrapper">
                            <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
                            <label for="mensaje" class="floating-label">Tu mensaje</label>
                        </div>
                    </div>
                    
                    <div class="form-group checkbox-group">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" id="acepto" name="acepto" required>
                            <span class="checkmark"></span>
                            Acepto los <a href="#" class="link">términos y condiciones</a> y la <a href="#" class="link">política de privacidad</a>
                        </label>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <span class="btn-text">Enviar mensaje</span>
                        <i class="fas fa-paper-plane btn-icon"></i>
                    </button>
                </form>
                
                <div class="success-message" id="successMessage">
                    <i class="fas fa-check-circle"></i>
                    <h4>¡Mensaje enviado!</h4>
                    <p>Gracias por contactarnos. Te responderemos pronto.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Switch button functionality
    const switchBtn = document.querySelector('.switch-btn');
    const origenInput = document.getElementById('origen');
    const destinoInput = document.getElementById('destino');
    
    if (switchBtn) {
        switchBtn.addEventListener('click', function() {
            const temp = origenInput.value;
            origenInput.value = destinoInput.value;
            destinoInput.value = temp;
        });
    }

    // Form validation (opcional)
    const publishBtn = document.querySelector('.publish-trip-btn');
    if (publishBtn) {
        publishBtn.addEventListener('click', function() {
            const origen = origenInput.value.trim();
            const destino = destinoInput.value.trim();
            const fecha = document.getElementById('fecha').value;
            
            if (!origen || !destino || !fecha) {
                alert('Por favor, completa todos los campos');
                return;
            }
            
            console.log('Publicando viaje:', { origen, destino, fecha });
        });
    }

    // Manejar formulario de contacto
    const form = document.getElementById('contactForm');
    const successMessage = document.getElementById('successMessage');
    
    if (form && successMessage) {
        // Manejar envío del formulario
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validar campos
            const nombre = document.getElementById('nombre').value.trim();
            const email = document.getElementById('email').value.trim();
            const mensaje = document.getElementById('mensaje').value.trim();
            const acepto = document.getElementById('acepto').checked;
            
            if (!nombre || !email || !mensaje || !acepto) {
                alert('Por favor, completa todos los campos obligatorios y acepta los términos.');
                return;
            }
            
            // Simular envío (aquí integrarías con tu backend)
            const submitBtn = form.querySelector('.submit-btn');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                form.style.display = 'none';
                successMessage.classList.add('show');
                
                // Resetear formulario después de 3 segundos
                setTimeout(() => {
                    form.reset();
                    form.style.display = 'block';
                    successMessage.classList.remove('show');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            }, 2000);
        });
        
        // Efecto de floating labels
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value) {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
        });
    }

    // Carrusel de viajes CORREGIDO
    const carousel = document.getElementById('viajesCarousel');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const dotsContainer = document.getElementById('carouselDots');
    
    if (carousel && prevBtn && nextBtn && dotsContainer) {
        const cards = carousel.querySelectorAll('.viaje-card');
        let currentIndex = 0;
        let autoPlayInterval;
        let cardsPerView = getCardsPerView();
        let maxIndex = Math.max(0, cards.length - cardsPerView);
        let isUserInteracting = false;
        
        // Función para calcular cuántas cards caben por vista
        function getCardsPerView() {
            const containerWidth = carousel.parentElement.offsetWidth;
            const cardWidth = cards[0].offsetWidth;
            const gap = 20; // Gap entre cards
            const cardsVisible = Math.floor(containerWidth / (cardWidth + gap));
            return Math.max(1, cardsVisible); // Mínimo 1 card visible
        }
        
        // Calcular el ancho real de una card incluyendo el gap
        function getCardWidth() {
            const cardWidth = cards[0].offsetWidth;
            const computedStyle = getComputedStyle(carousel);
            const gap = parseInt(computedStyle.gap) || 20;
            return cardWidth + gap;
        }
        
        // Actualizar cálculos en resize
        function updateCarouselCalculations() {
            cardsPerView = getCardsPerView();
            maxIndex = Math.max(0, cards.length - cardsPerView);
            
            // Ajustar currentIndex si es necesario
            if (currentIndex > maxIndex) {
                currentIndex = maxIndex;
            }
            
            goToSlide(currentIndex);
            updateDots();
        }
        
        // Crear dots basado en las páginas disponibles
        function updateDots() {
            dotsContainer.innerHTML = '';
            const totalPages = maxIndex + 1;
            
            // Solo crear dots si hay más de una página
            if (totalPages > 1) {
                for (let i = 0; i < totalPages; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'carousel-dot';
                    if (i === currentIndex) dot.classList.add('active');
                    dot.addEventListener('click', function() {
                        isUserInteracting = true;
                        goToSlide(i);
                        setTimeout(() => { isUserInteracting = false; }, 500);
                    });
                    dotsContainer.appendChild(dot);
                }
            }
        }
        
        // Función para ir a un slide específico
        function goToSlide(index) {
            // Limitar el índice dentro del rango válido
            currentIndex = Math.max(0, Math.min(index, maxIndex));
            
            const cardWidth = getCardWidth();
            const translateX = -(currentIndex * cardWidth);
            
            carousel.style.transform = `translateX(${translateX}px)`;
            carousel.style.transition = 'transform 0.3s ease-in-out';
            
            // Actualizar dots
            const dots = dotsContainer.querySelectorAll('.carousel-dot');
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentIndex);
            });
            
            // Actualizar estado de botones
            updateButtonStates();
        }
        
        // Actualizar estado de los botones
        function updateButtonStates() {
            if (maxIndex === 0) {
                // Si todas las cards caben en pantalla, ocultar botones
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            } else {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';
                
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex >= maxIndex;
                
                prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
                nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
            }
        }
        
        // Función para el siguiente slide
        function nextSlide() {
            if (currentIndex < maxIndex) {
                goToSlide(currentIndex + 1);
            } else {
                // Carrusel infinito: volver al inicio
                goToSlide(0);
            }
        }
        
        // Función para el slide anterior
        function prevSlide() {
            if (currentIndex > 0) {
                goToSlide(currentIndex - 1);
            } else {
                // Carrusel infinito: ir al final
                goToSlide(maxIndex);
            }
        }
        
        // Event listeners para botones
        nextBtn.addEventListener('click', function() {
            isUserInteracting = true;
            nextSlide();
            setTimeout(() => { isUserInteracting = false; }, 500);
        });
        prevBtn.addEventListener('click', function() {
            isUserInteracting = true;
            prevSlide();
            setTimeout(() => { isUserInteracting = false; }, 500);
        });
        
        // Auto-play functions
        function startAutoPlay() {
            // Solo auto-play si hay más de una página y el usuario no está interactuando
            if (maxIndex > 0 && !isUserInteracting) {
                stopAutoPlay(); // Limpiar cualquier intervalo previo
                autoPlayInterval = setInterval(function() {
                    if (!isUserInteracting) {
                        nextSlide();
                    }
                }, 4000);
            }
        }
        
        function stopAutoPlay() {
            if (autoPlayInterval) {
                clearInterval(autoPlayInterval);
                autoPlayInterval = null;
            }
        }
        
        // Inicializar carrusel
        function initCarousel() {
            updateCarouselCalculations();
            startAutoPlay();
        }
        
        // Pausar al hover (sin mover el carrusel)
        carousel.addEventListener('mouseenter', function() {
            isUserInteracting = true;
            stopAutoPlay();
        });
        carousel.addEventListener('mouseleave', function() {
            isUserInteracting = false;
            setTimeout(startAutoPlay, 100); // Pequeño delay antes de reanudar
        });
        
        // Manejar resize de ventana (con mejor control)
        let resizeTimeout;
        window.addEventListener('resize', function() {
            // Pausar auto-play durante resize
            stopAutoPlay();
            
            // Debounce para evitar múltiples calls
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                updateCarouselCalculations();
                // Reanudar auto-play después del resize
                setTimeout(startAutoPlay, 100);
            }, 300);
        });
        
        // Inicializar cuando las imágenes estén cargadas
        Promise.all(Array.from(cards).map(card => {
            const img = card.querySelector('img');
            if (img && !img.complete) {
                return new Promise(resolve => {
                    img.addEventListener('load', resolve);
                    img.addEventListener('error', resolve);
                });
            }
            return Promise.resolve();
        })).then(() => {
            initCarousel();
        });
        
        // Fallback: inicializar después de un pequeño delay
        setTimeout(initCarousel, 100);
    }
});

// Función para ir al login
function goToLogin(origen = '', destino = '') {
    if (origen && destino) {
        sessionStorage.setItem('selectedTrip', JSON.stringify({
            origen: origen,
            destino: destino
        }));
    }
    
    window.location.href = '/login?message=login_required';
}
</script>
@endsection