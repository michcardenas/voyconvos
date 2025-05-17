@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <!-- Hero Section -->
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
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2>¿Por qué elegir VoyConVos?</h2>
            <div class="feature-cards">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-coins"></i></div>
                    <h3>Ahorra en cada viaje</h3>
                    <p>Comparte los gastos de gasolina y peajes con otros viajeros</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-users"></i></div>
                    <h3>Conoce nuevas personas</h3>
                    <p>Conecta con gente que comparte tu ruta e intereses</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-leaf"></i></div>
                    <h3>Cuida el medio ambiente</h3>
                    <p>Reduce la contaminación compartiendo vehículo</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Slogan Section -->
    <section class="slogan">
        <div class="container">
            <h2>Conduce. Comparte. Ahorra.</h2>
            <a href="#" class="btn btn-primary">Publica un viaje</a>
        </div>
    </section>
@endsection
