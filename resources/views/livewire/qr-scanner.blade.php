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
                    <button wire:click="startScanning" type="button" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-cyan-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-cyan-500/30 hover:from-indigo-400 hover:to-cyan-400 transition-all hover:scale-[1.02]">
                        <svg class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Activar Cámara
                    </button>
                </div>
            </div>
        @else
            <div class="w-full glass rounded-2xl shadow-xl overflow-hidden relative">
                <div id="reader" class="w-full h-auto" wire:ignore></div>
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
                            <label for="qr-selected-user" class="block text-sm font-medium text-slate-400">Empleado Responsable</label>
                            <select wire:model="selectedUser" id="qr-selected-user" class="mt-1 block w-full bg-slate-800/50 border-slate-600/50 text-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 backdrop-blur-sm">
                                <option value="">Selecciona un empleado...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedUser') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
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
                                        <label for="qr-quick-user" class="sr-only">Empleado Responsable</label>
                                        <select wire:model="selectedUser" id="qr-quick-user" class="block w-full bg-slate-800/50 border-slate-600/50 text-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 backdrop-blur-sm">
                                            <option value="">Selecciona un empleado...</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedUser') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
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
        window.QrScannerInit = window.QrScannerInit || {
            initialized: false,
            scanner: null,

            startScanner() {
                const readerEl = document.getElementById('reader');
                if (!readerEl) {
                    return false;
                }
                
                if (typeof Html5QrcodeScanner === 'undefined') {
                    setTimeout(() => this.startScanner(), 100);
                    return false;
                }
                
                if (this.scanner) return true;

                try {
                    this.scanner = new Html5QrcodeScanner(
                        "reader", 
                        { fps: 10, qrbox: {width: 250, height: 250} },
                        false
                    );
                    
                    this.scanner.render(this.onScanSuccess.bind(this), this.onScanFailure.bind(this));
                    return true;
                } catch (e) {
                    console.error("Error al iniciar el scanner:", e);
                    return false;
                }
            },

            stopScanner() {
                if (this.scanner) {
                    this.scanner.clear().then(() => {
                        this.scanner = null;
                    }).catch(() => {
                        this.scanner = null;
                    });
                }
            },

            onScanSuccess(decodedText) {
                console.log('QR Code scanned:', decodedText);
                const component = Livewire.findByName('qr-scanner');
                console.log('Component found:', component);
                if (component && typeof component.processQr === 'function') {
                    component.processQr(decodedText);
                } else {
                    console.error('processQr method not found on component');
                }
                this.stopScanner();
            },

            onScanFailure(error) {},

            tryStart() {
                if (this.initialized) return;
                const started = this.startScanner();
                if (!started && typeof Html5QrcodeScanner !== 'undefined') {
                    setTimeout(() => this.tryStart(), 100);
                }
                this.initialized = true;
            },

            reset() {
                this.stopScanner();
                this.initialized = false;
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            window.QrScannerInit.tryStart();
        });

        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph', ({ el }) => {
                if (el.querySelector && el.querySelector('#reader')) {
                    setTimeout(() => window.QrScannerInit.tryStart(), 200);
                }
            });

            Livewire.on('scanner-started', () => {
                window.QrScannerInit.reset();
                setTimeout(() => window.QrScannerInit.tryStart(), 200);
            });

            Livewire.on('auto-scan-next', () => {
                setTimeout(() => {
                    const component = Livewire.findByName('qr-scanner');
                    if (component && typeof component.resetScanner === 'function') {
                        component.resetScanner();
                    }
                    if (component && typeof component.startScanning === 'function') {
                        component.startScanning();
                    }
                }, 1500);
            });
        }
    </script>
    @endpush
</div>
