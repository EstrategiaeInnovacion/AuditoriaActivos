<div class="max-w-md mx-auto p-4 flex flex-col items-center">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Escáner de Activos</h2>

    @if(!$scannedCode)
        @if(!$isScanning)
            <div class="w-full bg-white rounded-lg shadow-md p-6 text-center">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Escanear Activo</h3>
                <p class="mt-1 text-sm text-gray-500">Activa la cámara para escanear el código QR.</p>
                <div class="mt-6">
                    <button wire:click="startScanning" type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
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
            <button wire:click="resetScanner" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg shadow hover:bg-green-700">
                Escanear otro equipo
            </button>
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
                            <label class="block text-sm font-medium text-gray-700">Empleado Responsable</label>
                            <select wire:model="selectedUser" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Selecciona un empleado...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedUser') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Tipo de Movimiento</label>
                            <select wire:model.live="assignmentType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="asignacion_fija">Asignación Fija</option>
                                <option value="prestamo_temporal">Préstamo Temporal (Por horas/días)</option>
                            </select>
                        </div>

                        @if($assignmentType === 'prestamo_temporal')
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700">Fecha y Hora de Devolución</label>
                                <input type="datetime-local" wire:model="expectedReturnDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('expectedReturnDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Condiciones de Entrega (Opcional)</label>
                            <textarea wire:model="deliveryConditions" rows="2" placeholder="Ej. Tiene un rayón en la pantalla..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
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
                            <button wire:click="toggleAssignForm" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg shadow hover:bg-blue-700">
                                Asignar / Prestar Equipo
                            </button>
                        @elseif($device->status === 'broken')
                             <div class="p-3 bg-red-100 text-red-800 rounded text-center">
                                Este equipo está marcado como averiado.
                             </div>
                        @else
                            <button wire:click="returnDevice" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg shadow hover:bg-green-700">
                                Recibir Devolución
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
        document.addEventListener('livewire:initialized', () => {
            let scanner = null;

            function startScanner() {
                if (!document.getElementById('reader')) return;
                
                // Si ya existe una instancia, no crear otra
                if (scanner) {
                    // Si el elemento reader existe pero el scanner dice que no esta corriendo (estado inusual), limpiamos y reiniciamos
                    // Pero generalmente, si scanner existe, asumimos que está corriendo o preparándose.
                    return; 
                }

                try {
                    scanner = new Html5QrcodeScanner(
                        "reader", 
                        { fps: 10, qrbox: {width: 250, height: 250} },
                        /* verbose= */ false
                    );
                    
                    scanner.render(onScanSuccess, onScanFailure);
                } catch (e) {
                    console.error("Error al iniciar el scanner:", e);
                }
            }

            function stopScanner() {
                if (scanner) {
                    scanner.clear().then(() => {
                        scanner = null;
                    }).catch(error => {
                        console.error("Error al detener el scanner:", error);
                        scanner = null; // Forzar null aunque falle
                    });
                }
            }

            function onScanSuccess(decodedText, decodedResult) {
                console.log(`Code matched = ${decodedText}`, decodedResult);
                @this.processQr(decodedText);
                stopScanner();
            }

            function onScanFailure(error) {
                // Ignore errors mostly
            }

            // Iniciar si ya está el elemento presente (ej. al recargar página con estado guardado)
            startScanner();

            // Escuchar actualizaciones de Livewire
            Livewire.hook('morph.updated', ({ el, component }) => {
                // Verificamos si el elemento 'reader' está presente en el DOM
                if (document.getElementById('reader')) {
                    startScanner();
                } else {
                    // Si el elemento desapareció (por ejemplo, isScanning = false), asegúrate de liberar la cámara
                    stopScanner();
                }
            });

            // Limpieza al salir
            Livewire.hook('element.removed', ({ el, component }) => {
                 // Si el componente se elimina, liberamos
                 stopScanner();
            });
        });
    </script>
</div>
