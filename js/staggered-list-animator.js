document.addEventListener('DOMContentLoaded', () => {
    if (typeof anime === 'undefined') {
        console.warn('anime.js is not loaded. Skipping staggered list animations.');
        // Fallback: make all potential animated items visible
        document.querySelectorAll('.animated-item').forEach(item => {
            item.style.opacity = 1;
        });
        return;
    }

    const animatedContainers = document.querySelectorAll('.animated-container');

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const items = entry.target.querySelectorAll('.animated-item');
                anime({
                    targets: items,
                    translateY: [50, 0],
                    opacity: [0, 1],
                    scale: [0.95, 1],
                    duration: 800,
                    delay: anime.stagger(100), // Stagger delay
                    easing: 'easeOutCubic'
                });
                observer.unobserve(entry.target); // Animate only once
            }
        });
    }, { threshold: 0.1 }); // Trigger when 10% of container is visible

    animatedContainers.forEach(container => {
        observer.observe(container);
    });
});
