/**
 * Device Index Page — selection, view toggle, and print QR functionality.
 * Works with both full page loads and Livewire SPA navigation.
 */
(function() {
    const STORAGE_KEY = 'selectedDeviceIds';
    const VIEW_KEY = 'deviceViewMode';
    let initialized = false;

    function getElements() {
        return {
            selectAllCheckbox: document.getElementById('select-all-devices'),
            deviceCheckboxes: document.querySelectorAll('.device-checkbox'),
            printBtn: document.getElementById('print-selected-qrs-btn'),
            badge: document.getElementById('selected-count-badge'),
            clearBtn: document.getElementById('clear-selection-btn'),
            selectionBar: document.getElementById('qr-selection-bar'),
            tableView: document.getElementById('table-view'),
            gridView: document.getElementById('grid-view'),
            toggleBtns: document.querySelectorAll('.view-toggle-btn'),
        };
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

    function init() {
        const els = getElements();
        
        if (!els.selectAllCheckbox || els.deviceCheckboxes.length === 0) {
            return false;
        }

        const { selectAllCheckbox, deviceCheckboxes, printBtn, badge, clearBtn, selectionBar, tableView, gridView, toggleBtns } = els;
        const printUrl = printBtn ? printBtn.dataset.printUrl : '';

        function getVisibleCheckboxes() {
            const activeView = gridView.classList.contains('hidden') ? tableView : gridView;
            return Array.from(activeView.querySelectorAll('.device-checkbox'));
        }

        function updateUI() {
            const selectedIds = getSelectedIds();
            const totalCount = selectedIds.size;
            badge.textContent = totalCount;
            selectionBar.classList.toggle('hidden', totalCount === 0);
            printBtn.disabled = totalCount === 0;
            clearBtn.classList.toggle('hidden', totalCount === 0);
            const visibleCheckboxes = getVisibleCheckboxes();
            selectAllCheckbox.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);
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
            });
            restoreCheckboxes();
        }

        toggleBtns.forEach(btn => {
            btn.addEventListener('click', () => setView(btn.dataset.view));
        });

        selectAllCheckbox.addEventListener('change', function () {
            const selectedIds = getSelectedIds();
            getVisibleCheckboxes().forEach(cb => {
                cb.checked = this.checked;
                syncCheckbox(cb.value, this.checked);
                this.checked ? selectedIds.add(cb.value) : selectedIds.delete(cb.value);
            });
            saveSelectedIds(selectedIds);
            updateUI();
        });

        deviceCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                const selectedIds = getSelectedIds();
                this.checked ? selectedIds.add(this.value) : selectedIds.delete(this.value);
                syncCheckbox(this.value, this.checked);
                saveSelectedIds(selectedIds);
                updateUI();
            });
        });

        printBtn.addEventListener('click', function () {
            const selectedIds = getSelectedIds();
            if (selectedIds.size > 0) {
                window.open(`${printUrl}?ids=${[...selectedIds].join(',')}`, '_blank');
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
            selectionBar.classList.add('hidden');
        });

        setView(localStorage.getItem(VIEW_KEY) || 'table');
        initialized = true;
        return true;
    }

    function handleNavigation() {
        initialized = false;
        setTimeout(init, 50);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    document.addEventListener('livewire:navigating', handleNavigation);
    document.addEventListener('livewire:navigated', handleNavigation);
})();
