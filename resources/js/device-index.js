/**
 * Device Index Page — selection, view toggle, and print QR functionality.
 * Loaded only on the devices index page.
 */
(function initDeviceIndex() {
    const STORAGE_KEY = 'selectedDeviceIds';
    const VIEW_KEY = 'deviceViewMode';
    
    let selectAllCheckbox, deviceCheckboxes, printBtn, badge, clearBtn, selectionBar, tableView, gridView, toggleBtns, printUrl;
    let isInitialized = false;

    function getElements() {
        selectAllCheckbox = document.getElementById('select-all-devices');
        deviceCheckboxes = document.querySelectorAll('.device-checkbox');
        printBtn = document.getElementById('print-selected-qrs-btn');
        badge = document.getElementById('selected-count-badge');
        clearBtn = document.getElementById('clear-selection-btn');
        selectionBar = document.getElementById('qr-selection-bar');
        tableView = document.getElementById('table-view');
        gridView = document.getElementById('grid-view');
        toggleBtns = document.querySelectorAll('.view-toggle-btn');
        printUrl = printBtn ? printBtn.dataset.printUrl : '';
    }

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
        restoreCheckboxes();
    }

    function getVisibleCheckboxes() {
        const activeView = gridView.classList.contains('hidden') ? tableView : gridView;
        return Array.from(activeView.querySelectorAll('.device-checkbox'));
    }

    function updateUI() {
        if (!badge || !selectionBar || !printBtn || !clearBtn || !selectAllCheckbox) return;
        
        const selectedIds = getSelectedIds();
        const totalCount = selectedIds.size;

        badge.textContent = totalCount;
        selectionBar.classList.toggle('hidden', totalCount === 0);
        printBtn.disabled = totalCount === 0;
        clearBtn.classList.toggle('hidden', totalCount === 0);

        const visibleCheckboxes = getVisibleCheckboxes();
        const allOnPageChecked = visibleCheckboxes.length > 0 &&
            visibleCheckboxes.every(cb => cb.checked);
        selectAllCheckbox.checked = allOnPageChecked;
    }

    function restoreCheckboxes() {
        const selectedIds = getSelectedIds();
        deviceCheckboxes.forEach(cb => {
            cb.checked = selectedIds.has(cb.value);
        });
        updateUI();
    }

    function syncCheckbox(value, checked) {
        deviceCheckboxes.forEach(cb => {
            if (cb.value === value) cb.checked = checked;
        });
    }

    function attachEvents() {
        toggleBtns.forEach(btn => {
            btn.addEventListener('click', () => setView(btn.dataset.view));
        });

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

        if (printBtn) {
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
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                sessionStorage.removeItem(STORAGE_KEY);
                deviceCheckboxes.forEach(cb => cb.checked = false);
                selectAllCheckbox.checked = false;
                updateUI();
                if (selectionBar) selectionBar.classList.add('hidden');
            });
        }
    }

    function init() {
        if (isInitialized) return;
        getElements();
        
        if (!selectAllCheckbox || !deviceCheckboxes.length) return;
        
        isInitialized = true;
        attachEvents();
        setView(localStorage.getItem(VIEW_KEY) || 'table');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    document.addEventListener('livewire:navigated', init);
})();
