/**
 * Password Protection Script
 * This script adds a password protection overlay to the website.
 * Change the password in the configuration below.
 */

// Configuration
const passwordConfig = {
    password: "lingua2025", // Change this to your desired password
    storageKey: "linguaAuthenticated",
    sessionOnly: false // Set to true to require password on each new browser session
};

// Initialize password protection
function initPasswordProtection() {
    // Check if user is already authenticated
    if (isAuthenticated()) {
        return; // Already authenticated, no need for password
    }
    
    // Create and show password overlay
    createPasswordOverlay();
}

// Check if user is authenticated
function isAuthenticated() {
    if (passwordConfig.sessionOnly) {
        return sessionStorage.getItem(passwordConfig.storageKey) === "true";
    } else {
        return localStorage.getItem(passwordConfig.storageKey) === "true";
    }
}

// Set authentication status
function setAuthenticated(status) {
    if (passwordConfig.sessionOnly) {
        sessionStorage.setItem(passwordConfig.storageKey, status);
    } else {
        localStorage.setItem(passwordConfig.storageKey, status);
    }
}

// Create password overlay
function createPasswordOverlay() {
    const overlay = document.createElement('div');
    overlay.className = 'password-overlay';
    
    overlay.innerHTML = `
        <div class="password-container">
            <h2>Password Protected</h2>
            <p>Please enter the password to view this website.</p>
            <form class="password-form" id="passwordForm">
                <input type="password" id="passwordInput" placeholder="Enter password" required autofocus>
                <button type="submit">Submit</button>
            </form>
            <div class="password-error" id="passwordError">Incorrect password. Please try again.</div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
    
    // Handle form submission
    setupPasswordForm(overlay);
}

// Set up password form event handlers
function setupPasswordForm(overlay) {
    const passwordForm = document.getElementById('passwordForm');
    const passwordInput = document.getElementById('passwordInput');
    const passwordError = document.getElementById('passwordError');
    
    passwordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (passwordInput.value === passwordConfig.password) {
            // Correct password
            setAuthenticated(true);
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        } else {
            // Incorrect password
            passwordError.style.display = 'block';
            passwordInput.value = '';
            passwordInput.focus();
            
            // Hide error message after 3 seconds
            setTimeout(() => {
                passwordError.style.display = 'none';
            }, 3000);
        }
    });
}

// Add a logout function that can be called via console
window.logoutFromSite = function() {
    setAuthenticated(false);
    window.location.reload();
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initPasswordProtection); 