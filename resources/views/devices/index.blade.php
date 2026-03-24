<x-app-layout title="Listado de Activos">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">
                    {{ __('Gestión de Activos') }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">Administra y controla el inventario de hardware</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('devices.export.excel') }}" class="inline-flex items-center px-3 py-2 glass-light text-slate-300 text-xs font-medium rounded-xl hover:text-white hover:bg-slate-700/50 transition-all border border-slate-700/50">
                    <svg class="w-4 h-4 mr-1.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Excel
                </a>
                <a href="{{ route('devices.export.pdf') }}" class="inline-flex items-center px-3 py-2 glass-light text-red-300 text-xs font-medium rounded-xl hover:text-red-200 hover:bg-red-500/10 transition-all border border-red-500/30">
                    <svg class="w-4 h-4 mr-1.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    PDF
                </a>
                <a href="{{ route('devices.broken') }}" class="inline-flex items-center px-3 py-2 glass-light text-orange-300 text-xs font-medium rounded-xl hover:text-orange-200 hover:bg-orange-500/10 transition-all border border-orange-500/30">
                    <svg class="w-4 h-4 mr-1.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Averiados
                </a>
                <a href="{{ route('devices.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-xs font-bold rounded-xl hover:from-indigo-500 hover:to-purple-500 shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all">
                    <svg class="w-4 h-4 mr-1.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nuevo Activo
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ exportFormat: 'excel' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="glass-light rounded-xl p-4 flex items-center gap-3 border border-emerald-500/30 bg-emerald-500/10" role="status" aria-live="polite">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-400" aria-hidden="true" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-sm text-emerald-300">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div class="metric-card p-5 rounded-2xl card-hover">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-xl bg-indigo-500/20">
                            <svg class="w-5 h-5 text-indigo-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                        </div>
                        <span class="text-slate-400 text-sm font-medium">Total</span>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="metric-card p-5 rounded-2xl card-hover border-l-4 border-l-emerald-500">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-xl bg-emerald-500/20">
                            <svg class="w-5 h-5 text-emerald-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-slate-400 text-sm font-medium">Disponibles</span>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ $stats['available'] }}</p>
                </div>
                <div class="metric-card p-5 rounded-2xl card-hover border-l-4 border-l-blue-500">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-xl bg-blue-500/20">
                            <svg class="w-5 h-5 text-blue-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <span class="text-slate-400 text-sm font-medium">Asignados</span>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ $stats['assigned'] }}</p>
                </div>
                <div class="metric-card p-5 rounded-2xl card-hover border-l-4 border-l-amber-500">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-xl bg-amber-500/20">
                            <svg class="w-5 h-5 text-amber-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        </div>
                        <span class="text-slate-400 text-sm font-medium">Mantenimiento</span>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ $stats['maintenance'] }}</p>
                </div>
                <div class="metric-card p-5 rounded-2xl card-hover border-l-4 border-l-red-500">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-xl bg-red-500/20">
                            <svg class="w-5 h-5 text-red-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <span class="text-slate-400 text-sm font-medium">Dañados</span>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ $stats['broken'] }}</p>
                </div>
            </div>

            {{-- QR Selection Bar --}}
            <div id="qr-selection-bar" class="hidden glass rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border border-indigo-500/30 bg-indigo-500/10">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8v4H6v-4H4v-4h2v-1m6-8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold">Selección de QRs</p>
                        <p class="text-indigo-300 text-sm"><span id="selected-count-badge" class="font-bold">0</span> dispositivos seleccionados</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button id="clear-selection-btn" type="button" class="px-4 py-2 glass-light text-slate-300 text-sm font-medium rounded-xl hover:text-white hover:bg-slate-700/50 transition-all border border-slate-700/50">
                        Limpiar selección
                    </button>
                    <button id="print-selected-qrs-btn" type="button" data-print-url="{{ route('devices.print-multiple-qrs') }}" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-bold rounded-xl hover:from-indigo-500 hover:to-purple-500 shadow-lg transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Imprimir QRs
                    </button>
                </div>
            </div>

            {{-- Search & Filters --}}
            <div class="glass-light rounded-2xl overflow-hidden">
                <div class="p-4 border-b border-slate-700/50">
                    <form method="GET" action="{{ route('devices.index') }}" class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" id="filter-search" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, serie o marca..." class="w-full pl-10 pr-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 transition-all text-sm text-white placeholder-slate-500">
                        </div>
                        <select name="type" id="filter-type" class="bg-slate-800/50 border border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 py-3 px-4 text-sm text-slate-300">
                            <option value="">Todos los tipos</option>
                            <option value="computer" {{ request('type') == 'computer' ? 'selected' : '' }}>Computadora</option>
                            <option value="printer" {{ request('type') == 'printer' ? 'selected' : '' }}>Impresora</option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Otro</option>
                        </select>
                        <select name="status" id="filter-status" class="bg-slate-800/50 border border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 py-3 px-4 text-sm text-slate-300">
                            <option value="">Todos los estados</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Asignado</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="broken" {{ request('status') == 'broken' ? 'selected' : '' }}>Averiado</option>
                        </select>
                        <button type="submit" class="px-5 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-xl hover:from-indigo-500 hover:to-purple-500 shadow-lg shadow-indigo-500/20 transition-all">
                            Buscar
                        </button>
                        @if(request()->hasAny(['search', 'type', 'status']))
                            <a href="{{ route('devices.index') }}" class="px-4 py-3 glass-light text-slate-400 text-sm font-medium rounded-xl hover:text-white hover:bg-slate-700/50 transition-all border border-slate-700/50 text-center">
                                Limpiar
                            </a>
                        @endif
                    </form>
                </div>

                @php
                    $currentSort = request('sort');
                    $currentDir = request('direction', 'desc');
                    $baseParams = request()->only('search', 'type', 'status');
                    $sortLink = function($col) use ($currentSort, $currentDir, $baseParams) {
                        $dir = ($currentSort === $col && $currentDir === 'asc') ? 'desc' : 'asc';
                        return route('devices.index', array_merge($baseParams, ['sort' => $col, 'direction' => $dir]));
                    };
                    $sortIcon = function($col) use ($currentSort, $currentDir) {
                        if ($currentSort !== $col) return '';
                        return $currentDir === 'asc' ? '↑' : '↓';
                    };
                    $statusConfig = [
                        'available' => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-400', 'dot' => 'bg-emerald-500', 'label' => 'Disponible', 'border' => 'border-emerald-500/30'],
                        'assigned' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'dot' => 'bg-blue-500', 'label' => 'Asignado', 'border' => 'border-blue-500/30'],
                        'maintenance' => ['bg' => 'bg-amber-500/20', 'text' => 'text-amber-400', 'dot' => 'bg-amber-500', 'label' => 'Mantenimiento', 'border' => 'border-amber-500/30'],
                        'broken' => ['bg' => 'bg-red-500/20', 'text' => 'text-red-400', 'dot' => 'bg-red-500', 'label' => 'Averiado', 'border' => 'border-red-500/30'],
                    ];
                @endphp

                {{-- Table View --}}
                <div id="table-view">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-800/50 border-b border-slate-700/50">
                                <tr>
                                    <th scope="col" class="py-4 px-6 w-10">
                                        <input type="checkbox" id="select-all-devices" aria-label="Seleccionar todos" class="rounded text-indigo-500 border-slate-600 bg-slate-800 focus:ring-indigo-500 cursor-pointer">
                                    </th>
                                    <th scope="col" class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        <a href="{{ $sortLink('name') }}" class="inline-flex items-center gap-1 hover:text-indigo-400 transition">
                                            Dispositivo <span class="text-indigo-400">{{ $sortIcon('name') }}</span>
                                        </a>
                                    </th>
                                    <th scope="col" class="hidden sm:table-cell py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        <a href="{{ $sortLink('type') }}" class="inline-flex items-center gap-1 hover:text-indigo-400 transition">
                                            Tipo <span class="text-indigo-400">{{ $sortIcon('type') }}</span>
                                        </a>
                                    </th>
                                    <th scope="col" class="hidden md:table-cell py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        <a href="{{ $sortLink('serial_number') }}" class="inline-flex items-center gap-1 hover:text-indigo-400 transition">
                                            Serie <span class="text-indigo-400">{{ $sortIcon('serial_number') }}</span>
                                        </a>
                                    </th>
                                    <th scope="col" class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        <a href="{{ $sortLink('status') }}" class="inline-flex items-center gap-1 hover:text-indigo-400 transition">
                                            Estado <span class="text-indigo-400">{{ $sortIcon('status') }}</span>
                                        </a>
                                    </th>
                                    <th scope="col" class="py-4 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/30">
                                @forelse ($devices as $device)
                                    @php $config = $statusConfig[$device->status] ?? ['bg' => 'bg-slate-500/20', 'text' => 'text-slate-400', 'dot' => 'bg-slate-500', 'label' => ucfirst($device->status), 'border' => 'border-slate-500/30']; @endphp
                                    <tr class="hover:bg-slate-800/30 transition-colors group">
                                        <td class="py-4 px-6">
                                            <input type="checkbox" class="device-checkbox rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500 cursor-pointer" value="{{ $device->id }}" aria-label="Seleccionar {{ $device->name }}">
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-10 w-10 bg-slate-800/50 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-indigo-500/20 group-hover:text-indigo-400 transition-all">
                                                    @if($device->type == 'computer')
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    @elseif($device->type == 'printer')
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                    @else
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <a href="{{ route('devices.show', $device) }}" class="text-sm font-semibold text-white hover:text-indigo-400 transition">{{ $device->name }}</a>
                                                    <p class="text-xs text-slate-500">{{ $device->brand }} {{ $device->model }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell py-4 px-4">
                                            <span class="px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-800/50 text-slate-400 border border-slate-700/50">{{ ucfirst($device->type) }}</span>
                                        </td>
                                        <td class="hidden md:table-cell py-4 px-4 text-sm text-slate-400 font-mono bg-slate-800/30 px-2 py-0.5 rounded">{{ $device->serial_number }}</td>
                                        <td class="py-4 px-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
                                                {{ $config['label'] }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('devices.show', $device) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-xl transition" aria-label="Ver">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                                <a href="{{ route('devices.edit', $device) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-xl transition" aria-label="Editar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <a href="{{ route('devices.print-qr', $device) }}" target="_blank" class="p-2 text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/10 rounded-xl transition" aria-label="Imprimir QR">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8v4H6v-4H4v-4h2v-1m6-8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-16 text-center">
                                            <div class="text-slate-400">
                                                <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                                <p class="text-sm font-medium">No se encontraron dispositivos</p>
                                                <p class="text-xs mt-1 text-slate-500">Intenta con otros filtros o agrega un nuevo activo.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Card View --}}
                <div id="grid-view" class="hidden p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @forelse ($devices as $device)
                            @php $cfg = $statusConfig[$device->status] ?? ['bg' => 'bg-slate-500/20', 'text' => 'text-slate-400', 'dot' => 'bg-slate-500', 'label' => ucfirst($device->status), 'border' => 'border-slate-500/30']; @endphp
                            <div class="glass-light rounded-2xl p-4 hover:border-indigo-500/50 transition-all group relative border border-slate-700/50 card-hover">
                                <div class="absolute top-3 right-3">
                                    <input type="checkbox" class="device-checkbox rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500 cursor-pointer" value="{{ $device->id }}">
                                </div>
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="h-12 w-12 bg-slate-800/50 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-indigo-500/20 group-hover:text-indigo-400 transition-all">
                                        @if($device->type == 'computer')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        @elseif($device->type == 'printer')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1 pr-6">
                                        <a href="{{ route('devices.show', $device) }}" class="text-sm font-semibold text-white hover:text-indigo-400 transition truncate block">{{ $device->name }}</a>
                                        <p class="text-xs text-slate-500 truncate">{{ $device->brand }} {{ $device->model }}</p>
                                    </div>
                                </div>
                                <div class="text-xs text-slate-400 font-mono bg-slate-800/50 px-2 py-1 rounded-lg mb-3 truncate border border-slate-700/50">{{ $device->serial_number }}</div>
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border {{ $cfg['bg'] }} {{ $cfg['text'] }} {{ $cfg['border'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                        {{ $cfg['label'] }}
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('devices.show', $device) }}" class="text-slate-400 hover:text-indigo-400 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                                        <a href="{{ route('devices.edit', $device) }}" class="text-slate-400 hover:text-indigo-400 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-slate-400">
                                <p class="text-sm font-medium">No se encontraron dispositivos</p>
                            </div>
                        @endforelse
                    </div>
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

    @vite('resources/js/device-index.js')
</x-app-layout>
