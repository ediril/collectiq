// Waitlist Component JavaScript

class WaitlistHandler {
    constructor(formSelector = '.collectiq-waitlist-form', endpoint = null) {
        this.formSelector = formSelector;
        this.endpoint = endpoint || this.getEndpointPath();
        this.init();
    }
    
    getEndpointPath() {
        // Try to auto-detect endpoint path based on current script location
        const scripts = document.getElementsByTagName('script');
        for (let script of scripts) {
            if (script.src && script.src.includes('waitlist.js')) {
                return script.src.replace('/assets/waitlist.js', '/endpoint.php');
            }
        }
        // Fallback to relative path
        return '/components/waitlist/endpoint.php';
    }
    
    init() {
        const forms = document.querySelectorAll(this.formSelector);
        if (forms.length === 0) {
            console.warn(`Waitlist form with selector '${this.formSelector}' not found`);
            return;
        }
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => this.handleSubmit(e));
        });
    }
    
    isValidEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    async handleSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitButton = form.querySelector('button[type="submit"]');
        const emailInput = form.querySelector('.collectiq-input');
        const nameInput = form.querySelector('input[type="text"]');
        
        // Disable submit button
        submitButton.disabled = true;
        
        const email = emailInput.value.trim();
        const name = nameInput ? nameInput.value : '';
        
        // Validate email
        if (!this.isValidEmail(email)) {
            alert('Please enter a valid email address');
            submitButton.disabled = false;
            emailInput.focus();
            return;
        }
        
        try {
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email, name: name })
            });
            
            const data = await response.json();
            
            // Handle honeypot response
            if (data.message === "not human") {
                return;
            }
            
            if (!data.success) {
                if (data.message === "too many requests") {
                    alert('Too many requests, please try again later.');
                } else {
                    alert('There was an error. Please try again.');
                }
                submitButton.disabled = false;
                emailInput.focus();
                return;
            }
            
            // Success - hide form and show thank you message
            this.showSuccessMessage(form);
            
        } catch (error) {
            console.error('Waitlist submission error:', error);
            alert('There was an error adding you to the waitlist. Please try again.');
            submitButton.disabled = false;
            emailInput.focus();
        }
    }
    
    showSuccessMessage(form) {
        // Hide submit button so layout remains but no action
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) submitButton.style.display = 'none';

        // Find the input container to replace in-place
        const inputContainer = form.querySelector('.collectiq-input-container');

        // Create thank you message styled like the input container
        const message = document.createElement('div');
        message.className = 'collectiq-input-container collectiq-thank-you';
        message.innerHTML = '<strong>Thank you for signing up!</strong>';

        if (inputContainer && inputContainer.parentNode === form) {
            form.replaceChild(message, inputContainer);
        } else if (inputContainer) {
            inputContainer.replaceWith(message);
        } else {
            // Fallback: insert at top of form
            form.prepend(message);
        }

        form.reset();
    }
}

// Auto-initialize for all waitlist forms
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all waitlist forms
    if (document.querySelector('.collectiq-waitlist-form')) {
        new WaitlistHandler();
    }
});

// Make WaitlistHandler available globally for custom implementations
window.WaitlistHandler = WaitlistHandler;
