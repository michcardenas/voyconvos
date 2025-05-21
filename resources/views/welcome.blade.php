@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    {{-- HERO --}}
    <section class="hero position-relative">
        <div class="hero-background"
             style="background: linear-gradient(rgba(31, 78, 121, 0.7), rgba(31, 78, 121, 0.4)), 
             url('{{ asset(\App\Models\Contenido::get('hero', 'background', 'img/fondo.jpg')) }}'); 
             background-size: cover; background-position: center;">
        </div>

        <div class="container hero-content position-relative text-white">
            <div class="hero-text text-center">
                <h1>{{ \App\Models\Contenido::get('hero', 'h1') }}</h1>
                <h2>{{ \App\Models\Contenido::get('hero', 'h2') }}</h2>
                <div class="hero-buttons mt-3">
                    <a href="#" class="btn btn-primary me-2">
                        {{ \App\Models\Contenido::get('hero', 'btn_buscar') }}
                    </a>
                    <a href="#" class="btn btn-outline-light">
                        {{ \App\Models\Contenido::get('hero', 'btn_publicar') }}
                    </a>
                </div>
            </div>

            <div class="search-box mt-5 bg-white text-dark p-4 rounded shadow">
                {{-- Puedes mantener inputs fijos o dinámicos si deseas --}}
                <div class="savings mt-3">
                    <h3>{{ \App\Models\Contenido::get('hero', 'ahorro_texto') }}
                        <span class="highlight">{{ \App\Models\Contenido::get('hero', 'ahorro_valor') }}</span>
                        {{ \App\Models\Contenido::get('hero', 'ahorro_sufijo') }}
                    </h3>
                </div>

                <button class="publish-trip-btn btn btn-success mt-3">
                    {{ \App\Models\Contenido::get('hero', 'btn_publicar_main') }}
                </button>
                <a href="#" class="como-funciona d-block mt-2">
                    {{ \App\Models\Contenido::get('hero', 'como_funciona') }} <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section class="features py-5 text-center">
        <div class="container">
            <h2 class="mb-5">{{ \App\Models\Contenido::get('features', 'titulo') }}</h2>

            <div class="feature-cards">
                @for ($i = 1; $i <= 3; $i++)
                    <div class="feature-card">
                        <div class="feature-icon mb-3">
                            <i class="fas {{ \App\Models\Contenido::get('features', 'feature_' . $i . '_icon') }} fa-2x text-primary"></i>
                        </div>
                        <h3>{{ \App\Models\Contenido::get('features', 'feature_' . $i . '_titulo') }}</h3>
                        <p>{{ \App\Models\Contenido::get('features', 'feature_' . $i . '_texto') }}</p>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- SLOGAN --}}
    <section class="slogan py-5 text-center bg-white">
        <div class="container">
            <h2>{{ \App\Models\Contenido::get('slogan', 'titulo') }}</h2>
            <a href="#" class="btn btn-primary">{{ \App\Models\Contenido::get('slogan', 'boton') }}</a>
        </div>
    </section>
@endsection
