@extends('layouts.app')

@section('title', 'Términos y Condiciones')

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-bg: #FCFCFD;
    }

    .terms-wrapper {
        background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgba(31, 78, 121, 0.03) 100%);
        min-height: 100vh;
        padding: 6rem 0 2rem 0;
        position: relative;
    }

    .terms-wrapper::before {
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

    .terms-header {
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

    .terms-header::before {
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

    .terms-header h1 {
        margin: 0 0 1rem 0;
        font-weight: 700;
        font-size: 2.5rem;
        position: relative;
        z-index: 2;
    }

    .terms-subtitle {
        margin: 0;
        opacity: 0.95;
        font-size: 1.2rem;
        position: relative;
        z-index: 2;
        font-weight: 300;
    }

    .last-updated {
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 1rem;
        position: relative;
        z-index: 2;
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
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        max-height: calc(100vh - 8rem);
        overflow-y: auto;
    }

    .sidebar h4 {
        color: var(--vcv-primary);
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
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
        border-left-color: var(--vcv-primary);
        text-decoration: none;
        transform: translateX(3px);
    }

    .main-content {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 0;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        overflow: hidden;
    }

    .content-header {
        background: rgba(221, 242, 254, 0.5);
        padding: 2rem;
        border-bottom: 1px solid rgba(31, 78, 121, 0.1);
    }

    .content-header h2 {
        color: var(--vcv-primary);
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
        color: var(--vcv-primary);
        font-weight: 600;
        margin: 2rem 0 1rem 0;
        scroll-margin-top: 6rem;
    }

    .content-body h2 {
        font-size: 1.5rem;
        border-bottom: 2px solid rgba(31, 78, 121, 0.1);
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
        color: var(--vcv-primary);
        font-weight: 600;
    }

    .section-divider {
        border: none;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(31, 78, 121, 0.2), transparent);
        margin: 3rem 0;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: rgba(58, 58, 58, 0.6);
    }

    .empty-state i {
        font-size: 4rem;
        color: rgba(31, 78, 121, 0.3);
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: var(--vcv-primary);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .empty-state p {
        margin: 0;
    }

    .reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(90deg, var(--vcv-primary), var(--vcv-accent));
        z-index: 1000;
        transition: width 0.1s ease;
    }

    @media (max-width: 992px) {
        .terms-wrapper {
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
    }

    @media (max-width: 768px) {
        .terms-header {
            padding: 2rem 1.5rem;
        }
        
        .terms-header h1 {
            font-size: 2rem;
        }
        
        .content-body {
            padding: 1.5rem;
        }
    }
</style>

<div class="reading-progress" id="readingProgress"></div>

<div class="terms-wrapper">
    <div class="container">

        <!-- Terms Header -->
        <div class="terms-header">
            <h1>{{ \App\Models\Contenido::getValor('header-terminos', 'titulo') }}</h1>
            <p class="terms-subtitle">{{ \App\Models\Contenido::getValor('header-terminos', 'subtitulo') }}</p>
            <div class="last-updated">
                <i class="fas fa-calendar-alt me-1"></i>
                Última actualización: {{ now()->format('d/m/Y') }}
            </div>
        </div>

       <!-- Content Layout -->
        @php
            $parrafos = [];
            for ($i = 1; $i <= 8; $i++) {
                $valor = \App\Models\Contenido::getValor('contenido-terminos', 'contenido_' . $i);
                if ($valor) $parrafos[] = $valor;
            }
        @endphp

        @if(count($parrafos))
            <div class="content-layout">
                <!-- Sidebar with Table of Contents -->
                <div class="sidebar">
                    <h4>
                        <i class="fas fa-list"></i>
                        Índice de contenido
                    </h4>
                    <ul class="toc-list" id="tableOfContents">
                        <!-- Se genera dinámicamente con JavaScript -->
                    </ul>
                </div>

                <!-- Main Content -->
                <div class="main-content">
                    <div class="content-header">
                        <h2>Términos y Condiciones de Uso</h2>
                        <div class="content-meta">
                            <span><i class="fas fa-file-alt me-1"></i>Documento legal</span>
                            <span><i class="fas fa-clock me-1"></i>Tiempo de lectura: ~15 min</span>
                            <span><i class="fas fa-shield-alt me-1"></i>Versión vigente</span>
                        </div>
                    </div>

                    <div class="content-body" id="mainContent">
                        @foreach($parrafos as $i => $texto)
                            <p><strong>{{ $i + 1 }}.</strong> {{ $texto }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="main-content">
                <div class="empty-state">
                    <i class="fas fa-file-contract"></i>
                    <h3>Términos y Condiciones</h3>
                    <p>Los términos y condiciones están siendo actualizados.<br>Por favor, vuelve pronto para conocer nuestras políticas.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
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
        let sectionCount = 1;
        paragraphs.forEach((p, index) => {
            if (p.textContent.length > 100) {
                const id = `section-${sectionCount}`;
                p.id = id;
                
                const li = document.createElement('li');
                li.className = 'toc-item';
                
                const a = document.createElement('a');
                a.href = `#${id}`;
                a.className = 'toc-link';
                a.textContent = `Sección ${sectionCount}`;
                a.onclick = (e) => {
                    e.preventDefault();
                    document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
                    updateActiveToc(a);
                };
                
                li.appendChild(a);
                toc.appendChild(li);
                sectionCount++;
                
                if (sectionCount > 10) return; // Máximo 10 secciones
            }
        });
    } else {
        // Usar headings existentes
        headings.forEach((heading, index) => {
            const id = `heading-${index}`;
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
        const sections = document.querySelectorAll('[id^="section-"], [id^="heading-"]');
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
</script>
@endsection