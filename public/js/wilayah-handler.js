/**
 * Wilayah Indonesia Dropdown Handler
 * Handles province, regency, district, and village dropdown dependencies
 */

class WilayahHandler {
    constructor() {
        this.baseUrl = window.location.origin + '/api/wilayah';
        this.loadingText = 'Memuat...';
        this.defaultOptionText = 'Pilih';
        this.init();
    }

    init() {
        this.loadProvinces();
        this.setupEventListeners();
    }

    /**
     * Setup event listeners for dropdown changes
     */
    setupEventListeners() {
        // KTP Address Event Listeners
        const provinsiKtp = document.getElementById('ProvinsiKtpKry');
        const kotaKtp = document.getElementById('KotaKtpKry');
        const kecamatanKtp = document.getElementById('KecamatanKtpKry');
        const kelurahanKtp = document.getElementById('KelurahanKtpKry');

        if (provinsiKtp) {
            provinsiKtp.addEventListener('change', (e) => {
                const provinceId = e.target.value;
                this.loadRegencies(provinceId, 'KotaKtpKry');
                this.resetDropdown('KecamatanKtpKry');
                this.resetDropdown('KelurahanKtpKry');
            });
        }

        if (kotaKtp) {
            kotaKtp.addEventListener('change', (e) => {
                const regencyId = e.target.value;
                this.loadDistricts(regencyId, 'KecamatanKtpKry');
                this.resetDropdown('KelurahanKtpKry');
            });
        }

        if (kecamatanKtp) {
            kecamatanKtp.addEventListener('change', (e) => {
                const districtId = e.target.value;
                this.loadVillages(districtId, 'KelurahanKtpKry');
            });
        }

        // Domicile Address Event Listeners
        const provinsiDom = document.getElementById('ProvinsiDomKry');
        const kotaDom = document.getElementById('KotaDomKry');
        const kecamatanDom = document.getElementById('KecamatanDomKry');
        const kelurahanDom = document.getElementById('KelurahanDomKry');

        if (provinsiDom) {
            provinsiDom.addEventListener('change', (e) => {
                const provinceId = e.target.value;
                this.loadRegencies(provinceId, 'KotaDomKry');
                this.resetDropdown('KecamatanDomKry');
                this.resetDropdown('KelurahanDomKry');
            });
        }

        if (kotaDom) {
            kotaDom.addEventListener('change', (e) => {
                const regencyId = e.target.value;
                this.loadDistricts(regencyId, 'KecamatanDomKry');
                this.resetDropdown('KelurahanDomKry');
            });
        }

        if (kecamatanDom) {
            kecamatanDom.addEventListener('change', (e) => {
                const districtId = e.target.value;
                this.loadVillages(districtId, 'KelurahanDomKry');
            });
        }
    }

    /**
     * Load provinces from API
     */
    async loadProvinces() {
        try {
            this.showLoading('ProvinsiKtpKry', this.loadingText);
            this.showLoading('ProvinsiDomKry', this.loadingText);

            const response = await fetch(`${this.baseUrl}/provinces`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.status === 'success') {
                this.populateDropdown('ProvinsiKtpKry', result.data, 'id', 'name', this.defaultOptionText + ' Provinsi');
                this.populateDropdown('ProvinsiDomKry', result.data, 'id', 'name', this.defaultOptionText + ' Provinsi');
            } else {
                throw new Error(result.message || 'Failed to load provinces');
            }
        } catch (error) {
            console.error('Error loading provinces:', error);
            this.showError('ProvinsiKtpKry', 'Error loading provinces');
            this.showError('ProvinsiDomKry', 'Error loading provinces');
            this.showNotification('Error memuat data provinsi: ' + error.message, 'error');
        }
    }

