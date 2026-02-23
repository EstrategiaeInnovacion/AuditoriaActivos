<x-app-layout title="Detalles del Activo">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ $device->name }}
            </h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('devices.edit', $device) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-sm font-semibold rounded-lg shadow-md hover:from-amber-400 hover:to-amber-500 hover:shadow-lg transition-all hover:scale-105">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar
                </a>
                <a href="{{ route('devices.print-qr', $device) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm font-semibold rounded-lg shadow-md hover:from-slate-600 hover:to-slate-700 hover:shadow-lg transition-all hover:scale-105">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Imprimir QR
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ===================== INFO + CREDENTIALS ROW ===================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Device Info Card --}}
                <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/80 backdrop-blur-sm">
                        <h3 class="text-lg font-bold text-slate-800 tracking-tight">Información del Dispositivo</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div>
                                <dt class="text-slate-500 font-medium">Nombre</dt>
                                <dd class="text-slate-900 font-semibold mt-0.5">{{ $device->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">Tipo</dt>
                                <dd class="mt-0.5">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full bg-slate-100 text-slate-700">{{ ucfirst($device->type) }}</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">Marca</dt>
                                <dd class="text-slate-900 mt-0.5">{{ $device->brand }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">Modelo</dt>
                                <dd class="text-slate-900 mt-0.5">{{ $device->model }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">No. Serie</dt>
                                <dd class="text-slate-900 font-mono text-xs mt-0.5">{{ $device->serial_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">Estado</dt>
                                <dd class="mt-0.5">
                                    @php
                                        $statusConfig = [
                                            'available' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Disponible'],
                                            'assigned' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Asignado'],
                                            'maintenance' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Mantenimiento'],
                                            'broken' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Averiado'],
                                        ];
                                        $sc = $statusConfig[$device->status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => ucfirst($device->status)];
                                    @endphp
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sc['bg'] }} {{ $sc['text'] }}">{{ $sc['label'] }}</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">Fecha Compra</dt>
                                <dd class="text-slate-900 mt-0.5">{{ $device->purchase_date ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium">Venc. Garantía</dt>
                                <dd class="text-slate-900 mt-0.5">{{ $device->warranty_expiration ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                        @if($device->notes)
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <dt class="text-slate-500 font-medium text-sm">Notas</dt>
                                <dd class="text-slate-700 text-sm mt-1 bg-slate-50 p-3 rounded-lg">{{ $device->notes }}</dd>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Credentials Card --}}
                <div class="space-y-8">
                    @if($device->credential)
                    <div class="bg-white rounded-2xl shadow-xl shadow-indigo-100/50 border border-indigo-100 overflow-hidden" x-data="{ showPasswords: false }">
                        <div class="px-6 py-4 border-b border-indigo-100 bg-gradient-to-r from-indigo-50 to-indigo-100/50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-indigo-900 tracking-tight">🔐 Credenciales</h3>
                            <button @click="showPasswords = !showPasswords" type="button" class="text-sm px-3 py-1 bg-white border border-indigo-200 rounded-md text-indigo-600 hover:text-indigo-800 font-medium transition shadow-sm hover:shadow">
                                <span x-text="showPasswords ? '🔒 Ocultar' : '👁️ Mostrar'"></span>
                            </button>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-3 text-sm">
                                <div class="flex justify-between items-center">
                                    <dt class="text-slate-500 font-medium">Usuario Equipo</dt>
                                    <dd class="text-slate-900 font-mono">{{ $device->credential->username }}</dd>
                                </div>
                                <div class="flex justify-between items-center">
                                    <dt class="text-slate-500 font-medium">Password Equipo</dt>
                                    <dd>
                                        <span x-show="!showPasswords" class="font-mono text-slate-400">••••••••</span>
                                        <span x-show="showPasswords" x-cloak class="font-mono text-indigo-700 font-bold bg-indigo-50 px-2 py-0.5 rounded">{{ $device->credential->password }}</span>
                                    </dd>
                                </div>
                                <hr class="border-slate-200">
                                <div class="flex justify-between items-center">
                                    <dt class="text-slate-500 font-medium">Correo</dt>
                                    <dd class="text-slate-900 font-mono text-xs">{{ $device->credential->email }}</dd>
                                </div>
                                <div class="flex justify-between items-center">
                                    <dt class="text-slate-500 font-medium">Password Correo</dt>
                                    <dd>
                                        <span x-show="!showPasswords" class="font-mono text-slate-400">••••••••</span>
                                        <span x-show="showPasswords" x-cloak class="font-mono text-indigo-700 font-bold bg-indigo-50 px-2 py-0.5 rounded">{{ $device->credential->email_password }}</span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    @endif

                    {{-- Photos Card --}}
                    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden" x-data="{ lightbox: false, currentImg: '' }">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/80 backdrop-blur-sm">
                            <h3 class="text-lg font-bold text-slate-800 tracking-tight">📷 Fotos del Equipo</h3>
                        </div>
                        <div class="p-6">
                            @if($device->photos->count())
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    @foreach($device->photos as $photo)
                                        <div class="relative group cursor-pointer" @click="lightbox = true; currentImg = '{{ route('device.photos.show', $photo) }}'">
                                            <img src="{{ route('device.photos.show', $photo) }}" alt="{{ $photo->caption ?? 'Foto del equipo' }}" class="w-full h-24 object-cover rounded-lg border border-slate-200 group-hover:border-indigo-400 transition">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 rounded-lg transition flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-400 text-center py-4">Sin fotos. Agrega fotos al editar el dispositivo.</p>
                            @endif
                        </div>
                        {{-- Lightbox --}}
                        <div x-show="lightbox" x-cloak @click="lightbox = false" class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                            <img :src="currentImg" class="max-w-full max-h-[85vh] rounded-xl shadow-2xl">
                            <button @click="lightbox = false" class="absolute top-4 right-4 text-white hover:text-slate-300 transition">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== DOCUMENTS SECTION ===================== --}}
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/80 backdrop-blur-sm flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight">📎 Documentos Adjuntos</h3>
                    <form method="POST" action="{{ route('device.documents.store', $device) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3 w-full md:w-auto">
                        @csrf
                        <select name="type" required class="text-xs border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="" disabled selected>Selecciona tipo...</option>
                            <option value="factura">Factura</option>
                            <option value="garantia">Garantía</option>
                            <option value="contrato">Contrato</option>
                            <option value="manual">Manual</option>
                            <option value="otro">Otro</option>
                        </select>
                        <input type="file" name="document" required class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 w-full sm:w-auto border border-slate-200 bg-white shadow-sm p-1 rounded-md">
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-cyan-500 text-white text-xs font-bold rounded-md hover:from-indigo-400 hover:to-cyan-400 shadow-md shadow-cyan-500/20 transition-all hover:scale-105 w-full sm:w-auto">Subir</button>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50/80 backdrop-blur-sm border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 tracking-wider">Archivo</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 tracking-wider">Tipo</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 tracking-wider hidden sm:table-cell">Subido</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-indigo-900 tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($device->documents as $document)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-3 font-medium text-slate-900">{{ $document->original_name }}</td>
                                    <td class="px-6 py-3">
                                        @php
                                            $typeColors = [
                                                'factura' => 'bg-green-100 text-green-700',
                                                'garantia' => 'bg-blue-100 text-blue-700',
                                                'contrato' => 'bg-purple-100 text-purple-700',
                                                'manual' => 'bg-amber-100 text-amber-700',
                                                'otro' => 'bg-slate-100 text-slate-700',
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $typeColors[$document->type] ?? $typeColors['otro'] }}">{{ ucfirst($document->type) }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-slate-500 hidden sm:table-cell">{{ $document->created_at->diffForHumans() }}</td>
                                    <td class="px-6 py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('device.documents.download', $document) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">Descargar</a>
                                            <form method="POST" action="{{ route('device.documents.destroy', $document) }}" onsubmit="return confirm('¿Eliminar este documento?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">No hay documentos adjuntos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ===================== ASSIGNMENT TIMELINE ===================== --}}
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/80 backdrop-blur-sm">
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight">📋 Historial de Asignaciones</h3>
                </div>
                <div class="p-6">
                    @if($device->assignments->count())
                        <div class="relative">
                            {{-- Timeline line --}}
                            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-slate-200"></div>

                            <div class="space-y-6">
                                @foreach($device->assignments->sortByDesc('assigned_at') as $assignment)
                                    @php
                                        $isCurrent = is_null($assignment->returned_at);
                                        $dotColor = $isCurrent ? 'bg-blue-500 ring-blue-100' : 'bg-emerald-500 ring-emerald-100';
                                    @endphp
                                    <div class="relative flex items-start ml-4 pl-8">
                                        {{-- Dot --}}
                                        <div class="absolute -left-2 top-1 w-4 h-4 rounded-full {{ $dotColor }} ring-4 z-10"></div>

                                        <div class="flex-1 bg-slate-50 rounded-lg p-4 border border-slate-200 {{ $isCurrent ? 'border-blue-300 bg-blue-50/50' : '' }}">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-900">
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
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                            🔵 Asignado actualmente · {{ $assignment->assigned_at->diffForHumans() }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                                            ✅ Devuelto
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($assignment->notes)
                                                <p class="mt-2 text-xs text-slate-600 bg-white/60 p-2 rounded border border-slate-100">{{ $assignment->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm text-slate-400">No hay historial de asignaciones para este equipo.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
