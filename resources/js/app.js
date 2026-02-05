import './bootstrap';

// Import jQuery FIRST
import $ from 'jquery';
window.$ = window.jQuery = $;

// Import Bootstrap
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Import DataTables
import 'datatables.net';
import 'datatables.net-bs5';
import 'datatables.net-responsive';
import 'datatables.net-responsive-bs5';

// Import Select2 - ensure jQuery is available
try {
    require('select2');
    console.log('Select2 loaded via require');
} catch (e) {
    console.error('Failed to load Select2:', e);
}

// Import CSS
import '../css/app.css';

// Import Select2 CSS
import 'select2/dist/css/select2.min.css';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css';

// Flag untuk mencegah inisialisasi ganda
if (window.appInitialized) {
    console.log("App already initialized, skipping");
} else {
    window.appInitialized = true;

    // Initialize components when document is ready
    document.addEventListener('DOMContentLoaded', function () {
        console.log("Initializing app.js components");
        console.log("jQuery version:", $.fn.jquery);
        console.log("Select2 available:", typeof $.fn.select2);

        // Initialize DataTables
        initDataTables();

        // Initialize Bootstrap components
        initBootstrapComponents();

        // Initialize delete confirmation
        initDeleteConfirmation();

        // Initialize Select2 with delay to ensure DOM is ready
        setTimeout(function() {
            initSelect2();
        }, 100);

        // Auto-hide alerts after 5 seconds
        initAutoHideAlerts();
    });

    // Function to initialize DataTables
    function initDataTables() {
        console.log("Initializing DataTables");
        if ($.fn.DataTable) {
            $('.data-table').each(function () {
                if (!$.fn.DataTable.isDataTable(this)) {
                    console.log("Initializing table:", this.id);
                    $(this).DataTable({
                        responsive: true,
                        columnDefs: [
                            {
                                responsivePriority: 1,
                                targets: [0, 1, -1]
                            },
                            {
                                orderable: false,
                                targets: [-1]
                            }
                        ]
                    });
                } else {
                    console.log("Table already initialized:", this.id);
                }
            });
        }
    }

    // Function to initialize Bootstrap components
    function initBootstrapComponents() {
        console.log("Initializing Bootstrap components");

        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.forEach(function (popoverTriggerEl) {
            new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // Function to initialize delete confirmation
    function initDeleteConfirmation() {
        console.log("Initializing delete confirmation");

        $(document).on('click', '.delete-confirm', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const id = $(this).data('id');
            const name = $(this).data('name');
            const url = $(this).data('url') || $(this).data('route');

            console.log("Delete confirmation clicked for:", name);

            $('#itemNameToDelete').text(name);
            $('#jenisNameToDelete').text(name);

            $('#deleteForm').attr('action', url);

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        });
    }

    // Function to initialize Select2
    function initSelect2() {
        console.log("Initializing Select2");
        console.log("jQuery available:", typeof $ !== 'undefined');
        console.log("Select2 plugin available:", typeof $.fn.select2);

        // Check if Select2 is available
        if (typeof $.fn.select2 === 'undefined') {
            console.error('Select2 is not loaded properly - plugin not found on jQuery');
            console.log('Attempting to load Select2 from CDN as fallback...');
            
            // Fallback: load from CDN
            loadSelect2FromCDN();
            return;
        }

        try {
            // Initialize all select elements with class 'select2'
            $('.select2').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    console.log('Initializing select2 on:', this);
                    $(this).select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: $(this).data('placeholder') || 'Pilih opsi',
                        allowClear: true
                    });
                }
            });

            // Initialize select2 with search
            $('.select2-search').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: $(this).data('placeholder') || 'Cari dan pilih',
                        allowClear: true,
                        minimumInputLength: 0
                    });
                }
            });

            // Initialize select2 with tags (allows creating new options)
            $('.select2-tags').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: $(this).data('placeholder') || 'Pilih atau ketik',
                        allowClear: true,
                        tags: true
                    });
                }
            });

            console.log('Select2 initialized successfully');
        } catch (error) {
            console.error('Error initializing Select2:', error);
        }
    }

    // Fallback function to load Select2 from CDN
    function loadSelect2FromCDN() {
        // Check if already loading
        if (window.select2Loading) {
            console.log('Select2 already loading from CDN');
            return;
        }
        
        window.select2Loading = true;
        
        // Load Select2 CSS
        const cssLink1 = document.createElement('link');
        cssLink1.rel = 'stylesheet';
        cssLink1.href = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css';
        document.head.appendChild(cssLink1);
        
        const cssLink2 = document.createElement('link');
        cssLink2.rel = 'stylesheet';
        cssLink2.href = 'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css';
        document.head.appendChild(cssLink2);
        
        // Load Select2 JS
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
        script.onload = function() {
            console.log('Select2 loaded successfully from CDN');
            window.select2Loading = false;
            // Retry initialization
            setTimeout(initSelect2, 100);
        };
        script.onerror = function() {
            console.error('Failed to load Select2 from CDN');
            window.select2Loading = false;
        };
        document.head.appendChild(script);
    }

    // Function to auto-hide alerts
    function initAutoHideAlerts() {
        setTimeout(function () {
            $(".alert").fadeOut("slow");
        }, 5000);
    }

    // Export initSelect2 function for reinitializing after dynamic content load
    window.reinitSelect2 = initSelect2;
}