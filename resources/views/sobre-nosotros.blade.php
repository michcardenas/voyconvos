@extends('layouts.app')

@section('title', \App\Models\Contenido::getTitulo('sobre-nosotros', 'Sobre Nosotros'))

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-info: #00a8e1;
        --vcv-bg: #FCFCFD;
        --vcv-gradient-primary: linear-gradient(135deg, #1F4E79 0%, #2d5f8d 100%);
        --vcv-gradient-accent: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
        --vcv-gradient-hero: linear-gradient(135deg, rgba(31, 78, 121, 0.95) 0%, rgba(76, 175, 80, 0.85) 100%);
    }

    body {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 50%, #f1f5f9 100%);
        background-attachment: fixed;
    }

    /* Wrapper con animación de fondo */
    .info-wrapper {
        position: relative;
        min-height: 100vh;
        padding: 6rem 0 4rem 0;
        overflow: hidden;
    }

    .info-wrapper::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(31, 78, 121, 0.02) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(76, 175, 80, 0.02) 0%, transparent 50%);
        animation: subtleShift 20s ease infinite;
        pointer-events: none;
        z-index: 0;
    }

    @keyframes subtleShift {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .container {
        position: relative;
        z-index: 1;
        max-width: 1100px;
        padding-left: 1rem;
        padding-right: 1rem;
        margin-left: auto;
        margin-right: auto;
    }

    /* Hero Header Elegante */
    .info-header {
        background: var(--vcv-gradient-hero),
                    url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=1600') center/cover;
        border-radius: 24px;
        padding: 4rem 3rem;
        margin-bottom: 3rem;
        box-shadow: 0 10px 40px rgba(31, 78, 121, 0.15);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
        border: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
    }

    .info-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
        transform: rotate(45deg);
        animation: elegantShine 4s infinite;
    }

    @keyframes elegantShine {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .section-title {
        margin: 0;
        font-weight: 800;
        font-size: 3rem;
        color: white;
        position: relative;
        z-index: 2;
        line-height: 1.2;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
        letter-spacing: -0.5px;
    }

    .info-subtitle {
        margin: 1rem 0 0 0;
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.2rem;
        position: relative;
        z-index: 2;
        font-weight: 500;
        opacity: 0.95;
    }

    /* Main Content - Elegante */
    .main-content {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(31, 78, 121, 0.08);
        animation: fadeInUp 0.6s ease-out 0.2s both;
        position: relative;
    }

    .main-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--vcv-gradient-primary);
    }

    .content-inner {
        padding: 3rem;
        line-height: 1.8;
        font-size: 1.05rem;
        color: var(--vcv-dark);
        position: relative;
    }

    /* Typography Elegante */
    .contenido h1,
    .contenido h2,
    .contenido h3,
    .contenido h4,
    .contenido h5,
    .contenido h6 {
        color: var(--vcv-primary);
        font-weight: 700;
        margin: 2.5rem 0 1.25rem 0;
        scroll-margin-top: 6rem;
        position: relative;
        line-height: 1.3;
    }

    .contenido h1 {
        font-size: 2.2rem;
        border-bottom: 2px solid rgba(31, 78, 121, 0.1);
        padding-bottom: 1rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, var(--vcv-primary) 0%, #2d5f8d 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .contenido h2 {
        font-size: 1.8rem;
        padding-left: 1rem;
        border-left: 4px solid var(--vcv-accent);
    }

    .contenido h3 {
        font-size: 1.5rem;
        color: rgba(31, 78, 121, 0.9);
    }

    .contenido h4 {
        font-size: 1.3rem;
        color: rgba(31, 78, 121, 0.85);
    }

    .contenido p {
        margin-bottom: 1.75rem;
        text-align: justify;
        font-weight: 400;
        letter-spacing: 0.01em;
        line-height: 1.8;
        color: rgba(58, 58, 58, 0.95);
    }

    .contenido ul,
    .contenido ol {
        margin-bottom: 2rem;
        padding-left: 2rem;
    }

    .contenido li {
        margin-bottom: 0.9rem;
        color: rgba(58, 58, 58, 0.9);
        line-height: 1.7;
        padding-left: 0.5rem;
        position: relative;
    }

    .contenido ul li::before {
        content: '→';
        position: absolute;
        left: -1.5rem;
        color: var(--vcv-accent);
        font-weight: 700;
    }

    .contenido strong,
    .contenido b {
        color: var(--vcv-primary);
        font-weight: 700;
    }

    .contenido em,
    .contenido i {
        color: rgba(31, 78, 121, 0.85);
        font-style: italic;
    }

    .contenido a {
        color: var(--vcv-accent);
        text-decoration: none;
        font-weight: 600;
        border-bottom: 2px solid rgba(76, 175, 80, 0.3);
        transition: all 0.3s ease;
        padding-bottom: 2px;
    }

    .contenido a:hover {
        color: #45a049;
        border-bottom-color: var(--vcv-accent);
        text-decoration: none;
    }

    .contenido blockquote {
        background: linear-gradient(135deg, rgba(221, 242, 254, 0.4) 0%, rgba(221, 242, 254, 0.2) 100%);
        border-left: 5px solid var(--vcv-primary);
        margin: 2.5rem 0;
        padding: 2rem 2.5rem;
        border-radius: 0 16px 16px 0;
        font-style: italic;
        color: rgba(31, 78, 121, 0.95);
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.08);
        position: relative;
    }

    .contenido blockquote::before {
        content: '"';
        position: absolute;
        top: 1rem;
        left: 1rem;
        font-size: 4rem;
        color: rgba(31, 78, 121, 0.1);
        font-family: Georgia, serif;
        line-height: 1;
    }

    .contenido img {
        max-width: 100%;
        height: auto;
        border-radius: 16px;
        margin: 2rem 0;
        box-shadow: 0 6px 20px rgba(31, 78, 121, 0.12);
        transition: transform 0.3s ease;
    }

    .contenido img:hover {
        transform: scale(1.02);
    }

    .contenido table {
        width: 100%;
        border-collapse: collapse;
        margin: 2.5rem 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.1);
    }

    .contenido th,
    .contenido td {
        padding: 1.25rem;
        text-align: left;
        border-bottom: 1px solid rgba(31, 78, 121, 0.08);
    }

    .contenido th {
        background: var(--vcv-gradient-primary);
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }

    .contenido tr:hover {
        background: rgba(221, 242, 254, 0.3);
        transition: background 0.2s ease;
    }

    .contenido td {
        color: var(--vcv-dark);
    }

    /* Reading Progress Bar - Elegante */
    .reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: var(--vcv-gradient-primary);
        z-index: 1000;
        transition: width 0.1s ease;
        box-shadow: 0 2px 4px rgba(31, 78, 121, 0.3);
    }

    /* Back to Top - Elegante */
    .back-to-top {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: white;
        color: var(--vcv-primary);
        border: 2px solid rgba(31, 78, 121, 0.15);
        border-radius: 50%;
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0;
        visibility: hidden;
        box-shadow: 0 4px 16px rgba(31, 78, 121, 0.15);
        z-index: 999;
    }

    .back-to-top::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--vcv-gradient-primary);
        opacity: 0;
        border-radius: 50%;
        transition: opacity 0.3s ease;
    }

    .back-to-top i {
        position: relative;
        z-index: 1;
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }

    .back-to-top.visible {
        opacity: 1;
        visibility: visible;
        animation: bounceIn 0.5s ease-out;
    }

    @keyframes bounceIn {
        0% { transform: scale(0); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .back-to-top:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(31, 78, 121, 0.25);
        border-color: var(--vcv-primary);
    }

    .back-to-top:hover::before {
        opacity: 1;
    }

    .back-to-top:hover i {
        color: white;
    }

    /* Feature Cards (si necesitas agregar cards) */
    .feature-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin: 2rem 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(31, 78, 121, 0.08);
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(31, 78, 121, 0.12);
    }

    /* Stats o Highlights */
    .highlight-box {
        background: linear-gradient(135deg, rgba(221, 242, 254, 0.5) 0%, rgba(221, 242, 254, 0.2) 100%);
        border-left: 4px solid var(--vcv-accent);
        border-radius: 0 12px 12px 0;
        padding: 1.5rem 2rem;
        margin: 2rem 0;
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.08);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-wrapper {
            padding: 5rem 0 3rem 0;
        }
        
        .info-header {
            padding: 3rem 2rem;
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 2rem;
        }

        .info-subtitle {
            font-size: 1rem;
        }
        
        .content-inner {
            padding: 2rem 1.5rem;
        }
        
        .contenido h1 {
            font-size: 1.8rem;
        }
        
        .contenido h2 {
            font-size: 1.5rem;
        }
        
        .contenido h3 {
            font-size: 1.3rem;
        }

        .contenido p {
            font-size: 1rem;
            text-align: left;
        }
        
        .back-to-top {
            bottom: 1.5rem;
            right: 1.5rem;
            width: 48px;
            height: 48px;
        }

        .contenido blockquote {
            padding: 1.5rem 1.5rem 1.5rem 2rem;
            margin: 1.5rem 0;
        }
    }

    @media (max-width: 480px) {
        .info-header {
            padding: 2.5rem 1.5rem;
        }
        
        .section-title {
            font-size: 1.8rem;
        }

        .info-subtitle {
            font-size: 0.95rem;
        }
        
        .content-inner {
            padding: 1.5rem 1rem;
            font-size: 1rem;
        }

        .contenido h1 {
            font-size: 1.6rem;
        }

        .contenido h2 {
            font-size: 1.4rem;
        }

        .back-to-top {
            bottom: 1rem;
            right: 1rem;
            width: 44px;
            height: 44px;
        }

        .back-to-top i {
            font-size: 1rem;
        }
    }

    /* Print styles */
    @media print {
        .info-wrapper::before,
        .info-header::before,
        .reading-progress,
        .back-to-top {
            display: none;
        }

        .info-wrapper {
            padding: 1rem 0;
        }

        .main-content {
            box-shadow: none;
            border: 1px solid #ddd;
        }
    }
