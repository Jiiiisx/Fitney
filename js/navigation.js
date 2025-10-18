// Navigation Active State Management
(function() {
    'use strict';

    // Function to get current page name from URL
    function getCurrentPage() {
        const path = window.location.pathname;
        const page = path.split('/').pop();
        return page || 'index.php';
    }

    // Function to set active navigation item
    function setActiveNavItem() {
        const currentPage = getCurrentPage();
        const navLinks = document.querySelectorAll('.nav-link');
        
        // Remove active class from all links
        navLinks.forEach(link => {
            link.classList.remove('active');
        });

        // Add active class based on current page
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            const dataPage = link.getAttribute('data-page');
            
            // Check for exact matches
            if (href === currentPage || 
                (currentPage === 'index.php' && href === 'index.php') ||
                (currentPage === '' && href === 'index.php')) {
                link.classList.add('active');
            }
            
            // Check for section-based navigation (like #services, #tips)
            if (dataPage && window.location.hash) {
                const hash = window.location.hash.substring(1);
                if (dataPage === hash) {
                    link.classList.add('active');
                }
            }
        });
    }

    // Function to handle smooth scrolling for anchor links
    function handleSmoothScroll() {
        const navLinks = document.querySelectorAll('.nav-link[href^="#"]');
        
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);
                
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Update URL without page reload
                    history.pushState(null, null, `#${targetId}`);
                    
                    // Update active state
                    setActiveNavItem();
                }
            });
        });
    }

    // Function to handle scroll-based active state for sections
    function handleScrollActiveState() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        
        function updateActiveOnScroll() {
            let current = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                
                if (window.scrollY >= sectionTop - 100) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-page') === current) {
                    link.classList.add('active');
                }
            });
        }
        
        window.addEventListener('scroll', updateActiveOnScroll);
    }

    // Initialize navigation
    function initNavigation() {
        setActiveNavItem();
        handleSmoothScroll();
        
        // Only add scroll-based active state if we're on the landing page
        if (window.location.pathname.includes('index.php') || 
            window.location.pathname === '/' || 
            window.location.pathname === '') {
            handleScrollActiveState();
        }
    }

    // Run when DOM is loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNavigation);
    } else {
        initNavigation();
    }

    // Handle browser back/forward navigation
    window.addEventListener('popstate', setActiveNavItem);

    // Export for global use
    window.NavigationManager = {
        setActiveNavItem: setActiveNavItem,
        getCurrentPage: getCurrentPage
    };
})();
