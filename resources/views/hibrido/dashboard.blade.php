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

/* Ocultar visualización del select */
.field-select {
    color: transparent;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    opacity: 0;
    appearance: none;
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

/* Responsive */
@media (max-width: 992px) {
    .search-bar {
        flex-wrap: wrap;
    }
    
    .search-field {
        min-width: 45%;
    }
    
    .field-separator {
        display: none;
    }
    
    .btn-search-bar {
        width: 100%;
        margin-top: 1rem;
        margin-left: 0;
    }
}

@media (max-width: 768px) {
    .search-box {
        padding: 1.5rem;
    }
    
    .search-bar {
        flex-direction: column;
        padding: 1rem;
        gap: 1rem;
    }
    
    .search-field {
        width: 100%;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 1rem;
    }
    
    .search-field:last-of-type {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .search-tabs {
        flex-direction: column;
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
        <div class="search-bar">
            <!-- De -->
            <div class="search-field">
                <label class="field-label">De</label>
                <input type="text" 
                       name="origen" 
                       class="field-input" 
                       placeholder="¿Desde dónde sales?" 
                       required>
            </div>
            
            <div class="field-separator"></div>
            
            <!-- A -->
            <div class="search-field">
                <label class="field-label">A</label>
                <input type="text" 
                       name="destino" 
                       class="field-input" 
                       placeholder="¿A dónde vas?" 
                       required>
            </div>
            
            <div class="field-separator"></div>
            
            <!-- Fecha -->
            <div class="search-field">
                <label class="field-label">
                    <i class="far fa-calendar-alt" style="margin-right: 4px;"></i>
                    Hoy
                </label>
                <input type="date" 
                       name="fecha" 
                       class="field-input field-date" 
                       min="{{ date('Y-m-d') }}"
                       value="{{ date('Y-m-d') }}"
                       required>
            </div>
            
            <div class="field-separator"></div>
            
            <!-- Pasajeros -->
            <div class="search-field">
                <label class="field-label">
                    <i class="fas fa-user" style="margin-right: 4px;"></i>
                    <span id="pasajeros-texto">1 pasajero</span>
                </label>
                <select name="pasajeros" id="pasajeros-select" class="field-input field-select">
                    <option value="1">1 pasajero</option>
                    <option value="2">2 pasajeros</option>
                    <option value="3">3 pasajeros</option>
                    <option value="4">4 pasajeros</option>
                    <option value="5">5+ pasajeros</option>
                </select>
            </div>
            
            <div class="field-separator"></div>
            
            <!-- Botón Buscar -->
            <button type="submit" class="btn-search-bar">
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

<!-- Features Section -->
<div class="features-section">
    <h2 class="section-title">¿Por qué viajar con VoyConVos?</h2>
    
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-piggy-bank"></i>
            </div>
            <h3 class="feature-title">Ahorra dinero</h3>
            <p class="feature-description">
                Comparte los gastos del viaje y ahorra hasta un 70% comparado con otros medios de transporte
            </p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="feature-title">Conoce gente nueva</h3>
            <p class="feature-description">
                Conecta con personas que comparten tu ruta y haz del viaje una experiencia memorable
            </p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-leaf"></i>
            </div>
            <h3 class="feature-title">Cuida el planeta</h3>
            <p class="feature-description">
                Reduce tu huella de carbono viajando juntos. Menos autos, menos contaminación
            </p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="feature-title">Viaja seguro</h3>
            <p class="feature-description">
                Todos los conductores están verificados. Revisa calificaciones y comentarios antes de reservar
            </p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h3 class="feature-title">Flexibilidad total</h3>
            <p class="feature-description">
                Elige el horario que mejor te convenga. Hay viajes disponibles todos los días
            </p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <h3 class="feature-title">Fácil de usar</h3>
            <p class="feature-description">
                Reserva en segundos desde tu computadora o celular. Sin complicaciones
            </p>
        </div>
    </div>
</div>

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

// Auto-completar fecha con hoy
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.querySelector('input[name="fecha"]');
    if (fechaInput && !fechaInput.value) {
        fechaInput.value = new Date().toISOString().split('T')[0];
    }
});
</script>
@endsection