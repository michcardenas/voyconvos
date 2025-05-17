/**
 * VoyConVoz - Script para animaciones y navegación
 * Ruta: C:\voyconvoz\voyconvos\public\js\script.js
 */

document.addEventListener('DOMContentLoaded', function() {
    // Navbar que aparece al hacer scroll
    const navbar = document.getElementById('navbar');
    
    // Mostrar el navbar inicialmente
    navbar.classList.add('visible');
    
    let lastScrollTop = 0;
    let scrollTimer;
    
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        clearTimeout(scrollTimer);
        
        // Si hacemos scroll hacia abajo, ocultar el navbar
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            navbar.classList.remove('visible');
            navbar.classList.add('hidden');
        } 
        // Si hacemos scroll hacia arriba, mostrar el navbar
        else if (scrollTop < lastScrollTop) {
            navbar.classList.remove('hidden');
            navbar.classList.add('visible');
        }
        
        lastScrollTop = scrollTop;
        
        // Si dejamos de hacer scroll, mostrar el navbar después de un tiempo
        scrollTimer = setTimeout(function() {
            navbar.classList.remove('hidden');
            navbar.classList.add('visible');
        }, 1500);
    });
    
    // Animación de elementos al hacer scroll
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.feature-card, .slogan h2, .slogan .btn');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementPosition < windowHeight * 0.85) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };
    
    // Inicializar las animaciones
    window.addEventListener('scroll', animateOnScroll);
    
    // Preparar las cards para la animación
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `all 0.5s ease ${index * 0.2}s`;
    });
    
    const sloganElements = document.querySelectorAll('.slogan h2, .slogan .btn');
    sloganElements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = `all 0.5s ease ${index * 0.2}s`;
    });
    
    // Ejecutar la primera vez para elementos que ya están visibles
    animateOnScroll();
    
    // Funcionalidad para intercambiar origen y destino
    const switchBtn = document.querySelector('.switch-btn');
    if (switchBtn) {
        switchBtn.addEventListener('click', function() {
            const inputGroups = document.querySelectorAll('.route-inputs .input-group input');
            if (inputGroups.length >= 2) {
                const temp = inputGroups[0].value;
                inputGroups[0].value = inputGroups[1].value;
                inputGroups[1].value = temp;
            }
        });
    }
    
    // Añadir efecto hover a los elementos interactivos
    const interactiveElements = document.querySelectorAll('.btn, .publish-trip-btn, .input-group');
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
        });
    });
});