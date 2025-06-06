@extends('layouts.app')

@section('title', \App\Models\Contenido::getTitulo('sobre-nosotros', 'Sobre Nosotros'))

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-bg: #FCFCFD;
    }

    .info-wrapper {
        background: linear-gradient(145deg, rgba(221, 242, 254, 0.3) 0%, rgba(252, 252, 253, 0.8) 40%, rgba(31, 78, 121, 0.02) 100%);
        min-height: 100vh;
        padding: 6rem 0 3rem 0;
        position: relative;
    }

    .info-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 15% 85%, rgba(31, 78, 121, 0.03) 0%, transparent 40%),
            radial-gradient(circle at 85% 15%, rgba(76, 175, 80, 0.02) 0%, transparent 40%);
        pointer-events: none;
    }

    .container {
        position: relative;
        z-index: 1;
        max-width: 1000px;
        padding-left: 1rem;
        padding-right: 1rem;
        margin-left: auto;
        margin-right: auto;
    }

    .info-header {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(221, 242, 254, 0.7) 50%, rgba(252, 252, 253, 0.9) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(31, 78, 121, 0.08);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 
            0 8px 32px rgba(31, 78, 121, 0.06),
            0 1px 2px rgba(31, 78, 121, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.7);
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .info-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.08) 0%, transparent 60%);
        border-radius: 50%;
        transform: translate(25%, -25%);
    }

    .info-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 80px;
        background: radial-gradient(circle, rgba(31, 78, 121, 0.05) 0%, transparent 60%);
        border-radius: 50%;
        transform: translate(-25%, 25%);
    }

    .section-title {
        margin: 0;
        font-weight: 600;
        font-size: 2.2rem;
        color: var(--vcv-primary);
        position: relative;
        z-index: 2;
        line-height: 1.3;
        text-shadow: 0 1px 2px rgba(31, 78, 121, 0.1);
    }

    .info-subtitle {
        margin: 1rem 0 0 0;
        color: rgba(58, 58, 58, 0.7);
        font-size: 1rem;
        position: relative;
        z-index: 2;
        font-weight: 400;
    }

    .main-content {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(221, 242, 254, 0.4) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(31, 78, 121, 0.08);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 
            0 8px 32px rgba(31, 78, 121, 0.06),
            0 1px 2px rgba(31, 78, 121, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.7);
    }

    .content-inner {
        padding: 2.5rem;
        line-height: 1.75;
        font-size: 1rem;
        color: var(--vcv-dark);
        position: relative;
    }

    .content-inner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(31, 78, 121, 0.1), transparent);
    }

    .contenido h1,
    .contenido h2,
    .contenido h3,
    .contenido h4,
    .contenido h5,
    .contenido h6 {
        color: var(--vcv-primary);
        font-weight: 600;
        margin: 2rem 0 1rem 0;
        scroll-margin-top: 6rem;
        position: relative;
    }

    .contenido h1 {
        font-size: 1.8rem;
        border-bottom: 1px solid rgba(31, 78, 121, 0.1);
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .contenido h2 {
        font-size: 1.5rem;
    }

    .contenido h3 {
        font-size: 1.3rem;
        color: rgba(31, 78, 121, 0.85);
    }

    .contenido h4 {
        font-size: 1.1rem;
        color: rgba(31, 78, 121, 0.8);
    }

    .contenido p {
        margin-bottom: 1.5rem;
        text-align: justify;
        font-weight: 400;
        letter-spacing: 0.01em;
    }

    .contenido ul,
    .contenido ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .contenido li {
        margin-bottom: 0.7rem;
        color: rgba(58, 58, 58, 0.9);
    }

    .contenido strong,
    .contenido b {
        color: var(--vcv-primary);
        font-weight: 600;
    }

    .contenido em,
    .contenido i {
        color: rgba(31, 78, 121, 0.8);
        font-style: italic;
    }

    .contenido a {
        color: var(--vcv-accent);
        text-decoration: none;
        font-weight: 500;
        border-bottom: 1px solid rgba(76, 175, 80, 0.3);
        transition: all 0.3s ease;
    }

    .contenido a:hover {
        color: var(--vcv-accent);
        border-bottom-color: var(--vcv-accent);
        text-decoration: none;
    }

    .contenido blockquote {
        background: rgba(221, 242, 254, 0.3);
        border-left: 4px solid var(--vcv-primary);
        margin: 2rem 0;
        padding: 1.5rem 2rem;
        border-radius: 0 12px 12px 0;
        font-style: italic;
        color: rgba(31, 78, 121, 0.9);
    }

    .contenido img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 1.5rem 0;
        box-shadow: 0 4px 16px rgba(31, 78, 121, 0.1);
    }

    .contenido table {
        width: 100%;
        border-collapse: collapse;
        margin: 2rem 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.1);
    }

    .contenido th,
    .contenido td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(31, 78, 121, 0.1);
    }

    .contenido th {
        background: rgba(31, 78, 121, 0.05);
        color: var(--vcv-primary);
        font-weight: 600;
    }

    .contenido tr:hover {
        background: rgba(221, 242, 254, 0.2);
    }

    .reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 2px;
        background: linear-gradient(90deg, var(--vcv-primary), var(--vcv-accent));
        z-index: 1000;
        transition: width 0.1s ease;
        opacity: 0.8;
    }

    .back-to-top {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        color: var(--vcv-primary);
        border: 1px solid rgba(31, 78, 121, 0.1);
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0;
        visibility: hidden;
        box-shadow: 0 4px 16px rgba(31, 78, 121, 0.1);
    }

    .back-to-top.visible {
        opacity: 1;
        visibility: visible;
    }

    .back-to-top:hover {
        background: rgba(31, 78, 121, 0.1);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(31, 78, 121, 0.15);
    }

    @media (max-width: 768px) {
        .info-wrapper {
            padding: 5rem 0 2rem 0;
        }
        
        .info-header {
            padding: 2rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .section-title {
            font-size: 1.8rem;
        }
        
        .content-inner {
            padding: 1.5rem;
        }
        
        .contenido h1 {
            font-size: 1.5rem;
        }
        
        .contenido h2 {
            font-size: 1.3rem;
        }
        
        .contenido h3 {
            font-size: 1.2rem;
        }
        
        .back-to-top {
            bottom: 1rem;
            right: 1rem;
            width: 44px;
            height: 44px;
        }
    }

    @media (max-width: 480px) {
        .info-header {
            padding: 1.5rem 1rem;
        }
        
        .section-title {
            font-size: 1.6rem;
        }
        
        .content-inner {
            padding: 1rem;
        }
    }
</style>

<section class="info-wrapper">
    <div class="container">
        <div class="info-header">
            <h1 class="section-title">{{ \App\Models\Contenido::getTitulo('sobre-nosotros') }}</h1>
            <p class="info-subtitle">{{ \App\Models\Contenido::getValor('sobre-nosotros', 'subtitulo') }}</p>
        </div>
            <div class="main-content contenido" id="mainContent">
                <div style="margin: 15px;">
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