document.addEventListener('DOMContentLoaded', function() {
    // Animation for hero text on page load
    const heroText = document.querySelector('.hero-text');
    if (heroText) {
        // Use a short timeout to ensure the initial state is rendered before animating
        setTimeout(() => {
            heroText.classList.add('is-visible');
        }, 100);
    }

    // Check if anime.js is available
    if (typeof anime !== 'undefined') {
        const observerOptions = {
            threshold: 0.3, // Trigger when 30% is visible
            rootMargin: '0px 0px -50px 0px'
        };

        const scrollObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Animate Service Cards
                    if (entry.target.classList.contains('service-cards')) {
                        anime({
                            targets: entry.target.querySelectorAll('.card'),
                            opacity: [0, 1],
                            translateY: [50, 0],
                            scale: [0.95, 1],
                            duration: 1000,
                            delay: anime.stagger(150),
                            easing: 'easeInOutExpo'
                        });
                    }
                    // Animate Health Tip Cards
                    else if (entry.target.classList.contains('Tips-container')) {
                         anime({
                            targets: entry.target.querySelectorAll('.health-tip-card'),
                            opacity: [0, 1],
                            translateY: [50, 0],
                            scale: [0.95, 1],
                            duration: 1000,
                            delay: anime.stagger(150),
                            easing: 'easeInOutExpo'
                        });
                    }
                    // Animate Feedback Cards
                    else if (entry.target.classList.contains('feedback-cards')) {
                         anime({
                            targets: entry.target.querySelectorAll('.feedback-card'),
                            opacity: [0, 1],
                            translateX: [-50, 0],
                            scale: [0.95, 1],
                            duration: 1000,
                            delay: anime.stagger(150),
                            easing: 'easeInOutExpo'
                        });
                    }
                    // Animate Section Headers
                    else if (entry.target.classList.contains('services-header') || entry.target.classList.contains('innovation-text') || entry.target.classList.contains('feedback-header')) {
                        // Animate H2 from the left
                        anime({
                            targets: entry.target.querySelector('h2'),
                            opacity: [0, 1],
                            translateX: [-50, 0],
                            duration: 1000,
                            easing: 'easeInOutExpo'
                        });
                        // Animate p or .services-description from the right
                        anime({
                            targets: entry.target.querySelector('p, .services-description, .feedback-description'),
                            opacity: [0, 1],
                            translateX: [50, 0],
                            duration: 1000,
                            delay: 200, // Add a slight delay
                            easing: 'easeInOutExpo'
                        });
                    }

                    // Unobserve the target after animation
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe ALL the containers for animations
        document.querySelectorAll('.service-cards, .Tips-container, .feedback-cards, .services-header, .innovation-text, .feedback-header').forEach(el => {
            scrollObserver.observe(el);
        });
    }

    // Smooth scroll for navigation links with offset for sticky header
    const navLinks = document.querySelectorAll('a[href^="#"]');
    const header = document.querySelector('.header');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);

            if (targetSection) {
                const headerHeight = header.offsetHeight;
                const targetPosition = targetSection.getBoundingClientRect().top + window.pageYOffset;
                const offsetPosition = targetPosition - headerHeight;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Scrollspy for navigation links
    const sections = document.querySelectorAll('section[id]');
    const navListLinks = document.querySelectorAll('.nav-list a');

    const scrollspyObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const targetId = entry.target.getAttribute('id');
                navListLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${targetId}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    }, { threshold: 0.6 });

    sections.forEach(section => {
        scrollspyObserver.observe(section);
    });

    // Back to Top Button Functionality
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopBtn.style.opacity = '1';
                backToTopBtn.style.visibility = 'visible';
            } else {
                backToTopBtn.style.opacity = '0';
                backToTopBtn.style.visibility = 'hidden';
            }
        });
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Parallax effect for the showcase section
    const parallaxShowcase = document.querySelector('.parallax-showcase');
    if (parallaxShowcase) {
        window.addEventListener('scroll', () => {
            const scrollPosition = window.pageYOffset;
            parallaxShowcase.style.backgroundPositionY = (scrollPosition - parallaxShowcase.offsetTop) * 0.5 + 'px';
        });
    }
});