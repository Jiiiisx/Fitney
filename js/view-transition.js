// View Transition Handler
class ViewTransitionHandler {
    constructor() {
        this.init();
    }

    init() {
        // Check if view transitions are supported
        if (!document.startViewTransition) {
            document.documentElement.classList.add('no-view-transitions');
            this.setupFallback();
            return;
        }

        this.setupViewTransitions();
    }

    setupViewTransitions() {
        // Handle all navigation links with view-transition class
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href].view-transition');
            if (!link) return;

            e.preventDefault();
            this.navigateWithTransition(link.href);
        });

        // Handle form submissions with transition
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.classList.contains('view-transition-form')) {
                e.preventDefault();
                this.handleFormTransition(form);
            }
        });
    }

    async navigateWithTransition(url) {
        try {
            // Create overlay element
            this.createTransitionOverlay();
            document.body.classList.add('page-transitioning');
            
            // Small delay to ensure overlay is rendered
            await new Promise(resolve => setTimeout(resolve, 50));
            
            const transition = document.startViewTransition(() => {
                window.location.href = url;
            });

            await transition.finished;
        } catch (error) {
            console.error('View transition failed:', error);
            window.location.href = url;
        }
    }

    createTransitionOverlay() {
        // Remove existing overlay if any
        const existingOverlay = document.querySelector('.view-transition-overlay');
        if (existingOverlay) {
            existingOverlay.remove();
        }

        // Create new overlay
        const overlay = document.createElement('div');
        overlay.className = 'view-transition-overlay';
        document.body.appendChild(overlay);
    }

    async handleFormTransition(form) {
        try {
            document.body.classList.add('page-transitioning');
            
            const formData = new FormData(form);
            const action = form.action || window.location.href;
            
            const transition = document.startViewTransition(async () => {
                const response = await fetch(action, {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    window.location.href = response.url;
                }
            });

            await transition.finished;
        } catch (error) {
            console.error('Form transition failed:', error);
            form.submit();
        }
    }

    setupFallback() {
        // Fallback for browsers without view transition support
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href].view-transition');
            if (!link) return;

            e.preventDefault();
            const container = document.querySelector('.container');
            container.classList.add('fade-out');
            
            setTimeout(() => {
                window.location.href = link.href;
            }, 300);
        });
    }
}

// Initialize view transition handler
document.addEventListener('DOMContentLoaded', () => {
    new ViewTransitionHandler();
});

// Utility function for programmatic navigation
function navigateWithViewTransition(url) {
    if (document.startViewTransition) {
        document.startViewTransition(() => {
            window.location.href = url;
        });
    } else {
        window.location.href = url;
    }
}
