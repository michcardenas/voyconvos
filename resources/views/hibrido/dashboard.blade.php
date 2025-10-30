@extends('layouts.app')

@section('content')
<style>
:root {
    --vcv-primary: #1F4E79;
    --vcv-light: #DDF2FE;
    --vcv-dark: #3A3A3A;
    --vcv-accent: #4CAF50;
    --vcv-bg: #FCFCFD;
}

body {
    background: var(--vcv-bg);
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, rgba(31, 78, 121, 0.95) 0%, rgba(76, 175, 80, 0.85) 100%),
                url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=1600') center/cover;
    min-height: 500px;
    display: flex;
    align-items: center;
    color: white;
    padding: 4rem 0;
    position: relative;
}

.hero-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
     display: flex;
    flex-direction: column;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.hero-subtitle {
    font-size: 1.3rem;
    margin-bottom: 2rem;
    opacity: 0.95;
}

/* Search Box */
.search-box {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    max-width: 900px;
    margin: 0 auto;
}

.search-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.search-tab {
    flex: 1;
    padding: 0.875rem;
    border: 2px solid #e2e8f0;
    background: white;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--vcv-dark);
}

.search-tab:hover {
    border-color: var(--vcv-primary);
    background: var(--vcv-light);
}

.search-tab.active {
    border-color: var(--vcv-primary);
    background: var(--vcv-primary);
    color: white;
}

.search-tab i {
    font-size: 1.2rem;
}

.search-form {
    display: none;
}

.search-form.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.search-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr auto auto;
    gap: 1rem;
    align-items: end;
}

.input-group {
    display: flex;
    flex-direction: column;
}

.input-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--vcv-dark);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-input {
    padding: 0.875rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: var(--vcv-primary);
    box-shadow: 0 0 0 3px rgba(31, 78, 121, 0.1);
}

.btn-search {
    padding: 0.875rem 2rem;
    background: var(--vcv-accent);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-search:hover {
    background: #45a049;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
}

.btn-publish {
    padding: 0.875rem 2rem;
    background: var(--vcv-primary);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-publish:hover {
    background: #173d61;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(31, 78, 121, 0.3);
}

/* Features Section */
.features-section {
    max-width: 1200px;
    margin: 4rem auto;
    padding: 0 2rem;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--vcv-primary);
    text-align: center;
    margin-bottom: 3rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.feature-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    text-align: center;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--vcv-light) 0%, rgba(76, 175, 80, 0.1) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 1.5rem;
    color: var(--vcv-primary);
}

.feature-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--vcv-primary);
    margin-bottom: 0.75rem;
}

.feature-description {
    color: #64748b;
    line-height: 1.6;
}

/* How it Works */
.how-it-works {
    background: linear-gradient(135deg, var(--vcv-light) 0%, white 100%);
    padding: 4rem 2rem;
    margin: 4rem 0;
}

.steps-container {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.step-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    position: relative;
}

.step-number {
    width: 50px;
    height: 50px;
    background: var(--vcv-accent);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 auto 1rem;
}

.step-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--vcv-primary);
    margin-bottom: 0.5rem;
}

.step-description {
    color: #64748b;
    font-size: 0.95rem;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, var(--vcv-primary) 0%, var(--vcv-accent) 100%);
    color: white;
    padding: 4rem 2rem;
    text-align: center;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.btn-cta {
    padding: 1rem 2.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
}

.btn-cta-primary {
    background: white;
    color: var(--vcv-primary);
    border: 2px solid white;
}

.btn-cta-primary:hover {
    background: transparent;
    color: white;
    transform: translateY(-2px);
}

.btn-cta-secondary {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-cta-secondary:hover {
    background: white;
    color: var(--vcv-primary);
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .search-box {
        padding: 1.5rem;
    }
    
    .search-inputs {
        grid-template-columns: 1fr;
    }
    
    .search-tabs {
        flex-direction: column;
    }
    
    .cta-title {
        font-size: 1.8rem;
    }
}
/* Search Box - NUEVO DISEÑO LIMPIO */
.search-box {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    max-width: 1100px;
    width: 100%;
    margin: 0 auto;
}

.search-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.search-tab {
    flex: 1;
    padding: 0.875rem;
    border: 2px solid #e2e8f0;
    background: white;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--vcv-dark);
}

.search-tab:hover {
    border-color: var(--vcv-primary);
    background: var(--vcv-light);
}

