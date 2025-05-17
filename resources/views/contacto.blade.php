@extends('layouts.app')

@section('title', 'Contacto - VoyConVos')

@section('content')
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

    <!-- FAQs -->
    <section class="faq-section">
        <div class="container">
            <h2>Preguntas frecuentes</h2>
            <div class="faq-container">
                @foreach([
                    ['¿Cómo funciona VoyConVos?', 'Conecta a conductores con pasajeros que viajan en la misma dirección.'],
                    ['¿Cómo me registro?', 'Haz clic en "Registrarse" y completa el formulario.'],
                    ['¿Cómo se realizan los pagos?', 'Se procesan a través de nuestra plataforma de forma segura.'],
                    ['¿Es seguro viajar con desconocidos?', 'Sí, usamos verificación de identidad y calificaciones de usuarios.']
                ] as [$question, $answer])
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>{{ $question }}</h3>
                            <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>{{ $answer }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
