document.addEventListener('DOMContentLoaded', function() {
    
    // ====================================
    // MENÚ HAMBURGUESA
    // ====================================
    
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const closeBtn = document.getElementById('closeBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const body = document.body;

    // Función para abrir el menú
    function openMenu() {
        if (hamburgerBtn) hamburgerBtn.classList.add('active');
        if (mobileMenu) mobileMenu.classList.add('active');
        if (mobileOverlay) mobileOverlay.classList.add('active');
        body.classList.add('menu-open');
        
        // Accesibilidad
        if (hamburgerBtn) hamburgerBtn.setAttribute('aria-expanded', 'true');
        if (mobileMenu) mobileMenu.setAttribute('aria-hidden', 'false');
        
        // Enfocar el primer elemento del menú
        const firstLink = mobileMenu.querySelector('.mobile-nav-link');
        if (firstLink) {
            setTimeout(() => firstLink.focus(), 300);
        }
    }

    // Función para cerrar el menú
    function closeMenu() {
        if (hamburgerBtn) hamburgerBtn.classList.remove('active');
        if (mobileMenu) mobileMenu.classList.remove('active');
        if (mobileOverlay) mobileOverlay.classList.remove('active');
        body.classList.remove('menu-open');
        
        // Accesibilidad
        if (hamburgerBtn) hamburgerBtn.setAttribute('aria-expanded', 'false');
        if (mobileMenu) mobileMenu.setAttribute('aria-hidden', 'true');
        
        // Devolver foco al botón hamburguesa
        if (hamburgerBtn) hamburgerBtn.focus();
    }

    // Event listeners del menú
    if (hamburgerBtn) {
        hamburgerBtn.addEventListener('click', function() {
            if (mobileMenu && mobileMenu.classList.contains('active')) {
                closeMenu();
            } else {
                openMenu();
            }
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeMenu);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMenu);
    }

    // Cerrar menú al hacer clic en un enlace
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            closeMenu();
            // Smooth scroll si es un enlace de anchor
            const href = this.getAttribute('href');
            if (href && href.startsWith('#')) {
                setTimeout(() => {
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                }, 300);
            }
        });
    });

    // Cerrar menú con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('active')) {
            closeMenu();
        }
    });

    // Manejar resize de ventana - CAMBIADO A 768px
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && mobileMenu && mobileMenu.classList.contains('active')) {
            closeMenu();
        }
    });

    // ====================================
    // DROPDOWN DE USUARIO (DESKTOP)
    // ====================================
    
    const userDropdown = document.getElementById('userDropdown');
    const userMenu = document.getElementById('userMenu');

    if (userDropdown && userMenu) {
        userDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            const isVisible = userMenu.style.opacity === '1';
            
            if (isVisible) {
                userMenu.style.opacity = '0';
                userMenu.style.visibility = 'hidden';
                userMenu.style.transform = 'translateY(-10px)';
            } else {
                userMenu.style.opacity = '1';
                userMenu.style.visibility = 'visible';
                userMenu.style.transform = 'translateY(0)';
            }
        });

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.style.opacity = '0';
                userMenu.style.visibility = 'hidden';
                userMenu.style.transform = 'translateY(-10px)';
            }
        });

        // Cerrar con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && userMenu.style.opacity === '1') {
                userMenu.style.opacity = '0';
                userMenu.style.visibility = 'hidden';
                userMenu.style.transform = 'translateY(-10px)';
            }
        });
    }

    // ====================================
    // SCROLL TO TOP
    // ====================================
    
    const scrollToTopBtn = document.getElementById('scrollToTop');
    
    if (scrollToTopBtn) {
        // Mostrar/ocultar botón según scroll
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        });

        // Acción de scroll to top
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ====================================
    // NEWSLETTER
    // ====================================
    
    // Newsletter desktop
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[type="email"]').value;
            const button = this.querySelector('button');
            const originalText = button.innerHTML;
            
            // Validación básica
            if (!isValidEmail(email)) {
                showNotification('Por favor, ingresa un email válido', 'error');
                return;
            }
            
            // Simular envío
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
            button.disabled = true;
            
            setTimeout(() => {
                showNotification('¡Gracias! Te has suscrito correctamente', 'success');
                this.reset();
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        });
    }

    // Newsletter móvil
    const mobileNewsletter = document.getElementById('mobileNewsletter');
    const mobileNewsletterClose = document.getElementById('mobileNewsletterClose');
    const mobileNewsletterForm = document.querySelector('.mobile-newsletter-form');

    // Mostrar newsletter móvil después de un tiempo
    if (mobileNewsletter && window.innerWidth <= 768) {
        setTimeout(() => {
            if (!localStorage.getItem('newsletter-shown')) {
                mobileNewsletter.classList.add('show');
                localStorage.setItem('newsletter-shown', 'true');
            }
        }, 10000); // Mostrar después de 10 segundos
    }

    // Cerrar newsletter móvil
    if (mobileNewsletterClose) {
        mobileNewsletterClose.addEventListener('click', function() {
            if (mobileNewsletter) {
                mobileNewsletter.classList.remove('show');
                localStorage.setItem('newsletter-closed', 'true');
            }
        });
    }

    // Formulario newsletter móvil
    if (mobileNewsletterForm) {
        mobileNewsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[type="email"]').value;
            const button = this.querySelector('button');
            const originalText = button.textContent;
            
            if (!isValidEmail(email)) {
                showNotification('Email inválido', 'error');
                return;
            }
            
            button.textContent = 'Enviando...';
            button.disabled = true;
            
            setTimeout(() => {
                showNotification('¡Suscrito correctamente!', 'success');
                if (mobileNewsletter) {
                    mobileNewsletter.classList.remove('show');
                }
                this.reset();
                button.textContent = originalText;
                button.disabled = false;
            }, 2000);
        });
    }

    // ====================================
    // HEADER SCROLL EFFECT
    // ====================================
    
    const navbar = document.getElementById('navbar');
    if (navbar) {
        let lastScrollTop = 0;
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Cambiar apariencia del navbar al hacer scroll
            if (scrollTop > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 2px 30px rgba(0, 0, 0, 0.15)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
            }
            
            // Ocultar/mostrar navbar en scroll (opcional)
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // Scrolling down
                navbar.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up
                navbar.style.transform = 'translateY(0)';
            }
            
            lastScrollTop = scrollTop;
        });
    }

    // ====================================
    // SMOOTH SCROLL PARA ENLACES INTERNOS
    // ====================================
    
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            
            if (target) {
                const offsetTop = target.offsetTop - 80; // Compensar altura del header
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ====================================
    // FUNCIONES AUXILIARES
    // ====================================
    
    // Validar email
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Mostrar notificaciones
    function showNotification(message, type = 'info') {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span>${message}</span>
            <button class="notification-close"><i class="fas fa-times"></i></button>
        `;
        
        // Estilos inline para la notificación
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            background: type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#3498db',
            color: 'white',
            padding: '15px 20px',
            borderRadius: '10px',
            boxShadow: '0 4px 15px rgba(0, 0, 0, 0.2)',
            zIndex: '9999',
            display: 'flex',
            alignItems: 'center',
            gap: '10px',
            maxWidth: '350px',
            transform: 'translateX(100%)',
            transition: 'transform 0.3s ease'
        });
        
        // Estilo del botón cerrar
        const closeBtn = notification.querySelector('.notification-close');
        Object.assign(closeBtn.style, {
            background: 'none',
            border: 'none',
            color: 'white',
            cursor: 'pointer',
            marginLeft: 'auto'
        });
        
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Cerrar notificación
        function closeNotification() {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }
        
        closeBtn.addEventListener('click', closeNotification);
        
        // Auto cerrar después de 5 segundos
        setTimeout(closeNotification, 5000);
    }

    // ====================================
    // ANIMACIONES DE ENTRADA
    // ====================================
    
    // Intersection Observer para animaciones
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observar elementos para animar
    document.querySelectorAll('.footer-column, .social-icons a').forEach(el => {
        observer.observe(el);
    });

    // ====================================
    // MANEJO DE ERRORES
    // ====================================
    
    window.addEventListener('error', function(e) {
        console.warn('Error en header-footer.js:', e.error);
    });

    // ====================================
    // INICIALIZACIÓN FINAL
    // ====================================
    
    console.log('Header-Footer.js inicializado correctamente');
    
    // Dispatch evento personalizado para otros scripts
    document.dispatchEvent(new CustomEvent('headerFooterReady'));
});

// ====================================
// FUNCIONES GLOBALES ADICIONALES
// ====================================

// Función para abrir enlaces externos con target="_blank"
document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link && link.hostname !== window.location.hostname) {
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
    }
});

// Prevenir zoom accidental en iOS
document.addEventListener('touchstart', function(e) {
    if (e.touches.length > 1) {
        e.preventDefault();
    }
});

// Manejar orientación de dispositivo
window.addEventListener('orientationchange', function() {
    setTimeout(() => {
        // Recalcular heights si es necesario
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }, 500);
});

// Inicializar custom properties CSS
const vh = window.innerHeight * 0.01;
document.documentElement.style.setProperty('--vh', `${vh}px`);

// ====================================
// ESTILOS CSS PARA NOTIFICACIONES
// ====================================

// Inyectar estilos CSS para notificaciones si no existen
if (!document.querySelector('#notification-styles')) {
    const style = document.createElement('style');
    style.id = 'notification-styles';
    style.textContent = `
        .notification {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            animation: slideInRight 0.3s ease;
        }
        
        .notification-close:hover {
            opacity: 0.8;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .animate-in {
            animation: fadeInUp 0.6s ease forwards;
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
        
        /* Fix para iOS */
        .mobile-menu {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Smooth scrolling fallback */
        @media (prefers-reduced-motion: no-preference) {
            html {
                scroll-behavior: smooth;
            }
        }
    `;
    document.head.appendChild(style);
}