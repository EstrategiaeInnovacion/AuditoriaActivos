<x-app-layout title="Activos Averiados">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">
                    Activos Averiados
                </h2>
                <p class="text-xs text-slate-400 mt-1">Dispositivos fuera de servicio. Puedes eliminarlos del inventario.</p>
            </div>
            <a href="{{ route('devices.index') }}" class="inline-flex items-center px-4 py-2 glass-light text-slate-300 text-sm font-medium rounded-xl hover:text-white hover:bg-slate-700/50 transition-all border border-slate-700/50">
                <svg class="w-4 h-4 mr-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a Activos
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="glass-light rounded-xl p-4 flex items-center gap-3 border border-emerald-500/30 bg-emerald-500/10" role="status" aria-live="polite">
                    <svg class="h-5 w-5 text-emerald-400 flex-shrink-0" aria-hidden="true" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    <p class="text-sm text-emerald-300">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="glass-light rounded-xl p-4 flex items-center gap-3 border border-red-500/30 bg-red-500/10" role="alert" aria-live="assertive">
                    <svg class="h-5 w-5 text-red-400 flex-shrink-0" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-red-300">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Counter --}}
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-xl bg-red-500/20">
                    <svg class="w-6 h-6 text-red-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ $total }}</p>
                    <p class="text-xs text-slate-400">{{ Illuminate\Support\Str::plural('dispositivo', $total) }} averiado{{ $total !== 1 ? 's' : '' }}</p>
                </div>
            </div>

            {{-- Search --}}
            <div class="glass-light rounded-2xl overflow-hidden">
                <div class="p-4 border-b border-slate-700/50">
                    <form method="GET" action="{{ route('devices.broken') }}" class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, serie o marca..." class="w-full pl-10 pr-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-500/30 transition-all text-sm text-white placeholder-slate-500">
                        </div>
                        <select name="type" class="bg-slate-800/50 border border-slate-700 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-500/30 py-3 px-4 text-sm text-slate-300">
                            <option value="">Todos los tipos</option>
                            <option value="computer" {{ request('type') == 'computer' ? 'selected' : '' }}>Computadora</option>
                            <option value="peripheral" {{ request('type') == 'peripheral' ? 'selected' : '' }}>Periférico</option>
                            <option value="printer" {{ request('type') == 'printer' ? 'selected' : '' }}>Impresora</option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Otro</option>
                        </select>
                        <button type="submit" class="px-5 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-semibold rounded-xl hover:from-red-500 hover:to-rose-500 shadow-lg shadow-red-500/20 transition-all">
                            Buscar
                        </button>
                        @if(request()->hasAny(['search', 'type']))
                            <a href="{{ route('devices.broken') }}" class="px-4 py-3 glass-light text-slate-400 text-sm font-medium rounded-xl hover:text-white hover:bg-slate-700/50 transition-all border border-slate-700/50 text-center">
                                Limpiar
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-800/50 border-b border-slate-700/50">
                            <tr>
                                <th scope="col" class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Dispositivo</th>
                                <th scope="col" class="hidden sm:table-cell py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Tipo</th>
                                <th scope="col" class="hidden md:table-cell py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Serie</th>
                                <th scope="col" class="hidden lg:table-cell py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Notas</th>
                                <th scope="col" class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/30">
                            @forelse ($devices as $device)
                                <tr class="hover:bg-red-500/5 transition-colors group">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 bg-red-500/10 rounded-xl flex items-center justify-center text-red-400 flex-shrink-0">
                                                @if($device->type == 'computer')
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                @elseif($device->type == 'printer')
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                @else
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('devices.show', $device) }}" class="text-sm font-semibold text-white hover:text-red-400 transition">{{ $device->name }}</a>
                                                <p class="text-xs text-slate-500">{{ $device->brand }} {{ $device->model }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hidden sm:table-cell py-4 px-4">
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-800/50 text-slate-400 border border-slate-700/50">{{ ucfirst($device->type) }}</span>
                                    </td>
                                    <td class="hidden md:table-cell py-4 px-4 text-sm text-slate-400 font-mono">{{ $device->serial_number }}</td>
                                    <td class="hidden lg:table-cell py-4 px-4 text-sm text-slate-500 max-w-xs truncate">{{ $device->notes ?? '—' }}</td>
                                    <td class="py-4 px-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('devices.show', $device) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-xl transition" aria-label="Ver {{ $device->name }}">
                                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            @can('delete', $device)
                                            <form method="POST" action="{{ route('devices.destroy', $device) }}"
                                                  onsubmit="return confirm('¿Eliminar permanentemente {{ addslashes($device->name) }}? Esta acción no se puede deshacer.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-xl transition" aria-label="Eliminar {{ $device->name }}">
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="text-slate-400">
                                            <svg class="mx-auto h-12 w-12 mb-4 text-emerald-500/50" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-sm font-medium text-slate-300">No hay activos averiados</p>
                                            <p class="text-xs mt-1 text-slate-500">¡Todo el inventario funciona correctamente!</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-slate-800/50 border-t border-slate-700/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-slate-400">
                        Mostrando <span class="font-bold text-white">{{ $devices->firstItem() ?? 0 }}-{{ $devices->lastItem() ?? 0 }}</span> de <span class="font-bold text-white">{{ $devices->total() }}</span> resultados
                    </p>
                    {{ $devices->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
