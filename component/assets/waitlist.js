// Waitlist Component JavaScript

class WaitlistHandler {
    constructor(formId = 'waitlist-form', endpoint = null) {
        this.formId = formId;
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
        const form = document.getElementById(this.formId);
        if (!form) {
            console.warn(`Waitlist form with ID '${this.formId}' not found`);
            return;
        }
        
        form.addEventListener('submit', (e) => this.handleSubmit(e));
    }
    
    isValidEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    async handleSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitButton = form.querySelector('button[type="submit"]');
        const emailInput = form.querySelector('input[type="email"]');
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
        // Hide the form
        form.style.display = 'none';
        
        // Create and show thank you message
        const message = document.createElement('p');
        message.className = 'input-container thank-you';
        message.innerHTML = '<strong>Thank you for signing up!</strong>';
        
        let messageContainer = document.getElementById('message-container');
        if (!messageContainer) {
            messageContainer = document.createElement('div');
            messageContainer.id = 'message-container';
            form.parentNode.appendChild(messageContainer);
        }
        
        messageContainer.appendChild(message);
        form.reset();
    }
}

// Auto-initialize for default waitlist form
document.addEventListener('DOMContentLoaded', function() {
    // Initialize default waitlist form if it exists
    if (document.getElementById('waitlist-form')) {
        new WaitlistHandler();
    }
});

// Make WaitlistHandler available globally for custom implementations
window.WaitlistHandler = WaitlistHandler;