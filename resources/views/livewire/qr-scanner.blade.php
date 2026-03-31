<div class="max-w-md mx-auto p-4 flex flex-col items-center">
    <div class="flex items-center justify-between w-full mb-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            Escáner de Activos
        </h2>
    </div>

    @if(!$scannedCode)
        @if(!$isScanning)
            <div class="w-full glass rounded-2xl shadow-xl p-6 text-center">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-slate-500" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-slate-300">Escanear Activo</h3>
                <p class="mt-1 text-sm text-slate-500">Activa la cámara para escanear el código QR.</p>
                <div class="mt-6">
                    <button 
                        wire:click="startScanning" 
                        type="button" 
                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-cyan-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-cyan-500/30 hover:from-indigo-400 hover:to-cyan-400 transition-all hover:scale-[1.02]"
                        id="start-scanner-btn"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Activar Cámara
                    </button>
                </div>
            </div>
        @else
            <div class="w-full glass rounded-2xl shadow-xl overflow-hidden relative" id="scanner-container">
                <div id="reader" class="w-full h-auto" wire:ignore></div>
                
                <div id="scanner-loading" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm flex flex-col items-center justify-center z-10 hidden">
                    <svg class="animate-spin h-10 w-10 text-indigo-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-slate-300 text-sm font-medium">Inicializando cámara...</span>
                </div>
                
                <div id="scanner-error" class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm flex flex-col items-center justify-center z-10 hidden">
                    <svg class="h-12 w-12 text-red-500 mb-3" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p id="scanner-error-message" class="text-slate-300 text-sm font-medium text-center px-4 mb-4"></p>
                    <button 
                        onclick="window.QrScannerInit?.retry()" 
                        class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors"
                    >
                        Reintentar
                    </button>
                </div>
            </div>
            <p class="mt-4 text-sm text-slate-500 text-center">Apunta la cámara al código QR.</p>
        @endif
        
    @elseif($scannedCode === 'success_screen')
        <div class="w-full bg-emerald-500/10 border border-emerald-500/30 rounded-2xl shadow-xl p-6 text-center backdrop-blur-sm">
            <div class="mb-2">
                <svg class="mx-auto h-12 w-12 text-emerald-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-emerald-400 mb-2">¡Listo!</h3>
            <p class="text-emerald-300/80 mb-6">{{ $message }}</p>
            @if($quickMode)
                <p class="text-sm text-emerald-400/60 animate-pulse">Reiniciando escáner...</p>
            @else
                <button wire:click="resetScanner" class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-500/30 hover:from-emerald-400 hover:to-emerald-500 transition-all">
                    Escanear otro equipo
                </button>
            @endif
        </div>

    @else
        <div class="w-full glass rounded-2xl shadow-xl p-6">
            
            @if($device)
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold text-slate-200">{{ $device->type }} {{ $device->brand }}</h3>
                    <p class="text-slate-400">No. Serie: {{ $device->serial_number }}</p>
                    <span class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-400 border border-blue-500/30">
                        @php
                            $statusLabels = [
                                'available' => 'DISPONIBLE',
                                'assigned' => 'ASIGNADO',
                                'maintenance' => 'MANTENIMIENTO',
                                'broken' => 'AVERIADO',
                            ];
                        @endphp
                        {{ $statusLabels[$device->status] ?? strtoupper($device->status) }}
                    </span>
                </div>

                @if($showAssignForm)
                    <div class="border-t border-slate-700/50 pt-4">
                        <h4 class="font-bold text-slate-300 mb-3 text-center flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            Datos de Asignación
                        </h4>
                        
                        <div class="mb-3">
                            <label for="qr-selected-employee" class="block text-sm font-medium text-slate-400">Empleado Responsable</label>
                            <select wire:model="selectedEmployeeId" id="qr-selected-employee" class="mt-1 block w-full bg-slate-800/50 border-slate-600/50 text-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 backdrop-blur-sm">
                                <option value="">Selecciona un empleado...</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee['id'] }}">{{ $employee['name'] }} ({{ $employee['employee_id'] ?? 'Sin ID' }}) - {{ $employee['department'] ?? '' }}</option>
                                @endforeach
                            </select>
                            @error('selectedEmployeeId') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="qr-assignment-type" class="block text-sm font-medium text-slate-400">Tipo de Movimiento</label>
                            <select wire:model.live="assignmentType" id="qr-assignment-type" class="mt-1 block w-full bg-slate-800/50 border-slate-600/50 text-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 backdrop-blur-sm">
                                <option value="asignacion_fija">Asignación Fija</option>
                                <option value="prestamo_temporal">Préstamo Temporal (Por horas/días)</option>
                            </select>
                        </div>

                        @if($assignmentType === 'prestamo_temporal')
                            <div class="mb-3">
                                <label for="qr-return-date" class="block text-sm font-medium text-slate-400">Fecha y Hora de Devolución</label>
                                <input type="datetime-local" wire:model="expectedReturnDate" id="qr-return-date" class="mt-1 block w-full bg-slate-800/50 border-slate-600/50 text-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 backdrop-blur-sm">
                                @error('expectedReturnDate') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="qr-delivery-conditions" class="block text-sm font-medium text-slate-400">Condiciones de Entrega (Opcional)</label>
                            <textarea wire:model="deliveryConditions" id="qr-delivery-conditions" rows="2" placeholder="Ej. Tiene un rayón en la pantalla..." class="mt-1 block w-full bg-slate-800/50 border-slate-600/50 text-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 backdrop-blur-sm"></textarea>
                        </div>

                        <div class="flex gap-2">
                            <button wire:click="toggleAssignForm" class="w-1/3 bg-slate-700/60 backdrop-blur-sm border border-slate-600/50 text-slate-300 font-bold py-3 rounded-xl hover:bg-slate-600/80 transition-all">
                                Cancelar
                            </button>
                            <button wire:click="saveAssignment" class="w-2/3 bg-gradient-to-r from-indigo-500 to-cyan-500 text-white font-bold py-3 rounded-xl shadow-lg shadow-cyan-500/30 hover:from-indigo-400 hover:to-cyan-400 transition-all">
                                Confirmar
                            </button>
                        </div>
                    </div>

                @else
                    <div class="mt-6 flex flex-col gap-3">
                        @if($device->status === 'available')
                            @if($quickMode)
                                <div class="border-t border-slate-700/50 pt-4">
                                    <h4 class="font-bold text-slate-300 mb-3 text-center flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5 text-amber-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        Asignación Rápida
                                    </h4>
                                    <div class="mb-3">
                                        <label for="qr-quick-employee" class="sr-only">Empleado Responsable</label>
                                        <select wire:model="selectedEmployeeId" id="qr-quick-employee" class="block w-full bg-slate-800/50 border-slate-600/50 text-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 backdrop-blur-sm">
                                            <option value="">Selecciona un empleado...</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee['id'] }}">{{ $employee['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedEmployeeId') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <button wire:click="saveAssignment" class="w-full bg-gradient-to-r from-indigo-500 to-cyan-500 text-white font-bold py-3 rounded-xl shadow-lg shadow-cyan-500/30 hover:from-indigo-400 hover:to-cyan-400 transition-all flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Asignar
                                    </button>
                                </div>
                            @else
                                <button wire:click="toggleAssignForm" class="w-full bg-gradient-to-r from-indigo-500 to-cyan-500 text-white font-bold py-3 rounded-xl shadow-lg shadow-cyan-500/30 hover:from-indigo-400 hover:to-cyan-400 transition-all">
                                    Asignar / Prestar Equipo
                                </button>
                            @endif
                        @elseif($device->status === 'broken')
                             <div class="p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl text-center">
                                 Este equipo está marcado como averiado.
                             </div>
                        @else
                            @if($quickMode && $device->currentAssignment)
                                <div class="p-3 bg-blue-500/10 border border-blue-500/30 rounded-xl text-center mb-2">
                                    <p class="text-xs text-slate-500">Asignado a:</p>
                                    <p class="font-bold text-blue-400">{{ $device->currentAssignment->user ? $device->currentAssignment->user->name : $device->currentAssignment->assigned_to }}</p>
                                    <p class="text-xs text-slate-500">{{ $device->currentAssignment->assigned_at->diffForHumans() }}</p>
                                </div>
                            @endif
                            <button wire:click="returnDevice" class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-500/30 hover:from-emerald-400 hover:to-emerald-500 transition-all {{ $quickMode ? 'text-lg py-4' : '' }} flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Recibir Devolución
                            </button>
                        @endif
                        
                        <button wire:click="resetScanner" class="w-full bg-slate-700/60 backdrop-blur-sm border border-slate-600/50 text-slate-300 font-bold py-3 rounded-xl hover:bg-slate-600/80 transition-all">
                            Cancelar Escaneo
                        </button>
                    </div>
                @endif
                
            @else
                <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-3 rounded-xl mb-4 font-semibold">
                    {{ $message }}
                </div>
                <button wire:click="resetScanner" class="w-full bg-slate-700/60 backdrop-blur-sm border border-slate-600/50 text-slate-300 font-bold py-3 rounded-xl hover:bg-slate-600/80 transition-all">
                    Intentar de nuevo
                </button>
            @endif

        </div>
    @endif


    @push('scripts')
    <script>
        (function() {
            'use strict';
            
            const MAX_RETRIES = 5;
            const RETRY_DELAY = 500;
            const SCANNER_COMPONENT_NAME = 'qr-scanner';
            
            function showLoading(show = true) {
                const loadingEl = document.getElementById('scanner-loading');
                if (loadingEl) {
                    loadingEl.classList.toggle('hidden', !show);
                }
            }
            
            function showError(message = 'Error desconocido') {
                const errorEl = document.getElementById('scanner-error');
                const messageEl = document.getElementById('scanner-error-message');
                if (errorEl && messageEl) {
                    messageEl.textContent = message;
                    errorEl.classList.remove('hidden');
                }
            }
            
            function hideError() {
                const errorEl = document.getElementById('scanner-error');
                if (errorEl) {
                    errorEl.classList.add('hidden');
                }
            }
            
            function getCameraErrorMessage(error) {
                if (!error) return 'Error desconocido al acceder a la cámara.';
                
                const errorName = error.name || (typeof error === 'string' ? error : 'UnknownError');
                
                const errorMap = {
                    'NotAllowedError': 'Permiso denegado. Por favor permite el acceso a la cámara en la configuración del navegador.',
                    'PermissionDeniedError': 'Permiso denegado. Por favor permite el acceso a la cámara en la configuración del navegador.',
                    'NotFoundError': 'No se encontró ninguna cámara. Verifica que tu dispositivo tenga una cámara conectada.',
                    'DevicesNotFoundError': 'No se encontró ninguna cámara. Verifica que tu dispositivo tenga una cámara conectada.',
                    'NotReadableError': 'La cámara está siendo utilizada por otra aplicación. Cierra otras apps que usen la cámara e intenta de nuevo.',
                    'TrackStartError': 'La cámara está siendo utilizada por otra aplicación. Cierra otras apps que usen la cámara e intenta de nuevo.',
                    'NotSupportedError': 'El acceso a la cámara no está soportado en este contexto (HTTP sin SSL).',
                    'SecurityError': 'El acceso a la cámara fue bloqueado por razones de seguridad. Asegúrate de usar HTTPS.',
                    'ContainerNotFound': 'Contenedor del scanner no encontrado.',
                    'LibraryNotLoaded': 'La librería del scanner no se cargó correctamente.'
                };
                
                return errorMap[errorName] || error.message || `Error: ${errorName}`;
            }
            
            function checkCameraSupport() {
                if (!navigator.mediaDevices) {
                    return {
                        supported: false,
                        error: 'Tu navegador no soporta acceso a dispositivos multimedia. Actualiza a una versión moderna.'
                    };
                }
                
                if (!navigator.mediaDevices.getUserMedia) {
                    return {
                        supported: false,
                        error: 'Tu navegador no soporta la API getUserMedia necesaria para acceder a la cámara.'
                    };
                }
                
                return { supported: true, error: null };
            }
            
            function findQrScannerComponent() {
                try {
                    if (typeof Livewire !== 'undefined' && typeof Livewire.getByName === 'function') {
                        const component = Livewire.getByName(SCANNER_COMPONENT_NAME);
                        if (component) {
                            console.log('Found component via Livewire.getByName:', component);
                            return component;
                        }
                    }
                } catch (e) {
                    console.warn('Error finding component by name:', e);
                }
                
                try {
                    if (typeof Livewire !== 'undefined') {
                        const scannerEl = document.querySelector('[wire\\:id]');
                        if (scannerEl) {
                            const wireId = scannerEl.getAttribute('wire:id');
                            if (wireId && typeof Livewire.find === 'function') {
                                const component = Livewire.find(wireId);
                                if (component) {
                                    console.log('Found component via wire:id:', wireId);
                                    return component;
                                }
                            }
                        }
                    }
                } catch (e) {
                    console.warn('Error finding component by wire:id:', e);
                }
                
                try {
                    if (typeof Livewire !== 'undefined' && typeof Livewire.all === 'function') {
                        const components = Livewire.all();
                        if (Array.isArray(components)) {
                            for (const cmp of components) {
                                const id = cmp?.id || cmp?.__instance?.id || '';
                                if (id.includes('qr') || id.includes('scanner')) {
                                    console.log('Found component by ID pattern:', id);
                                    return cmp;
                                }
                            }
                        } else if (components instanceof Map || typeof components === 'object') {
                            for (const [id, cmp] of Object.entries(components)) {
                                if (id.includes('qr') || id.includes('scanner')) {
                                    console.log('Found component by ID pattern:', id);
                                    return cmp;
                                }
                            }
                        }
                    }
                } catch (e) {
                    console.warn('Error finding component by pattern:', e);
                }
                
                return null;
            }
            
            function onScanSuccess(decodedText) {
                console.log('QR Code scanned:', decodedText);
                hideError();
                
                const component = findQrScannerComponent();
                
                if (component && typeof component.processQr === 'function') {
                    component.processQr(decodedText);
                } else {
                    console.warn('processQr method not found, attempting fallback');
                    try {
                        if (typeof Livewire !== 'undefined' && typeof Livewire.emit === 'function') {
                            Livewire.emit('process-qr-code', decodedText);
                        }
                    } catch (e) {
                        console.error('Failed to emit QR code event:', e);
                    }
                }
                
                window.QrScannerInit?.stopScanner();
            }
            
            function onScanFailure(error, errorContext) {
                // Silent failure - this is normal when no QR is in view
            }
            
            function onCameraError(error) {
                console.error('Camera error:', error);
                showLoading(false);
                showError(getCameraErrorMessage(error));
            }
            
            function onScannerReady() {
                console.log('Scanner is ready');
                showLoading(false);
                hideError();
            }
            
            window.QrScannerInit = window.QrScannerInit || {
                initialized: false,
                scanner: null,
                retryCount: 0,
                
                startScanner() {
                    const readerEl = document.getElementById('reader');
                    if (!readerEl) {
                        console.log('Reader element not found');
                        onCameraError({ name: 'ContainerNotFound', message: 'Elemento reader no encontrado' });
                        return false;
                    }
                    
                    const support = checkCameraSupport();
                    if (!support.supported) {
                        onCameraError({ name: 'NotSupported', message: support.error });
                        return false;
                    }
                    
                    if (typeof Html5QrcodeScanner === 'undefined') {
                        console.log('Html5QrcodeScanner not loaded yet');
                        if (this.retryCount < MAX_RETRIES) {
                            this.retryCount++;
                            setTimeout(() => this.startScanner(), RETRY_DELAY);
                        } else {
                            onCameraError({ name: 'LibraryNotLoaded', message: 'La librería no se cargó' });
                        }
                        return false;
                    }
                    
                    if (this.scanner) {
                        console.log('Scanner already initialized');
                        return true;
                    }
                    
                    try {
                        showLoading(true);
                        hideError();
                        
                        this.scanner = new Html5QrcodeScanner(
                            'reader', 
                            { 
                                fps: 10, 
                                qrbox: { width: 250, height: 250 },
                                showTorchButtonIfSupported: true,
                                showZoomButtonIfSupported: true
                            },
                            false
                        );
                        
                        this.scanner.render(
                            (decodedText, decodedResult) => {
                                if (!this.destroyed) {
                                    onScanSuccess(decodedText);
                                }
                            },
                            (error, errorContext) => {
                                if (!this.destroyed) {
                                    onScanFailure(error, errorContext);
                                }
                            }
                        );
                        
                        this.initialized = true;
                        this.retryCount = 0;
                        onScannerReady();
                        return true;
                        
                    } catch (e) {
                        console.error('Error initializing scanner:', e);
                        onCameraError(e);
                        return false;
                    }
                },
                
                stopScanner() {
                    this.destroyed = true;
                    
                    if (this.scanner) {
                        this.scanner.clear()
                            .then(() => {
                                this.scanner = null;
                                this.initialized = false;
                                showLoading(false);
                            })
                            .catch((e) => {
                                console.error('Error clearing scanner:', e);
                                this.scanner = null;
                                this.initialized = false;
                                showLoading(false);
                            });
                    } else {
                        showLoading(false);
                    }
                },
                
                retry() {
                    hideError();
                    this.retryCount = 0;
                    this.destroyed = false;
                    this.startScanner();
                },
                
                reset() {
                    this.destroyed = false;
                    this.stopScanner();
                    this.initialized = false;
                    this.retryCount = 0;
                }
            };
            
            document.addEventListener('DOMContentLoaded', () => {
                console.log('QR Scanner script loaded');
            });
            
            if (typeof Livewire !== 'undefined') {
                Livewire.hook('morph', ({ el }) => {
                    if (el.querySelector && el.querySelector('#reader')) {
                        console.log('Reader element appeared in DOM');
                        if (!window.QrScannerInit?.initialized) {
                            setTimeout(() => window.QrScannerInit?.startScanner(), 300);
                        }
                    }
                });
                
                Livewire.hook('messageProcessed', ({ component, message }) => {
                    if (component?.name === SCANNER_COMPONENT_NAME || component?.id?.includes('qr')) {
                        console.log('QR Scanner component updated');
                    }
                });
                
                Livewire.on('scanner-started', () => {
                    console.log('Scanner started event received');
                    window.QrScannerInit?.reset();
                    setTimeout(() => window.QrScannerInit?.startScanner(), 300);
                });
                
                Livewire.on('scanner-stopped', () => {
                    console.log('Scanner stopped event received');
                    window.QrScannerInit?.stopScanner();
                });
                
                Livewire.on('auto-scan-next', () => {
                    setTimeout(() => {
                        const component = findQrScannerComponent();
                        
                        if (component) {
                            if (typeof component.resetScanner === 'function') {
                                component.resetScanner();
                            }
                            if (typeof component.startScanning === 'function') {
                                component.startScanning();
                            }
                        }
                    }, 1500);
                });
            }
            
            const startBtn = document.getElementById('start-scanner-btn');
            if (startBtn) {
                startBtn.addEventListener('click', () => {
                    window.QrScannerInit?.reset();
                });
            }
            
        })();
    </script>
    @endpush
</div>
