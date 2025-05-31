// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileToggle = document.querySelector('.mobile-toggle');
    const mainNavigation = document.querySelector('.main-navigation');
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            mainNavigation.classList.toggle('active');
        });
    }
    
    // Dropdown menu toggle for mobile
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            if (window.innerWidth <= 992) {
                e.preventDefault();
                this.parentNode.classList.toggle('dropdown-active');
            }
        });
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (mainNavigation && mainNavigation.classList.contains('active')) {
            if (!mainNavigation.contains(e.target) && e.target !== mobileToggle && !mobileToggle.contains(e.target)) {
                mainNavigation.classList.remove('active');
                if (mobileToggle) {
                    mobileToggle.classList.remove('active');
                }
            }
        }
    });
    
    // Close dropdowns when window is resized
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
            document.querySelectorAll('.dropdown-active').forEach(function(item) {
                item.classList.remove('dropdown-active');
            });
        }
        
        // If mobile menu is open and screen size changes to desktop, close it
        if (window.innerWidth > 992 && mainNavigation && mainNavigation.classList.contains('active')) {
            mainNavigation.classList.remove('active');
            if (mobileToggle) {
                mobileToggle.classList.remove('active');
            }
        }
    });
    
    // Enhanced animated scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            if (this.getAttribute('href') !== '#') {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    // Close mobile menu if open
                    if (mobileToggle && mobileToggle.classList.contains('active')) {
                        mobileToggle.classList.remove('active');
                        mainNavigation.classList.remove('active');
                    }
                    
                    // Improved smooth scroll to target with offset adjustment based on screen size
                    const headerOffset = window.innerWidth <= 768 ? 60 : 80;
                    const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerOffset;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Update URL hash after scrolling
                    setTimeout(() => {
                        history.pushState(null, null, targetId);
                    }, 1000);
                }
            }
        });
    });
    
    // Improved animation for elements when they come into view
    const fadeElements = document.querySelectorAll('.fade-in');
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    // Unobserve after animation is triggered
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        fadeElements.forEach(element => {
            observer.observe(element);
        });
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        function checkIfInView() {
            const windowHeight = window.innerHeight;
            const windowTopPosition = window.scrollY;
            const windowBottomPosition = windowTopPosition + windowHeight;
            
            fadeElements.forEach(function(element) {
                const elementHeight = element.offsetHeight;
                const elementTopPosition = element.offsetTop;
                const elementBottomPosition = elementTopPosition + elementHeight;
                
                // Check if element is in viewport with buffer
                if ((elementBottomPosition >= windowTopPosition - 100) && 
                    (elementTopPosition <= windowBottomPosition + 100)) {
                    element.classList.add('visible');
                }
            });
        }
        
        // Initial check and add scroll event listener
        checkIfInView();
        window.addEventListener('scroll', checkIfInView);
    }
    
    // Active menu items based on current page
    const currentPage = window.location.pathname.split('/').pop();
    
    document.querySelectorAll('nav ul li a').forEach(link => {
        if (link.getAttribute('href') === currentPage || 
            (currentPage === '' && link.getAttribute('href') === 'index.html')) {
            link.classList.add('active');
        }
    });
    
    // Add touch optimization for better mobile experience
    if ('ontouchstart' in window || navigator.maxTouchPoints) {
        document.body.classList.add('touch-device');
        
        // Add hover effect for cards on touch
        const interactiveElements = document.querySelectorAll('.service-card, .industry-card, .feature-card, .opportunity-card, .sub-service-item');
        
        interactiveElements.forEach(element => {
            element.addEventListener('touchstart', function() {
                this.classList.add('touch-focus');
            }, {passive: true});
            
            element.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('touch-focus');
                }, 300);
            }, {passive: true});
        });
    }
    
    // Form submission handling for contact form
    const contactForm = document.querySelector('.contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic form validation
            const nameInput = contactForm.querySelector('input[name="name"]');
            const emailInput = contactForm.querySelector('input[name="email"]');
            const messageInput = contactForm.querySelector('textarea[name="message"]');
            
            let isValid = true;
            
            if (!nameInput.value.trim()) {
                nameInput.classList.add('error');
                isValid = false;
            } else {
                nameInput.classList.remove('error');
            }
            
            if (!emailInput.value.trim() || !isValidEmail(emailInput.value)) {
                emailInput.classList.add('error');
                isValid = false;
            } else {
                emailInput.classList.remove('error');
            }
            
            if (!messageInput.value.trim()) {
                messageInput.classList.add('error');
                isValid = false;
            } else {
                messageInput.classList.remove('error');
            }
            
            if (isValid) {
                // Show success message (in a real implementation, this would submit the form to a server)
                const successMessage = document.createElement('div');
                successMessage.className = 'success-message';
                successMessage.textContent = 'Thank you for your message! We will get back to you soon.';
                
                contactForm.innerHTML = '';
                contactForm.appendChild(successMessage);
            }
        });
    }
    
    // Email validation helper function
    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});

// Delay animation start until page is loaded
window.addEventListener('load', function() {
    document.body.classList.add('loaded');
    
    // Scroll to hash target if present in URL
    if (window.location.hash) {
        const targetElement = document.querySelector(window.location.hash);
        if (targetElement) {
            setTimeout(() => {
                const headerOffset = window.innerWidth <= 768 ? 60 : 80;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }, 500);
        }
    }
}); 