</style>

<!-- Reading Progress Bar -->
<div class="reading-progress" id="readingProgress"></div>

<section class="info-wrapper">
    <div class="container">
        <!-- Hero Header -->
        <div class="info-header">
            <h1 class="section-title">{{ \App\Models\Contenido::getTitulo('sobre-nosotros') }}</h1>
            <p class="info-subtitle">{{ \App\Models\Contenido::getValor('sobre-nosotros', 'subtitulo') }}</p>
        </div>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <div class="content-inner contenido">
                <p class="mb-4 text-lg">
                    {{ \App\Models\Contenido::getValor('sobre-nosotros', 'parrafo_1') }}
                </p>
                <p class="text-lg">
                    {{ \App\Models\Contenido::getValor('sobre-nosotros', 'parrafo_2') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Back to Top Button -->
<button class="back-to-top" id="backToTop" aria-label="Volver arriba">
    <i class="fas fa-arrow-up"></i>
</button>

<script>
// Reading progress bar
function updateReadingProgress() {
    const content = document.getElementById('mainContent');
    const progressBar = document.getElementById('readingProgress');
    
    if (!content || !progressBar) return;
    
    const contentHeight = content.offsetHeight;
    const windowHeight = window.innerHeight;
    const contentTop = content.offsetTop;
    const scrollTop = window.pageYOffset;
    
    const totalScrollable = contentHeight + contentTop - windowHeight;
    const scrolled = Math.max(0, scrollTop - contentTop + (windowHeight * 0.3));
    const progress = Math.min(100, (scrolled / (contentHeight - windowHeight * 0.7)) * 100);
    
    progressBar.style.width = Math.max(0, progress) + '%';
}

// Back to top button
function updateBackToTop() {
    const backToTop = document.getElementById('backToTop');
    if (!backToTop) return;
    
    if (window.pageYOffset > 400) {
        backToTop.classList.add('visible');
    } else {
        backToTop.classList.remove('visible');
    }
}

// Scroll to top function
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        backToTop.addEventListener('click', scrollToTop);
    }
    
    // Initial calls
    updateReadingProgress();
    updateBackToTop();
});

window.addEventListener('scroll', function() {
    updateReadingProgress();
    updateBackToTop();
});

// Smooth scroll for internal links
document.addEventListener('DOMContentLoaded', function() {
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