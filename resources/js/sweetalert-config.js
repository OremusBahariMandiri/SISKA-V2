/**
 * SweetAlert2 Global Configuration
 * File: resources/js/sweetalert-config.js
 *
 * Provides centralized configuration for all SweetAlert2 instances
 * across the application with consistent styling and behavior.
 */

import Swal from 'sweetalert2';

// Set default configuration for all Swal instances
export const setupSweetAlert = () => {
    // Default Swal configuration
    Swal.mixin({
        customClass: {
            container: 'swal-container',
            popup: 'swal-popup',
            title: 'swal-title',
            content: 'swal-content',
            confirmButton: 'swal-btn swal-btn-confirm',
            cancelButton: 'swal-btn swal-btn-cancel',
            denyButton: 'swal-btn swal-btn-deny',
        },
        buttonsStyling: false,
        didOpen: (modal) => {
            // Add custom animations if needed
            modal.classList.add('swal-animate-in');
        }
    });
};

/**
 * Success Alert
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {function} callback - Callback function on confirm
 */
export const showSuccess = (title, message, callback = null) => {
    return Swal.fire({
        icon: 'success',
        title: title || 'Berhasil',
        html: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#198754',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
    }).then((result) => {
        if (result.isConfirmed || result.isDismissed) {
            if (callback && typeof callback === 'function') {
                callback();
            }
        }
    });
};

/**
 * Error Alert
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {function} callback - Callback function on confirm
 */
export const showError = (title, message, callback = null) => {
    return Swal.fire({
        icon: 'error',
        title: title || 'Error',
        html: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545',
        allowOutsideClick: false,
        allowEscapeKey: false,
    }).then((result) => {
        if (result.isConfirmed) {
            if (callback && typeof callback === 'function') {
                callback();
            }
        }
    });
};

/**
 * Warning Alert
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {function} callback - Callback function on confirm
 */
export const showWarning = (title, message, callback = null) => {
    return Swal.fire({
        icon: 'warning',
        title: title || 'Peringatan',
        html: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#ffc107',
        allowOutsideClick: false,
        allowEscapeKey: false,
    }).then((result) => {
        if (result.isConfirmed) {
            if (callback && typeof callback === 'function') {
                callback();
            }
        }
    });
};

/**
 * Info Alert
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {function} callback - Callback function on confirm
 */
export const showInfo = (title, message, callback = null) => {
    return Swal.fire({
        icon: 'info',
        title: title || 'Informasi',
        html: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#0d6efd',
        allowOutsideClick: false,
        allowEscapeKey: false,
    }).then((result) => {
        if (result.isConfirmed) {
            if (callback && typeof callback === 'function') {
                callback();
            }
        }
    });
};

/**
 * Confirmation Alert (Yes/No)
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {function} onConfirm - Callback when confirmed
 * @param {function} onCancel - Callback when cancelled
 * @param {object} options - Additional options
 */
export const showConfirm = (title, message, onConfirm, onCancel = null, options = {}) => {
    const defaultOptions = {
        icon: 'warning',
        title: title || 'Konfirmasi',
        html: message,
        showCancelButton: true,
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        reverseButtons: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        ...options
    };

    return Swal.fire(defaultOptions).then((result) => {
        if (result.isConfirmed) {
            if (onConfirm && typeof onConfirm === 'function') {
                onConfirm();
            }
        } else if (result.isDismissed) {
            if (onCancel && typeof onCancel === 'function') {
                onCancel();
            }
        }
    });
};

/**
 * Delete Confirmation Alert
 * @param {string} itemName - Name of item to delete
 * @param {function} onConfirm - Callback when confirmed
 * @param {function} onCancel - Callback when cancelled
 */
export const showDeleteConfirm = (itemName, onConfirm, onCancel = null) => {
    return Swal.fire({
        icon: 'warning',
        title: 'Konfirmasi Hapus',
        html: `<p>Apakah Anda yakin ingin menghapus <strong>${itemName}</strong>?</p>
               <p class="text-muted small mt-2">Data yang sudah dihapus tidak dapat dikembalikan.</p>`,
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: (modal) => {
            // Focus on cancel button for safety
            modal.querySelector('.swal-btn-cancel').focus();
        }
    }).then((result) => {
        if (result.isConfirmed) {
            if (onConfirm && typeof onConfirm === 'function') {
                onConfirm();
            }
        } else if (result.isDismissed) {
            if (onCancel && typeof onCancel === 'function') {
                onCancel();
            }
        }
    });
};

/**
 * Loading Alert (shows spinner)
 * @param {string} message - Loading message
 */
export const showLoading = (message = 'Memproses...') => {
    return Swal.fire({
        icon: 'info',
        title: message,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: (modal) => {
            Swal.showLoading();
        }
    });
};

/**
 * Close current alert
 */
export const closeAlert = () => {
    Swal.close();
};

/**
 * Hide/Update alert with new content
 * @param {object} config - New Swal configuration
 */
export const updateAlert = (config) => {
    return Swal.update(config);
};

/**
 * Toast notification (corner alert)
 * @param {string} icon - Icon type (success, error, warning, info)
 * @param {string} message - Message to display
 * @param {number} timer - Duration in milliseconds
 */
export const showToast = (icon, message, timer = 3000) => {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: timer,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    return Toast.fire({
        icon: icon,
        title: message
    });
};

/**
 * Custom Alert with full control
 * @param {object} config - Full Swal configuration
 */
export const showAlert = (config) => {
    return Swal.fire(config);
};

export default Swal;