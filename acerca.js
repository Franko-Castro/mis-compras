// acerca.js - CON CARRUSEL INTERACTIVO

document.addEventListener('DOMContentLoaded', function() {
    // ========== ANIMACIÓN DE REVELADO ==========
    const revealElements = document.querySelectorAll('.reveal');
    
    const revealOnScroll = () => {
        revealElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementTop < windowHeight - 100) {
                element.classList.add('is-visible');
            }
        });
    };
    
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();
    
    // ========== CARRUSEL AUTOMÁTICO E INTERACTIVO ==========
    const carouselSlides = document.querySelector('.carousel-slides');
    const indicators = document.querySelectorAll('.carousel-indicator');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    let currentSlide = 0;
    const totalSlides = document.querySelectorAll('.carousel-slide').length;
    let autoSlideInterval;
    
    // Función para cambiar de slide
    function goToSlide(slideIndex) {
        currentSlide = slideIndex;
        carouselSlides.style.transform = `translateX(-${currentSlide * 25}%)`;
        
        // Actualizar indicadores
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentSlide);
        });
    }
    
    // Función para siguiente slide
    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        goToSlide(currentSlide);
    }
    
    // Función para slide anterior
    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        goToSlide(currentSlide);
    }
    
    // Iniciar carrusel automático
    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 5000); // Cambia cada 5 segundos
    }
    
    // Detener carrusel automático al interactuar
    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }
    
    // Configurar eventos
    if (carouselSlides) {
        // Eventos para botones
        if (prevBtn) prevBtn.addEventListener('click', () => {
            stopAutoSlide();
            prevSlide();
            setTimeout(startAutoSlide, 10000); // Reinicia después de 10 segundos
        });
        
        if (nextBtn) nextBtn.addEventListener('click', () => {
            stopAutoSlide();
            nextSlide();
            setTimeout(startAutoSlide, 10000);
        });
        
        // Eventos para indicadores
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                stopAutoSlide();
                goToSlide(index);
                setTimeout(startAutoSlide, 10000);
            });
        });
        
        // Pausar automático al hover
        carouselSlides.parentElement.addEventListener('mouseenter', stopAutoSlide);
        carouselSlides.parentElement.addEventListener('mouseleave', startAutoSlide);
        
        // Iniciar carrusel automático
        startAutoSlide();
    }
    
    // ========== EFECTOS HOVER EN BOTONES ==========
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
     // acerca.js - VERSIÓN SIMPLIFICADA

document.addEventListener('DOMContentLoaded', function() {
    // Animación de revelado
    const revealElements = document.querySelectorAll('.reveal');
    
    const revealOnScroll = () => {
        revealElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementTop < windowHeight - 100) {
                element.classList.add('is-visible');
            }
        });
    };
    
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();
    
    // Carrusel simple
    const carouselSlides = document.querySelector('.carousel-slides');
    const indicators = document.querySelectorAll('.carousel-indicator');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    let currentSlide = 0;
    const totalSlides = document.querySelectorAll('.carousel-slide').length;
    
    function goToSlide(slideIndex) {
        currentSlide = slideIndex;
        carouselSlides.style.transform = `translateX(-${currentSlide * 25}%)`;
        
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentSlide);
        });
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        goToSlide(currentSlide);
    }
    
    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        goToSlide(currentSlide);
    }
    
    // Configurar eventos si existe el carrusel
    if (carouselSlides) {
        // Auto slide cada 5 segundos
        setInterval(nextSlide, 5000);
        
        // Controles manuales
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        
        // Indicadores
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => goToSlide(index));
        });
    }
    
    // Efectos hover en botones
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});   btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // ========== EFECTO PARALLAX SUAVE ==========
    const heroVideo = document.querySelector('.hero-video');
    if (heroVideo) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            heroVideo.style.transform = `translate3d(0px, ${rate}px, 0px)`;
        });
    }
    
    // ========== ANIMACIÓN PARA LA SECCIÓN HISTORIA ==========
    const historySection = document.querySelector('.history-section');
    if (historySection) {
        window.addEventListener('scroll', function() {
            const scrolled = window.scrollY;
            const historyOffset = historySection.offsetTop;
            const windowHeight = window.innerHeight;
            
            if (scrolled > historyOffset - windowHeight * 0.7) {
                historySection.style.opacity = '1';
                historySection.style.transform = 'translateY(0)';
            }
        });
    }
});