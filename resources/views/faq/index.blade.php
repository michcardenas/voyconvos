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
        max-width: 900px;
    }

    .faq-header {
        background: linear-gradient(135deg, var(--vcv-primary) 0%, rgba(31, 78, 121, 0.9) 50%, rgba(58, 58, 58, 0.8) 100%);
        color: white;
        border-radius: 20px;
        padding: 3rem 2rem;
        margin-bottom: 3rem;
        box-shadow: 0 8px 24px rgba(31, 78, 121, 0.15);
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
        background: radial-gradient(circle, rgba(76, 175, 80, 0.2) 0%, transparent 70%);
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

    .search-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
    }

    .search-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .search-header h3 {
        color: var(--vcv-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .search-header p {
        color: rgba(58, 58, 58, 0.7);
        margin: 0;
    }

    .search-container {
        position: relative;
        max-width: 500px;
        margin: 0 auto;
    }

    .search-input {
        width: 100%;
        padding: 1.2rem 1.5rem 1.2rem 3.5rem;
        border: 2px solid rgba(31, 78, 121, 0.2);
        border-radius: 25px;
        font-size: 1rem;
        background: white;
        color: var(--vcv-dark);
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.1);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--vcv-primary);
        box-shadow: 0 4px 16px rgba(31, 78, 121, 0.2);
        transform: translateY(-2px);
    }

    .search-input::placeholder {
        color: rgba(58, 58, 58, 0.5);
    }

    .search-icon {
        position: absolute;
        left: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(31, 78, 121, 0.6);
        font-size: 1.1rem;
    }

    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid rgba(31, 78, 121, 0.08);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(31, 78, 121, 0.12);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--vcv-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: rgba(58, 58, 58, 0.7);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .categories-section {
        margin-bottom: 2rem;
    }

    .category-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
        margin-bottom: 2rem;
    }

    .category-tab {
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid rgba(31, 78, 121, 0.2);
        border-radius: 25px;
        padding: 0.8rem 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        color: var(--vcv-dark);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .category-tab:hover,
    .category-tab.active {
        background: var(--vcv-primary);
        color: white;
        border-color: var(--vcv-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.3);
        text-decoration: none;
    }

    .faq-list {
        display: grid;
        gap: 1rem;
    }

    .faq-item {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(31, 78, 121, 0.12);
    }

    .faq-item.hidden {
        display: none;
    }

    .faq-question {
        padding: 1.5rem 2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(221, 242, 254, 0.3);
        border-bottom: 1px solid rgba(31, 78, 121, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .faq-question::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(31, 78, 121, 0.05), transparent);
        transition: left 0.5s;
    }

    .faq-question:hover::before {
        left: 100%;
    }

    .faq-question:hover {
        background: rgba(221, 242, 254, 0.5);
    }

    .faq-question.active {
        background: rgba(31, 78, 121, 0.1);
        border-bottom-color: var(--vcv-primary);
    }

    .question-content {
        display: flex;
        align-items: center;
        flex: 1;
        position: relative;
        z-index: 2;
    }

    .question-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--vcv-primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .question-text {
        font-weight: 600;
        color: var(--vcv-dark);
        font-size: 1.1rem;
        margin: 0;
    }

    .toggle-icon {
        font-size: 1.2rem;
        color: var(--vcv-primary);
        transition: transform 0.3s ease;
        position: relative;
        z-index: 2;
    }

    .faq-question.active .toggle-icon {
        transform: rotate(180deg);
    }

    .faq-answer {
        padding: 0;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
    }

    .faq-answer.active {
        padding: 2rem;
        max-height: 500px;
    }

    .answer-content {
        color: rgba(58, 58, 58, 0.8);
        line-height: 1.6;
        font-size: 1rem;
        margin: 0;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .contact-section {
        background: linear-gradient(135deg, var(--vcv-accent) 0%, rgba(76, 175, 80, 0.9) 50%, rgba(31, 78, 121, 0.8) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-top: 3rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .contact-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .contact-section h3 {
        margin: 0 0 1rem 0;
        font-weight: 600;
        position: relative;
        z-index: 2;
    }

    .contact-section p {
        margin: 0 0 1.5rem 0;
        opacity: 0.95;
        position: relative;
        z-index: 2;
    }

    .contact-btn {
        background: white;
        color: var(--vcv-primary);
        border: none;
        border-radius: 25px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
    }

    .contact-btn:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        color: var(--vcv-primary);
        text-decoration: none;
    }

    .no-results {
        text-align: center;
        padding: 3rem 2rem;
        color: rgba(58, 58, 58, 0.6);
    }

    .no-results i {
        font-size: 3rem;
        color: rgba(31, 78, 121, 0.3);
        margin-bottom: 1rem;
    }

    .no-results h4 {
        color: var(--vcv-primary);
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .faq-wrapper {
            padding: 1rem 0;
        }
        
        .faq-header {
            padding: 2rem 1.5rem;
        }
        
        .faq-header h1 {
            font-size: 2rem;
        }
        
        .search-section {
            padding: 1.5rem;
        }
        
        .stats-section {
            grid-template-columns: 1fr 1fr;
            gap: 0.8rem;
        }
        
        .category-tabs {
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }
        
        .faq-question {
            padding: 1rem 1.5rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .question-content {
            width: 100%;
        }
        
        .question-text {
            font-size: 1rem;
        }
        
        .faq-answer.active {
            padding: 1.5rem;
        }
    }
</style>

<div class="faq-wrapper">
    <div class="container">
        <!-- FAQ Header -->
        <div class="faq-header">
            <h1>❓ Preguntas Frecuentes</h1>
            <p class="faq-subtitle">Encuentra respuestas rápidas a las dudas más comunes sobre nuestro servicio</p>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <div class="search-header">
                <h3>🔍 Busca tu pregunta</h3>
                <p>Escribe palabras clave para encontrar respuestas rápidamente</p>
            </div>
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    class="search-input" 
                    placeholder="Ej: reserva, pago, cancelación..." 
                    id="searchInput"
                    onkeyup="buscarFAQ()"
                >
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number" id="totalFaqs">{{ count($faqs) }}</div>
                <div class="stat-label">Preguntas disponibles</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">⚡</div>
                <div class="stat-label">Respuestas instantáneas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Disponible siempre</div>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="categories-section">
            <div class="category-tabs">
                <button class="category-tab active" onclick="filtrarCategoria('todas')">
                    <i class="fas fa-list"></i>
                    Todas
                </button>
                <button class="category-tab" onclick="filtrarCategoria('reservas')">
                    <i class="fas fa-ticket-alt"></i>
                    Reservas
                </button>
                <button class="category-tab" onclick="filtrarCategoria('pagos')">
                    <i class="fas fa-credit-card"></i>
                    Pagos
                </button>
                <button class="category-tab" onclick="filtrarCategoria('viajes')">
                    <i class="fas fa-car"></i>
                    Viajes
                </button>
                <button class="category-tab" onclick="filtrarCategoria('cuenta')">
                    <i class="fas fa-user"></i>
                    Cuenta
                </button>
                <button class="category-tab" onclick="filtrarCategoria('seguridad')">
                    <i class="fas fa-shield-alt"></i>
                    Seguridad
                </button>
            </div>
        </div>

        <!-- FAQ List -->
        <div class="faq-list" id="faqList">
            @foreach($faqs as $index => $faq)
                <div class="faq-item" data-categoria="{{ strtolower($faq->categoria ?? 'general') }}">
                    <div class="faq-question" onclick="toggleFAQ({{ $index }})">
                        <div class="question-content">
                            <div class="question-icon">
                                @php
                                    $categoria = strtolower($faq->categoria ?? 'general');
                                    $iconos = [
                                        'reservas' => 'fas fa-ticket-alt',
                                        'pagos' => 'fas fa-credit-card',
                                        'viajes' => 'fas fa-car',
                                        'cuenta' => 'fas fa-user',
                                        'seguridad' => 'fas fa-shield-alt',
                                        'general' => 'fas fa-question'
                                    ];
                                    $icono = $iconos[$categoria] ?? 'fas fa-question';
                                @endphp
                                <i class="{{ $icono }}"></i>
                            </div>
                            <h4 class="question-text">{{ $faq->pregunta }}</h4>
                        </div>
                        <i class="fas fa-chevron-down toggle-icon"></i>
                    </div>
                    <div class="faq-answer" id="answer-{{ $index }}">
                        <p class="answer-content">{{ $faq->respuesta }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results -->
        <div class="no-results" id="noResults" style="display: none;">
            <i class="fas fa-search-minus"></i>
            <h4>No encontramos resultados</h4>
            <p>Intenta con diferentes palabras clave o contáctanos directamente</p>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <h3>💬 ¿No encontraste lo que buscabas?</h3>
            <p>Nuestro equipo de soporte está listo para ayudarte con cualquier duda</p>
            <a href="#" class="contact-btn">
                <i class="fas fa-headset"></i>
                Contactar Soporte
            </a>
        </div>
    </div>
</div>

<script>
let faqsData = [];

// Inicializar datos de FAQs
function initFAQs() {
    @foreach($faqs as $index => $faq)
        faqsData.push({
            index: {{ $index }},
            pregunta: "{{ addslashes($faq->pregunta) }}",
            respuesta: "{{ addslashes($faq->respuesta) }}",
            categoria: "{{ strtolower($faq->categoria ?? 'general') }}"
        });
    @endforeach
}

// Toggle FAQ accordion
function toggleFAQ(index) {
    const question = document.querySelector(`[onclick="toggleFAQ(${index})"]`);
    const answer = document.getElementById(`answer-${index}`);
    
    // Cerrar todos los otros FAQs
    document.querySelectorAll('.faq-question.active').forEach(q => {
        if (q !== question) {
            q.classList.remove('active');
            const answerId = q.getAttribute('onclick').match(/\d+/)[0];
            document.getElementById(`answer-${answerId}`).classList.remove('active');
        }
    });
    
    // Toggle el FAQ actual
    question.classList.toggle('active');
    answer.classList.toggle('active');
}

// Buscar FAQs
function buscarFAQ() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const faqItems = document.querySelectorAll('.faq-item');
    let visibleCount = 0;
    
    faqItems.forEach(item => {
        const pregunta = item.querySelector('.question-text').textContent.toLowerCase();
        const respuesta = item.querySelector('.answer-content').textContent.toLowerCase();
        
        if (pregunta.includes(searchTerm) || respuesta.includes(searchTerm) || searchTerm === '') {
            item.classList.remove('hidden');
            visibleCount++;
        } else {
            item.classList.add('hidden');
        }
    });
    
    // Mostrar/ocultar mensaje de "no resultados"
    const noResults = document.getElementById('noResults');
    if (visibleCount === 0 && searchTerm !== '') {
        noResults.style.display = 'block';
    } else {
        noResults.style.display = 'none';
    }
}

// Filtrar por categoría
function filtrarCategoria(categoria) {
    // Actualizar tabs activos
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Filtrar FAQs
    const faqItems = document.querySelectorAll('.faq-item');
    let visibleCount = 0;
    
    faqItems.forEach(item => {
        const itemCategoria = item.getAttribute('data-categoria');
        
        if (categoria === 'todas' || itemCategoria === categoria) {
            item.classList.remove('hidden');
            visibleCount++;
        } else {
            item.classList.add('hidden');
        }
    });
    
    // Limpiar búsqueda
    document.getElementById('searchInput').value = '';
    document.getElementById('noResults').style.display = 'none';
}

// Cerrar FAQs al hacer clic fuera
document.addEventListener('click', function(e) {
    if (!e.target.closest('.faq-item')) {
        document.querySelectorAll('.faq-question.active').forEach(q => {
            q.classList.remove('active');
            const answerId = q.getAttribute('onclick').match(/\d+/)[0];
            document.getElementById(`answer-${answerId}`).classList.remove('active');
        });
    }
});

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    initFAQs();
});

// Función para expandir todos los FAQs (útil para testing)
function expandirTodos() {
    document.querySelectorAll('.faq-question').forEach((q, index) => {
        q.classList.add('active');
        document.getElementById(`answer-${index}`).classList.add('active');
    });
}

// Función para colapsar todos los FAQs
function colapsarTodos() {
    document.querySelectorAll('.faq-question.active').forEach(q => {
        q.classList.remove('active');
        const answerId = q.getAttribute('onclick').match(/\d+/)[0];
        document.getElementById(`answer-${answerId}`).classList.remove('active');
    });
}
</script>
@endsection