.search-tab.active {
    border-color: var(--vcv-primary);
    background: var(--vcv-primary);
    color: white;
}

.search-tab i {
    font-size: 1.2rem;
}

.search-form {
    display: none;
}

.search-form.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

/* Barra de búsqueda - ESTILO IMAGEN */
.search-bar {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.5rem;
    gap: 0;
}

.search-field {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 0.5rem 1rem;
    position: relative;
    overflow: hidden; /* Evitar desbordamiento del select */
    min-width: 0; /* Permitir reducción flexible */
}

.field-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.field-input {
    border: none;
    background: transparent;
    font-size: 0.95rem;
    color: var(--vcv-dark);
    font-weight: 500;
    padding: 0;
    outline: none;
    width: 100%;
}

.field-input::placeholder {
    color: #94a3b8;
    font-weight: 400;
}

/* Display visible para los selects - MEJORADO */
.field-display {
    font-size: 0.95rem;
    color: var(--vcv-dark);
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    gap: 0.75rem;
    padding-right: 0.5rem;
    min-height: 24px;
    pointer-events: none; /* Evitar interferencia con el select */
}

.field-display span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex: 1;
    min-width: 0; /* Importante para que funcione el ellipsis */
}

.field-display::after {
    content: '▼';
    font-size: 0.65rem;
    color: #94a3b8;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

/* Estados del search-field con select */
.search-field:has(.field-display) {
    cursor: pointer;
    position: relative;
    transition: all 0.3s ease;
    min-width: 0; /* Permitir reducción */
}

.search-field:has(.field-display):hover {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 8px;
}

.search-field:has(.field-display):hover .field-display::after {
    color: var(--vcv-primary);
    animation: chevronBounce 0.6s ease-in-out;
}

.search-field:has(.field-select:focus) {
    background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
    box-shadow: 0 0 0 2px rgba(31, 78, 121, 0.15);
    border-radius: 8px;
}

.search-field:has(.field-select:focus) .field-display::after {
    transform: rotate(180deg);
    color: var(--vcv-primary);
}

/* Animación del chevron */
@keyframes chevronBounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(2px);
    }
}

/* Ocultar visualización del date input */
.field-date {
    color: transparent;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    opacity: 0;
    z-index: 2;
}

.field-date::-webkit-calendar-picker-indicator {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    opacity: 0;
}

/* Select personalizado - MEJORADO Y MÁS ESTABLE */
.field-select {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    opacity: 0;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    z-index: 3;
    font-size: 0.95rem;
    background: transparent;
    border: none;
    outline: none;
}

/* Estilos para las opciones del select cuando se abre */
.field-select option {
    padding: 10px 16px;
    background: white;
    color: var(--vcv-dark);
    font-size: 1rem;
    font-weight: 500;
    line-height: 1.5;
}

.field-select option:hover {
    background: var(--vcv-light);
}

