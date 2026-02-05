/**
 * Region API Helper untuk Indonesia
 * Menggunakan API dari https://www.emsifa.com/api-wilayah-indonesia/
 */

const RegionAPI = {
    baseURL: 'https://www.emsifa.com/api-wilayah-indonesia/api',

    /**
     * Load Provinsi
     */
    async loadProvinsi(selectElement) {
        try {
            const response = await fetch(`${this.baseURL}/provinces.json`);
            const data = await response.json();

            // Clear existing options
            selectElement.innerHTML = '<option value="">Pilih Provinsi</option>';

            // Add provinces
            data.forEach(prov => {
                const option = new Option(prov.name, prov.name);
                option.setAttribute('data-id', prov.id);
                selectElement.add(option);
            });

            return data;
        } catch (error) {
            console.error('Error loading provinsi:', error);
            throw error;
        }
    },

    /**
     * Load Kota/Kabupaten by Provinsi ID
     */
    async loadKota(provId, selectElement) {
        try {
            const response = await fetch(`${this.baseURL}/regencies/${provId}.json`);
            const data = await response.json();

            // Destroy Select2 first if exists
            if ($(selectElement).data('select2')) {
                $(selectElement).select2('destroy');
            }

            // Clear existing options
            selectElement.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';

            // Add cities
            data.forEach(kota => {
                const option = new Option(kota.name, kota.name);
                option.setAttribute('data-id', kota.id);
                selectElement.add(option);
            });

            // Enable select
            selectElement.disabled = false;

            // Reinitialize Select2 with proper configuration
            $(selectElement).select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih Kota/Kabupaten',
                dropdownParent: $(selectElement).parent()
            });

            return data;
        } catch (error) {
            console.error('Error loading kota:', error);
            selectElement.disabled = true;
            throw error;
        }
    },

    /**
     * Load Kecamatan by Kota ID
     */
    async loadKecamatan(kotaId, selectElement) {
        try {
            const response = await fetch(`${this.baseURL}/districts/${kotaId}.json`);
            const data = await response.json();

            // Destroy Select2 first if exists
            if ($(selectElement).data('select2')) {
                $(selectElement).select2('destroy');
            }

            // Clear existing options
            selectElement.innerHTML = '<option value="">Pilih Kecamatan</option>';

            // Add districts
            data.forEach(kec => {
                const option = new Option(kec.name, kec.name);
                option.setAttribute('data-id', kec.id);
                selectElement.add(option);
            });

            // Enable select
            selectElement.disabled = false;

            // Reinitialize Select2
            $(selectElement).select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih Kecamatan',
                dropdownParent: $(selectElement).parent()
            });

            return data;
        } catch (error) {
            console.error('Error loading kecamatan:', error);
            selectElement.disabled = true;
            throw error;
        }
    },

    /**
     * Load Kelurahan/Desa by Kecamatan ID
     */
    async loadKelurahan(kecId, selectElement) {
        try {
            const response = await fetch(`${this.baseURL}/villages/${kecId}.json`);
            const data = await response.json();

            // Destroy Select2 first if exists
            if ($(selectElement).data('select2')) {
                $(selectElement).select2('destroy');
            }

            // Clear existing options
            selectElement.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';

            // Add villages
            data.forEach(kel => {
                const option = new Option(kel.name, kel.name);
                option.setAttribute('data-id', kel.id);
                selectElement.add(option);
            });

            // Enable select
            selectElement.disabled = false;

            // Reinitialize Select2
            $(selectElement).select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih Kelurahan/Desa',
                dropdownParent: $(selectElement).parent()
            });

            return data;
        } catch (error) {
            console.error('Error loading kelurahan:', error);
            selectElement.disabled = true;
            throw error;
        }
    },

    /**
     * Reset dependent selects
     */
    resetDependentSelects(selects) {
        selects.forEach(select => {
            // Destroy Select2 if exists
            if ($(select).data('select2')) {
                $(select).select2('destroy');
            }

            select.innerHTML = '<option value="">Pilih ' + select.getAttribute('data-placeholder') + '</option>';
            select.disabled = true;

            // Reinitialize Select2 untuk yang disabled
            $(select).select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $(select).parent()
            });
        });
    }
};

