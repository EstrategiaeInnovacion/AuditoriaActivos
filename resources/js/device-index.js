/**
 * Device Index Page — selection, view toggle, and print QR functionality.
 * Works with both full page loads and Livewire SPA navigation.
 * 
 * @module device-index
 * @version 1.0.0
 */
(function() {
    'use strict';

    const STORAGE_KEY = 'selectedDeviceIds';
    const VIEW_KEY = 'deviceViewMode';
    const DEBUG = false;

    let initialized = false;
    let updateUITimeout = null;
    let boundCleanup = null;
    let boundHandleNavigation = null;
    let boundHandleNavigated = null;

    /**
     * Safe logging utility that only logs in development mode.
     * @param {...any} args - Arguments to log
     */
    function debugLog(...args) {
        if (DEBUG) {
            console.debug('[DeviceIndex]', ...args);
        }
    }

    /**
     * Safely retrieves a DOM element by ID, returning null if not found.
     * @param {string} id - The element ID to look up
     * @returns {Element|null} The element or null if not found
     */
    function safeGetElement(id) {
        try {
            const element = document.getElementById(id);
            if (!element) {
                debugLog(`Element not found: #${id}`);
            }
            return element;
        } catch (error) {
            console.error(`Error getting element #${id}:`, error);
            return null;
        }
    }

    /**
     * Checks if storage is available (handles private browsing, Safari, etc.)
     * @param {string} type - 'localStorage' or 'sessionStorage'
     * @returns {boolean}
     */
    function isStorageAvailable(type) {
        try {
            const storage = window[type];
            const testKey = '__storage_test__';
            storage.setItem(testKey, testKey);
            storage.removeItem(testKey);
            return true;
        } catch (e) {
            debugLog(`${type} not available:`, e.message);
            return false;
        }
    }

    const hasSessionStorage = isStorageAvailable('sessionStorage');
    const hasLocalStorage = isStorageAvailable('localStorage');

    /**
     * Safely retrieves selected IDs from sessionStorage.
     * @returns {Set<string>} Set of selected device IDs
     */
    function getSelectedIds() {
        try {
            if (!hasSessionStorage) {
                return new Set();
            }
            const stored = sessionStorage.getItem(STORAGE_KEY);
            if (!stored) {
                return new Set();
            }
            const parsed = JSON.parse(stored);
            if (!Array.isArray(parsed)) {
                return new Set();
            }
            return new Set(parsed.filter(id => typeof id === 'string' || typeof id === 'number'));
        } catch (error) {
            console.error('Error reading selected IDs:', error);
            return new Set();
        }
    }

    /**
     * Safely saves selected IDs to sessionStorage.
     * @param {Set<string>} idSet - Set of device IDs to save
     */
    function saveSelectedIds(idSet) {
        try {
            if (!hasSessionStorage) {
                debugLog('SessionStorage unavailable, cannot save selections');
                return;
            }
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify([...idSet]));
        } catch (error) {
            console.error('Error saving selected IDs:', error);
        }
    }

    /**
     * Debounced UI update to prevent excessive updates.
     * @param {Function} fn - Function to debounce
     * @param {number} delay - Delay in milliseconds
     * @returns {Function}
     */
    function debounce(fn, delay = 16) {
        let timeoutId = null;
        return function(...args) {
            if (timeoutId) {
                clearTimeout(timeoutId);
            }
            timeoutId = setTimeout(() => {
                fn.apply(this, args);
                timeoutId = null;
            }, delay);
        };
    }

    /**
     * Safely queries elements, returning empty array on error.
     * @param {string} selector - CSS selector
     * @param {Element|null} context - Context element (defaults to document)
     * @returns {NodeList|Array}
     */
    function safeQueryAll(selector, context = document) {
        try {
            return context.querySelectorAll(selector);
        } catch (error) {
            console.error(`Error querying "${selector}":`, error);
            return [];
        }
    }

    /**
     * Safely toggles a class on an element.
     * @param {Element|null} element - Target element
     * @param {string} className - Class to toggle
     * @param {boolean} force - Force add/remove
     */
    function safeClassToggle(element, className, force) {
        if (!element) {
            return;
        }
        try {
            element.classList.toggle(className, force);
        } catch (error) {
            console.error(`Error toggling class "${className}":`, error);
        }
    }

    /**
     * Safely sets text content on an element.
     * @param {Element|null} element - Target element
     * @param {string} text - Text to set
     */
    function safeSetText(element, text) {
        if (!element) {
            return;
        }
        try {
            element.textContent = text;
        } catch (error) {
            console.error('Error setting text content:', error);
        }
    }

    /**
     * Safely sets disabled state on an element.
     * @param {Element|null} element - Target element
     * @param {boolean} disabled - Disabled state
     */
    function safeSetDisabled(element, disabled) {
        if (!element) {
            return;
        }
        try {
            element.disabled = disabled;
        } catch (error) {
            console.error('Error setting disabled state:', error);
        }
    }

    /**
     * Safely saves view mode to localStorage.
     * @param {string} mode - View mode ('table' or 'grid')
     */
    function saveViewMode(mode) {
        try {
            if (hasLocalStorage) {
                localStorage.setItem(VIEW_KEY, mode);
            }
        } catch (error) {
            console.error('Error saving view mode:', error);
        }
    }

    /**
     * Safely retrieves view mode from localStorage.
     * @returns {string} View mode ('table' or 'grid')
     */
    function getViewMode() {
        try {
            if (hasLocalStorage) {
                const mode = localStorage.getItem(VIEW_KEY);
                if (mode === 'table' || mode === 'grid') {
                    return mode;
                }
            }
        } catch (error) {
            console.error('Error reading view mode:', error);
        }
        return 'table';
    }

    /**
     * Safely adds an event listener with error handling.
     * @param {Element|null} element - Target element
     * @param {string} event - Event name
     * @param {Function} handler - Event handler
     * @returns {boolean} Success status
     */
    function safeAddListener(element, event, handler) {
        if (!element) {
            debugLog(`Cannot add listener: element is null for event "${event}"`);
            return false;
        }
        try {
            element.addEventListener(event, handler);
            return true;
        } catch (error) {
            console.error(`Error adding "${event}" listener:`, error);
            return false;
        }
    }

    /**
     * Safely opens a URL in a new tab.
     * @param {string} url - URL to open
     */
    function safeOpenPrintUrl(url) {
        try {
            if (!url || typeof url !== 'string') {
                console.error('Invalid print URL');
                return;
            }
            window.open(url, '_blank');
        } catch (error) {
            console.error('Error opening print URL:', error);
        }
    }

    function getElements() {
        return {
            selectAllCheckbox: safeGetElement('select-all-devices'),
            deviceCheckboxes: safeQueryAll('.device-checkbox'),
            printBtn: safeGetElement('print-selected-qrs-btn'),
            badge: safeGetElement('selected-count-badge'),
            clearBtn: safeGetElement('clear-selection-btn'),
            selectionBar: safeGetElement('qr-selection-bar'),
            tableView: safeGetElement('table-view'),
            gridView: safeGetElement('grid-view'),
            toggleBtns: safeQueryAll('.view-toggle-btn'),
        };
    }

    function init() {
        debugLog('Initializing...');

        const els = getElements();

        if (!els.selectAllCheckbox || els.deviceCheckboxes.length === 0) {
            debugLog('Required elements not found, skipping initialization');
            return false;
        }

        const {
            selectAllCheckbox,
            deviceCheckboxes,
            printBtn,
            badge,
            clearBtn,
            selectionBar,
            tableView,
            gridView,
            toggleBtns
        } = els;

        const printUrl = printBtn ? (printBtn.dataset.printUrl || '') : '';

        /**
         * Gets checkboxes from the currently active view.
         * @returns {Element[]}
         */
        function getVisibleCheckboxes() {
            try {
                const activeView = gridView && gridView.classList.contains('hidden') ? tableView : gridView;
                if (!activeView) {
                    return [];
                }
                return Array.from(activeView.querySelectorAll('.device-checkbox')).filter(cb => cb && cb.value);
            } catch (error) {
                console.error('Error getting visible checkboxes:', error);
                return [];
            }
        }

        const debouncedUpdateUI = debounce(function updateUI() {
            const selectedIds = getSelectedIds();
            const totalCount = selectedIds.size;

            safeSetText(badge, totalCount);
            safeClassToggle(selectionBar, 'hidden', totalCount === 0);
            safeSetDisabled(printBtn, totalCount === 0);
            safeClassToggle(clearBtn, 'hidden', totalCount === 0);

            const visibleCheckboxes = getVisibleCheckboxes();
            if (selectAllCheckbox && visibleCheckboxes.length > 0) {
                selectAllCheckbox.checked = visibleCheckboxes.every(cb => cb.checked);
            }
        }, 16);

        function updateUI() {
            debouncedUpdateUI();
        }

        function restoreCheckboxes() {
            const selectedIds = getSelectedIds();
            deviceCheckboxes.forEach(cb => {
                if (cb && cb.value) {
                    cb.checked = selectedIds.has(cb.value);
                }
            });
            updateUI();
        }

        function syncCheckbox(value, checked) {
            deviceCheckboxes.forEach(cb => {
                if (cb && cb.value === value) {
                    cb.checked = checked;
                }
            });
        }

        function setView(mode) {
            if (mode !== 'table' && mode !== 'grid') {
                mode = 'table';
            }

            saveViewMode(mode);

            if (mode === 'grid') {
                safeClassToggle(tableView, 'hidden', true);
                safeClassToggle(gridView, 'hidden', false);
            } else {
                safeClassToggle(tableView, 'hidden', false);
                safeClassToggle(gridView, 'hidden', true);
            }

            toggleBtns.forEach(btn => {
                if (!btn || !btn.dataset) return;
                const isActive = btn.dataset.view === mode;
                safeClassToggle(btn, 'bg-slate-800', isActive);
                safeClassToggle(btn, 'text-white', isActive);
                safeClassToggle(btn, 'text-slate-500', !isActive);
            });

            restoreCheckboxes();
        }

        toggleBtns.forEach(btn => {
            if (!btn || !btn.dataset || !btn.dataset.view) return;
            safeAddListener(btn, 'click', () => setView(btn.dataset.view));
        });

        safeAddListener(selectAllCheckbox, 'change', function() {
            const selectedIds = getSelectedIds();
            const visibleCheckboxes = getVisibleCheckboxes();

            visibleCheckboxes.forEach(cb => {
                if (!cb || !cb.value) return;
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
            if (!cb || !cb.value) return;
            safeAddListener(cb, 'change', function() {
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

        safeAddListener(printBtn, 'click', function() {
            const selectedIds = getSelectedIds();
            if (selectedIds.size > 0) {
                const idsParam = [...selectedIds].join(',');
                safeOpenPrintUrl(`${printUrl}?ids=${idsParam}`);

                try {
                    if (hasSessionStorage) {
                        sessionStorage.removeItem(STORAGE_KEY);
                    }
                } catch (e) {
                    debugLog('Could not clear session storage:', e);
                }

                deviceCheckboxes.forEach(cb => {
                    if (cb) cb.checked = false;
                });
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                }
                updateUI();
            }
        });

        safeAddListener(clearBtn, 'click', function() {
            try {
                if (hasSessionStorage) {
                    sessionStorage.removeItem(STORAGE_KEY);
                }
            } catch (e) {
                debugLog('Could not clear session storage:', e);
            }

            deviceCheckboxes.forEach(cb => {
                if (cb) cb.checked = false;
            });
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
            }
            updateUI();
            safeClassToggle(selectionBar, 'hidden', true);
        });

        setView(getViewMode());
        initialized = true;
        debugLog('Initialized successfully');
        return true;
    }

    /**
     * Cleans up event listeners and intervals.
     */
    function cleanup() {
        debugLog('Cleaning up...');

        if (updateUITimeout) {
            clearTimeout(updateUITimeout);
            updateUITimeout = null;
        }

        if (boundHandleNavigation) {
            document.removeEventListener('livewire:navigating', boundHandleNavigation);
            boundHandleNavigation = null;
        }

        if (boundHandleNavigated) {
            document.removeEventListener('livewire:navigated', boundHandleNavigated);
            boundHandleNavigated = null;
        }

        initialized = false;
        debugLog('Cleanup complete');
    }

    /**
     * Handles Livewire navigation events.
     */
    function handleNavigation() {
        debugLog('Navigation detected, reinitializing...');
        initialized = false;

        if (updateUITimeout) {
            clearTimeout(updateUITimeout);
        }

        updateUITimeout = setTimeout(() => {
            init();
        }, 50);
    }

    /**
     * Public API - Cleanup function for manual invocation.
     * @returns {void}
     */
    function publicCleanup() {
        cleanup();
    }

    try {
        boundHandleNavigation = handleNavigation;
        boundHandleNavigated = handleNavigation;

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

        document.addEventListener('livewire:navigating', boundHandleNavigation);
        document.addEventListener('livewire:navigated', boundHandleNavigated);

        window.deviceIndexCleanup = publicCleanup;

        debugLog('Module loaded, waiting for DOM ready');
    } catch (error) {
        console.error('Device index initialization failed:', error);

        debugLog('Attempting fallback initialization...');

        try {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                setTimeout(init, 100);
            }
        } catch (fallbackError) {
            console.error('Fallback initialization also failed:', fallbackError);
        }
    }
})();
