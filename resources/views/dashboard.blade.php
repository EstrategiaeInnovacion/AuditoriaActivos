<x-app-layout title="Panel de Control">
    <x-slot name="header">
        <h2 class="font-bold text-xl text-white leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-8" x-data="{ showQrScanner: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ===================== HEADER ACTIONS ===================== --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Panel de Control</h1>
                    <p class="text-slate-500 text-sm mt-1">Resumen general del inventario tecnológico institucional.</p>
                </div>
                <div class="flex gap-3">
                    <button @click="showQrScanner = true" class="flex items-center justify-center gap-2 rounded-lg h-10 px-4 bg-white border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition-all shadow-sm">
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-9v2m-3-2v2m-3-2v2m3 6v2m-3-2v2m-3-2v2m-3-2v2m3-2v2m-3-2v2"></path></svg>
                        <span>Escanear QR</span>
                    </button>
                    <a href="{{ route('devices.create') }}" class="flex items-center justify-center gap-2 rounded-lg h-10 px-5 bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition-all shadow-md shadow-indigo-600/20">
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Nuevo Activo</span>
                    </a>
                </div>
            </div>

            {{-- ===================== ALERTS SECTION ===================== --}}
            @if($warrantyExpiring->count() || $overdueLoans->count())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if($warrantyExpiring->count())
                <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-amber-500">verified_user</span>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Garantías por Vencer</h3>
                        </div>
                        <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full text-xs font-bold">{{ $warrantyExpiring->count() }} Próximos</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($warrantyExpiring as $device)
                        <a href="{{ route('devices.show', $device) }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 border border-slate-100 hover:border-amber-200 hover:bg-amber-50/30 transition-all group">
                            <div class="size-10 rounded bg-white flex items-center justify-center text-slate-400 group-hover:text-indigo-600 shadow-sm">
                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $device->name }}</p>
                                <p class="text-xs text-slate-500">{{ $device->brand }} {{ $device->model }} · Vence: {{ $device->warranty_expiration->format('d/m/Y') }}</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-300" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($overdueLoans->count())
                <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-red-500">calendar_clock</span>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Préstamos Atrasados</h3>
                        </div>
                        <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full text-xs font-bold">{{ $overdueLoans->count() }} Urgentes</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($overdueLoans as $loan)
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-red-50/30 border border-red-100 group">
                            <div class="size-10 rounded bg-white flex items-center justify-center text-slate-400 shadow-sm">
                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $loan->device->name ?? 'Dispositivo' }}</p>
                                <p class="text-xs text-red-600 font-semibold">{{ $loan->user ? $loan->user->name : $loan->assigned_to }} · {{ $loan->assigned_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- ===================== METRICS ROW ===================== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-500 text-sm font-semibold">Total Activos</span>
                        <span class="material-symbols-outlined text-slate-400 bg-slate-50 p-2 rounded-lg">devices</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-slate-900">{{ $totalDevices }}</p>
                        <p class="text-xs text-slate-400 mt-1">En inventario</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border-l-4 border-l-emerald-500 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-500 text-sm font-semibold">Disponibles</span>
                        <span class="material-symbols-outlined text-emerald-500 bg-emerald-50 p-2 rounded-lg">check_circle</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-slate-900">{{ $availableDevices }}</p>
                        <p class="text-xs text-slate-400 mt-1">Listo para asignar</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border-l-4 border-l-indigo-500 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-500 text-sm font-semibold">Asignados</span>
                        <span class="material-symbols-outlined text-indigo-500 bg-indigo-50 p-2 rounded-lg">person_search</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-slate-900">{{ $assignedDevices }}</p>
                        <p class="text-xs text-slate-400 mt-1">En uso actualmente</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border-l-4 border-l-amber-500 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-500 text-sm font-semibold">Mantenimiento</span>
                        <span class="material-symbols-outlined text-amber-500 bg-amber-50 p-2 rounded-lg">build</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-slate-900">{{ $maintenanceDevices }}</p>
                        <p class="text-xs text-slate-400 mt-1">Servicio técnico</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border-l-4 border-l-red-500 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-500 text-sm font-semibold">Dañados</span>
                        <span class="material-symbols-outlined text-red-500 bg-red-50 p-2 rounded-lg">report</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-slate-900">{{ $brokenDevices }}</p>
                        <p class="text-xs text-slate-400 mt-1">Pendiente</p>
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

                    @if($monthlyTrend->count())
                    <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Tendencia Mensual</h3>
                                <p class="text-xs text-slate-500">Movimiento en los últimos 6 meses</p>
                            </div>
                        </div>
                        <div class="h-32 flex items-end gap-2 px-2">
                            @php $maxTrend = $monthlyTrend->max() ?: 1; @endphp
                            @foreach($monthlyTrend as $month => $count)
                            <div class="flex-1 flex flex-col items-center gap-2">
                                <div class="w-full bg-indigo-100 rounded-t-lg group relative hover:bg-indigo-200 transition-all" style="height: {{ intval(($count / $maxTrend) * 100) }}%">
                                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 text-xs font-bold bg-slate-800 text-white px-2 py-1 rounded transition-opacity">{{ $count }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between mt-3 px-2 text-xs font-medium text-slate-400">
                            @foreach($monthlyTrend->keys() as $month)
                            <span class="uppercase">{{ substr($month, -2) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ===================== QR SCANNER MODAL ===================== --}}
            <div x-show="showQrScanner" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                @keydown.escape.window="showQrScanner = false">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-900/75 transition-opacity" aria-hidden="true" @click="showQrScanner = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                            <button @click="showQrScanner = false" type="button" class="bg-white rounded-full text-slate-400 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span class="sr-only">Cerrar</span>
                                <svg class="h-6 w-6" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
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
