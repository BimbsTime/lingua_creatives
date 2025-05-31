// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get the form element
    const contactForm = document.getElementById('contactForm');
    const formStatus = document.getElementById('form-status');
    
    // Function to display status message
    function showStatus(type, message) {
        formStatus.innerHTML = `<div class="${type}-message">${message}</div>`;
        
        // Clear status message after 5 seconds if it's a success message
        if (type === 'success') {
            setTimeout(() => {
                formStatus.innerHTML = '';
            }, 5000);
        }
    }

    // Add form submission event listener
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic form validation
            const nameInput = contactForm.querySelector('#name');
            const emailInput = contactForm.querySelector('#email');
            const messageInput = contactForm.querySelector('#message');
            
            if (!nameInput.value.trim()) {
                showStatus('error', 'Please enter your name.');
                nameInput.focus();
                return;
            }
            
            if (!emailInput.value.trim() || !isValidEmail(emailInput.value)) {
                showStatus('error', 'Please enter a valid email address.');
                emailInput.focus();
                return;
            }
            
            if (!messageInput.value.trim()) {
                showStatus('error', 'Please enter your message.');
                messageInput.focus();
                return;
            }
            
            // Show loading state
            const submitButton = contactForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            submitButton.textContent = 'Sending...';
            submitButton.disabled = true;
            
            // Use FormData to collect all form fields
            const formData = new FormData(contactForm);
            
            // Send form data using fetch API
            fetch('process-form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success message
                    showStatus('success', data.message);
                    // Reset the form
                    contactForm.reset();
                } else {
                    // Error message
                    showStatus('error', data.message);
                }
                
                // Reset button
                submitButton.textContent = originalButtonText;
                submitButton.disabled = false;
            })
            .catch(error => {
                // Error message
                showStatus('error', 'Oops! Something went wrong. Please try again later.');
                
                // Reset button
                submitButton.textContent = originalButtonText;
                submitButton.disabled = false;
                
                console.error('Error:', error);
            });
        });
    }
    
    // Email validation helper function
    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
}); 