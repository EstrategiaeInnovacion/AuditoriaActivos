<x-app-layout title="Panel de Control">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showQrScanner: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ===================== ALERTS SECTION ===================== --}}
            @if($warrantyExpiring->count() || $overdueLoans->count())
            <div class="space-y-4">
                @if($warrantyExpiring->count())
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-amber-800">⚠️ Garantías por vencer (próximos 30 días)</h3>
                            <div class="mt-2 space-y-1">
                                @foreach($warrantyExpiring as $device)
                                    <a href="{{ route('devices.show', $device) }}" class="block text-sm text-amber-700 hover:text-amber-900 hover:underline">
                                        <strong>{{ $device->name }}</strong> — {{ $device->brand }} {{ $device->model }} · Vence: {{ $device->warranty_expiration }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($overdueLoans->count())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-xl shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-red-800">🔴 Préstamos vencidos (+30 días sin devolución)</h3>
                            <div class="mt-2 space-y-1">
                                @foreach($overdueLoans as $loan)
                                    <a href="{{ route('devices.show', $loan->device) }}" class="block text-sm text-red-700 hover:text-red-900 hover:underline">
                                        <strong>{{ $loan->device->name }}</strong> → {{ $loan->user ? $loan->user->name : $loan->assigned_to }} · Asignado: {{ $loan->assigned_at->format('d/m/Y') }} ({{ $loan->assigned_at->diffForHumans() }})
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- ===================== METRICS GRID ===================== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                <!-- Total Assets -->
                <div class="bg-white p-6 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center transition-all hover:-translate-y-1 hover:shadow-2xl duration-300">
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 text-slate-600 mr-4 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Total</p>
                        <p class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $totalDevices }}</p>
                    </div>
                </div>

                <!-- Available -->
                <div class="bg-white p-6 rounded-2xl shadow-xl shadow-emerald-100/50 border border-slate-100 flex items-center transition-all hover:-translate-y-1 hover:shadow-2xl duration-300 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-emerald-400 to-emerald-600"></div>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100 text-emerald-600 mr-4 shadow-inner group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Disponibles</p>
                        <p class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $availableDevices }}</p>
                    </div>
                </div>

                <!-- Assigned -->
                <div class="bg-white p-6 rounded-2xl shadow-xl shadow-blue-100/50 border border-slate-100 flex items-center transition-all hover:-translate-y-1 hover:shadow-2xl duration-300 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-blue-400 to-blue-600"></div>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600 mr-4 shadow-inner group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Asignados</p>
                        <p class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $assignedDevices }}</p>
                    </div>
                </div>

                <!-- Maintenance -->
                <div class="bg-white p-6 rounded-2xl shadow-xl shadow-amber-100/50 border border-slate-100 flex items-center transition-all hover:-translate-y-1 hover:shadow-2xl duration-300 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-amber-400 to-amber-600"></div>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100 text-amber-600 mr-4 shadow-inner group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Mantenimiento</p>
                        <p class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $maintenanceDevices }}</p>
                    </div>
                </div>

                 <!-- Broken -->
                 <div class="bg-white p-6 rounded-2xl shadow-xl shadow-red-100/50 border border-slate-100 flex items-center transition-all hover:-translate-y-1 hover:shadow-2xl duration-300 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-red-400 to-red-600"></div>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-red-50 to-red-100 text-red-600 mr-4 shadow-inner group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Averiados</p>
                        <p class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $brokenDevices }}</p>
                    </div>
                </div>
            </div>

            {{-- ===================== MAIN CONTENT GRID ===================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Recent Activity (2/3) -->
                <div class="lg:col-span-2">
                    <livewire:recent-activity />
                </div>

                <!-- Right Column: Charts & Quick Actions (1/3) -->
                <div class="space-y-8">
                    <!-- Chart -->
                    <livewire:dashboard-chart />

                    {{-- Monthly Trend --}}
                    @if($monthlyTrend->count())
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">📈 Tendencia Mensual</h3>
                        <div class="space-y-2">
                            @php $maxTrend = $monthlyTrend->max() ?: 1; @endphp
                            @foreach($monthlyTrend as $month => $count)
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-slate-500 w-16 font-mono font-medium">{{ $month }}</span>
                                    <div class="flex-1 bg-slate-100 rounded-full h-3 overflow-hidden shadow-inner">
                                        <div class="h-full bg-gradient-to-r from-indigo-500 to-cyan-500 rounded-full transition-all duration-500 drop-shadow-md" style="width: {{ ($count / $maxTrend) * 100 }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-700 w-6 text-right">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="bg-white p-6 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4 tracking-tight">Acciones Rápidas</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('devices.create') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50 hover:bg-white border border-transparent hover:border-cyan-200 hover:shadow-lg hover:shadow-cyan-100 transition-all text-slate-700">
                                <div class="p-3 bg-indigo-50 text-indigo-500 rounded-full group-hover:bg-cyan-50 group-hover:text-cyan-600 transition-colors mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <span class="text-sm font-semibold">Nuevo Activo</span>
                            </a>
                            <button @click="showQrScanner = true" class="group flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50 hover:bg-white border border-transparent hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-100 transition-all text-slate-700">
                                <div class="p-3 bg-cyan-50 text-cyan-500 rounded-full group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-9v2m-3-2v2m-3-2v2m3 6v2m-3-2v2m-3-2v2m-3-2v2m3-2v2m-3-2v2m6 10v2m-3-2v2m-3-2v2m-3-2v2m-3-2v2m-3-2v2m-3-2v2"></path></svg>
                                </div>
                                <span class="text-sm font-semibold">Escanear QR</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Scanner Modal -->
            <div x-show="showQrScanner" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-slate-900/75 transition-opacity" aria-hidden="true" @click="showQrScanner = false"></div>

                    <!-- Center modal -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                         <!-- Close button -->
                         <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                            <button @click="showQrScanner = false" type="button" class="bg-white rounded-full text-slate-400 hover:text-slate-500 focus:outline-none">
                                <span class="sr-only">Cerrar</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
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
</x-app-layout>
