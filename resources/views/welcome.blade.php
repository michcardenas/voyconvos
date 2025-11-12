@extends('layouts.app')

@section('title', 'Inicio')

@push('styles')
<link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
@endpush

@section('content')

{{-- HERO --}}
<section class="hero-modern">
    <div class="hero-background-image"></div>
    <div class="hero-overlay"></div>
    
    <div class="container hero-container">
        <!-- Texto del Hero -->
        <div class="hero-text-content">
            <h1 class="hero-title">{{ \App\Models\Contenido::getValor('hero', 'h1') }}</h1>
            <p class="hero-subtitle">{{ \App\Models\Contenido::getValor('hero', 'h2') }}</p>
        </div>

        <!-- Tarjeta de Búsqueda -->
        <div class="search-card-modern">
            <form id="searchForm" class="search-form-modern">
                <div class="form-row">
                    <!-- Origen -->
                    <div class="form-field">
                        <i class="fas fa-map-marker-alt field-icon"></i>
                        <select id="origen" name="origen" required>
                            <option value="">Origen</option>
                            @foreach($origenes as $ciudad)
                                <option value="{{ Str::slug($ciudad) }}">{{ trim($ciudad) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botón Intercambiar -->
                    <button type="button" class="swap-button" id="switchBtn">
                        <i class="fas fa-exchange-alt"></i>
                    </button>

                    <!-- Destino -->
                    <div class="form-field">
                        <i class="fas fa-map-pin field-icon"></i>
                        <select id="destino" name="destino" required>
                            <option value="">Destino</option>
                            @foreach($destinos as $ciudad)
                                <option value="{{ Str::slug($ciudad) }}">{{ trim($ciudad) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha -->
                    <div class="form-field">
                        <i class="fas fa-calendar-alt field-icon"></i>
                        <input type="date" id="fecha" name="fecha" required min="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Pasajeros -->
                    <div class="form-field">
                        <i class="fas fa-users field-icon"></i>
                        <select id="pasajeros" name="pasajeros" required>
                            <option value="1">1 pasajero</option>
                            <option value="2">2 pasajeros</option>
                            <option value="3">3 pasajeros</option>
                            <option value="4">4 pasajeros</option>
                            <option value="5">5 pasajeros</option>
                            <option value="6">6 pasajeros</option>
                        </select>
                    </div>

                    <!-- Botón Buscar -->
                    <button type="button" class="search-button-modern"
                        onclick="goToLogin('{{ \App\Models\Contenido::getValor('viajes', 'origen_1') }}', '{{ \App\Models\Contenido::getValor('viajes', 'destino_1') }}')">
                        <i class="fas fa-search"></i>
                        Buscar
                    </button>
                </div>
            </form>

            <!-- Información adicional bajo la tarjeta -->
            <div class="hero-bottom-info">
                <p class="savings-text">
                    {{ \App\Models\Contenido::getValor('hero', 'ahorro_texto') }}
                    <strong class="savings-amount">{{ \App\Models\Contenido::getValor('hero', 'ahorro_valor') }}</strong>
                    {{ \App\Models\Contenido::getValor('hero', 'ahorro_sufijo') }}
                </p>
                
                <div class="hero-actions">
                    <button type="button" class="publish-button-modern"
                        onclick="goToLogin('{{ \App\Models\Contenido::getValor('viajes', 'origen_1') }}', '{{ \App\Models\Contenido::getValor('viajes', 'destino_1') }}')">
                        {{ \App\Models\Contenido::getValor('hero', 'btn_publicar_main') }}
                    </button>
                    
                    <a href="/dashboard" class="how-it-works-link">
                        {{ \App\Models\Contenido::getValor('hero', 'como_funciona') }}
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- VIAJES DISPONIBLES SECTION --}}
<section class="viajes-disponibles">
    <div class="container">
        <div class="section-header">
            <h2>{{ \App\Models\Contenido::getValor('viajes', 'titulo') }}</h2>
            <p>{{ \App\Models\Contenido::getValor('viajes', 'descripcion') }}</p>
        </div>

        <div class="carousel-container">
            <div class="viajes-carousel" id="viajesCarousel">
                @if($viajesDestacados->count() > 0)
                    @foreach($viajesDestacados as $viaje)
                    <div class="viaje-card">
                        <div class="route">
                            <div class="cities">
                                <span class="from">
                                    @php
                                        $origenParts = array_map('trim', explode(',', $viaje->origen_direccion));
                                        $count = count($origenParts);
                                        // Si tiene 3 o más partes, toma las penúltimas 2 (ciudad y provincia)
                                        $origenCorta = $count >= 3 ? $origenParts[$count - 3] . ', ' . $origenParts[$count - 2] : $viaje->origen_direccion;
                                        // Eliminar códigos postales alfanuméricos (C1414CMV, B1650, etc)
                                        $origenCorta = preg_replace('/\b[A-Z]?\d{4}[A-Z]{0,3}\b\s*/i', '', $origenCorta);
                                        // Eliminar palabras con más de 2 números consecutivos (como 1704)
                                        $origenCorta = preg_replace('/\b\w*\d{3,}\w*\b\s*/i', '', $origenCorta);
                                        // Limpiar espacios dobles y comas dobles
                                        $origenCorta = preg_replace('/\s+/', ' ', $origenCorta);
                                        $origenCorta = preg_replace('/,\s*,/', ',', $origenCorta);
                                        $origenCorta = trim($origenCorta, ' ,');
                                    @endphp
                                    {{ $origenCorta }}
                                </span>
                                <i class="fas fa-arrow-right"></i>
                                <span class="to">
                                    @php
                                        $destinoParts = array_map('trim', explode(',', $viaje->destino_direccion));
                                        $count = count($destinoParts);
                                        // Si tiene 3 o más partes, toma las penúltimas 2 (ciudad y provincia)
                                        $destinoCorta = $count >= 3 ? $destinoParts[$count - 3] . ', ' . $destinoParts[$count - 2] : $viaje->destino_direccion;
                                        // Eliminar códigos postales alfanuméricos (C1414CMV, B1650, etc)
                                        $destinoCorta = preg_replace('/\b[A-Z]?\d{4}[A-Z]{0,3}\b\s*/i', '', $destinoCorta);
                                        // Eliminar palabras con más de 2 números consecutivos (como 1704)
                                        $destinoCorta = preg_replace('/\b\w*\d{3,}\w*\b\s*/i', '', $destinoCorta);
                                        // Limpiar espacios dobles y comas dobles
                                        $destinoCorta = preg_replace('/\s+/', ' ', $destinoCorta);
                                        $destinoCorta = preg_replace('/,\s*,/', ',', $destinoCorta);
                                        $destinoCorta = trim($destinoCorta, ' ,');
                                    @endphp
                                    {{ $destinoCorta }}
                                </span>
                            </div>
                            <div class="time">{{ $viaje->hora_salida ?? 'Hora por definir' }}</div>
                        </div>
                        <div class="driver">
                            <img src="{{ $viaje->conductor && $viaje->conductor->foto ? asset('storage/' . $viaje->conductor->foto) : asset('img/usuario.png') }}" alt="Conductor">
                            <div class="info">
                                <h4>{{ $viaje->conductor?->name ?? 'Conductor' }}</h4>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <span>{{ $viaje->conductor && $viaje->conductor->calificacion_promedio ? number_format($viaje->conductor->calificacion_promedio, 1) : 'Nuevo' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="details">
                            <div class="item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</span>
                            </div>
                            <div class="item">
                                <i class="fas fa-users"></i>
                                <span>{{ $viaje->puestos_disponibles }} {{ $viaje->puestos_disponibles == 1 ? 'lugar' : 'lugares' }}</span>
                            </div>
                            <div class="price">
                                <span class="amount">${{ number_format(floor($viaje->valor_persona ?? 5200), 0, ',', '.') }}</span>
                                <small>por persona</small>
                            </div>
                        </div>
                        <button class="reserve-btn" onclick="goToLogin('{{ explode(',', $viaje->origen_direccion)[0] ?? $viaje->origen_direccion }}', '{{ explode(',', $viaje->destino_direccion)[0] ?? $viaje->destino_direccion }}')">
                            {{ \App\Models\Contenido::getValor('viajes', 'btn_reservar') }}
                        </button>
                    </div>
                    @endforeach
                @else
                    {{-- Fallback: Si no hay viajes reales, mostrar los del seeder --}}
                    @for ($i = 1; $i <= 4; $i++)
                    <div class="viaje-card">
                        <div class="route">
                            <div class="cities">
                                <span class="from">{{ \App\Models\Contenido::getValor('viajes', 'origen_' . $i) }}</span>
                                <i class="fas fa-arrow-right"></i>
                                <span class="to">{{ \App\Models\Contenido::getValor('viajes', 'destino_' . $i) }}</span>
                            </div>
                            <div class="time">{{ \App\Models\Contenido::getValor('viajes', 'tiempo_' . $i) }}</div>
                        </div>
                        <div class="driver">
                            <img src="{{ \App\Models\Contenido::getValor('viajes', 'img_' . $i) }}" alt="Viaje">
                            <div class="info">
                                <h4>{{ \App\Models\Contenido::getValor('viajes', 'conductor_' . $i) }}</h4>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <span>{{ \App\Models\Contenido::getValor('viajes', 'rating_' . $i) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="details">
                            <div class="item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ \App\Models\Contenido::getValor('viajes', 'fecha_' . $i) }}</span>
                            </div>
                            <div class="item">
                                <i class="fas fa-users"></i>
                                <span>{{ \App\Models\Contenido::getValor('viajes', 'lugares_' . $i) }}</span>
                            </div>
                            <div class="price">
                                <span class="amount">{{ \App\Models\Contenido::getValor('viajes', 'precio_' . $i) }}</span>
                                <small>por persona</small>
                            </div>
                        </div>
                        <button class="reserve-btn" onclick="goToLogin('{{ \App\Models\Contenido::getValor('viajes', 'origen_' . $i) }}', '{{ \App\Models\Contenido::getValor('viajes', 'destino_' . $i) }}')">
                            {{ \App\Models\Contenido::getValor('viajes', 'btn_reservar') }}
                        </button>
                    </div>
                    @endfor
                @endif
            </div>
            <div class="carousel-controls">
                <button class="carousel-btn" id="prevBtn"><i class="fas fa-chevron-left"></i></button>
                <div class="carousel-dots" id="carouselDots"></div>
                <button class="carousel-btn" id="nextBtn"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
        <div class="cta-section">
            <h3>{{ \App\Models\Contenido::getValor('cta', 'titulo') }}</h3>
            <button class="cta-btn" onclick="goToLogin()">
                {{ \App\Models\Contenido::getValor('cta', 'boton') }}
            </button>
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section class="features">
    <div class="container">
        <h2>{{ \App\Models\Contenido::getValor('features', 'titulo') }}</h2>
        <div class="feature-cards">
            @for ($i = 1; $i <= 3; $i++)
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas {{ \App\Models\Contenido::getValor('features', 'feature_' . $i . '_icon') }}"></i>
                    </div>
                    <h3>{{ \App\Models\Contenido::getValor('features', 'feature_' . $i . '_titulo') }}</h3>
                    <p>{{ \App\Models\Contenido::getValor('features', 'feature_' . $i . '_texto') }}</p>
                </div>
            @endfor
        </div>
    </div>
</section>

{{-- SLOGAN --}}
<section class="slogan">
    <div class="container">
        <h2>{{ \App\Models\Contenido::getValor('slogan', 'titulo') }}</h2>
        <a href="#" class="btn btn-primary"
        onclick="goToLogin('{{ \App\Models\Contenido::getValor('viajes', 'origen_1') }}', '{{ \App\Models\Contenido::getValor('viajes', 'destino_1') }}')">
            {{ \App\Models\Contenido::getValor('slogan', 'boton') }}
        </a>
        <p>{{ \App\Models\Contenido::getValor('slogan', 'descripcion') }}</p>
    </div>
</section>

{{-- CONTACTO --}}
<section class="contact-section">
    <div class="container">
        <div class="contact-content">
            <div class="contact-info">                 
    <h2>{{ \App\Models\Contenido::getValor('contacto', 'titulo') }}</h2>                 
    
    <div class="contact-methods">                     
        <div class="contact-method">                         
            <i class="fas fa-envelope"></i>                         
            <p>{{ \App\Models\Contenido::getValor('contacto', 'email') }}</p>                     
        </div>                     
        <div class="contact-method">                         
            <i class="fas fa-phone"></i>                         
            <p>{{ \App\Models\Contenido::getValor('contacto', 'telefono') }}</p>                     
        </div>                     
        <div class="contact-method">                         
            <i class="fas fa-clock"></i>                         
            <p>{{ \App\Models\Contenido::getValor('contacto', 'horario') }}</p>                     
        </div>                 
    </div>
    
    <p>{{ \App\Models\Contenido::getValor('contacto', 'descripcion') }}</p>
    
<div class="social-icons" style="text-align: center; padding-top: 10px;">
    @php
        $sociales = [
            'facebook' => 'fab fa-facebook',
            'threads' => 'threads-img',
            'instagram' => 'fab fa-instagram',
            'whatsapp' => 'fab fa-whatsapp',
        ];
    @endphp

    @foreach($sociales as $clave => $icono)
        @php
            $url = \App\Models\Contenido::getValor('contacto', 'social_' . $clave);
        @endphp

        @if($url)
            <a href="{{ $url }}" target="_blank"
               style="display: inline-block; margin: 0 10px; transition: transform 0.3s ease;">
                @if($icono === 'threads-img')
                    <img src="{{ asset('img/threads.png') }}"
                         alt="Threads"
                         style="width: 27px; height: 27px; border-radius: 4px; transition: transform 0.3s ease;"
                         onmouseover="this.style.transform='scale(1.2)'"
                         onmouseout="this.style.transform='scale(1)'">
                @else
                    <i class="{{ $icono }}"
                       style="color: #27ae60 !important; font-size: 28px; transition: transform 0.3s ease;"
                       onmouseover="this.style.transform='scale(1.2)'"
                       onmouseout="this.style.transform='scale(1)'"></i>
                @endif
            </a>
        @endif
    @endforeach
</div>
            
</div>

            {{-- Formulario de contacto --}}

            <div class="contact-form-container"> 
                <div class="form-header">
                    <h3>{{ $titulo }}</h3>
                    <p>{{ $subtitulo }}</p>
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
                                    @foreach($asuntos as $a)
                                        <option value="{{ Str::slug($a) }}">{{ trim($a) }}</option>
                                    @endforeach
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
                    
                   <div class="form-group" style="margin-top: 1rem;">
                        <label for="acepto" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" id="acepto" name="acepto" required
                                style="width: 20px; height: 20px; accent-color: #27ae60; margin: 0;">
                            <span style="font-size: 14px; color: #333;">
                                {!! $acepto !!}
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="submit-btn">
                        <span class="btn-text">{{ $boton }}</span>
                        <i class="fas fa-paper-plane btn-icon"></i>
                    </button>
                </form>
                
                <div class="success-message" id="successMessage">
                    <i class="fas fa-check-circle"></i>
                    <h4>¡Mensaje enviado!</h4>
                    <p>{{ $msg_ok }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


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

