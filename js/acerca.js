// acerca.js - CARRUSEL DE OBJETIVOS + CARRUSEL DE PROYECTOS

document.addEventListener('DOMContentLoaded', function() {
    // ========== ANIMACIÓN DE REVEAL ==========
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

    // ========== CARRUSEL DE OBJETIVOS EXISTENTE ==========
    const carouselSlides = document.querySelector('.carousel-slides');
    const indicators = document.querySelectorAll('.carousel-indicator');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');

    let currentSlide = 0;
    const totalSlides = document.querySelectorAll('.carousel-slide').length;
    let autoSlideInterval;

    function goToSlide(slideIndex) {
        currentSlide = slideIndex;
        if (carouselSlides) {
            carouselSlides.style.transform = `translateX(-${currentSlide * 25}%)`;
        }
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

    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 5000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    if (carouselSlides) {
        if (prevBtn) prevBtn.addEventListener('click', () => { stopAutoSlide(); prevSlide(); setTimeout(startAutoSlide, 10000); });
        if (nextBtn) nextBtn.addEventListener('click', () => { stopAutoSlide(); nextSlide(); setTimeout(startAutoSlide, 10000); });

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => { stopAutoSlide(); goToSlide(index); setTimeout(startAutoSlide, 10000); });
        });

        carouselSlides.parentElement.addEventListener('mouseenter', stopAutoSlide);
        carouselSlides.parentElement.addEventListener('mouseleave', startAutoSlide);

        startAutoSlide();
    }

    // ========== PROYECTOS ESTÁTICOS CON HOVER ==========    
    const proyectoCards = document.querySelectorAll('.proyectos-card');

    proyectoCards.forEach(card => {
        card.addEventListener('click', () => {
            const url = card.dataset.url;
            if (url && url !== '#') {
                window.location.href = url;
            }
        });
    });

    // ========== EFECTOS HOVER EN BOTONES ==========
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // ========== PARALLAX SUAVE 
    const heroVideo = document.querySelector('.hero-video');
    if (heroVideo) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            heroVideo.style.transform = `translate3d(0px, ${rate}px, 0px)`;
        });
    }

    // ========== Efecto historia
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

    // ========== LOGICA PARA CARRUSEL DE INVESTIGACIONES ==========
    const researchTrack = document.querySelector('.research-track');
    if (researchTrack) {
        // Pausar animación al entrar
        researchTrack.addEventListener('mouseenter', () => {
            researchTrack.style.animationPlayState = 'paused';
        });
        
        // Reanudar animación al salir
        researchTrack.addEventListener('mouseleave', () => {
            researchTrack.style.animationPlayState = 'running';
        });
        
        // Opcional: Soporte para touch en móviles
        researchTrack.addEventListener('touchstart', () => {
            researchTrack.style.animationPlayState = 'paused';
        }, {passive: true});
        
        researchTrack.addEventListener('touchend', () => {
            researchTrack.style.animationPlayState = 'running';
        }, {passive: true});
    }
});
