/**
 * Region API Helper untuk Indonesia
 * Menggunakan API dari https://www.emsifa.com/api-wilayah-indonesia/
 *
 * FIXED VERSION:
 * - Tunggu Select2 ready sebelum init
 * - Support restore old values untuk edit mode
 * - Prevent event trigger saat restore
 * - Support 3 section: KTP, Domisili, Kontak Darurat
 */

// ============================================================
// HELPER: Wait for Select2 to be ready
// ============================================================
function waitForSelect2Ready() {
    return new Promise((resolve, reject) => {
        const maxWait = 15000; // 15 seconds
        const interval = 100; // check every 100ms
        let elapsed = 0;

        const check = () => {
            if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                console.log('[RegionAPI] ✓ Select2 ready after', elapsed, 'ms');
                resolve();
            } else if (elapsed >= maxWait) {
                console.error('[RegionAPI] ✗ Select2 timeout after', maxWait, 'ms');
                reject(new Error('Select2 not available after ' + maxWait + 'ms'));
            } else {
                elapsed += interval;
                setTimeout(check, interval);
            }
        };

        check();
    });
}

// ============================================================
// REGION API OBJECT
// ============================================================
const RegionAPI = {
    baseURL: 'https://www.emsifa.com/api-wilayah-indonesia/api',
    isRestoring: false, // Flag untuk mencegah trigger event saat restore

    /**
     * Load Provinsi dengan optional selected value
     */
    async loadProvinsi(selectElement, selectedValue = null) {
        try {
            const response = await fetch(`${this.baseURL}/provinces.json`);
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();

            // Clear existing options
            selectElement.innerHTML = '<option value="">Pilih Provinsi</option>';

            let selectedId = null;

            // Add provinces
            data.forEach(prov => {
                const option = new Option(prov.name, prov.name);
                option.setAttribute('data-id', prov.id);

                // Set selected if matches
                if (selectedValue && prov.name === selectedValue) {
                    option.selected = true;
                    selectedId = prov.id;
                }

                selectElement.add(option);
            });

            // Enable select
            selectElement.disabled = false;

            // Initialize Select2
            // $(selectElement).select2({
            //     theme: 'bootstrap-5',
            //     width: '100%',
            //     placeholder: 'Pilih Provinsi',
            //     dropdownParent: $(selectElement).parent()
            // });

            // Trigger change.select2 untuk update UI tanpa trigger event handler user
            if (selectedValue && selectedId) {
                $(selectElement).trigger('change.select2');
            }

            return { data, selectedId };
        } catch (error) {
            console.error('[RegionAPI] Error loading provinsi:', error);
            throw error;
        }
    },

    /**
     * Load Kota/Kabupaten by Provinsi ID dengan optional selected value
     */
    async loadKota(provId, selectElement, selectedValue = null) {
        try {
            const response = await fetch(`${this.baseURL}/regencies/${provId}.json`);
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();

            // Destroy Select2 first if exists
            if ($(selectElement).data('select2')) {
                $(selectElement).select2('destroy');
            }

            // Clear existing options
            selectElement.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';

            let selectedId = null;

            // Add cities
            data.forEach(kota => {
                const option = new Option(kota.name, kota.name);
                option.setAttribute('data-id', kota.id);

                // Set selected if matches
                if (selectedValue && kota.name === selectedValue) {
                    option.selected = true;
                    selectedId = kota.id;
                }

                selectElement.add(option);
            });

            // Enable select
            selectElement.disabled = false;

            // Reinitialize Select2
            $(selectElement).select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih Kota/Kabupaten',
                dropdownParent: $(selectElement).parent()
            });

            // Trigger change.select2 untuk update UI
            if (selectedValue && selectedId) {
                $(selectElement).trigger('change.select2');
            }

            return { data, selectedId };
        } catch (error) {
            console.error('[RegionAPI] Error loading kota:', error);
            selectElement.disabled = true;
            throw error;
        }
    },

    /**
     * Load Kecamatan by Kota ID dengan optional selected value
     */
    async loadKecamatan(kotaId, selectElement, selectedValue = null) {
        try {
            const response = await fetch(`${this.baseURL}/districts/${kotaId}.json`);
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();

            // Destroy Select2 first if exists
            if ($(selectElement).data('select2')) {
                $(selectElement).select2('destroy');
            }

            // Clear existing options
            selectElement.innerHTML = '<option value="">Pilih Kecamatan</option>';

            let selectedId = null;

            // Add districts
            data.forEach(kec => {
                const option = new Option(kec.name, kec.name);
                option.setAttribute('data-id', kec.id);

                // Set selected if matches
                if (selectedValue && kec.name === selectedValue) {
                    option.selected = true;
                    selectedId = kec.id;
                }

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

            // Trigger change.select2 untuk update UI
            if (selectedValue && selectedId) {
                $(selectElement).trigger('change.select2');
            }

            return { data, selectedId };
        } catch (error) {
            console.error('[RegionAPI] Error loading kecamatan:', error);
            selectElement.disabled = true;
            throw error;
        }
    },

    /**
     * Load Kelurahan/Desa by Kecamatan ID dengan optional selected value
     */
    async loadKelurahan(kecId, selectElement, selectedValue = null) {
        try {
            const response = await fetch(`${this.baseURL}/villages/${kecId}.json`);
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

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

                // Set selected if matches
                if (selectedValue && kel.name === selectedValue) {
                    option.selected = true;
                }

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

            // Trigger change.select2 untuk update UI
            if (selectedValue) {
                $(selectElement).trigger('change.select2');
            }

            return data;
        } catch (error) {
            console.error('[RegionAPI] Error loading kelurahan:', error);
            selectElement.disabled = true;
            throw error;
        }
    },

    /**
     * Reset dependent selects
     */
    resetDependentSelects(selects) {
        selects.forEach(select => {
            if (!select) return;

            // Destroy Select2 if exists
            if ($(select).data('select2')) {
                $(select).select2('destroy');
            }

            const placeholder = select.getAttribute('data-placeholder') || 'Pilih';
            select.innerHTML = `<option value="">Pilih ${placeholder}</option>`;
            select.disabled = true;

            // Reinitialize Select2 untuk yang disabled
            $(select).select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $(select).parent()
            });
        });
    },

    /**
     * Restore Region Values (untuk edit mode)
     * SEQUENTIAL: Load prov → kota → kec → kel
     */
    async restoreRegionValues(sectionId, oldValues) {
        if (!oldValues || !oldValues.prov) {
            console.log(`[RegionAPI] No data to restore for ${sectionId}`);
            return;
        }

        console.log(`[RegionAPI] Restoring ${sectionId}:`, oldValues);

        this.isRestoring = true;

        try {
            const provEl = document.getElementById(`prov_${sectionId}`);
            const kotaEl = document.getElementById(`kota_${sectionId}`);
            const kecEl = document.getElementById(`kec_${sectionId}`);
            const kelEl = document.getElementById(`kel_${sectionId}`);

            if (!provEl) {
                console.warn(`[RegionAPI] Element prov_${sectionId} not found`);
                return;
            }

            // 1. Load & Select Provinsi
            const { selectedId: provId } = await this.loadProvinsi(provEl, oldValues.prov);
            console.log(`[RegionAPI] ✓ ${sectionId} - Provinsi: ${oldValues.prov} (ID: ${provId})`);

            if (!provId || !oldValues.kota || !kotaEl) {
                return;
            }

            // 2. Load & Select Kota
            const { selectedId: kotaId } = await this.loadKota(provId, kotaEl, oldValues.kota);
            console.log(`[RegionAPI] ✓ ${sectionId} - Kota: ${oldValues.kota} (ID: ${kotaId})`);

            if (!kotaId || !oldValues.kec || !kecEl) {
                return;
            }

            // 3. Load & Select Kecamatan
            const { selectedId: kecId } = await this.loadKecamatan(kotaId, kecEl, oldValues.kec);
            console.log(`[RegionAPI] ✓ ${sectionId} - Kecamatan: ${oldValues.kec} (ID: ${kecId})`);

            if (!kecId || !oldValues.kel || !kelEl) {
                return;
            }

            // 4. Load & Select Kelurahan
            await this.loadKelurahan(kecId, kelEl, oldValues.kel);
            console.log(`[RegionAPI] ✓ ${sectionId} - Kelurahan: ${oldValues.kel}`);

            console.log(`[RegionAPI] ✅ Restore complete for ${sectionId}`);

        } catch (error) {
            console.error(`[RegionAPI] ✗ Error restoring ${sectionId}:`, error);
            // Show user-friendly error
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: `Gagal memuat data ${sectionId}`,
                    text: error.message,
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        } finally {
            this.isRestoring = false;
        }
    }
};

