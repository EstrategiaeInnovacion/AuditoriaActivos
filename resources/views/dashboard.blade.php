<x-app-layout title="Panel de Control">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showQrScanner: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                <!-- Total Assets -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center transition-transform hover:-translate-y-1 duration-300">
                    <div class="p-3 rounded-full bg-slate-100 text-slate-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $totalDevices }}</p>
                    </div>
                </div>

                <!-- Available -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center transition-transform hover:-translate-y-1 duration-300 border-l-4 border-l-emerald-500">
                    <div class="p-3 rounded-full bg-emerald-50 text-emerald-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Disponibles</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $availableDevices }}</p>
                    </div>
                </div>

                <!-- Assigned -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center transition-transform hover:-translate-y-1 duration-300 border-l-4 border-l-blue-500">
                    <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Asignados</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $assignedDevices }}</p>
                    </div>
                </div>

                <!-- Maintenance -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center transition-transform hover:-translate-y-1 duration-300 border-l-4 border-l-amber-500">
                    <div class="p-3 rounded-full bg-amber-50 text-amber-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Mantenimiento</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $maintenanceDevices }}</p>
                    </div>
                </div>

                 <!-- Broken -->
                 <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center transition-transform hover:-translate-y-1 duration-300 border-l-4 border-l-red-500">
                    <div class="p-3 rounded-full bg-red-50 text-red-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Averiados</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $brokenDevices }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Recent Activity (2/3) -->
                <div class="lg:col-span-2">
                    <livewire:recent-activity />
                </div>

                <!-- Right Column: Charts & Quick Actions (1/3) -->
                <div class="space-y-8">
                    <!-- Chart -->
                    <livewire:dashboard-chart />

                    <!-- Quick Actions -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Acciones Rápidas</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('devices.create') }}" class="flex flex-col items-center justify-center p-4 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors text-slate-700">
                                <svg class="w-6 h-6 mb-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="text-sm font-medium">Nuevo Activo</span>
                            </a>
                            <button @click="showQrScanner = true" class="flex flex-col items-center justify-center p-4 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors text-slate-700">
                                <svg class="w-6 h-6 mb-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-9v2m-3-2v2m-3-2v2m3 6v2m-3-2v2m-3-2v2m-3-2v2m3-2v2m-3-2v2m6 10v2m-3-2v2m-3-2v2m-3-2v2m-3-2v2m-3-2v2m-3-2v2"></path></svg>
                                <span class="text-sm font-medium">Escanear QR</span>
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
