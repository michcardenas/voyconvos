@extends('layouts.app')

@section('title', 'Contacto - VoyConVos')

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-bg: #FCFCFD;
    }

    .contact-wrapper {
        background: linear-gradient(145deg, rgba(221, 242, 254, 0.3) 0%, rgba(252, 252, 253, 0.8) 40%, rgba(31, 78, 121, 0.02) 100%);
        min-height: 100vh;
        padding-top: 5rem;
        position: relative;
    }

    .contact-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 10%, rgba(76, 175, 80, 0.03) 0%, transparent 40%),
            radial-gradient(circle at 80% 90%, rgba(31, 78, 121, 0.04) 0%, transparent 40%);
        pointer-events: none;
    }

    .container {
        position: relative;
        z-index: 1;
        max-width: 1200px;
    }

    /* Hero Section */
    .page-hero {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(221, 242, 254, 0.7) 50%, rgba(252, 252, 253, 0.9) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(31, 78, 121, 0.08);
        border-radius: 24px;
        padding: 4rem 2.5rem;
        margin-bottom: 3rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 
            0 8px 32px rgba(31, 78, 121, 0.06),
            0 1px 2px rgba(31, 78, 121, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.7);
    }

    .page-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.05) 0%, transparent 60%);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite;
    }

    .page-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(31, 78, 121, 0.04) 0%, transparent 60%);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }

    .page-hero h1 {
        font-size: 3.2rem;
        font-weight: 700;
        color: var(--vcv-primary);
        margin: 0 0 1.5rem 0;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 4px rgba(31, 78, 121, 0.1);
        letter-spacing: -0.02em;
    }

    .page-hero p {
        font-size: 1.2rem;
        color: rgba(58, 58, 58, 0.8);
        margin: 0;
        position: relative;
        z-index: 2;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Contact Section */
    .contact-section {
        margin-bottom: 3rem;
    }

    .contact-container {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 3rem;
        align-items: start;
    }

    .contact-info {
        display: grid;
        gap: 1.5rem;
    }

    .info-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(221, 242, 254, 0.4) 100%);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(31, 78, 121, 0.08);
        border-radius: 20px;
        padding: 2rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(31, 78, 121, 0.04);
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--vcv-primary), var(--vcv-accent));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(31, 78, 121, 0.08);
        border-color: rgba(31, 78, 121, 0.15);
    }

    .info-card:hover::before {
        opacity: 1;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(31, 78, 121, 0.08) 0%, rgba(76, 175, 80, 0.05) 100%);
        border: 2px solid rgba(31, 78, 121, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--vcv-primary);
        font-size: 1.4rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .info-card:hover .info-icon {
        background: linear-gradient(135deg, rgba(31, 78, 121, 0.12) 0%, rgba(76, 175, 80, 0.08) 100%);
        border-color: rgba(31, 78, 121, 0.2);
        transform: scale(1.05);
    }

    .info-card h3 {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--vcv-primary);
        margin: 0 0 0.8rem 0;
    }

    .info-card p {
        color: rgba(58, 58, 58, 0.8);
        margin: 0;
        font-weight: 400;
        line-height: 1.5;
    }

    /* Contact Form */
    .contact-form-container {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(221, 242, 254, 0.3) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(31, 78, 121, 0.08);
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 
            0 8px 32px rgba(31, 78, 121, 0.06),
            0 1px 2px rgba(31, 78, 121, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.7);
    }

    .contact-form-container h2 {
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--vcv-primary);
        margin: 0 0 2rem 0;
        text-align: center;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--vcv-dark);
        font-size: 0.95rem;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 1rem 1.2rem;
        border: 1.5px solid rgba(31, 78, 121, 0.15);
        border-radius: 12px;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.9);
        color: var(--vcv-dark);
        transition: all 0.3s ease;
        font-family: inherit;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--vcv-primary);
        box-shadow: 0 0 0 3px rgba(31, 78, 121, 0.1);
        background: white;
    }

    .form-group textarea {
        min-height: 120px;
        line-height: 1.6;
    }

    .submit-btn {
        background: linear-gradient(135deg, var(--vcv-accent) 0%, rgba(76, 175, 80, 0.9) 100%);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 1rem 2.5rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
        position: relative;
        overflow: hidden;
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .contact-container {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .contact-info {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .contact-wrapper {
            padding-top: 4rem;
        }
        
        .page-hero {
            padding: 3rem 2rem;
            margin-bottom: 2rem;
        }
        
        .page-hero h1 {
            font-size: 2.5rem;
        }
        
        .page-hero p {
            font-size: 1.1rem;
        }
        
        .contact-info {
            grid-template-columns: 1fr;
        }
        
        .info-card {
            padding: 1.5rem;
        }
        
        .contact-form-container {
            padding: 2rem;
        }
    }

    @media (max-width: 480px) {
        .page-hero {
            padding: 2rem 1.5rem;
        }
        
        .page-hero h1 {
            font-size: 2rem;
        }
        
        .contact-form-container {
            padding: 1.5rem;
        }
    }
</style>

<div class="contact-wrapper">
    <!-- Hero Section para Contacto -->
    <section class="page-hero">
        <div class="container">
            <h1>{{ \App\Models\Contenido::getValor('hero-contacto', 'titulo') }}</h1>
            <p>{{ \App\Models\Contenido::getValor('hero-contacto', 'texto') }}</p>
        </div>
    </section>

    <!-- Sección de Contacto -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-container">
                <div class="contact-info">
                    <div class="info-card">
                        <div class="info-icon">📍</div>
                        <h3>Ubicación</h3>
                        <p>{{ \App\Models\Contenido::getValor('info-contacto', 'ubicacion') }}</p>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">✉️</div>
                        <h3>Email</h3>
                        <p>{{ \App\Models\Contenido::getValor('info-contacto', 'email') }}</p>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">📞</div>
                        <h3>Teléfono</h3>
                        <p>{{ \App\Models\Contenido::getValor('info-contacto', 'telefono') }}</p>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">🕒</div>
                        <h3>Horario</h3>
                        <p>{{ \App\Models\Contenido::getValor('info-contacto', 'horario') }}</p>
                    </div>
                </div>

                <div class="contact-form-container">
                    <h2>{{ \App\Models\Contenido::getValor('form-contacto', 'titulo') }}</h2>
                    <form class="contact-form" action="#" method="POST" onsubmit="enviarFormulario(event)">
                        @csrf
                        <div class="form-group">
                            <label for="nombre">{{ \App\Models\Contenido::getValor('form-contacto', 'label_nombre') }}</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="email">{{ \App\Models\Contenido::getValor('form-contacto', 'label_email') }}</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="asunto">{{ \App\Models\Contenido::getValor('form-contacto', 'label_asunto') }}</label>
                            <input type="text" id="asunto" name="asunto" required>
                        </div>
                        <div class="form-group">
                            <label for="mensaje">{{ \App\Models\Contenido::getValor('form-contacto', 'label_mensaje') }}</label>
                            <textarea id="mensaje" name="mensaje" rows="5" required placeholder="{{ \App\Models\Contenido::getValor('form-contacto', 'placeholder') }}"></textarea>
                        </div>
                        <button type="submit" class="submit-btn">
                            {{ \App\Models\Contenido::getValor('form-contacto', 'boton') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
function enviarFormulario(event) {
    event.preventDefault();
    
    // Simular envío del formulario
    const submitBtn = event.targetValor.querySelector('.submit-btn');
    const originalText = submitBtn.innerHTML;
    
    // Cambiar el botón durante el "envío"
    submitBtn.innerHTML = '⏳ Enviando...';
    submitBtn.disabled = true;
    
    setTimeout(() => {
        // Simular respuesta exitosa
        submitBtn.innerHTML = '✅ ¡Mensaje enviado!';
        submitBtn.style.background = 'linear-gradient(135deg, var(--vcv-accent) 0%, rgba(76, 175, 80, 0.9) 100%)';
        
        // Limpiar el formulario
        event.targetValor.reset();
        
        setTimeout(() => {
            // Restaurar el botón
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            submitBtn.style.background = '';
        }, 3000);
        
        // Mostrar notificación
        mostrarNotificacion('¡Gracias por contactarnos! Te responderemos pronto.', 'success');
    }, 2000);
}

function mostrarNotificacion(mensaje, tipo = 'success') {
    const notification = document.createElement('div');
    notification.textContent = mensaje;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${tipo === 'success' ? 'var(--vcv-accent)' : 'var(--vcv-primary)'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 500;
        z-index: 10000;
        box-shadow: 0 8px 24px rgba(31, 78, 121, 0.2);
        backdrop-filter: blur(10px);
        animation: slideIn 0.3s ease;
        max-width: 300px;
    `;
    
    // Agregar animación CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            notification.remove();
            style.remove();
        }, 300);
    }, 4000);
}

// Validación en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '' && this.hasAttribute('required')) {
                this.style.borderColor = '#ff6b6b';
                this.style.boxShadow = '0 0 0 3px rgba(255, 107, 107, 0.1)';
            } else {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            }
        });
        
        input.addEventListener('input', function() {
            if (this.style.borderColor === 'rgb(255, 107, 107)') {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            }
        });
    });
});

// Smooth scroll para enlaces internos
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetValor = document.querySelector(this.getValorAttribute('href'));
            if (targetValor) {
                targetValor.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection