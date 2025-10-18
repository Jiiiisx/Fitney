// Working View Transition untuk Sign In Button
document.addEventListener('DOMContentLoaded', function() {
    const signInBtn = document.querySelector('.btn-signin');
    
    if (signInBtn) {
        signInBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetUrl = this.getAttribute('href');
            const button = this;
            
            // Animasi keluar
            button.style.transition = 'all 0.3s ease';
            button.style.transform = 'scale(0.9)';
            button.style.opacity = '0.5';
            
            // Fade out seluruh halaman
            document.body.style.transition = 'opacity 0.4s ease';
            document.body.style.opacity = '0';
            
            // Navigasi setelah animasi
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 400);
        });
    }
});

// Tambahkan CSS untuk efek transisi
const style = document.createElement('style');
style.textContent = `
    body {
        transition: opacity 0.4s ease;
    }
    
    .btn-signin {
        transition: all 0.3s ease;
    }
`;
document.head.appendChild(style);