/**
 * Initialize Region Select for KTP Address
 */
function initRegionKTP() {
    const provKTP = document.getElementById('prov_ktp');
    const kotaKTP = document.getElementById('kota_ktp');
    const kecKTP = document.getElementById('kec_ktp');
    const kelKTP = document.getElementById('kel_ktp');

    // Set data-placeholder attributes
    if (kotaKTP) kotaKTP.setAttribute('data-placeholder', 'Kota/Kabupaten');
    if (kecKTP) kecKTP.setAttribute('data-placeholder', 'Kecamatan');
    if (kelKTP) kelKTP.setAttribute('data-placeholder', 'Kelurahan/Desa');

    // Load Provinsi on page load
    if (provKTP) {
        RegionAPI.loadProvinsi(provKTP).catch(err => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Gagal memuat data provinsi',
                showConfirmButton: false,
                timer: 3000
            });
        });

        // Provinsi change event
        $(provKTP).on('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const provId = selectedOption.getAttribute('data-id');

            // Reset dependent selects
            RegionAPI.resetDependentSelects([kotaKTP, kecKTP, kelKTP]);

            if (provId) {
                // Show loading
                kotaKTP.innerHTML = '<option value="">Memuat...</option>';
                kotaKTP.disabled = true;

                RegionAPI.loadKota(provId, kotaKTP).catch(err => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Gagal memuat data kota',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            }
        });

        // Kota change event
        $(kotaKTP).on('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const kotaId = selectedOption.getAttribute('data-id');

            // Reset dependent selects
            RegionAPI.resetDependentSelects([kecKTP, kelKTP]);

            if (kotaId) {
                // Show loading
                kecKTP.innerHTML = '<option value="">Memuat...</option>';
                kecKTP.disabled = true;

                RegionAPI.loadKecamatan(kotaId, kecKTP).catch(err => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Gagal memuat data kecamatan',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            }
        });

        // Kecamatan change event
        $(kecKTP).on('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const kecId = selectedOption.getAttribute('data-id');

            // Reset kelurahan
            if ($(kelKTP).data('select2')) {
                $(kelKTP).select2('destroy');
            }
            kelKTP.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
            kelKTP.disabled = true;

            if (kecId) {
                // Show loading
                kelKTP.innerHTML = '<option value="">Memuat...</option>';

                RegionAPI.loadKelurahan(kecId, kelKTP).catch(err => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Gagal memuat data kelurahan',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            } else {
                // Reinitialize Select2 for disabled state
                $(kelKTP).select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $(kelKTP).parent()
                });
            }
        });
    }
}

/**
 * Initialize Region Select for Domisili Address
 */
