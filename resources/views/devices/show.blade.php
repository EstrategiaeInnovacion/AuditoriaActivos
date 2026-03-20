<x-app-layout title="Detalles del Activo">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-xl text-white leading-tight">
                {{ $device->name }}
            </h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('devices.edit', $device) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-lg shadow-lg shadow-amber-500/20 hover:from-amber-400 hover:to-orange-400 transition-all hover:scale-[1.02]">
                    <svg class="w-4 h-4 mr-1.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar
                </a>
                <a href="{{ route('devices.print-qr', $device) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-slate-700/60 backdrop-blur-sm border border-slate-600/50 text-white text-sm font-semibold rounded-lg hover:bg-slate-600/80 hover:border-slate-500 transition-all">
                    <svg class="w-4 h-4 mr-1.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Imprimir QR
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        lightbox: false,
        currentImg: '',
        currentIndex: 0,
        photos: {{ Js::from($device->photos->values()) }},
        scale: 1,
        loading: false,
        openLightbox(index, img) {
            this.currentIndex = index;
            this.currentImg = img;
            this.lightbox = true;
            this.scale = 1;
        },
        navigatePhoto(dir) {
            if (this.photos.length <= 1) return;
            this.currentIndex = (this.currentIndex + dir + this.photos.length) % this.photos.length;
            this.currentImg = '/photos/' + this.photos[this.currentIndex].id;
            this.scale = 1;
        },
        zoomIn() { this.scale = Math.min(this.scale + 0.25, 4); },
        zoomOut() { this.scale = Math.max(this.scale - 0.25, 0.5); },
        resetZoom() { this.scale = 1; }
    }" @keydown.escape.window="lightbox = false" @keydown.arrow-left.window="navigatePhoto(-1)" @keydown.arrow-right.window="navigatePhoto(1)">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="glass rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50 bg-slate-800/50 backdrop-blur-sm">
                        <h3 class="text-lg font-bold text-slate-200 tracking-tight flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            Información del Dispositivo
                        </h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div>
                                <dt class="text-slate-500 font-medium">Nombre</dt>
                                <dd class="text-slate-200 font-semibold mt-0.5">{{ $device->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">Tipo</dt>
                                <dd class="mt-0.5">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full bg-slate-700/50 text-slate-300 border border-slate-600/50">{{ ucfirst($device->type) }}</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">Marca</dt>
                                <dd class="text-slate-200 mt-0.5">{{ $device->brand }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">Modelo</dt>
                                <dd class="text-slate-200 mt-0.5">{{ $device->model }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">No. Serie</dt>
                                <dd class="text-slate-200 font-mono text-xs mt-0.5">{{ $device->serial_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">Estado</dt>
                                <dd class="mt-0.5">
                                    @php
                                        $statusConfig = [
                                            'available' => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/30', 'label' => 'Disponible'],
                                            'assigned' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/30', 'label' => 'Asignado'],
                                            'maintenance' => ['bg' => 'bg-amber-500/20', 'text' => 'text-amber-400', 'border' => 'border-amber-500/30', 'label' => 'Mantenimiento'],
                                            'broken' => ['bg' => 'bg-red-500/20', 'text' => 'text-red-400', 'border' => 'border-red-500/30', 'label' => 'Averiado'],
                                        ];
                                        $sc = $statusConfig[$device->status] ?? ['bg' => 'bg-slate-700/50', 'text' => 'text-slate-400', 'border' => 'border-slate-600/50', 'label' => ucfirst($device->status)];
                                    @endphp
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sc['bg'] }} {{ $sc['text'] }} border {{ $sc['border'] }}">{{ $sc['label'] }}</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">Fecha Compra</dt>
                                <dd class="text-slate-200 mt-0.5">{{ $device->purchase_date ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">Venc. Garantía</dt>
                                <dd class="text-slate-200 mt-0.5">{{ $device->warranty_expiration ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                        @if($device->notes)
                            <div class="mt-4 pt-4 border-t border-slate-700/50">
                                <dt class="text-slate-500 font-medium text-sm">Notas</dt>
                                <dd class="text-slate-300 text-sm mt-1 bg-slate-800/50 p-3 rounded-lg border border-slate-700/50">{{ $device->notes }}</dd>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-8">
                    @if($device->credential)
                    <div class="glass rounded-2xl overflow-hidden" x-data="{ showPasswords: false }">
                        <div class="px-6 py-4 border-b border-slate-700/50 bg-gradient-to-r from-indigo-500/10 to-cyan-500/10 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-200 tracking-tight flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Credenciales
                            </h3>
                            <button @click="showPasswords = !showPasswords" type="button" class="text-sm px-3 py-1 bg-slate-700/50 border border-slate-600/50 rounded-md text-slate-300 hover:text-white hover:bg-slate-600/50 font-medium transition">
                                <span x-text="showPasswords ? 'Ocultar' : 'Mostrar'"></span>
                            </button>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-3 text-sm">
                                <div class="flex justify-between items-center">
                                    <dt class="text-slate-500 font-medium">Usuario Equipo</dt>
                                    <dd class="text-slate-200 font-mono">{{ $device->credential->username }}</dd>
                                </div>
                                <div class="flex justify-between items-center">
                                    <dt class="text-slate-500 font-medium">Password Equipo</dt>
                                    <dd>
                                        <span x-show="!showPasswords" class="font-mono text-slate-500">••••••••</span>
                                        <span x-show="showPasswords" x-cloak class="font-mono text-indigo-400 font-bold bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-500/30">{{ $device->credential->password }}</span>
                                    </dd>
                                </div>
                                <hr class="border-slate-700/50">
                                <div class="flex justify-between items-center">
                                    <dt class="text-slate-500 font-medium">Correo</dt>
                                    <dd class="text-slate-200 font-mono text-xs">{{ $device->credential->email }}</dd>
                                </div>
                                <div class="flex justify-between items-center">
                                    <dt class="text-slate-500 font-medium">Password Correo</dt>
                                    <dd>
                                        <span x-show="!showPasswords" class="font-mono text-slate-500">••••••••</span>
                                        <span x-show="showPasswords" x-cloak class="font-mono text-indigo-400 font-bold bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-500/30">{{ $device->credential->email_password }}</span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    @endif

                    <div class="glass rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-700/50 bg-slate-800/50 backdrop-blur-sm">
                            <h3 class="text-lg font-bold text-slate-200 tracking-tight flex items-center gap-2">
                            <svg class="w-5 h-5 text-cyan-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Fotos del Equipo ({{ $device->photos->count() }})
                        </h3>
                        </div>
                        <div class="p-6">
                            @if($device->photos->count())
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    @foreach($device->photos as $index => $photo)
                                        <div class="relative group cursor-pointer" @click="openLightbox({{ $index }}, '{{ route('device.photos.show', $photo) }}')">
                                            <img src="{{ route('device.photos.show', $photo) }}" alt="{{ $photo->caption ?? 'Foto del equipo' }}" class="w-full h-24 object-cover rounded-lg border border-slate-700/50 group-hover:border-indigo-500/50 transition">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 rounded-lg transition flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                            </div>
                                            @if($device->photos->count() > 1)
                                                <span class="absolute bottom-1 right-1 bg-black/60 text-white text-xs px-1.5 py-0.5 rounded">{{ $index + 1 }}/{{ $device->photos->count() }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-500 text-center py-4">Sin fotos. Agrega fotos al editar el dispositivo.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-700/50 bg-slate-800/50 backdrop-blur-sm flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <h3 class="text-lg font-bold text-slate-200 tracking-tight flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        Documentos Adjuntos
                    </h3>
                    <form method="POST" action="{{ route('device.documents.store', $device) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3 w-full md:w-auto">
                        @csrf
                        <label for="document-type-select" class="sr-only">Tipo de documento</label>
                        <select id="document-type-select" name="type" required class="text-xs bg-slate-800/50 border-slate-600/50 text-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm backdrop-blur-sm">
                            <option value="" disabled selected>Selecciona tipo...</option>
                            <option value="factura">Factura</option>
                            <option value="garantia">Garantía</option>
                            <option value="contrato">Contrato</option>
                            <option value="manual">Manual</option>
                            <option value="otro">Otro</option>
                        </select>
                        <label for="document-file-input" class="sr-only">Archivo del documento</label>
                        <input type="file" id="document-file-input" name="document" required class="text-xs text-slate-400 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-500/20 file:text-indigo-300 hover:file:bg-indigo-500/30 w-full sm:w-auto border border-slate-600/50 bg-slate-800/50 shadow-sm p-1 rounded-md backdrop-blur-sm">
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-cyan-500 text-white text-xs font-bold rounded-lg hover:from-indigo-400 hover:to-cyan-400 shadow-lg shadow-cyan-500/20 transition-all hover:scale-[1.02] w-full sm:w-auto">Subir</button>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700/50 text-sm">
                        <thead class="bg-slate-800/30 backdrop-blur-sm border-b border-slate-700/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-300 tracking-wider">Archivo</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-300 tracking-wider">Tipo</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-300 tracking-wider hidden sm:table-cell">Subido</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-300 tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            @forelse($device->documents as $document)
                                <tr class="hover:bg-slate-800/30 transition">
                                    <td class="px-6 py-3 font-medium text-slate-200">{{ $document->original_name }}</td>
                                    <td class="px-6 py-3">
                                        @php
                                            $typeColors = [
                                                'factura' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                                'garantia' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                                'contrato' => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                                                'manual' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                                                'otro' => 'bg-slate-700/50 text-slate-400 border-slate-600/50',
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full border {{ $typeColors[$document->type] ?? $typeColors['otro'] }}">{{ ucfirst($document->type) }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-slate-500 hidden sm:table-cell">{{ $document->created_at->diffForHumans() }}</td>
                                    <td class="px-6 py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('device.documents.download', $document) }}" class="text-indigo-400 hover:text-indigo-300 text-xs font-medium transition">Descargar</a>
                                            <form method="POST" action="{{ route('device.documents.destroy', $document) }}" onsubmit="return confirm('¿Eliminar este documento?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium transition">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500 text-sm">No hay documentos adjuntos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="glass rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-700/50 bg-slate-800/50 backdrop-blur-sm">
                    <h3 class="text-lg font-bold text-slate-200 tracking-tight flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Historial de Asignaciones
                    </h3>
                </div>
                <div class="p-6">
                    @if($device->assignments->count())
                        <div class="relative">
                            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-slate-700/50"></div>

                            <div class="space-y-6">
                                @foreach($device->assignments->sortByDesc('assigned_at') as $assignment)
                                    @php
                                        $isCurrent = is_null($assignment->returned_at);
                                        $dotColor = $isCurrent ? 'bg-blue-500 ring-blue-500/30' : 'bg-emerald-500 ring-emerald-500/30';
                                    @endphp
                                    <div class="relative flex items-start ml-4 pl-8">
                                        <div class="absolute -left-2 top-1 w-4 h-4 rounded-full {{ $dotColor }} ring-4 z-10"></div>

                                        <div class="flex-1 bg-slate-800/50 rounded-lg p-4 border border-slate-700/50 {{ $isCurrent ? 'border-blue-500/30 bg-blue-500/5' : '' }}">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-200">
                                                        {{ $assignment->user ? $assignment->user->name : $assignment->assigned_to }}
                                                    </p>
                                                    <p class="text-xs text-slate-500 mt-0.5">
                                                        {{ $assignment->assigned_at->format('d/m/Y H:i') }}
                                                        @if($assignment->returned_at)
                                                            → {{ $assignment->returned_at->format('d/m/Y H:i') }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <div>
                                                    @if($isCurrent)
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                                            <svg class="w-3 h-3" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="10"/></svg>
                                                            Asignado actualmente · {{ $assignment->assigned_at->diffForHumans() }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                                            <svg class="w-3 h-3" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            Devuelto
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($assignment->notes)
                                                <p class="mt-2 text-xs text-slate-400 bg-slate-900/50 p-2 rounded border border-slate-700/50">{{ $assignment->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8" role="status">
                            <svg class="mx-auto h-12 w-12 text-slate-600 mb-3" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm text-slate-500">No hay historial de asignaciones para este equipo.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div x-show="lightbox" x-cloak class="fixed inset-0 z-[9999] bg-slate-950/95 backdrop-blur-sm flex items-center justify-center" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0" @click="lightbox = false"></div>
        <div class="absolute top-4 right-4 flex items-center gap-2 z-10">
            <button @click.stop="zoomIn()" class="p-2 bg-slate-800/80 hover:bg-slate-700 rounded-lg text-white transition" title="Acercar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path></svg>
            </button>
            <span class="text-white text-sm font-mono bg-slate-800/80 px-2 py-1 rounded" x-text="Math.round(scale * 100) + '%'"></span>
            <button @click.stop="zoomOut()" class="p-2 bg-slate-800/80 hover:bg-slate-700 rounded-lg text-white transition" title="Alejar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path></svg>
            </button>
            <button @click.stop="resetZoom()" class="p-2 bg-slate-800/80 hover:bg-slate-700 rounded-lg text-white transition" title="Restablecer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            </button>
            <button @click="lightbox = false" class="p-2 bg-slate-800/80 hover:bg-red-600 rounded-lg text-white transition ml-2">
                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        @if($device->photos->count() > 1)
            <button @click.stop="navigatePhoto(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 p-3 bg-slate-800/80 hover:bg-slate-700 rounded-full text-white transition z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click.stop="navigatePhoto(1)" class="absolute right-4 top-1/2 -translate-y-1/2 p-3 bg-slate-800/80 hover:bg-slate-700 rounded-full text-white transition z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-slate-800/80 px-3 py-1 rounded-full text-white text-sm z-10" x-text="(currentIndex + 1) + ' / {{ $device->photos->count() }}'"></div>
        @endif
        <div class="relative max-w-[95vw] max-h-[95vh] flex items-center justify-center" @click.stop>
            <img :src="currentImg" alt="Foto ampliada" class="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl border border-slate-700/50 transition-transform duration-200" :style="'transform: scale(' + scale + ')'" @load="loading = false" @error="loading = false">
        </div>
    </div>
</x-app-layout>
