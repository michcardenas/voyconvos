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
                        @php
                            // Determinar cuántas tarjetas mostrar
                            $viajesTipos = $viaje->ida_vuelta ? ['ida', 'vuelta'] : ['ida'];

                            // Función para acortar nombres de provincias
                            $acortarProvincia = function($texto) {
                                $reemplazos = [
                                    'Cdad. Autónoma de Buenos Aires' => 'CABA',
                                    'Ciudad Autónoma de Buenos Aires' => 'CABA',
                                    'Autonomous City of Buenos Aires' => 'CABA',
                                    'Provincia de Buenos Aires' => 'Bs.As.',
                                    'Buenos Aires Province' => 'Bs.As.',
                                ];
                                return str_replace(array_keys($reemplazos), array_values($reemplazos), $texto);
                            };

                            // Procesar origen
                            $origenParts = array_map('trim', explode(',', $viaje->origen_direccion));
                            $count = count($origenParts);
                            $origenCorta = $count >= 3 ? $origenParts[$count - 3] . ', ' . $origenParts[$count - 2] : $viaje->origen_direccion;
                            // Limpiar códigos Plus Code (ej: 8M9H+7P)
                            $origenCorta = preg_replace('/\b[A-Z0-9]{4,}\+[A-Z0-9]{2,}\b\s*/i', '', $origenCorta);
                            // Limpiar códigos postales (ej: B1650, C1405)
                            $origenCorta = preg_replace('/\b[A-Z]?\d{4}[A-Z]{0,3}\b\s*/i', '', $origenCorta);
                            // Limpiar números largos
                            $origenCorta = preg_replace('/\b\w*\d{3,}\w*\b\s*/i', '', $origenCorta);
                            $origenCorta = preg_replace('/\s+/', ' ', $origenCorta);
                            $origenCorta = preg_replace('/,\s*,/', ',', $origenCorta);
                            $origenCorta = trim($origenCorta, ' ,');
                            $origenCorta = $acortarProvincia($origenCorta);

                            // Procesar destino
                            $destinoParts = array_map('trim', explode(',', $viaje->destino_direccion));
                            $count = count($destinoParts);
                            $destinoCorta = $count >= 3 ? $destinoParts[$count - 3] . ', ' . $destinoParts[$count - 2] : $viaje->destino_direccion;
                            // Limpiar códigos Plus Code (ej: 8M9H+7P)
                            $destinoCorta = preg_replace('/\b[A-Z0-9]{4,}\+[A-Z0-9]{2,}\b\s*/i', '', $destinoCorta);
                            // Limpiar códigos postales (ej: B1650, C1405)
                            $destinoCorta = preg_replace('/\b[A-Z]?\d{4}[A-Z]{0,3}\b\s*/i', '', $destinoCorta);
                            // Limpiar números largos
                            $destinoCorta = preg_replace('/\b\w*\d{3,}\w*\b\s*/i', '', $destinoCorta);
                            $destinoCorta = preg_replace('/\s+/', ' ', $destinoCorta);
                            $destinoCorta = preg_replace('/,\s*,/', ',', $destinoCorta);
                            $destinoCorta = trim($destinoCorta, ' ,');
                            $destinoCorta = $acortarProvincia($destinoCorta);
                        @endphp

                        @foreach($viajesTipos as $tipoViaje)
                            @php
                                // Intercambiar origen/destino si es vuelta
                                $mostrarOrigen = $tipoViaje == 'ida' ? $origenCorta : $destinoCorta;
                                $mostrarDestino = $tipoViaje == 'ida' ? $destinoCorta : $origenCorta;
                                $mostrarHora = $tipoViaje == 'ida' ? $viaje->hora_salida : ($viaje->hora_regreso ?? $viaje->hora_salida);
                                $mostrarFecha = $tipoViaje == 'ida' ? $viaje->fecha_salida : ($viaje->fecha_regreso ?? $viaje->fecha_salida);
                            @endphp

                            <div class="viaje-card" style="min-width: 280px; max-width: 280px;">
                                <div style="background: linear-gradient(135deg, rgba(31, 78, 121, 0.95) 0%, rgba(76, 175, 80, 0.85) 100%); color: white; padding: 1rem; position: relative; overflow: hidden;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin: 0; font-weight: 600; font-size: 0.95rem; position: relative; z-index: 2; color: white;">
                                        <span style="flex: 1; text-align: center; color: white; font-weight: 600; font-size: 0.9rem;">{{ $mostrarOrigen }}</span>
                                        <i class="fas fa-arrow-right" style="margin: 0 0.75rem; font-size: 1.1rem; color: rgba(255, 255, 255, 0.9);"></i>
                                        <span style="flex: 1; text-align: center; color: white; font-weight: 600; font-size: 0.9rem;">{{ $mostrarDestino }}</span>
                                    </div>
                                </div>
                        <div style="display: flex; align-items: center; padding: 0.75rem; gap: 0.75rem; background: rgba(31, 78, 121, 0.03); border-radius: 10px; margin: 1rem 1rem 0.75rem; border: 1px solid rgba(31, 78, 121, 0.08);">
                            <img src="{{ $viaje->conductor && $viaje->conductor->foto ? asset('storage/' . $viaje->conductor->foto) : asset('img/usuario.png') }}" alt="Conductor" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #1F4E79; box-shadow: 0 2px 8px rgba(31, 78, 121, 0.2);">
                            <div style="flex: 1;">
                                <h4 style="font-size: 0.85rem; margin: 0 0 0.25rem 0; color: #3A3A3A; font-weight: 600;">{{ $viaje->conductor?->name ?? 'Conductor' }}</h4>
                                <div style="display: flex; align-items: center; gap: 0.35rem;">
                                    <i class="fas fa-star" style="font-size: 0.75rem; color: #ffc107;"></i>
                                    <span style="font-size: 0.75rem; font-weight: 600; color: #1F4E79;">{{ $viaje->conductor && $viaje->conductor->calificacion_promedio ? number_format($viaje->conductor->calificacion_promedio, 1) : 'Nuevo' }}</span>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 0 1rem; flex: 1; display: flex; flex-direction: column; gap: 0.5rem;">
                            <div style="display: flex; align-items: center; padding: 0.4rem 0;">
                                <i class="fas fa-calendar" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem; font-size: 0.85rem; flex-shrink: 0; background: linear-gradient(135deg, #DDF2FE 0%, rgba(31, 78, 121, 0.1) 100%); color: #1F4E79;"></i>
                                <span style="font-size: 0.85rem;">{{ \Carbon\Carbon::parse($mostrarFecha)->format('d/m/Y') }}</span>
                            </div>
                            <div style="display: flex; align-items: center; padding: 0.4rem 0;">
                                <i class="fas fa-clock" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem; font-size: 0.85rem; flex-shrink: 0; background: linear-gradient(135deg, #e8f5e9 0%, rgba(76, 175, 80, 0.1) 100%); color: #27ae60;"></i>
                                <span style="font-size: 0.85rem;">{{ $mostrarHora ?? 'Hora por definir' }}</span>
                            </div>
                            <div style="display: flex; align-items: center; padding: 0.4rem 0;">
                                <i class="fas fa-users" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem; font-size: 0.85rem; flex-shrink: 0; background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%); color: #f57c00;"></i>
                                <span style="font-size: 0.85rem;">{{ $viaje->puestos_disponibles }} {{ $viaje->puestos_disponibles == 1 ? 'lugar' : 'lugares' }}</span>
                            </div>
                        </div>
                        <div style="background: linear-gradient(135deg, rgba(76, 175, 80, 0.08) 0%, rgba(76, 175, 80, 0.03) 100%); border-radius: 10px; padding: 1rem; margin: 0.75rem 1rem; text-align: center; border: 2px solid rgba(76, 175, 80, 0.2);">
                            <span style="font-size: 1.75rem; font-weight: 700; color: #4CAF50; margin: 0; display: block;">${{ number_format(floor($viaje->valor_persona ?? 5200), 0, ',', '.') }}</span>
                            <small style="font-size: 0.75rem; color: rgba(58, 58, 58, 0.7); margin: 0.2rem 0 0 0; font-weight: 500; display: block;">por persona</small>
                        </div>
                        <button onclick="goToLogin('{{ $mostrarOrigen }}', '{{ $mostrarDestino }}', {{ $viaje->id }})" style="border: none; border-radius: 10px; padding: 0.75rem 1.25rem; font-weight: 600; transition: all 0.3s ease; text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer; background: #1F4E79; color: white; margin: 0 1rem 1rem;">
                            {{ \App\Models\Contenido::getValor('viajes', 'btn_reservar') }}
                        </button>
                    </div>
                        @endforeach
                    @endforeach
                @else
                    {{-- Fallback: Si no hay viajes reales, mostrar los del seeder --}}
                    @for ($i = 1; $i <= 4; $i++)
                    <div class="viaje-card" style="min-width: 280px; max-width: 280px;">
                        <div style="background: linear-gradient(135deg, rgba(31, 78, 121, 0.95) 0%, rgba(76, 175, 80, 0.85) 100%); color: white; padding: 1rem; position: relative; overflow: hidden;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin: 0; font-weight: 600; font-size: 0.95rem; position: relative; z-index: 2; color: white;">
                                <span style="flex: 1; text-align: center; color: white; font-weight: 600; font-size: 0.9rem;">{{ \App\Models\Contenido::getValor('viajes', 'origen_' . $i) }}</span>
                                <i class="fas fa-arrow-right" style="margin: 0 0.75rem; font-size: 1.1rem; color: rgba(255, 255, 255, 0.9);"></i>
                                <span style="flex: 1; text-align: center; color: white; font-weight: 600; font-size: 0.9rem;">{{ \App\Models\Contenido::getValor('viajes', 'destino_' . $i) }}</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; padding: 0.75rem; gap: 0.75rem; background: rgba(31, 78, 121, 0.03); border-radius: 10px; margin: 1rem 1rem 0.75rem; border: 1px solid rgba(31, 78, 121, 0.08);">
                            <img src="{{ \App\Models\Contenido::getValor('viajes', 'img_' . $i) }}" alt="Viaje" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #1F4E79; box-shadow: 0 2px 8px rgba(31, 78, 121, 0.2);">
                            <div style="flex: 1;">
                                <h4 style="font-size: 0.85rem; margin: 0 0 0.25rem 0; color: #3A3A3A; font-weight: 600;">{{ \App\Models\Contenido::getValor('viajes', 'conductor_' . $i) }}</h4>
                                <div style="display: flex; align-items: center; gap: 0.35rem;">
                                    <i class="fas fa-star" style="font-size: 0.75rem; color: #ffc107;"></i>
                                    <span style="font-size: 0.75rem; font-weight: 600; color: #1F4E79;">{{ \App\Models\Contenido::getValor('viajes', 'rating_' . $i) }}</span>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 0 1rem; flex: 1; display: flex; flex-direction: column; gap: 0.5rem;">
                            <div style="display: flex; align-items: center; padding: 0.4rem 0;">
                                <i class="fas fa-calendar" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem; font-size: 0.85rem; flex-shrink: 0; background: linear-gradient(135deg, #DDF2FE 0%, rgba(31, 78, 121, 0.1) 100%); color: #1F4E79;"></i>
                                <span style="font-size: 0.85rem;">{{ \App\Models\Contenido::getValor('viajes', 'fecha_' . $i) }}</span>
                            </div>
                            <div style="display: flex; align-items: center; padding: 0.4rem 0;">
                                <i class="fas fa-clock" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem; font-size: 0.85rem; flex-shrink: 0; background: linear-gradient(135deg, #e8f5e9 0%, rgba(76, 175, 80, 0.1) 100%); color: #27ae60;"></i>
                                <span style="font-size: 0.85rem;">{{ \App\Models\Contenido::getValor('viajes', 'tiempo_' . $i) }}</span>
                            </div>
                            <div style="display: flex; align-items: center; padding: 0.4rem 0;">
                                <i class="fas fa-users" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem; font-size: 0.85rem; flex-shrink: 0; background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%); color: #f57c00;"></i>
                                <span style="font-size: 0.85rem;">{{ \App\Models\Contenido::getValor('viajes', 'lugares_' . $i) }}</span>
                            </div>
                        </div>
                        <div style="background: linear-gradient(135deg, rgba(76, 175, 80, 0.08) 0%, rgba(76, 175, 80, 0.03) 100%); border-radius: 10px; padding: 1rem; margin: 0.75rem 1rem; text-align: center; border: 2px solid rgba(76, 175, 80, 0.2);">
                            <span style="font-size: 1.75rem; font-weight: 700; color: #4CAF50; margin: 0; display: block;">{{ \App\Models\Contenido::getValor('viajes', 'precio_' . $i) }}</span>
                            <small style="font-size: 0.75rem; color: rgba(58, 58, 58, 0.7); margin: 0.2rem 0 0 0; font-weight: 500; display: block;">por persona</small>
                        </div>
                        <button onclick="goToLogin('{{ \App\Models\Contenido::getValor('viajes', 'origen_' . $i) }}', '{{ \App\Models\Contenido::getValor('viajes', 'destino_' . $i) }}')" style="border: none; border-radius: 10px; padding: 0.75rem 1.25rem; font-weight: 600; transition: all 0.3s ease; text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer; background: #1F4E79; color: white; margin: 0 1rem 1rem;">
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
        
        // Touch Swipe Support para móviles
        let touchStartX = 0;
        let touchEndX = 0;
        let touchStartTime = 0;

        carousel.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
            touchStartTime = Date.now();
            isUserInteracting = true;
            stopAutoPlay();
        }, { passive: true });

        carousel.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            const touchDuration = Date.now() - touchStartTime;
            const swipeDistance = touchStartX - touchEndX;

            // Solo detectar swipe si fue rápido (<300ms) y con distancia mínima (>50px)
            if (touchDuration < 300 && Math.abs(swipeDistance) > 50) {
                if (swipeDistance > 0) {
                    // Swipe izquierda - siguiente
                    nextSlide();
                } else {
                    // Swipe derecha - anterior
                    prevSlide();
                }
            }

            setTimeout(() => {
                isUserInteracting = false;
                startAutoPlay();
            }, 100);
        }, { passive: true });

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

// Función para ir al dashboard híbrido o confirmar reserva
function goToLogin(origen = '', destino = '', viajeId = null) {
    // Si el usuario está logueado y hay un viajeId, ir directo a confirmar reserva
    @auth
        if (viajeId) {
            window.location.href = '/pasajero/reservar/' + viajeId;
            return;
        }
    @endauth

    // Si no está logueado o no hay viajeId específico, ir al dashboard híbrido
    if (origen && destino) {
        sessionStorage.setItem('selectedTrip', JSON.stringify({
            origen: origen,
            destino: destino
        }));
    }

    window.location.href = '/dashboard-hibrido';
}
</script>