    /**
     * Load regencies by province ID
     */
    async loadRegencies(provinceId, targetDropdownId) {
        if (!provinceId) {
            this.resetDropdown(targetDropdownId);
            return;
        }

        try {
            this.showLoading(targetDropdownId, this.loadingText);

            const response = await fetch(`${this.baseUrl}/regencies/${provinceId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.status === 'success') {
                this.populateDropdown(targetDropdownId, result.data, 'id', 'name', this.defaultOptionText + ' Kota/Kabupaten');
            } else {
                throw new Error(result.message || 'Failed to load regencies');
            }
        } catch (error) {
            console.error('Error loading regencies:', error);
            this.showError(targetDropdownId, 'Error loading cities');
            this.showNotification('Error memuat data kota/kabupaten: ' + error.message, 'error');
        }
    }

    /**
     * Load districts by regency ID
     */
    async loadDistricts(regencyId, targetDropdownId) {
        if (!regencyId) {
            this.resetDropdown(targetDropdownId);
            return;
        }

        try {
            this.showLoading(targetDropdownId, this.loadingText);

            const response = await fetch(`${this.baseUrl}/districts/${regencyId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.status === 'success') {
                this.populateDropdown(targetDropdownId, result.data, 'id', 'name', this.defaultOptionText + ' Kecamatan');
            } else {
                throw new Error(result.message || 'Failed to load districts');
            }
        } catch (error) {
            console.error('Error loading districts:', error);
            this.showError(targetDropdownId, 'Error loading districts');
            this.showNotification('Error memuat data kecamatan: ' + error.message, 'error');
        }
    }

    /**
     * Load villages by district ID
     */
    async loadVillages(districtId, targetDropdownId) {
        if (!districtId) {
            this.resetDropdown(targetDropdownId);
            return;
        }

        try {
            this.showLoading(targetDropdownId, this.loadingText);

            const response = await fetch(`${this.baseUrl}/villages/${districtId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.status === 'success') {
                this.populateDropdown(targetDropdownId, result.data, 'id', 'name', this.defaultOptionText + ' Kelurahan/Desa');
            } else {
                throw new Error(result.message || 'Failed to load villages');
            }
        } catch (error) {
            console.error('Error loading villages:', error);
            this.showError(targetDropdownId, 'Error loading villages');
            this.showNotification('Error memuat data kelurahan/desa: ' + error.message, 'error');
        }
    }

    /**
     * Populate dropdown with data
     */
    populateDropdown(dropdownId, data, valueField, textField, defaultText = '') {
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return;

        dropdown.innerHTML = '';

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = defaultText || this.defaultOptionText;
        dropdown.appendChild(defaultOption);

        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[valueField];
            option.textContent = item[textField];

            const savedValue = dropdown.getAttribute('data-selected-value');
            if (savedValue && savedValue == item[valueField]) {
                option.selected = true;
                dropdown.removeAttribute('data-selected-value');
            }

            dropdown.appendChild(option);
        });

        dropdown.disabled = false;
        dropdown.classList.remove('loading');
    }

    /**
     * Reset dropdown to default state
     */
    resetDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return;

        dropdown.innerHTML = '';
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = this.defaultOptionText;
        dropdown.appendChild(defaultOption);
        dropdown.disabled = false;
        dropdown.classList.remove('loading');
    }

    /**
     * Show loading state for dropdown
     */
    showLoading(dropdownId, text = 'Loading...') {
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return;

        dropdown.innerHTML = '';
        const loadingOption = document.createElement('option');
        loadingOption.value = '';
        loadingOption.textContent = text;
        dropdown.appendChild(loadingOption);
        dropdown.disabled = true;
        dropdown.classList.add('loading');
    }

    /**
     * Show error state for dropdown
     */
    showError(dropdownId, errorText = 'Error') {
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return;

        dropdown.innerHTML = '';
        const errorOption = document.createElement('option');
        errorOption.value = '';
        errorOption.textContent = errorText;
        dropdown.appendChild(errorOption);
        dropdown.disabled = false;
        dropdown.classList.remove('loading');
        dropdown.classList.add('error');
    }

    /**
     * Show notification to user
     */
    showNotification(message, type = 'info') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: type === 'error' ? 'Error' : 'Info',
                text: message,
                icon: type,
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            console.log(`[${type.toUpperCase()}] ${message}`);
        }
    }

    /**
     * Copy KTP address to domicile address
     */
    copyKtpToDomicile() {
        const fieldsMap = {
            'ProvinsiKtpKry': 'ProvinsiDomKry',
            'KotaKtpKry': 'KotaDomKry',
            'KecamatanKtpKry': 'KecamatanDomKry',
            'KelurahanKtpKry': 'KelurahanDomKry',
            'RtRwKtpKry': 'RtRwDomKry',
            'KodePosKtpKry': 'KodePosDomKry',
            'AlamatKtpKry': 'AlamatDomKry'
        };

        const ktpProvince = document.getElementById('ProvinsiKtpKry');
        const domProvince = document.getElementById('ProvinsiDomKry');

        if (ktpProvince?.value) {
            domProvince.value = ktpProvince.value;
            domProvince.dispatchEvent(new Event('change', { bubbles: true }));

            setTimeout(() => {
                const ktpCity = document.getElementById('KotaKtpKry');
                const domCity = document.getElementById('KotaDomKry');

                if (ktpCity?.value) {
                    domCity.value = ktpCity.value;
                    domCity.dispatchEvent(new Event('change', { bubbles: true }));

                    setTimeout(() => {
                        const ktpDistrict = document.getElementById('KecamatanKtpKry');
                        const domDistrict = document.getElementById('KecamatanDomKry');

                        if (ktpDistrict?.value) {
                            domDistrict.value = ktpDistrict.value;
                            domDistrict.dispatchEvent(new Event('change', { bubbles: true }));

                            setTimeout(() => {
                                const ktpVillage = document.getElementById('KelurahanKtpKry');
                                const domVillage = document.getElementById('KelurahanDomKry');

                                if (ktpVillage?.value) {
                                    domVillage.value = ktpVillage.value;
                                }
                            }, 1000);
                        }
                    }, 1000);
                }
            }, 1000);
        }

        // Copy other fields
        Object.keys(fieldsMap).forEach(ktpField => {
            if (['ProvinsiKtpKry', 'KotaKtpKry', 'KecamatanKtpKry', 'KelurahanKtpKry'].includes(ktpField)) {
                return;
            }

            const domField = fieldsMap[ktpField];
            const ktpElement = document.getElementById(ktpField);
            const domElement = document.getElementById(domField);

            if (ktpElement && domElement) {
                domElement.value = ktpElement.value;
            }
        });
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        window.wilayahHandler = new WilayahHandler();
    });
} else {
    window.wilayahHandler = new WilayahHandler();
}