.field-select option:checked,
.field-select option:active {
    background: linear-gradient(135deg, var(--vcv-light) 0%, #e0f2fe 100%);
    color: var(--vcv-primary);
    font-weight: 600;
}

/* Separadores verticales */
.field-separator {
    width: 1px;
    height: 40px;
    background: #e2e8f0;
    margin: 0;
}

/* Botón de búsqueda */
.btn-search-bar {
    background: #00a8e1;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 1rem 2rem;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
    margin-left: 0.5rem;
}

.btn-search-bar:hover {
    background: #0090c4;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 168, 225, 0.3);
}

/* Botón publicar */
.btn-publish {
    padding: 0.875rem 2rem;
    background: var(--vcv-primary);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none;
}

.btn-publish:hover {
    background: #173d61;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(31, 78, 121, 0.3);
}

/* Responsive - MEJORADO PARA EVITAR DEFORMACIÓN */
@media (max-width: 992px) {
    .search-bar {
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .search-field {
        min-width: calc(50% - 0.5rem);
        flex: 1 1 calc(50% - 0.5rem);
    }

    .field-separator {
        display: none;
    }

    .btn-search-bar {
        width: 100%;
        margin-top: 1rem;
        margin-left: 0;
    }

    /* Asegurar que los selects no se deformen */
    .field-select {
        min-width: 100%;
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .search-box {
        padding: 1.5rem;
    }

    .search-bar {
        flex-direction: column;
        padding: 1rem;
        gap: 0;
    }

    .search-field {
        width: 100%;
        min-width: 100%;
        max-width: 100%;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 0.75rem;
        margin: 0;
    }

    .search-field:last-of-type {
        border-bottom: none;
    }

    .search-tabs {
        flex-direction: column;
    }

    /* Ajustes para el display en móviles */
    .field-display {
        font-size: 0.875rem;
        min-width: 0;
        max-width: 100%;
        gap: 0.5rem;
        padding-right: 0.25rem;
    }

    .field-display span {
        max-width: calc(100% - 20px); /* Espacio para el chevron */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
    }

    .field-label {
        font-size: 0.7rem;
        margin-bottom: 0.5rem;
    }

    .field-separator {
        display: none;
    }

    .btn-search-bar {
        width: 100%;
        margin-left: 0;
        margin-top: 0.5rem;
        font-size: 0.95rem;
    }

    /* Asegurar que los selects ocupen todo el espacio en móvil */
    .field-select {
        width: 100%;
        min-width: 100%;
        max-width: 100%;
    }

    /* Mejorar el tamaño del área clickeable en móviles */
    .search-field:has(.field-select) {
        min-height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
}

/* Tablets en orientación landscape */
@media (max-width: 1024px) and (min-width: 769px) {
    .search-bar {
        flex-wrap: wrap;
    }

    .search-field {
        flex: 1 1 calc(33.333% - 1rem);
        min-width: calc(33.333% - 1rem);
    }

    .field-separator:nth-child(6),
    .field-separator:nth-child(8) {
        display: none;
    }
}

/* Viajes Cards */
.viaje-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
}

.btn-ver-viaje:hover {
    background: #173d61 !important;
    transform: translateX(2px);
}

@media (max-width: 768px) {
    .viajes-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<!-- Hero Section with Search -->
<div class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Viaja conectando con otros</h1>
        <p class="hero-subtitle">Ahorra dinero, conoce gente y cuida el medio ambiente</p>
        
       <div class="search-box">
    <!-- Tabs -->
    <div class="search-tabs">
        <button class="search-tab active" onclick="showSearchTab('buscar')">
            <i class="fas fa-search"></i>
            <span>Buscar viaje</span>
        </button>
        <button class="search-tab" onclick="showSearchTab('publicar')">
            <i class="fas fa-plus-circle"></i>
            <span>Publicar viaje</span>
        </button>
    </div>
    
    <!-- Formulario Buscar - NUEVO DISEÑO -->
    <form action="{{ route('pasajero.viajes.disponibles') }}" method="GET" class="search-form active" id="form-buscar">
        @if(isset($ciudadesOrigen) && isset($ciudadesDestino) && count($ciudadesOrigen) == 0 && count($ciudadesDestino) == 0)
            <div class="alert alert-info" style="background: #e0f2fe; border: 1px solid #0ea5e9; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; color: #0c4a6e; text-align: center;">
                <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                <strong>No hay viajes disponibles para buscar en este momento.</strong><br>
                <small style="font-size: 0.875rem;">Los viajes que has publicado no aparecen aquí. Si quieres reservar un viaje, espera a que otros conductores publiquen sus rutas.</small>
            </div>
        @endif
        <div class="search-bar">
            <!-- De -->
            <div class="search-field">
                <label class="field-label">
                    <i class="fas fa-map-marker-alt" style="margin-right: 4px; color: var(--vcv-accent);"></i>
                    De
                </label>
                <span id="origen-texto" class="field-display">
                    @if(request('ciudad_origen'))
                        <span>{{ request('ciudad_origen') }}</span>
                    @else
                        <span style="color: #94a3b8; font-weight: 400;">¿Desde dónde sales?</span>
                    @endif
                </span>
                <select name="ciudad_origen"
                        id="ciudad-origen-select"
                        class="field-input field-select"
                        onchange="updateOrigenLabel(this.value)">
                    <option value="">Todas las ciudades</option>
                    @if(isset($ciudadesOrigen) && count($ciudadesOrigen) > 0)
                        @foreach($ciudadesOrigen as $ciudad)
                            @if($ciudad)
                                <option value="{{ $ciudad }}" {{ request('ciudad_origen') == $ciudad ? 'selected' : '' }}>
                                    {{ $ciudad }}
                                </option>
                            @endif
                        @endforeach
                    @else
                        <option value="" disabled>No hay orígenes disponibles</option>
                    @endif
                </select>
            </div>

            <div class="field-separator"></div>

            <!-- A -->
            <div class="search-field">
                <label class="field-label">
                    <i class="fas fa-map-marker-alt" style="margin-right: 4px; color: var(--vcv-primary);"></i>
                    A
                </label>
                <span id="destino-texto" class="field-display">
                    @if(request('ciudad_destino'))
                        <span>{{ request('ciudad_destino') }}</span>
                    @else
                        <span style="color: #94a3b8; font-weight: 400;">¿A dónde vas?</span>
                    @endif
                </span>
                <select name="ciudad_destino"
                        id="ciudad-destino-select"
                        class="field-input field-select"
                        onchange="updateDestinoLabel(this.value)"
                        required>
                    <option value="">Todas las ciudades</option>
                    @if(isset($ciudadesDestino) && count($ciudadesDestino) > 0)
                        @foreach($ciudadesDestino as $ciudad)
                            @if($ciudad)
                                <option value="{{ $ciudad }}" {{ request('ciudad_destino') == $ciudad ? 'selected' : '' }}>
                                    {{ $ciudad }}
                                </option>
                            @endif
                        @endforeach
                    @else
                        <option value="" disabled>No hay destinos disponibles</option>
                    @endif
                </select>
            </div>

            <div class="field-separator"></div>

            <!-- Fecha -->
            <div class="search-field">
                <label class="field-label">
                    <i class="far fa-calendar-alt" style="margin-right: 4px;"></i>
                    <span id="fecha-texto">{{ request('fecha_salida') ? \Carbon\Carbon::parse(request('fecha_salida'))->format('d/m/Y') : 'Hoy' }}</span>
                </label>
                <input type="date"
                       name="fecha_salida"
                       id="fecha-input"
                       class="field-input field-date"
                       min="{{ date('Y-m-d') }}"
                       value="{{ request('fecha_salida', date('Y-m-d')) }}"
                       onchange="updateFechaLabel(this.value)">
            </div>

            <div class="field-separator"></div>

            <!-- Pasajeros -->
            <div class="search-field">
                <label class="field-label">
                    <i class="fas fa-user" style="margin-right: 4px;"></i>
                    <span id="pasajeros-texto">{{ request('puestos_minimos', 1) }} {{ request('puestos_minimos', 1) == 1 ? 'pasajero' : 'pasajeros' }}</span>
                </label>
                <select name="puestos_minimos" id="pasajeros-select" class="field-input field-select" onchange="updatePasajerosLabel(this.value)">
                    <option value="1" {{ request('puestos_minimos') == 1 ? 'selected' : '' }}>1 pasajero</option>
                    <option value="2" {{ request('puestos_minimos') == 2 ? 'selected' : '' }}>2 pasajeros</option>
                    <option value="3" {{ request('puestos_minimos') == 3 ? 'selected' : '' }}>3 pasajeros</option>
                    <option value="4" {{ request('puestos_minimos') == 4 ? 'selected' : '' }}>4 pasajeros</option>
                    <option value="5" {{ request('puestos_minimos') == 5 ? 'selected' : '' }}>5+ pasajeros</option>
                </select>
            </div>

            <div class="field-separator"></div>

            <!-- Botón Buscar -->
            <button type="submit" class="btn-search-bar">
                <i class="fas fa-search"></i>
                Buscar
            </button>
        </div>
    </form>
    
    <!-- Formulario Publicar -->
    <div class="search-form" id="form-publicar">
        <div class="text-center">
            <p class="mb-3" style="color: #64748b;">¿Tienes un viaje planeado? Comparte tu ruta y ahorra en combustible</p>
            
            @auth
                @if(auth()->user()->verificado)
                    <a href="{{ route('conductor.gestion') }}" class="btn-publish">
                        <i class="fas fa-car"></i>
                        Publicar mi viaje
                    </a>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-2"></i>
                        Tu cuenta está en proceso de verificación. Podrás publicar viajes cuando sea aprobada.
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-publish">
                    <i class="fas fa-sign-in-alt"></i>
                    Inicia sesión para publicar
                </a>
            @endauth
        </div>
    </div>
</div>
    </div>
</div>

<!-- Mis Viajes Publicados Section -->
@if($esConductor && $viajesProximosList->count() > 0)
<div class="features-section">
    <h2 class="section-title">Mis Viajes Publicados</h2>

    <div class="viajes-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem;">
        @foreach($viajesProximosList as $viaje)
        <div class="viaje-card" style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06); transition: all 0.3s ease; border-left: 4px solid var(--vcv-primary);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div>
                    <span class="badge" style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;
                        {{ $viaje->estado == 'pendiente' ? 'background: #fef3c7; color: #92400e;' : '' }}
                        {{ $viaje->estado == 'en_curso' ? 'background: #dbeafe; color: #1e40af;' : '' }}
                        {{ $viaje->estado == 'finalizado' ? 'background: #d1fae5; color: #065f46;' : '' }}
                        {{ $viaje->estado == 'cancelado' ? 'background: #fee2e2; color: #991b1b;' : '' }}">
                        {{ ucfirst($viaje->estado) }}
                    </span>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--vcv-accent);">
                        ${{ number_format($viaje->valor_persona, 2, ',', '.') }}
                    </div>
                    <div style="font-size: 0.75rem; color: #64748b;">por persona</div>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-map-marker-alt" style="color: var(--vcv-accent); width: 16px;"></i>
                    <span style="font-weight: 600; color: var(--vcv-dark);">
                        @php
                            $origenParts = array_map('trim', explode(',', $viaje->origen_direccion));
                            $count = count($origenParts);
                            // Si tiene 3 o más partes, toma las penúltimas 2 (ciudad y provincia)
                            $origenCorta = $count >= 3 ? $origenParts[$count - 3] . ', ' . $origenParts[$count - 2] : $viaje->origen_direccion;
                            // Eliminar códigos postales (alfanuméricos como B1650, C1405, etc)
                            $origenCorta = preg_replace('/\b[A-Z]\d{4}\b\s*/i', '', $origenCorta);
                        @endphp
                        {{ $origenCorta }}
                    </span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-map-marker-alt" style="color: var(--vcv-primary); width: 16px;"></i>
                    <span style="font-weight: 600; color: var(--vcv-dark);">
                        @php
                            $destinoParts = array_map('trim', explode(',', $viaje->destino_direccion));
                            $count = count($destinoParts);
                            // Si tiene 3 o más partes, toma las penúltimas 2 (ciudad y provincia)
                            $destinoCorta = $count >= 3 ? $destinoParts[$count - 3] . ', ' . $destinoParts[$count - 2] : $viaje->destino_direccion;
                            // Eliminar códigos postales (alfanuméricos como B1650, C1405, etc)
                            $destinoCorta = preg_replace('/\b[A-Z]\d{4}\b\s*/i', '', $destinoCorta);
                        @endphp
                        {{ $destinoCorta }}
                    </span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; padding: 1rem 0; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem;">
                        <i class="far fa-calendar" style="margin-right: 4px;"></i>Fecha
                    </div>
                    <div style="font-weight: 600; color: var(--vcv-dark);">
                        {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}
                    </div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem;">
                        <i class="far fa-clock" style="margin-right: 4px;"></i>Hora
                    </div>
                    <div style="font-weight: 600; color: var(--vcv-dark);">
                        {{ \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i') }}
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                <div>
                    <div style="font-size: 0.875rem; color: #64748b;">
                        <i class="fas fa-users" style="margin-right: 4px;"></i>
                        <span style="font-weight: 600; color: var(--vcv-primary);">{{ $viaje->puestos_disponibles }}</span> de {{ $viaje->puestos_totales }} disponibles
                    </div>
                    @if($viaje->reservas->count() > 0)
                    <div style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">
                        <i class="fas fa-check-circle" style="margin-right: 4px; color: var(--vcv-accent);"></i>
                        {{ $viaje->reservas->count() }} reserva(s)
                    </div>
                    @endif
                </div>
                <a href="{{ route('conductor.viaje.detalles', $viaje->id) }}"
                   class="btn-ver-viaje"
                   style="padding: 0.5rem 1.25rem; background: var(--vcv-primary); color: white; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;">
                    Ver detalles
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@elseif($esConductor)
<div class="features-section">
    <div style="text-align: center; padding: 3rem 2rem; background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);">
        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--vcv-light) 0%, rgba(76, 175, 80, 0.1) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem; color: var(--vcv-primary);">
            <i class="fas fa-car"></i>
        </div>
        <h3 style="font-size: 1.5rem; font-weight: 600; color: var(--vcv-primary); margin-bottom: 1rem;">
            No tienes viajes publicados
        </h3>
        <p style="color: #64748b; margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
            Comienza a publicar tus viajes y conecta con pasajeros que comparten tu ruta. Es fácil y rápido.
        </p>
        <a href="{{ route('conductor.gestion') }}" class="btn-publish">
            <i class="fas fa-plus-circle"></i>
            Publicar mi primer viaje
        </a>
    </div>
</div>
@endif

<!-- How it Works -->
<div class="how-it-works">
    <div class="features-section">
        <h2 class="section-title">¿Cómo funciona?</h2>
        
        <div class="steps-container">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3 class="step-title">Busca tu viaje</h3>
                <p class="step-description">
                    Ingresa tu origen, destino y fecha. Te mostramos los viajes disponibles
                </p>
            </div>
            
            <div class="step-card">
                <div class="step-number">2</div>
                <h3 class="step-title">Elige y reserva</h3>
                <p class="step-description">
                    Revisa los perfiles, calificaciones y elige el viaje que más te convenga
                </p>
            </div>
            
            <div class="step-card">
                <div class="step-number">3</div>
                <h3 class="step-title">Paga seguro</h3>
                <p class="step-description">
                    Realiza el pago de forma segura a través de nuestra plataforma
                </p>
            </div>
            
            <div class="step-card">
                <div class="step-number">4</div>
                <h3 class="step-title">¡A viajar!</h3>
                <p class="step-description">
                    Disfruta tu viaje, conoce gente y ahorra dinero en el camino
                </p>
            </div>
        </div>
    </div>
</div>



<script>
function showSearchTab(tab) {
    // Remover active de todos
    document.querySelectorAll('.search-tab').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.search-form').forEach(el => el.classList.remove('active'));

    // Activar el seleccionado
    event.target.closest('.search-tab').classList.add('active');
    document.getElementById('form-' + tab).classList.add('active');
}

// Actualizar label de origen
function updateOrigenLabel(value) {
    const origenTexto = document.getElementById('origen-texto');
    if (value) {
        origenTexto.innerHTML = `<span>${value}</span>`;
    } else {
        origenTexto.innerHTML = '<span style="color: #94a3b8; font-weight: 400;">¿Desde dónde sales?</span>';
    }
}

// Actualizar label de destino
function updateDestinoLabel(value) {
    const destinoTexto = document.getElementById('destino-texto');
    if (value) {
        destinoTexto.innerHTML = `<span>${value}</span>`;
    } else {
        destinoTexto.innerHTML = '<span style="color: #94a3b8; font-weight: 400;">¿A dónde vas?</span>';
    }
}

// Actualizar label de fecha
function updateFechaLabel(value) {
    const fechaTexto = document.getElementById('fecha-texto');
    if (value) {
        const fecha = new Date(value + 'T00:00:00');
        const opciones = { day: '2-digit', month: '2-digit', year: 'numeric' };
        fechaTexto.textContent = fecha.toLocaleDateString('es-AR', opciones);
    } else {
        fechaTexto.textContent = 'Hoy';
    }
}

// Actualizar label de pasajeros
function updatePasajerosLabel(value) {
    const pasajerosTexto = document.getElementById('pasajeros-texto');
    const numero = parseInt(value);
    if (numero === 1) {
        pasajerosTexto.textContent = '1 pasajero';
    } else if (numero >= 5) {
        pasajerosTexto.textContent = '5+ pasajeros';
    } else {
        pasajerosTexto.textContent = numero + ' pasajeros';
    }
}

// Auto-completar fecha con hoy
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.querySelector('input[name="fecha_salida"]');
    if (fechaInput && !fechaInput.value) {
        fechaInput.value = new Date().toISOString().split('T')[0];
    }

    // Asegurar que los selects inicialicen correctamente
    const selectOrigen = document.getElementById('ciudad-origen-select');
    const selectDestino = document.getElementById('ciudad-destino-select');
    const selectPasajeros = document.getElementById('pasajeros-select');

    if (selectOrigen && selectOrigen.value) {
        updateOrigenLabel(selectOrigen.value);
    }

    if (selectDestino && selectDestino.value) {
        updateDestinoLabel(selectDestino.value);
    }

    if (selectPasajeros && selectPasajeros.value) {
        updatePasajerosLabel(selectPasajeros.value);
    }
});
</script>
@endsection