/**
 * AJAX Utilities for Admin Panel
 * Common functions for AJAX form submission and CRUD operations
 */

// Set up CSRF token for all AJAX requests
const ajaxUtils = {
    
    /**
     * Get CSRF token from meta tag
     */
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content;
    },
    
    /**
     * Submit form via AJAX
     * @param {HTMLFormElement} form - The form element
     * @param {Object} options - Configuration options
     */
    submitForm(form, options = {}) {
        const {
            onSuccess = (data) => { 
                alert(data.message); 
                if (options.redirectTo) {
                    window.location.href = options.redirectTo;
                }
            },
            onError = (error) => { 
                console.error('Error:', error); 
                alert('Có lỗi xảy ra, vui lòng thử lại!'); 
            },
            showLoading = true,
            redirectTo = null
        } = options;
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const btnText = submitBtn?.querySelector('.btn-text');
        const spinner = submitBtn?.querySelector('.spinner-border');
        
        // Show loading state
        if (showLoading && submitBtn) {
            submitBtn.disabled = true;
            if (btnText) btnText.textContent = 'Đang xử lý...';
            if (spinner) spinner.classList.remove('d-none');
        }
        
        const formData = new FormData(form);
        
        fetch(form.action || window.location.href, {
            method: form.method || 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.getCsrfToken()
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                onSuccess(data);
            } else {
                alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
            }
        })
        .catch(onError)
        .finally(() => {
            // Hide loading state
            if (showLoading && submitBtn) {
                submitBtn.disabled = false;
                if (btnText) btnText.textContent = options.originalBtnText || 'Lưu';
                if (spinner) spinner.classList.add('d-none');
            }
        });
    },
    
    /**
     * Delete item via AJAX
     * @param {string} url - Delete URL
     * @param {string} itemName - Name of item for confirmation
     * @param {HTMLElement} triggerElement - Element that triggered delete
     * @param {Function} onSuccess - Success callback
     */
    deleteItem(url, itemName, triggerElement, onSuccess = null) {
        if (!confirm(`Bạn có chắc muốn xóa "${itemName}"?`)) {
            return;
        }
        
        // Show loading state
        const originalContent = triggerElement.innerHTML;
        triggerElement.innerHTML = '<i class="ri-loader-4-line"></i>';
        triggerElement.disabled = true;
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.getCsrfToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (onSuccess) {
                    onSuccess(data);
                } else {
                    // Default: remove row and show message
                    const row = triggerElement.closest('tr');
                    if (row) row.remove();
                    this.showAlert(data.message, 'success');
                }
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa!');
                // Restore button state
                triggerElement.innerHTML = originalContent;
                triggerElement.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa!');
            // Restore button state
            triggerElement.innerHTML = originalContent;
            triggerElement.disabled = false;
        });
    },
    
    /**
     * Toggle status via AJAX
     * @param {string} url - Toggle URL
     * @param {HTMLElement} triggerElement - Element that triggered toggle
     * @param {Function} onSuccess - Success callback
     */
    toggleStatus(url, triggerElement, onSuccess = null) {
        const originalText = triggerElement.textContent;
        triggerElement.textContent = 'Đang xử lý...';
        triggerElement.disabled = true;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.getCsrfToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (onSuccess) {
                    onSuccess(data);
                } else {
                    this.showAlert(data.message, 'success');
                }
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
                triggerElement.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra!');
            triggerElement.textContent = originalText;
        })
        .finally(() => {
            triggerElement.disabled = false;
        });
    },
    
    /**
     * Show alert message
     * @param {string} message - Alert message
     * @param {string} type - Alert type (success, danger, warning, info)
     */
    showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-3`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert alert at the top of main content
        const container = document.querySelector('.container-fluid') || document.querySelector('.container') || document.body;
        const firstChild = container.firstElementChild;
        container.insertBefore(alertDiv, firstChild);
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    },
    
    /**
     * Set up form with AJAX submission
     * @param {string} formSelector - CSS selector for form
     * @param {Object} options - Configuration options
     */
    setupForm(formSelector, options = {}) {
        const form = document.querySelector(formSelector);
        if (!form) return;
        
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitForm(form, options);
        });
    },
    
    /**
     * Set up delete buttons with AJAX
     * @param {string} buttonSelector - CSS selector for delete buttons
     * @param {Object} options - Configuration options
     */
    setupDeleteButtons(buttonSelector, options = {}) {
        document.querySelectorAll(buttonSelector).forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const name = button.dataset.name;
                const url = options.urlPattern ? options.urlPattern.replace(':id', id) : button.dataset.url;
                
                this.deleteItem(url, name, button, options.onSuccess);
            });
        });
    },
    
    /**
     * Set up toggle buttons with AJAX
     * @param {string} buttonSelector - CSS selector for toggle buttons
     * @param {Object} options - Configuration options
     */
    setupToggleButtons(buttonSelector, options = {}) {
        document.querySelectorAll(buttonSelector).forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const url = options.urlPattern ? options.urlPattern.replace(':id', id) : button.dataset.url;
                
                this.toggleStatus(url, button, options.onSuccess);
            });
        });
    }
};

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ajaxUtils;
}

// Make available globally
window.ajaxUtils = ajaxUtils;
