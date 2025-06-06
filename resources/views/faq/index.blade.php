@extends('layouts.app')

@section('title', 'Preguntas Frecuentes')

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-bg: #FCFCFD;
    }

    .faq-wrapper {
        background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgba(31, 78, 121, 0.03) 100%);
        min-height: 100vh;
        padding: 6rem 0 2rem 0;
        position: relative;
    }

    .faq-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(76, 175, 80, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(31, 78, 121, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    .container {
        position: relative;
        z-index: 1;
        max-width: 1200px;
    }

    .faq-header {
        background: linear-gradient(135deg, var(--vcv-accent) 0%, rgba(76, 175, 80, 0.9) 50%, rgba(31, 78, 121, 0.8) 100%);
        color: white;
        border-radius: 20px;
        padding: 3rem 2rem;
        margin-bottom: 3rem;
        box-shadow: 0 8px 24px rgba(76, 175, 80, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .faq-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(40%, -40%);
    }

    .faq-header h1 {
        margin: 0 0 1rem 0;
        font-weight: 700;
        font-size: 2.5rem;
        position: relative;
        z-index: 2;
    }

    .faq-subtitle {
        margin: 0;
        opacity: 0.95;
        font-size: 1.2rem;
        position: relative;
        z-index: 2;
        font-weight: 300;
    }

    .faq-stats {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 1.5rem;
        position: relative;
        z-index: 2;
        flex-wrap: wrap;
    }

    .faq-stat {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .faq-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid rgba(76, 175, 80, 0.12);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.08);
        margin-bottom: 2rem;
    }

    .faq-item {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(76, 175, 80, 0.15);
        border-radius: 12px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(76, 175, 80, 0.05);
    }

    .faq-item:hover {
        box-shadow: 0 4px 16px rgba(76, 175, 80, 0.1);
        transform: translateY(-1px);
    }

    .faq-question {
        padding: 1.5rem 2rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(76, 175, 80, 0.05);
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(76, 175, 80, 0.1);
    }

    .faq-question:hover {
        background: rgba(76, 175, 80, 0.1);
    }

    .faq-question.active {
        background: rgba(76, 175, 80, 0.15);
    }

    .faq-question h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--vcv-primary);
        margin: 0;
        flex: 1;
        line-height: 1.4;
    }

    .faq-toggle {
        width: 32px;
        height: 32px;
        background: var(--vcv-accent);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        font-weight: 300;
        flex-shrink: 0;
        margin-left: 1rem;
    }

    .faq-question.active .faq-toggle {
        background: var(--vcv-primary);
        transform: rotate(45deg);
    }

    .faq-answer {
        padding: 0;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }

    .faq-answer.active {
        padding: 1.5rem 2rem;
        max-height: 300px;
    }

    .faq-answer p {
        color: rgba(58, 58, 58, 0.8);
        line-height: 1.6;
        margin: 0;
        font-size: 0.95rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: rgba(58, 58, 58, 0.6);
    }

    .empty-state i {
        font-size: 4rem;
        color: rgba(76, 175, 80, 0.3);
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: var(--vcv-accent);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .empty-state p {
        margin: 0;
    }

    @media (max-width: 768px) {
        .faq-wrapper {
            padding: 5rem 0 2rem 0;
        }
        
        .faq-header {
            padding: 2rem 1.5rem;
        }
        
        .faq-header h1 {
            font-size: 2rem;
        }
        
        .faq-container {
            padding: 1.5rem;
        }
        
        .faq-question {
            padding: 1.2rem 1.5rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .faq-toggle {
            margin-left: 0;
            align-self: flex-end;
        }
        
        .faq-answer.active {
            padding: 1.2rem 1.5rem;
        }
        
        .faq-stats {
            gap: 1rem;
        }
        
        .faq-stat {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
    }
</style>

<div class="faq-wrapper">
    <div class="container">
        <!-- FAQ Header -->
        <div class="faq-header">
            <h1>{{ \App\Models\Contenido::get('faq-header', 'titulo') }}</h1>
            <p class="faq-subtitle">
                {{ \App\Models\Contenido::get('faq-header', 'subtitulo') }}
            </p>

            <div class="faq-stats">
                <div class="faq-stat">
                    <i class="fas fa-question-circle"></i>
                    {{ \App\Models\Contenido::get('faq-header', 'cantidad') ?? 0 }} Preguntas
                </div>
                <div class="faq-stat">
                    <i class="fas fa-clock"></i>
                    {{ \App\Models\Contenido::get('faq-header', 'respuesta_inmediata') }}
                </div>
                <div class="faq-stat">
                    <i class="fas fa-users"></i>
                    {{ \App\Models\Contenido::get('faq-header', 'soporte') }}
                </div>
            </div>
        </div>

        <!-- FAQ Content -->
        @php
            $faqs = \App\Models\Seccion::where('slug', 'like', 'faq-%')
                        ->where('slug', '!=', 'faq-header')
                        ->with('contenidos')
                        ->get();
        @endphp

        @if($faqs->count())
            <div class="faq-container">
                @foreach($faqs as $index => $faq)
                    @php
                        $pregunta = $faq->contenidos->where('clave', 'pregunta')->first()?->valor;
                        $respuesta = $faq->contenidos->where('clave', 'respuesta')->first()?->valor;
                    @endphp

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ({{ $index }})">
                            <h3>{{ $pregunta }}</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer" id="faq-answer-{{ $index }}">
                            <p>{{ $respuesta }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="faq-container">
                <div class="empty-state">
                    <i class="fas fa-question-circle"></i>
                    <h3>Preguntas Frecuentes</h3>
                    <p>Estamos preparando las preguntas más frecuentes.<br>¡Pronto tendrás todas las respuestas que necesitas!</p>
                </div>
            </div>
        @endif
    </div>
</div>



<script>
function toggleFAQ(index) {
    const question = document.querySelector(`[onclick="toggleFAQ(${index})"]`);
    const answer = document.getElementById(`faq-answer-${index}`);
    
    // Cerrar todos los otros FAQs
    document.querySelectorAll('.faq-question.active').forEach(q => {
        if (q !== question) {
            q.classList.remove('active');
            const answerId = q.getAttribute('onclick').match(/\d+/)[0];
            document.getElementById(`faq-answer-${answerId}`).classList.remove('active');
        }
    });
    
    // Toggle el FAQ actual
    question.classList.toggle('active');
    answer.classList.toggle('active');
}

// Funcionalidad adicional para mejorar UX
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll si hay enlaces internos
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection