<x-app-layout title="Panel de Control">
    <x-slot name="header">
        <h2 class="font-bold text-xl text-white leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ showQrScanner: false, loading: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            {{-- ===================== HEADER ACTIONS ===================== --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">Panel de Control</h1>
                    <p class="text-slate-400 text-sm mt-1">Resumen general del inventario tecnológico institucional.</p>
                </div>
                <div class="flex gap-3">
                    <button @click="loading = true; $wire.refresh().then(() => loading = false)" :disabled="loading" class="flex items-center justify-center gap-2 rounded-xl h-11 px-4 glass-light text-slate-300 text-sm font-semibold hover:text-white hover:bg-slate-700/50 transition-all border border-slate-700/50 group disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg :class="{'animate-spin': loading}" class="w-5 h-5 text-slate-400 group-hover:text-white transition-colors" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.001 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span x-show="!loading">Actualizar</span>
                        <span x-show="loading" class="hidden">Cargando...</span>
                    </button>
                    @auth
                    <button @click="showQrScanner = true" class="flex items-center justify-center gap-2 rounded-xl h-11 px-5 glass-light text-slate-300 text-sm font-semibold hover:text-white hover:bg-slate-700/50 transition-all border border-slate-700/50 group">
                        <svg class="w-5 h-5 text-emerald-400 group-hover:text-emerald-300 transition-colors" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-2v-4H8v4H6v-4H4v-4h2v-1h6v-1h2v1m6-8a4 4 0 100 8 4 4 0 000-8z"/>
                        </svg>
                        <span>Escanear QR</span>
                    </button>
                    @endauth
                    <a href="{{ route('devices.create') }}" class="flex items-center justify-center gap-2 rounded-xl h-11 px-6 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-bold hover:from-indigo-500 hover:to-purple-500 transition-all shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50">
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Nuevo Activo</span>
                    </a>
                </div>
            </div>

            {{-- ===================== ALERTS SECTION ===================== --}}
            @if($warrantyExpiring->count() || $overdueLoans->count())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if($warrantyExpiring->count())
                <div class="glass-light rounded-2xl p-6 card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-amber-500/20">
                                <svg class="w-5 h-5 text-amber-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Garantías por Vencer</h3>
                        </div>
                        <span class="bg-amber-500/20 text-amber-400 px-3 py-1 rounded-full text-xs font-bold">{{ $warrantyExpiring->count() }} Próximos</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($warrantyExpiring as $device)
                        <a href="{{ route('devices.show', $device) }}" class="flex items-center gap-4 p-4 rounded-xl bg-slate-800/50 border border-slate-700/50 hover:border-amber-500/50 hover:bg-slate-800 transition-all group">
                            <div class="size-12 rounded-xl bg-slate-700/50 flex items-center justify-center text-slate-400 group-hover:text-indigo-400 transition-colors">
                                <svg class="w-6 h-6" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-white truncate">{{ $device->name }}</p>
                                <p class="text-xs text-slate-400">{{ $device->brand }} {{ $device->model }} · Vence: {{ $device->warranty_expiration->format('d/m/Y') }}</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-500" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($overdueLoans->count())
                <div class="glass-light rounded-2xl p-6 card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-red-500/20">
                                <svg class="w-5 h-5 text-red-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Préstamos Atrasados</h3>
                        </div>
                        <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-xs font-bold">{{ $overdueLoans->count() }} Urgentes</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($overdueLoans as $loan)
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-red-500/10 border border-red-500/20">
                            <div class="size-12 rounded-xl bg-slate-700/50 flex items-center justify-center text-slate-400">
                                <svg class="w-6 h-6" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-white truncate">{{ $loan->device->name ?? 'Dispositivo' }}</p>
                                <p class="text-xs text-red-400 font-semibold">{{ $loan->user ? $loan->user->name : $loan->assigned_to }} · {{ $loan->assigned_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- ===================== METRICS ROW ===================== --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="metric-card p-5 rounded-2xl card-hover">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-slate-400 text-sm font-semibold">Total Activos</span>
                        <div class="p-2 rounded-xl bg-indigo-500/20">
                            <svg class="w-5 h-5 text-indigo-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <p x-show="!loading" class="text-3xl font-bold text-white">{{ $totalDevices ?? 0 }}</p>
                        <div x-show="loading" class="animate-pulse">
                            <div class="h-8 bg-slate-700 rounded w-16"></div>
                        </div>
                        <p x-show="!loading" class="text-xs text-slate-500 mt-1">En inventario</p>
                        <div x-show="loading" class="animate-pulse mt-1">
                            <div class="h-3 bg-slate-700 rounded w-20"></div>
                        </div>
                    </div>
                </div>

                <div class="metric-card p-5 rounded-2xl card-hover border-l-4 border-l-emerald-500">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-slate-400 text-sm font-semibold">Disponibles</span>
                        <div class="p-2 rounded-xl bg-emerald-500/20">
                            <svg class="w-5 h-5 text-emerald-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <div>
                        <p x-show="!loading" class="text-3xl font-bold text-white">{{ $availableDevices ?? 0 }}</p>
                        <div x-show="loading" class="animate-pulse">
                            <div class="h-8 bg-slate-700 rounded w-16"></div>
                        </div>
                        <p x-show="!loading" class="text-xs text-slate-500 mt-1">Listo para asignar</p>
                        <div x-show="loading" class="animate-pulse mt-1">
                            <div class="h-3 bg-slate-700 rounded w-24"></div>
                        </div>
                    </div>
                </div>

                <div class="metric-card p-5 rounded-2xl card-hover border-l-4 border-l-blue-500">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-slate-400 text-sm font-semibold">Asignados</span>
                        <div class="p-2 rounded-xl bg-blue-500/20">
                            <svg class="w-5 h-5 text-blue-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <p x-show="!loading" class="text-3xl font-bold text-white">{{ $assignedDevices ?? 0 }}</p>
                        <div x-show="loading" class="animate-pulse">
                            <div class="h-8 bg-slate-700 rounded w-16"></div>
                        </div>
                        <p x-show="!loading" class="text-xs text-slate-500 mt-1">En uso actualmente</p>
                        <div x-show="loading" class="animate-pulse mt-1">
                            <div class="h-3 bg-slate-700 rounded w-28"></div>
                        </div>
                    </div>
                </div>

                <div class="metric-card p-5 rounded-2xl card-hover border-l-4 border-l-amber-500">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-slate-400 text-sm font-semibold">Mantenimiento</span>
                        <div class="p-2 rounded-xl bg-amber-500/20">
                            <svg class="w-5 h-5 text-amber-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <p x-show="!loading" class="text-3xl font-bold text-white">{{ $maintenanceDevices ?? 0 }}</p>
                        <div x-show="loading" class="animate-pulse">
                            <div class="h-8 bg-slate-700 rounded w-16"></div>
                        </div>
                        <p x-show="!loading" class="text-xs text-slate-500 mt-1">Servicio técnico</p>
                        <div x-show="loading" class="animate-pulse mt-1">
                            <div class="h-3 bg-slate-700 rounded w-24"></div>
                        </div>
                    </div>
                </div>

                <div class="metric-card p-5 rounded-2xl card-hover border-l-4 border-l-red-500">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-slate-400 text-sm font-semibold">Dañados</span>
                        <div class="p-2 rounded-xl bg-red-500/20">
                            <svg class="w-5 h-5 text-red-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <p x-show="!loading" class="text-3xl font-bold text-white">{{ $brokenDevices ?? 0 }}</p>
                        <div x-show="loading" class="animate-pulse">
                            <div class="h-8 bg-slate-700 rounded w-16"></div>
                        </div>
                        <p x-show="!loading" class="text-xs text-slate-500 mt-1">Pendiente</p>
                        <div x-show="loading" class="animate-pulse mt-1">
                            <div class="h-3 bg-slate-700 rounded w-20"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== MAIN CONTENT GRID ===================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6 order-2 lg:order-1">
                    <livewire:recent-activity />
                </div>

                <div class="space-y-6 order-1 lg:order-2">
                    <livewire:dashboard-chart />

                    @if(!empty($monthlyTrend))
                    <div class="glass-light rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-white">Tendencia Mensual</h3>
                                <p class="text-xs text-slate-400">Movimiento en los últimos 6 meses</p>
                            </div>
                            <div class="p-2 rounded-xl bg-purple-500/20">
                                <svg class="w-5 h-5 text-purple-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                        </div>
                        <div class="h-32 flex items-end gap-2 px-2">
                            @php $maxTrend = max(array_values($monthlyTrend)) ?: 1; @endphp
                            @foreach($monthlyTrend as $month => $count)
                            <div class="flex-1 flex flex-col items-center gap-2">
                                <div class="w-full bg-gradient-to-t from-indigo-600 to-purple-600 rounded-t-lg group relative hover:from-indigo-500 hover:to-purple-500 transition-all" style="height: {{ intval(($count / $maxTrend) * 100) }}%">
                                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 text-xs font-bold bg-slate-800 text-white px-2 py-1 rounded transition-opacity whitespace-nowrap">{{ $count }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between mt-3 px-2 text-xs font-medium text-slate-400">
                            @foreach(array_keys($monthlyTrend) as $month)
                            <span class="uppercase">{{ substr($month, -2) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ===================== QR SCANNER MODAL ===================== --}}
            <div x-show="showQrScanner" 
                 x-init="$el.focus()"
                 @showQrScanner.window="setTimeout(() => { window.QrScannerInit && (window.QrScannerInit.initialized = false, window.QrScannerInit.tryStart()); }, 300)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 tabindex="-1" 
                 role="dialog" 
                 aria-modal="true" 
                 aria-labelledby="qr-scanner-title"
                 style="display: none;" 
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 @keydown.escape.window="showQrScanner = false">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="showQrScanner = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="relative inline-block align-bottom glass rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                            <button data-focus-first @click="showQrScanner = false" type="button" class="glass rounded-full p-2 text-slate-400 hover:text-white hover:bg-slate-700/50 transition-colors">
                                <span class="sr-only">Cerrar</span>
                                <svg class="h-6 w-6" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4" data-focus-last>
                            <h2 id="qr-scanner-title" class="sr-only">Escáner de Código QR</h2>
                            <livewire:qr-scanner />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @vite('resources/js/chart-loader.js')
    @vite('resources/js/qr-scanner-loader.js')
</x-app-layout>
