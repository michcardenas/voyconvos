@extends('layouts.app')

@section('title', 'Política de Privacidad')

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-bg: #FCFCFD;
        --privacy-accent: #9C27B0;
    }

    .privacy-wrapper {
        background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgba(156, 39, 176, 0.03) 100%);
        min-height: 100vh;
        padding: 6rem 0 2rem 0;
        position: relative;
    }

    .privacy-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(156, 39, 176, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(31, 78, 121, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    .container {
        position: relative;
        z-index: 1;
        max-width: 1200px;
    }

    .privacy-header {
        background: linear-gradient(135deg, var(--privacy-accent) 0%, rgba(156, 39, 176, 0.9) 50%, rgba(31, 78, 121, 0.8) 100%);
        color: white;
        border-radius: 20px;
        padding: 3rem 2rem;
        margin-bottom: 3rem;
        box-shadow: 0 8px 24px rgba(156, 39, 176, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .privacy-header::before {
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

    .privacy-header h1 {
        margin: 0 0 1rem 0;
        font-weight: 700;
        font-size: 2.5rem;
        position: relative;
        z-index: 2;
    }

    .privacy-subtitle {
        margin: 0;
        opacity: 0.95;
        font-size: 1.2rem;
        position: relative;
        z-index: 2;
        font-weight: 300;
    }

    .last-updated {
        background: rgba(156, 39, 176, 0.2);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 1rem;
        position: relative;
        z-index: 2;
    }

    .protection-badges {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1.5rem;
        position: relative;
        z-index: 2;
        flex-wrap: wrap;
    }

    .protection-badge {
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

    .tools-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(156, 39, 176, 0.12);
        box-shadow: 0 4px 12px rgba(156, 39, 176, 0.08);
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }

    .search-container {
        position: relative;
        flex: 1;
        min-width: 250px;
    }

    .search-input {
        width: 100%;
        padding: 0.8rem 1rem 0.8rem 2.5rem;
        border: 2px solid rgba(156, 39, 176, 0.2);
        border-radius: 20px;
        font-size: 0.9rem;
        background: white;
        color: var(--vcv-dark);
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--privacy-accent);
        box-shadow: 0 2px 8px rgba(156, 39, 176, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 0.8rem;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(156, 39, 176, 0.6);
    }

    .tools-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .tool-btn {
        background: rgba(156, 39, 176, 0.1);
        color: var(--privacy-accent);
        border: none;
        border-radius: 15px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .tool-btn:hover {
        background: var(--privacy-accent);
        color: white;
        transform: translateY(-1px);
    }

    .content-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2rem;
        align-items: start;
    }

    .sidebar {
        position: sticky;
        top: 6rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid rgba(156, 39, 176, 0.12);
        box-shadow: 0 4px 12px rgba(156, 39, 176, 0.08);
        max-height: calc(100vh - 8rem);
        overflow-y: auto;
    }

    .sidebar h4 {
        color: var(--privacy-accent);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .toc-item {
        margin-bottom: 0.5rem;
    }

    .toc-link {
        color: rgba(58, 58, 58, 0.7);
        text-decoration: none;
        font-size: 0.9rem;
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        display: block;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .toc-link:hover,
    .toc-link.active {
        background: rgba(156, 39, 176, 0.1);
        color: var(--privacy-accent);
        border-left-color: var(--privacy-accent);
        text-decoration: none;
        transform: translateX(3px);
    }

    .privacy-info-box {
        background: rgba(156, 39, 176, 0.05);
        border: 1px solid rgba(156, 39, 176, 0.2);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .privacy-info-title {
        color: var(--privacy-accent);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .privacy-info-text {
        color: rgba(58, 58, 58, 0.8);
        font-size: 0.9rem;
        margin: 0;
    }

    .main-content {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 0;
        border: 1px solid rgba(156, 39, 176, 0.12);
        box-shadow: 0 4px 12px rgba(156, 39, 176, 0.08);
        overflow: hidden;
    }

    .content-header {
        background: rgba(156, 39, 176, 0.1);
        padding: 2rem;
        border-bottom: 1px solid rgba(156, 39, 176, 0.1);
    }

    .content-header h2 {
        color: var(--privacy-accent);
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        font-size: 1.5rem;
    }

    .content-meta {
        color: rgba(58, 58, 58, 0.7);
        font-size: 0.9rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .content-body {
        padding: 2rem;
        line-height: 1.8;
        font-size: 1rem;
        color: var(--vcv-dark);
    }

    .content-body h2,
    .content-body h3,
    .content-body h4 {
        color: var(--privacy-accent);
        font-weight: 600;
        margin: 2rem 0 1rem 0;
        scroll-margin-top: 6rem;
    }

    .content-body h2 {
        font-size: 1.5rem;
        border-bottom: 2px solid rgba(156, 39, 176, 0.1);
        padding-bottom: 0.5rem;
    }

    .content-body h3 {
        font-size: 1.3rem;
    }

    .content-body h4 {
        font-size: 1.1rem;
    }

    .content-body p {
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    .content-body ul,
    .content-body ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .content-body li {
        margin-bottom: 0.8rem;
    }

    .content-body strong {
        color: var(--privacy-accent);
        font-weight: 600;
    }

    .highlight {
        background: linear-gradient(120deg, rgba(156, 39, 176, 0.2) 0%, rgba(156, 39, 176, 0.1) 100%);
        padding: 0.1rem 0.3rem;
        border-radius: 3px;
        font-weight: 600;
    }

    .section-divider {
        border: none;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(156, 39, 176, 0.2), transparent);
        margin: 3rem 0;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: rgba(58, 58, 58, 0.6);
    }

    .empty-state i {
        font-size: 4rem;
        color: rgba(156, 39, 176, 0.3);
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: var(--privacy-accent);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .empty-state p {
        margin: 0;
    }

    .contact-cta {
        background: linear-gradient(135deg, var(--privacy-accent) 0%, rgba(156, 39, 176, 0.9) 50%, rgba(31, 78, 121, 0.8) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-top: 3rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .contact-cta::before {
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

    .contact-cta h3 {
        margin: 0 0 1rem 0;
        font-weight: 600;
        position: relative;
        z-index: 2;
    }

    .contact-cta p {
        margin: 0 0 1.5rem 0;
        opacity: 0.95;
        position: relative;
        z-index: 2;
    }

    .contact-btn {
        background: white;
        color: var(--privacy-accent);
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
        color: var(--privacy-accent);
        text-decoration: none;
    }

    .reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(90deg, var(--privacy-accent), var(--vcv-accent));
        z-index: 1000;
        transition: width 0.1s ease;
    }

    @media (max-width: 992px) {
        .privacy-wrapper {
            padding: 5rem 0 2rem 0;
        }
        
        .content-layout {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .sidebar {
            position: static;
            order: 2;
        }
        
        .tools-section {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-container {
            min-width: auto;
        }
        
        .tools-buttons {
            justify-content: center;
        }
        
        .protection-badges {
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .privacy-header {
            padding: 2rem 1.5rem;
        }
        
        .privacy-header h1 {
            font-size: 2rem;
        }
        
        .content-body {
            padding: 1.5rem;
        }
        
        .tools-section {
            padding: 1rem;
        }
        
        .protection-badges {
            gap: 0.5rem;
        }
        
        .protection-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
    }
</style>

<div class="reading-progress" id="readingProgress"></div>

<div class="privacy-wrapper">
    <div class="container">
        <!-- Privacy Header -->
        <div class="privacy-header">
            <h1>🔒 Política de Privacidad</h1>
            <p class="privacy-subtitle">Tu privacidad es nuestra prioridad. Conoce cómo protegemos tus datos personales</p>
            
            <div class="protection-badges">
                <div class="protection-badge">
                    <i class="fas fa-shield-alt"></i>
                    Datos Encriptados
                </div>
                <div class="protection-badge">
                    <i class="fas fa-lock"></i>
                    Conexión Segura
                </div>
                <div class="protection-badge">
                    <i class="fas fa-user-shield"></i>
                    GDPR Compliant
                </div>
                <div class="protection-badge">
                    <i class="fas fa-eye-slash"></i>
                    Sin Tracking
                </div>
            </div>
            
            <div class="last-updated">
                <i class="fas fa-calendar-alt me-1"></i>
                Última actualización: {{ now()->format('d/m/Y') }}
            </div>
        </div>

        <!-- Tools Section -->
        <div class="tools-section">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    class="search-input" 
                    placeholder="Buscar información sobre privacidad..." 
                    id="searchInput"
                    onkeyup="buscarEnTexto()"
                >
            </div>
            <div class="tools-buttons">
                <button class="tool-btn" onclick="aumentarTexto()">
                    <i class="fas fa-search-plus"></i>A+
                </button>
                <button class="tool-btn" onclick="disminuirTexto()">
                    <i class="fas fa-search-minus"></i>A-
                </button>
                <button class="tool-btn" onclick="toggleContraste()">
                    <i class="fas fa-adjust"></i>Contraste
                </button>
                <button class="tool-btn" onclick="imprimirPagina()">
                    <i class="fas fa-print"></i>Imprimir
                </button>
                <button class="tool-btn" onclick="compartirEnlace()">
                    <i class="fas fa-share"></i>Compartir
                </button>
            </div>
        </div>

        <!-- Content Layout -->
        @if($politica && $politica->contenido)
            <div class="content-layout">
                <!-- Sidebar with Table of Contents -->
                <div class="sidebar">
                    <h4>
                        <i class="fas fa-list-ul"></i>
                        Índice de privacidad
                    </h4>
                    
                    <!-- Privacy Info Box -->
                    <div class="privacy-info-box">
                        <div class="privacy-info-title">
                            <i class="fas fa-info-circle"></i>
                            Resumen Rápido
                        </div>
                        <p class="privacy-info-text">
                            Recopilamos solo los datos necesarios para brindarte el mejor servicio. 
                            Nunca vendemos tu información personal.
                        </p>
                    </div>
                    
                    <ul class="toc-list" id="tableOfContents">
                        <!-- Se genera dinámicamente con JavaScript -->
                    </ul>
                </div>

                <!-- Main Content -->
                <div class="main-content">
                    <div class="content-header">
                        <h2>Política de Privacidad y Protección de Datos</h2>
                        <div class="content-meta">
                            <span><i class="fas fa-shield-alt me-1"></i>Documento de privacidad</span>
                            <span><i class="fas fa-clock me-1"></i>Tiempo de lectura: ~12 min</span>
                            <span><i class="fas fa-certificate me-1"></i>Política vigente</span>
                            <span><i class="fas fa-globe me-1"></i>Aplicable globalmente</span>
                        </div>
                    </div>
                    
                    <div class="content-body" id="mainContent">
                        {!! nl2br(e($politica->contenido)) !!}
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="main-content">
                <div class="empty-state">
                    <i class="fas fa-user-shield"></i>
                    <h3>Política de Privacidad</h3>
                    <p>Estamos actualizando nuestra política de privacidad para ofrecerte<br>mayor transparencia sobre el manejo de tus datos personales.</p>
                </div>
            </div>
        @endif

        <!-- Contact CTA -->
        <div class="contact-cta">
            <h3>🛡️ ¿Preguntas sobre tu privacidad?</h3>
            <p>Nuestro oficial de protección de datos está disponible para resolver tus dudas</p>
            <a href="#" class="contact-btn">
                <i class="fas fa-user-shield"></i>
                Contactar DPO
            </a>
        </div>
    </div>
</div>

<script>
let currentFontSize = 16;
let isHighContrast = false;

// Generar tabla de contenidos
function generarTablaContenidos() {
    const content = document.getElementById('mainContent');
    const toc = document.getElementById('tableOfContents');
    
    if (!content || !toc) return;
    
    // Buscar títulos en el contenido
    const headings = content.querySelectorAll('h1, h2, h3, h4, h5, h6');
    const paragraphs = content.querySelectorAll('p');
    
    toc.innerHTML = '';
    
    // Si no hay headings, crear TOC basado en párrafos largos
    if (headings.length === 0) {
        const commonSections = [
            'Información que recopilamos',
            'Cómo usamos tus datos',
            'Cookies y tecnologías',
            'Compartir información',
            'Seguridad de datos',
            'Tus derechos',
            'Retención de datos',
            'Contacto y consultas'
        ];
        
        let sectionCount = 1;
        paragraphs.forEach((p, index) => {
            if (p.textContent.length > 100 && sectionCount <= commonSections.length) {
                const id = `privacy-section-${sectionCount}`;
                p.id = id;
                
                const li = document.createElement('li');
                li.className = 'toc-item';
                
                const a = document.createElement('a');
                a.href = `#${id}`;
                a.className = 'toc-link';
                a.textContent = commonSections[sectionCount - 1];
                a.onclick = (e) => {
                    e.preventDefault();
                    document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
                    updateActiveToc(a);
                };
                
                li.appendChild(a);
                toc.appendChild(li);
                sectionCount++;
            }
        });
    } else {
        // Usar headings existentes
        headings.forEach((heading, index) => {
            const id = `privacy-heading-${index}`;
            heading.id = id;
            
            const li = document.createElement('li');
            li.className = 'toc-item';
            
            const a = document.createElement('a');
            a.href = `#${id}`;
            a.className = 'toc-link';
            a.textContent = heading.textContent.substring(0, 50) + (heading.textContent.length > 50 ? '...' : '');
            a.onclick = (e) => {
                e.preventDefault();
                heading.scrollIntoView({ behavior: 'smooth' });
                updateActiveToc(a);
            };
            
            li.appendChild(a);
            toc.appendChild(li);
        });
    }
}

// Actualizar enlace activo en TOC
function updateActiveToc(activeLink) {
    document.querySelectorAll('.toc-link').forEach(link => {
        link.classList.remove('active');
    });
    activeLink.classList.add('active');
}

// Búsqueda en texto
function buscarEnTexto() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const content = document.getElementById('mainContent');
    
    if (!content) return;
    
    // Limpiar highlights anteriores
    content.innerHTML = content.innerHTML.replace(/<span class="highlight">/g, '').replace(/<\/span>/g, '');
    
    if (searchTerm.length > 2) {
        // Highlight matches
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        content.innerHTML = content.innerHTML.replace(regex, '<span class="highlight">$1</span>');
    }
}

// Funciones de accesibilidad
function aumentarTexto() {
    if (currentFontSize < 20) {
        currentFontSize += 2;
        document.getElementById('mainContent').style.fontSize = currentFontSize + 'px';
    }
}

function disminuirTexto() {
    if (currentFontSize > 12) {
        currentFontSize -= 2;
        document.getElementById('mainContent').style.fontSize = currentFontSize + 'px';
    }
}

function toggleContraste() {
    isHighContrast = !isHighContrast;
    const body = document.body;
    
    if (isHighContrast) {
        body.style.filter = 'contrast(150%) brightness(1.2)';
    } else {
        body.style.filter = 'none';
    }
}

function imprimirPagina() {
    window.print();
}

function compartirEnlace() {
    if (navigator.share) {
        navigator.share({
            title: 'Política de Privacidad - VoyConVos',
            url: window.location.href
        });
    } else {
        // Fallback: copiar al clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Enlace copiado al portapapeles');
        });
    }
}

// Progress bar de lectura
function updateReadingProgress() {
    const content = document.getElementById('mainContent');
    if (!content) return;
    
    const scrollTop = window.pageYOffset;
    const docHeight = content.offsetHeight;
    const winHeight = window.innerHeight;
    const scrollPercent = scrollTop / (docHeight - winHeight);
    const scrollPercentRounded = Math.round(scrollPercent * 100);
    
    document.getElementById('readingProgress').style.width = Math.min(scrollPercentRounded, 100) + '%';
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    generarTablaContenidos();
    
    // Reading progress
    window.addEventListener('scroll', updateReadingProgress);
    
    // Auto-update active TOC based on scroll
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('[id^="privacy-section-"], [id^="privacy-heading-"]');
        let currentSection = '';
        
        sections.forEach(section => {
            const rect = section.getBoundingClientRect();
            if (rect.top <= 100) {
                currentSection = section.id;
            }
        });
        
        if (currentSection) {
            const activeLink = document.querySelector(`a[href="#${currentSection}"]`);
            if (activeLink) {
                updateActiveToc(activeLink);
            }
        }
    });
});

// Limpiar highlights al hacer scroll
let scrollTimer = null;
window.addEventListener('scroll', function() {
    if (scrollTimer !== null) {
        clearTimeout(scrollTimer);
    }
    scrollTimer = setTimeout(function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput && searchInput.value === '') {
            const content = document.getElementById('mainContent');
            if (content) {
                content.innerHTML = content.innerHTML.replace(/<span class="highlight">/g, '').replace(/<\/span>/g, '');
            }
        }
    }, 500);
});
</script>
@endsection