<div class="max-w-md mx-auto p-4 flex flex-col items-center">
    <div class="flex items-center justify-between w-full mb-4">
        <h2 class="text-xl font-bold text-gray-800">Escáner de Activos</h2>
    </div>

    @if(!$scannedCode)
        @if(!$isScanning)
            <div class="w-full bg-white rounded-lg shadow-md p-6 text-center">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Escanear Activo</h3>
                <p class="mt-1 text-sm text-gray-500">Activa la cámara para escanear el código QR.</p>
                <div class="mt-6">
                    <button wire:click="startScanning" type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Activar Cámara
                    </button>
                </div>
            </div>
        @else
            <div class="w-full bg-white rounded-lg shadow-md overflow-hidden relative">
                <div id="reader" class="w-full h-auto" wire:ignore></div>

            </div>
            <p class="mt-4 text-sm text-gray-500 text-center">Apunta la cámara al código QR.</p>
        @endif
        
    @elseif($scannedCode === 'success_screen')
        <div class="w-full bg-green-100 rounded-lg shadow-md p-6 text-center">
            <h3 class="text-2xl font-bold text-green-800 mb-2">¡Listo!</h3>
            <p class="text-green-700 mb-6">{{ $message }}</p>
            @if($quickMode)
                <p class="text-sm text-green-600 animate-pulse">Reiniciando escáner...</p>
            @else
                <button wire:click="resetScanner" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg shadow hover:bg-green-700">
                    Escanear otro equipo
                </button>
            @endif
        </div>

    @else
        <div class="w-full bg-white rounded-lg shadow-md p-6">
            
            @if($device)
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold">{{ $device->type }} {{ $device->brand }}</h3>
                    <p class="text-gray-600">No. Serie: {{ $device->serial_number }}</p>
                    <span class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
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
                    <div class="border-t pt-4">
                        <h4 class="font-bold text-gray-700 mb-3 text-center">Datos de Asignación</h4>
                        
                        <div class="mb-3">
                            <label for="qr-selected-user" class="block text-sm font-medium text-gray-700">Empleado Responsable</label>
                            <select wire:model="selectedUser" id="qr-selected-user" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Selecciona un empleado...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedUser') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="qr-assignment-type" class="block text-sm font-medium text-gray-700">Tipo de Movimiento</label>
                            <select wire:model.live="assignmentType" id="qr-assignment-type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="asignacion_fija">Asignación Fija</option>
                                <option value="prestamo_temporal">Préstamo Temporal (Por horas/días)</option>
                            </select>
                        </div>

                        @if($assignmentType === 'prestamo_temporal')
                            <div class="mb-3">
                                <label for="qr-return-date" class="block text-sm font-medium text-gray-700">Fecha y Hora de Devolución</label>
                                <input type="datetime-local" wire:model="expectedReturnDate" id="qr-return-date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('expectedReturnDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="qr-delivery-conditions" class="block text-sm font-medium text-gray-700">Condiciones de Entrega (Opcional)</label>
                            <textarea wire:model="deliveryConditions" id="qr-delivery-conditions" rows="2" placeholder="Ej. Tiene un rayón en la pantalla..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <div class="flex gap-2">
                            <button wire:click="toggleAssignForm" class="w-1/3 bg-gray-200 text-gray-800 font-bold py-3 rounded-lg shadow">
                                Cancelar
                            </button>
                            <button wire:click="saveAssignment" class="w-2/3 bg-blue-600 text-white font-bold py-3 rounded-lg shadow hover:bg-blue-700">
                                Confirmar
                            </button>
                        </div>
                    </div>

                @else
                    <div class="mt-6 flex flex-col gap-3">
                        @if($device->status === 'available')
                            @if($quickMode)
                                {{-- Quick mode: simplified assign --}}
                                <div class="border-t pt-4">
                                    <h4 class="font-bold text-gray-700 mb-3 text-center">⚡ Asignación Rápida</h4>
                                    <div class="mb-3">
                                        <label for="qr-quick-user" class="sr-only">Empleado Responsable</label>
                                        <select wire:model="selectedUser" id="qr-quick-user" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Selecciona un empleado...</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedUser') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <button wire:click="saveAssignment" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg shadow hover:bg-blue-700">
                                        ✅ Asignar
                                    </button>
                                </div>
                            @else
                                <button wire:click="toggleAssignForm" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg shadow hover:bg-blue-700">
                                    Asignar / Prestar Equipo
                                </button>
                            @endif
                        @elseif($device->status === 'broken')
                             <div class="p-3 bg-red-100 text-red-800 rounded text-center">
                                Este equipo está marcado como averiado.
                             </div>
                        @else
                            @if($quickMode && $device->currentAssignment)
                                <div class="p-3 bg-blue-50 rounded-lg text-center mb-2">
                                    <p class="text-xs text-slate-500">Asignado a:</p>
                                    <p class="font-bold text-blue-800">{{ $device->currentAssignment->user ? $device->currentAssignment->user->name : $device->currentAssignment->assigned_to }}</p>
                                    <p class="text-xs text-slate-400">{{ $device->currentAssignment->assigned_at->diffForHumans() }}</p>
                                </div>
                            @endif
                            <button wire:click="returnDevice" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg shadow hover:bg-green-700 {{ $quickMode ? 'text-lg py-4' : '' }}">
                                ✅ Recibir Devolución
                            </button>
                        @endif
                        
                        <button wire:click="resetScanner" class="w-full bg-gray-200 text-gray-800 font-bold py-3 rounded-lg shadow">
                            Cancelar Escaneo
                        </button>
                    </div>
                @endif
                
            @else
                <div class="bg-red-100 text-red-800 p-3 rounded-md mb-4 font-semibold">
                    {{ $message }}
                </div>
                <button wire:click="resetScanner" class="w-full bg-gray-200 text-gray-800 font-bold py-3 rounded-lg shadow">
                    Intentar de nuevo
                </button>
            @endif

        </div>
    @endif



    <script>
        let scanner = null;

        function startScanner() {
            const readerEl = document.getElementById('reader');
            if (!readerEl) {
                return false;
            }
            
            if (typeof Html5QrcodeScanner === 'undefined') {
                setTimeout(startScanner, 100);
                return false;
            }
            
            if (scanner) return true;

            try {
                scanner = new Html5QrcodeScanner(
                    "reader", 
                    { fps: 10, qrbox: {width: 250, height: 250} },
                    false
                );
                
                scanner.render(onScanSuccess, onScanFailure);
                return true;
            } catch (e) {
                console.error("Error al iniciar el scanner:", e);
                return false;
            }
        }

        function stopScanner() {
            if (scanner) {
                scanner.clear().then(() => {
                    scanner = null;
                }).catch(() => {
                    scanner = null;
                });
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            @this.processQr(decodedText);
            stopScanner();
        }

        function onScanFailure(error) {
            // Ignore
        }

        function tryStartScanner() {
            const started = startScanner();
            if (!started) {
                setTimeout(tryStartScanner, 100);
            }
        }

        document.addEventListener('DOMContentLoaded', tryStartScanner);

        Livewire.hook('morph', ({ el }) => {
            setTimeout(tryStartScanner, 200);
        });

        Livewire.on('scanner-started', () => {
            setTimeout(tryStartScanner, 200);
        });

        Livewire.on('auto-scan-next', () => {
            setTimeout(() => {
                @this.resetScanner();
                @this.startScanning();
            }, 1500);
        });
    </script>
</div>
