/**
 * Device Index Page — selection, view toggle, and print QR functionality.
 * Loaded only on the devices index page via @push('scripts').
 */
document.addEventListener('DOMContentLoaded', function () {
    const STORAGE_KEY = 'selectedDeviceIds';
    const VIEW_KEY = 'deviceViewMode';
    const selectAllCheckbox = document.getElementById('select-all-devices');
    const deviceCheckboxes = document.querySelectorAll('.device-checkbox');
    const printBtn = document.getElementById('print-selected-qrs-btn');
    const badge = document.getElementById('selected-count-badge');
    const clearBtn = document.getElementById('clear-selection-btn');
    const tableView = document.getElementById('table-view');
    const gridView = document.getElementById('grid-view');
    const toggleBtns = document.querySelectorAll('.view-toggle-btn');

    // Read the print URL from the button's data attribute
    const printUrl = printBtn ? printBtn.dataset.printUrl : '';

    // --- Helpers para sessionStorage ---
    function getSelectedIds() {
        try {
            return new Set(JSON.parse(sessionStorage.getItem(STORAGE_KEY) || '[]'));
        } catch {
            return new Set();
        }
    }

    function saveSelectedIds(idSet) {
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify([...idSet]));
    }

    // --- View Toggle ---
    function setView(mode) {
        localStorage.setItem(VIEW_KEY, mode);
        if (mode === 'grid') {
            tableView.classList.add('hidden');
            gridView.classList.remove('hidden');
        } else {
            tableView.classList.remove('hidden');
            gridView.classList.add('hidden');
        }
        toggleBtns.forEach(btn => {
            const isActive = btn.dataset.view === mode;
            btn.classList.toggle('bg-slate-800', isActive);
            btn.classList.toggle('text-white', isActive);
            btn.classList.toggle('text-slate-500', !isActive);
            btn.classList.toggle('hover:text-slate-700', !isActive);
            btn.classList.toggle('hover:bg-slate-100', !isActive);
        });
        // ReSync checkboxes after switching view
        restoreCheckboxes();
    }

    toggleBtns.forEach(btn => {
        btn.addEventListener('click', () => setView(btn.dataset.view));
    });

    // Initialize view from localStorage
    setView(localStorage.getItem(VIEW_KEY) || 'table');

    // --- Actualizar UI ---
    function updateUI() {
        const selectedIds = getSelectedIds();
        const totalCount = selectedIds.size;

        badge.textContent = totalCount;
        badge.classList.toggle('hidden', totalCount === 0);
        printBtn.disabled = totalCount === 0;
        clearBtn.classList.toggle('hidden', totalCount === 0);

        // Select-all checkbox (only visible checkboxes on current view)
        const visibleCheckboxes = getVisibleCheckboxes();
        const allOnPageChecked = visibleCheckboxes.length > 0 &&
            visibleCheckboxes.every(cb => cb.checked);
        selectAllCheckbox.checked = allOnPageChecked;
    }

    function getVisibleCheckboxes() {
        const activeView = gridView.classList.contains('hidden') ? tableView : gridView;
        return Array.from(activeView.querySelectorAll('.device-checkbox'));
    }

    // --- Restaurar checkboxes al cargar la página ---
    function restoreCheckboxes() {
        const selectedIds = getSelectedIds();
        deviceCheckboxes.forEach(cb => {
            cb.checked = selectedIds.has(cb.value);
        });
        updateUI();
    }

    // --- Sync all checkboxes with same value ---
    function syncCheckbox(value, checked) {
        deviceCheckboxes.forEach(cb => {
            if (cb.value === value) cb.checked = checked;
        });
    }

    // --- Eventos ---
    selectAllCheckbox.addEventListener('change', function () {
        const selectedIds = getSelectedIds();
        const visibleCheckboxes = getVisibleCheckboxes();
        visibleCheckboxes.forEach(cb => {
            cb.checked = this.checked;
            syncCheckbox(cb.value, this.checked);
            if (this.checked) {
                selectedIds.add(cb.value);
            } else {
                selectedIds.delete(cb.value);
            }
        });
        saveSelectedIds(selectedIds);
        updateUI();
    });

    deviceCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const selectedIds = getSelectedIds();
            if (this.checked) {
                selectedIds.add(this.value);
            } else {
                selectedIds.delete(this.value);
            }
            syncCheckbox(this.value, this.checked);
            saveSelectedIds(selectedIds);
            updateUI();
        });
    });

    printBtn.addEventListener('click', function () {
        const selectedIds = getSelectedIds();
        if (selectedIds.size > 0) {
            const idsParam = [...selectedIds].join(',');
            const url = `${printUrl}?ids=${idsParam}`;
            window.open(url, '_blank');
            sessionStorage.removeItem(STORAGE_KEY);
            deviceCheckboxes.forEach(cb => cb.checked = false);
            updateUI();
        }
    });

    clearBtn.addEventListener('click', function () {
        sessionStorage.removeItem(STORAGE_KEY);
        deviceCheckboxes.forEach(cb => cb.checked = false);
        selectAllCheckbox.checked = false;
        updateUI();
    });

    // Inicializar
    restoreCheckboxes();
});
