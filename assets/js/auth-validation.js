/**
 * Authentication Form Validation
 * 
 * Provides real-time client-side validation for login and registration forms.
 * Validates on blur and enables submit button when form is valid.
 * 
 * @module auth-validation
 */

/**
 * Validation rules for form fields
 */
const validationRules = {
    username: {
        minLength: 3,
        pattern: /^[a-zA-Z0-9_]+$/,
        message: 'Username must be at least 3 characters and contain only letters, numbers, and underscores'
    },
    password: {
        minLength: 6,
        message: 'Password must be at least 6 characters'
    }
};

/**
 * Validate a single field
 * 
 * @param {HTMLInputElement} field - The input field to validate
 * @returns {boolean} True if field is valid
 */
function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.id;
    const rules = validationRules[fieldName];
    
    if (!rules) {
        return true;
    }
    
    // Check if required
    if (field.required && !value) {
        setFieldInvalid(field, 'This field is required');
        return false;
    }
    
    // Skip further validation if empty and not required
    if (!value) {
        setFieldValid(field);
        return true;
    }
    
    // Check minimum length
    if (rules.minLength && value.length < rules.minLength) {
        setFieldInvalid(field, rules.message);
        return false;
    }
    
    // Check pattern if defined
    if (rules.pattern && !rules.pattern.test(value)) {
        setFieldInvalid(field, rules.message);
        return false;
    }
    
    setFieldValid(field);
    return true;
}

/**
 * Set field as invalid with error message
 * 
 * @param {HTMLInputElement} field - The input field
 * @param {string} message - Error message to display
 */
function setFieldInvalid(field, message) {
    field.classList.remove('is-valid');
    field.classList.add('is-invalid');
    
    // Show or create error message
    let feedback = field.parentNode.querySelector('.invalid-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        field.parentNode.appendChild(feedback);
    }
    feedback.textContent = message;
    feedback.style.display = 'block';
}

/**
 * Set field as valid
 * 
 * @param {HTMLInputElement} field - The input field
 */
function setFieldValid(field) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
    
    // Hide error message if exists
    const feedback = field.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.style.display = 'none';
    }
}

/**
 * Check if entire form is valid
 * 
 * @param {HTMLFormElement} form - The form to validate
 * @returns {boolean} True if all fields are valid
 */
function isFormValid(form) {
    const fields = form.querySelectorAll('input[required], input[minlength]');
    let isValid = true;
    
    fields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Initialize validation for a form
 * 
 * @param {string} formId - The form element ID
 * @param {string} submitBtnId - The submit button element ID
 */
function initAuthValidation(formId, submitBtnId) {
    const form = document.getElementById(formId);
    const submitBtn = document.getElementById(submitBtnId);
    
    if (!form || !submitBtn) {
        console.error('Form or submit button not found');
        return;
    }
    
    // Get all input fields
    const inputs = form.querySelectorAll('input');
    
    // Add blur event listeners for real-time validation
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
            // Check form validity after each field validation
            submitBtn.disabled = !isFormValid(form);
        });
        
        // Add input event listener to clear validation on typing
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
            submitBtn.disabled = !isFormValid(form);
        });
    });
    
    // Initial check
    submitBtn.disabled = !isFormValid(form);
    
    // Form submit validation
    form.addEventListener('submit', function(e) {
        if (!isFormValid(form)) {
            e.preventDefault();
            // Trigger validation on all fields
            inputs.forEach(input => validateField(input));
        }
    });
}

// Export for module usage (if needed)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        validateField,
        isFormValid,
        initAuthValidation,
        validationRules
    };
}