function initRegionDomisili() {
    const provDOM = document.getElementById('prov_dom');
    const kotaDOM = document.getElementById('kota_dom');
    const kecDOM = document.getElementById('kec_dom');
    const kelDOM = document.getElementById('kel_dom');

    // Set data-placeholder attributes
    if (kotaDOM) kotaDOM.setAttribute('data-placeholder', 'Kota/Kabupaten');
    if (kecDOM) kecDOM.setAttribute('data-placeholder', 'Kecamatan');
    if (kelDOM) kelDOM.setAttribute('data-placeholder', 'Kelurahan/Desa');

    // Load Provinsi on page load
    if (provDOM) {
        RegionAPI.loadProvinsi(provDOM).catch(err => {
            console.error('Error loading provinsi domisili:', err);
        });

        // Provinsi change event
        $(provDOM).on('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const provId = selectedOption.getAttribute('data-id');

            RegionAPI.resetDependentSelects([kotaDOM, kecDOM, kelDOM]);

            if (provId) {
                kotaDOM.innerHTML = '<option value="">Memuat...</option>';
                kotaDOM.disabled = true;

                RegionAPI.loadKota(provId, kotaDOM);
            }
        });

        // Kota change event
        $(kotaDOM).on('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const kotaId = selectedOption.getAttribute('data-id');

            RegionAPI.resetDependentSelects([kecDOM, kelDOM]);

            if (kotaId) {
                kecDOM.innerHTML = '<option value="">Memuat...</option>';
                kecDOM.disabled = true;

                RegionAPI.loadKecamatan(kotaId, kecDOM);
            }
        });

        // Kecamatan change event
        $(kecDOM).on('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const kecId = selectedOption.getAttribute('data-id');

            if ($(kelDOM).data('select2')) {
                $(kelDOM).select2('destroy');
            }
            kelDOM.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
            kelDOM.disabled = true;

            if (kecId) {
                kelDOM.innerHTML = '<option value="">Memuat...</option>';

                RegionAPI.loadKelurahan(kecId, kelDOM);
            } else {
                $(kelDOM).select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $(kelDOM).parent()
                });
            }
        });
    }
}

/**
 * Copy KTP Address to Domisili with Region Data
 */
function setupCopyKTPToDomisili() {
    const samaWithKtp = document.getElementById('samaWithKtp');

    if (samaWithKtp) {
        samaWithKtp.addEventListener('change', async function() {
            if (this.checked) {
                // Get KTP values
                const provKTP = $('#prov_ktp');
                const kotaKTP = $('#kota_ktp');
                const kecKTP = $('#kec_ktp');
                const kelKTP = $('#kel_ktp');

                const provDOM = $('#prov_dom');
                const kotaDOM = $('#kota_dom');
                const kecDOM = $('#kec_dom');
                const kelDOM = $('#kel_dom');

                // Copy text fields
                document.getElementById('alamat_dom').value = document.getElementById('alamat_ktp').value;
                document.getElementById('rt_rw_dom').value = document.getElementById('rt_rw_ktp').value;
                document.getElementById('kd_pos_dom').value = document.getElementById('kd_pos_ktp').value;

                // Copy region selects
                const provValue = provKTP.val();
                const provId = provKTP.find(':selected').attr('data-id');

                if (provValue && provId) {
                    provDOM.val(provValue).trigger('change');

                    // Wait for kota to load
                    await new Promise(resolve => setTimeout(resolve, 800));

                    const kotaValue = kotaKTP.val();
                    const kotaId = kotaKTP.find(':selected').attr('data-id');

                    if (kotaValue && kotaId) {
                        // Set the data-id on the matching option
                        kotaDOM.find('option').each(function() {
                            if ($(this).text() === kotaValue) {
                                $(this).attr('data-id', kotaId);
                            }
                        });
                        kotaDOM.val(kotaValue).trigger('change');

                        // Wait for kecamatan to load
                        await new Promise(resolve => setTimeout(resolve, 800));

                        const kecValue = kecKTP.val();
                        const kecId = kecKTP.find(':selected').attr('data-id');

                        if (kecValue && kecId) {
                            kecDOM.find('option').each(function() {
                                if ($(this).text() === kecValue) {
                                    $(this).attr('data-id', kecId);
                                }
                            });
                            kecDOM.val(kecValue).trigger('change');

                            // Wait for kelurahan to load
                            await new Promise(resolve => setTimeout(resolve, 800));

                            const kelValue = kelKTP.val();
                            if (kelValue) {
                                kelDOM.val(kelValue).trigger('change');
                            }
                        }
                    }
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Alamat berhasil disalin',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    }
}



// Initialize on document ready
$(document).ready(function() {

    // Initialize region dropdowns
    initRegionKTP();
    initRegionDomisili();
    setupCopyKTPToDomisili();
});