// ============================================================
// INITIALIZE REGION SELECT FOR KTP ADDRESS
// ============================================================
function initRegionKTP(oldValues = {}) {
    const provKTP = document.getElementById('prov_ktp');
    const kotaKTP = document.getElementById('kota_ktp');
    const kecKTP = document.getElementById('kec_ktp');
    const kelKTP = document.getElementById('kel_ktp');

    if (!provKTP) {
        console.warn('[RegionAPI] KTP elements not found');
        return;
    }

    // Set data-placeholder attributes
    if (kotaKTP) kotaKTP.setAttribute('data-placeholder', 'Kota/Kabupaten');
    if (kecKTP) kecKTP.setAttribute('data-placeholder', 'Kecamatan');
    if (kelKTP) kelKTP.setAttribute('data-placeholder', 'Kelurahan/Desa');

    // Load Provinsi on page load
    RegionAPI.loadProvinsi(provKTP).then(() => {
        // Restore old values if exist
        if (oldValues.prov) {
            RegionAPI.restoreRegionValues('ktp', oldValues);
        }
    }).catch(err => {
        console.error('[RegionAPI] Failed to load provinsi KTP:', err);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Gagal memuat data provinsi',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });

    // Provinsi change event
    $(provKTP).on('change', function() {
        // Skip if restoring
        if (RegionAPI.isRestoring) return;

        const selectedOption = this.options[this.selectedIndex];
        const provId = selectedOption ? selectedOption.getAttribute('data-id') : null;

        // Reset dependent selects
        RegionAPI.resetDependentSelects([kotaKTP, kecKTP, kelKTP]);

        if (provId) {
            // Show loading
            kotaKTP.innerHTML = '<option value="">Memuat...</option>';
            kotaKTP.disabled = true;

            RegionAPI.loadKota(provId, kotaKTP).catch(err => {
                console.error('[RegionAPI] Error loading kota KTP:', err);
            });
        }
    });

    // Kota change event
    $(kotaKTP).on('change', function() {
        if (RegionAPI.isRestoring) return;

        const selectedOption = this.options[this.selectedIndex];
        const kotaId = selectedOption ? selectedOption.getAttribute('data-id') : null;

        // Reset dependent selects
        RegionAPI.resetDependentSelects([kecKTP, kelKTP]);

        if (kotaId) {
            // Show loading
            kecKTP.innerHTML = '<option value="">Memuat...</option>';
            kecKTP.disabled = true;

            RegionAPI.loadKecamatan(kotaId, kecKTP).catch(err => {
                console.error('[RegionAPI] Error loading kecamatan KTP:', err);
            });
        }
    });

    // Kecamatan change event
    $(kecKTP).on('change', function() {
        if (RegionAPI.isRestoring) return;

        const selectedOption = this.options[this.selectedIndex];
        const kecId = selectedOption ? selectedOption.getAttribute('data-id') : null;

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
                console.error('[RegionAPI] Error loading kelurahan KTP:', err);
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

// ============================================================
// INITIALIZE REGION SELECT FOR DOMISILI ADDRESS
// ============================================================
function initRegionDomisili(oldValues = {}) {
    const provDOM = document.getElementById('prov_dom');
    const kotaDOM = document.getElementById('kota_dom');
    const kecDOM = document.getElementById('kec_dom');
    const kelDOM = document.getElementById('kel_dom');

    if (!provDOM) {
        console.warn('[RegionAPI] Domisili elements not found');
        return;
    }

    // Set data-placeholder attributes
    if (kotaDOM) kotaDOM.setAttribute('data-placeholder', 'Kota/Kabupaten');
    if (kecDOM) kecDOM.setAttribute('data-placeholder', 'Kecamatan');
    if (kelDOM) kelDOM.setAttribute('data-placeholder', 'Kelurahan/Desa');

    // Load Provinsi on page load
    RegionAPI.loadProvinsi(provDOM).then(() => {
        // Restore old values if exist
        if (oldValues.prov) {
            RegionAPI.restoreRegionValues('dom', oldValues);
        }
    }).catch(err => {
        console.error('[RegionAPI] Failed to load provinsi Domisili:', err);
    });

    // Provinsi change event
    $(provDOM).on('change', function() {
        if (RegionAPI.isRestoring) return;

        const selectedOption = this.options[this.selectedIndex];
        const provId = selectedOption ? selectedOption.getAttribute('data-id') : null;

        RegionAPI.resetDependentSelects([kotaDOM, kecDOM, kelDOM]);

        if (provId) {
            kotaDOM.innerHTML = '<option value="">Memuat...</option>';
            kotaDOM.disabled = true;

            RegionAPI.loadKota(provId, kotaDOM).catch(err => {
                console.error('[RegionAPI] Error loading kota Domisili:', err);
            });
        }
    });

    // Kota change event
    $(kotaDOM).on('change', function() {
        if (RegionAPI.isRestoring) return;

        const selectedOption = this.options[this.selectedIndex];
        const kotaId = selectedOption ? selectedOption.getAttribute('data-id') : null;

        RegionAPI.resetDependentSelects([kecDOM, kelDOM]);

        if (kotaId) {
            kecDOM.innerHTML = '<option value="">Memuat...</option>';
            kecDOM.disabled = true;

            RegionAPI.loadKecamatan(kotaId, kecDOM).catch(err => {
                console.error('[RegionAPI] Error loading kecamatan Domisili:', err);
            });
        }
    });

    // Kecamatan change event
    $(kecDOM).on('change', function() {
        if (RegionAPI.isRestoring) return;

        const selectedOption = this.options[this.selectedIndex];
        const kecId = selectedOption ? selectedOption.getAttribute('data-id') : null;

        if ($(kelDOM).data('select2')) {
            $(kelDOM).select2('destroy');
        }
        kelDOM.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        kelDOM.disabled = true;

        if (kecId) {
            kelDOM.innerHTML = '<option value="">Memuat...</option>';

            RegionAPI.loadKelurahan(kecId, kelDOM).catch(err => {
                console.error('[RegionAPI] Error loading kelurahan Domisili:', err);
            });
        } else {
            $(kelDOM).select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $(kelDOM).parent()
            });
        }
    });
}

// ============================================================
// INITIALIZE REGION SELECT FOR KONTAK DARURAT
// ============================================================
function initRegionKontakDarurat(oldValues = {}) {
    const provKD = document.getElementById('prov_kd');
    const kotaKD = document.getElementById('kota_kd');
    const kecKD = document.getElementById('kec_kd');
    const kelKD = document.getElementById('kel_kd');

    if (!provKD) {
        console.warn('[RegionAPI] Kontak Darurat elements not found');
        return;
    }

    // Set data-placeholder attributes
    if (kotaKD) kotaKD.setAttribute('data-placeholder', 'Kota/Kabupaten');
    if (kecKD) kecKD.setAttribute('data-placeholder', 'Kecamatan');
    if (kelKD) kelKD.setAttribute('data-placeholder', 'Kelurahan/Desa');

    // Load Provinsi on page load
    RegionAPI.loadProvinsi(provKD).then(() => {
        // Restore old values if exist
        if (oldValues.prov) {
            RegionAPI.restoreRegionValues('kd', oldValues);
        }
    }).catch(err => {
        console.error('[RegionAPI] Failed to load provinsi Kontak Darurat:', err);
    });

    // Provinsi change event
    $(provKD).on('change', function() {
        if (RegionAPI.isRestoring) return;

        const selectedOption = this.options[this.selectedIndex];
        const provId = selectedOption ? selectedOption.getAttribute('data-id') : null;

        RegionAPI.resetDependentSelects([kotaKD, kecKD, kelKD]);

        if (provId) {
            kotaKD.innerHTML = '<option value="">Memuat...</option>';
            kotaKD.disabled = true;

            RegionAPI.loadKota(provId, kotaKD).catch(err => {
                console.error('[RegionAPI] Error loading kota Kontak Darurat:', err);
            });
        }
    });

    // Kota change event
    $(kotaKD).on('change', function() {
        if (RegionAPI.isRestoring) return;

        const selectedOption = this.options[this.selectedIndex];
        const kotaId = selectedOption ? selectedOption.getAttribute('data-id') : null;

        RegionAPI.resetDependentSelects([kecKD, kelKD]);

        if (kotaId) {
            kecKD.innerHTML = '<option value="">Memuat...</option>';
            kecKD.disabled = true;

            RegionAPI.loadKecamatan(kotaId, kecKD).catch(err => {
                console.error('[RegionAPI] Error loading kecamatan Kontak Darurat:', err);
            });
        }
    });

    // Kecamatan change event
    $(kecKD).on('change', function() {
        if (RegionAPI.isRestoring) return;

        const selectedOption = this.options[this.selectedIndex];
        const kecId = selectedOption ? selectedOption.getAttribute('data-id') : null;

        if ($(kelKD).data('select2')) {
            $(kelKD).select2('destroy');
        }
        kelKD.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        kelKD.disabled = true;

        if (kecId) {
            kelKD.innerHTML = '<option value="">Memuat...</option>';

            RegionAPI.loadKelurahan(kecId, kelKD).catch(err => {
                console.error('[RegionAPI] Error loading kelurahan Kontak Darurat:', err);
            });
        } else {
            $(kelKD).select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $(kelKD).parent()
            });
        }
    });
}

// ============================================================
// COPY KTP ADDRESS TO DOMISILI
// ============================================================
function setupCopyKTPToDomisili() {
    const samaWithKtp = document.getElementById('samaWithKtp');

    if (!samaWithKtp) {
        console.warn('[RegionAPI] Checkbox samaWithKtp not found');
        return;
    }

    samaWithKtp.addEventListener('change', async function() {
        if (!this.checked) return;

        RegionAPI.isRestoring = true;

        try {
            // Get KTP values
            const provKTP = $('#prov_ktp');
            const kotaKTP = $('#kota_ktp');
            const kecKTP = $('#kec_ktp');
            const kelKTP = $('#kel_ktp');

            // Copy text fields
            const alamatKTP = document.getElementById('alamat_ktp');
            const rtRwKTP = document.getElementById('rt_rw_ktp');
            const kdPosKTP = document.getElementById('kd_pos_ktp');

            if (alamatKTP) document.getElementById('alamat_dom').value = alamatKTP.value;
            if (rtRwKTP) document.getElementById('rt_rw_dom').value = rtRwKTP.value;
            if (kdPosKTP) document.getElementById('kd_pos_dom').value = kdPosKTP.value;

            // Get KTP region values
            const oldValues = {
                prov: provKTP.val(),
                kota: kotaKTP.val(),
                kec: kecKTP.val(),
                kel: kelKTP.val()
            };

            console.log('[RegionAPI] Copying KTP to Domisili:', oldValues);

            // Restore to domisili
            if (oldValues.prov) {
                await RegionAPI.restoreRegionValues('dom', oldValues);
            }

            // Show success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Alamat KTP berhasil disalin ke domisili',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        } catch (error) {
            console.error('[RegionAPI] Error copying address:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Gagal menyalin alamat',
                    text: error.message,
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        } finally {
            RegionAPI.isRestoring = false;
        }
    });
}

// ============================================================
// GLOBAL INITIALIZATION FUNCTION
// ============================================================
/**
 * Global initialization function
 * Panggil ini dari blade file dengan parameter oldValues
 *
 * @param {Object} oldValues - Object berisi old values untuk setiap section
 * @param {Object} oldValues.ktp - {prov, kota, kec, kel}
 * @param {Object} oldValues.dom - {prov, kota, kec, kel}
 * @param {Object} oldValues.kd - {prov, kota, kec, kel}
 */
window.initRegionAPI = async function(oldValues = {}) {
    console.log('[RegionAPI] Initializing with old values:', oldValues);

    try {
        // TUNGGU SELECT2 READY
        await waitForSelect2Ready();
        console.log('[RegionAPI] Select2 confirmed ready, proceeding with initialization...');

        // Initialize all sections
        initRegionKTP(oldValues.ktp || {});
        initRegionDomisili(oldValues.dom || {});
        initRegionKontakDarurat(oldValues.kd || {});
        setupCopyKTPToDomisili();

        console.log('[RegionAPI] ✅ Initialization complete');
    } catch (error) {
        console.error('[RegionAPI] ✗ Initialization failed:', error);

        // Show user-friendly error
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat Data Wilayah',
                text: 'Select2 library tidak tersedia. Silakan refresh halaman.',
                confirmButtonText: 'Refresh',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        } else {
            alert('Gagal memuat data wilayah. Silakan refresh halaman.');
        }
    }
};

// ============================================================
// AUTO-INITIALIZE ON DOCUMENT READY (BACKWARD COMPATIBILITY)
// ============================================================
$(document).ready(function() {
    // Jika tidak ada initRegionAPI dipanggil dari blade,
    // inisialisasi tanpa old values (untuk create mode)
    if (!window._regionAPIInitialized) {
        console.log('[RegionAPI] Auto-initializing without old values (create mode)');
        window.initRegionAPI().then(() => {
            window._regionAPIInitialized = true;
        });
    }
});