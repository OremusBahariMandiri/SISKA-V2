/**
 * Auto Uppercase Helper
 * Automatically converts text input to uppercase as user types
 *
 * @version 1.0.0
 * @author Laravel Helper
 */

class AutoUppercase {
    constructor(options = {}) {
        this.options = {
            // CSS selector for inputs that should be auto-uppercase
            selector: '.auto-uppercase',
            // Whether to apply to all text inputs by default
            applyToAllTextInputs: false,
            // Exclude certain input types
            excludeTypes: ['email', 'url', 'password'],
            // Exclude inputs with certain classes
            excludeClasses: ['no-uppercase', 'email-input', 'url-input'],
            // Custom transformation function
            transform: null,
            // Debug mode
            debug: false,
            ...options
        };

        this.init();
    }

    /**
     * Initialize the auto uppercase functionality
     */
    init() {
        if (this.options.debug) {
            console.log('AutoUppercase: Initializing...');
        }

        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.bindEvents());
        } else {
            this.bindEvents();
        }
    }

    /**
     * Bind events to form inputs
     */
    bindEvents() {
        let inputs = [];

        // Get inputs based on configuration
        if (this.options.applyToAllTextInputs) {
            inputs = this.getAllTextInputs();
        } else {
            inputs = document.querySelectorAll(this.options.selector);
        }

        if (this.options.debug) {
            console.log(`AutoUppercase: Found ${inputs.length} inputs to bind`);
        }

        inputs.forEach(input => this.bindInputEvents(input));

        // Also bind to dynamically added inputs
        this.observeDynamicInputs();
    }

    /**
     * Get all text inputs that should be auto-uppercase
     */
    getAllTextInputs() {
        const allInputs = document.querySelectorAll('input[type="text"], input:not([type]), textarea');
        return Array.from(allInputs).filter(input => !this.shouldExcludeInput(input));
    }

    /**
     * Check if input should be excluded from auto-uppercase
     */
    shouldExcludeInput(input) {
        // Check type exclusions
        if (this.options.excludeTypes.includes(input.type)) {
            return true;
        }

        // Check class exclusions
        return this.options.excludeClasses.some(className =>
            input.classList.contains(className)
        );
    }

    /**
     * Bind events to a specific input
     */
    bindInputEvents(input) {
        if (this.shouldExcludeInput(input)) {
            return;
        }

        // Mark as processed to avoid double binding
        if (input.dataset.autoUppercaseProcessed) {
            return;
        }
        input.dataset.autoUppercaseProcessed = 'true';

        // Add visual indicator (optional)
        input.style.textTransform = 'uppercase';
        input.setAttribute('data-auto-uppercase', 'true');

        // Bind input event
        input.addEventListener('input', (e) => this.handleInput(e));

        // Bind paste event
        input.addEventListener('paste', (e) => this.handlePaste(e));

        // Handle existing value
        if (input.value) {
            this.transformValue(input);
        }

        if (this.options.debug) {
            console.log('AutoUppercase: Bound events to input', input);
        }
    }

    /**
     * Handle input event
     */
    handleInput(event) {
        const input = event.target;
        this.transformValue(input);
    }

    /**
     * Handle paste event
     */
    handlePaste(event) {
        const input = event.target;

        // Small delay to allow paste to complete
        setTimeout(() => {
            this.transformValue(input);
        }, 10);
    }

    /**
     * Transform the input value to uppercase
     */
    transformValue(input) {
        const currentValue = input.value;
        let newValue = currentValue;

        if (this.options.transform && typeof this.options.transform === 'function') {
            // Use custom transformation
            newValue = this.options.transform(currentValue);
        } else {
            // Default uppercase transformation
            newValue = currentValue.toUpperCase();
        }

        // Only update if value changed to avoid cursor position issues
        if (newValue !== currentValue) {
            const selectionStart = input.selectionStart;
            const selectionEnd = input.selectionEnd;

            input.value = newValue;

            // Restore cursor position
            if (input.setSelectionRange) {
                input.setSelectionRange(selectionStart, selectionEnd);
            }

            // Trigger change event for frameworks
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    /**
     * Observe for dynamically added inputs
     */
    observeDynamicInputs() {
        if (!window.MutationObserver) {
            return;
        }

        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Check if the node itself is an input
                        if (node.matches && node.matches('input, textarea')) {
                            this.bindInputEvents(node);
                        }

                        // Check for inputs within the added node
                        const inputs = node.querySelectorAll ? node.querySelectorAll('input, textarea') : [];
                        inputs.forEach(input => this.bindInputEvents(input));
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        if (this.options.debug) {
            console.log('AutoUppercase: Dynamic input observer initialized');
        }
    }

    /**
     * Manually add auto-uppercase to specific inputs
     */
    addToInputs(selector) {
        const inputs = document.querySelectorAll(selector);
        inputs.forEach(input => this.bindInputEvents(input));
        return inputs.length;
    }

    /**
     * Remove auto-uppercase from specific inputs
     */
    removeFromInputs(selector) {
        const inputs = document.querySelectorAll(selector);
        inputs.forEach(input => {
            input.style.textTransform = '';
            input.removeAttribute('data-auto-uppercase');
            input.removeAttribute('data-auto-uppercase-processed');
        });
        return inputs.length;
    }

    /**
     * Destroy the auto uppercase functionality
     */
    destroy() {
        const inputs = document.querySelectorAll('[data-auto-uppercase]');
        inputs.forEach(input => {
            input.style.textTransform = '';
            input.removeAttribute('data-auto-uppercase');
            input.removeAttribute('data-auto-uppercase-processed');
        });

        if (this.options.debug) {
            console.log('AutoUppercase: Destroyed');
        }
    }
}

// Auto-initialize with default settings
document.addEventListener('DOMContentLoaded', function() {
    // Initialize with default selector (.auto-uppercase)
    window.autoUppercase = new AutoUppercase();

    // Make it globally available for manual control
    window.AutoUppercase = AutoUppercase;
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AutoUppercase;
}