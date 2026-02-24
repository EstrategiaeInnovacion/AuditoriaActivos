<x-app-layout title="Listado de Activos">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Activos') }}
            </h2>
            <a href="{{ route('devices.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-cyan-500 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:from-indigo-400 hover:to-cyan-400 shadow-lg shadow-cyan-500/30 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-all hover:scale-105">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Agregar Nuevo
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-emerald-700">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <!-- Search & Filters -->
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <form method="GET" action="{{ route('devices.index') }}" class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, serie o marca..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                        </div>
                        <select name="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">Todos los tipos</option>
                            <option value="computer" {{ request('type') == 'computer' ? 'selected' : '' }}>Computadora</option>
                            <option value="printer" {{ request('type') == 'printer' ? 'selected' : '' }}>Impresora</option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Otro</option>
                        </select>
                        <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">Todos los estados</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Asignado</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="broken" {{ request('status') == 'broken' ? 'selected' : '' }}>Averiado</option>
                        </select>
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-slate-800 to-slate-700 text-white text-sm font-semibold rounded-md hover:from-slate-700 hover:to-slate-600 shadow-md transition-all">
                            Buscar
                        </button>
                        @if(request()->hasAny(['search', 'type', 'status']))
                            <a href="{{ route('devices.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 text-sm font-medium rounded-md hover:bg-slate-300 transition text-center">
                                Limpiar
                            </a>
                        @endif
                    </form>
                    <div class="flex gap-2 mt-3 sm:mt-0">
                        <a href="{{ route('devices.export.excel', request()->only('search', 'type', 'status')) }}" class="inline-flex items-center px-3 py-2 bg-emerald-600 text-white text-xs font-medium rounded-md hover:bg-emerald-700 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Excel
                        </a>
                        <a href="{{ route('devices.export.pdf', request()->only('search', 'type', 'status')) }}" class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            PDF
                        </a>
                        <button type="button" id="print-selected-qrs-btn" class="inline-flex items-center px-3 py-2 bg-slate-800 text-white text-xs font-medium rounded-md hover:bg-slate-700 transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Imprimir QR Seleccionados
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/80 backdrop-blur-sm border-b border-slate-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left">
                                    <input type="checkbox" id="select-all-devices" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-indigo-900 tracking-wider">Dispositivo</th>
                                <th scope="col" class="hidden sm:table-cell px-6 py-4 text-left text-xs font-bold text-indigo-900 tracking-wider">Tipo</th>
                                <th scope="col" class="hidden md:table-cell px-6 py-4 text-left text-xs font-bold text-indigo-900 tracking-wider">Serie</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-indigo-900 tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-indigo-900 tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse ($devices as $device)
                                <tr class="hover:bg-slate-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="device-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" value="{{ $device->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500">
                                                @if($device->type == 'computer')
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                @elseif($device->type == 'printer')
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-slate-900 leading-tight">
                                                    <a href="{{ route('devices.show', $device) }}" class="hover:text-indigo-600 transition">{{ $device->name }}</a>
                                                </div>
                                                <div class="text-xs text-slate-500">{{ $device->brand }} {{ $device->model }}</div>
                                                <!-- Mobile only meta -->
                                                <div class="sm:hidden text-xs text-slate-400 mt-0.5">
                                                    {{ $device->serial_number }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full bg-slate-100 text-slate-700">
                                            {{ ucfirst($device->type) }}
                                        </span>
                                    </td>
                                    <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-mono">
                                        {{ $device->serial_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusConfig = [
                                                'available' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Disponible'],
                                                'assigned' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Asignado'],
                                                'maintenance' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Mantenimiento'],
                                                'broken' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Averiado'],
                                            ];
                                            $config = $statusConfig[$device->status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => ucfirst($device->status)];
                                        @endphp
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                            {{ $config['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-3">
                                            <a href="{{ route('devices.show', $device) }}" class="text-slate-400 hover:text-indigo-600 transition" title="Ver Detalles">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <a href="{{ route('devices.edit', $device) }}" class="text-slate-400 hover:text-amber-600 transition" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <a href="{{ route('devices.print-qr', $device) }}" target="_blank" class="text-slate-400 hover:text-slate-800 transition" title="Imprimir QR">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-9a2 2 0 00-2-2h-6.19a2 2 0 00-1.79 1.11l-3.82 7.64a2 2 0 001.78 2.89H11m5-9v.01M5 11v.01"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-slate-400">
                                            <svg class="mx-auto h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p class="text-sm font-medium">No se encontraron dispositivos</p>
                                            <p class="text-xs mt-1">Intenta con otros filtros o agrega un nuevo activo.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="bg-white px-4 py-3 border-t border-slate-100 sm:px-6">
                    {{ $devices->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all-devices');
            const deviceCheckboxes = document.querySelectorAll('.device-checkbox');
            const printBtn = document.getElementById('print-selected-qrs-btn');

            function updatePrintButtonState() {
                const checkedCount = document.querySelectorAll('.device-checkbox:checked').length;
                printBtn.disabled = checkedCount === 0;
            }

            selectAllCheckbox.addEventListener('change', function() {
                deviceCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updatePrintButtonState();
            });

            deviceCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (!this.checked) {
                        selectAllCheckbox.checked = false;
                    } else {
                        const allChecked = Array.from(deviceCheckboxes).every(c => c.checked);
                        selectAllCheckbox.checked = allChecked;
                    }
                    updatePrintButtonState();
                });
            });

            printBtn.addEventListener('click', function() {
                const checkedIds = Array.from(document.querySelectorAll('.device-checkbox:checked'))
                    .map(checkbox => checkbox.value);
                
                if (checkedIds.length > 0) {
                    const idsParam = checkedIds.join(',');
                    const url = `{{ route('devices.print-multiple-qrs') }}?ids=${idsParam}`;
                    window.open(url, '_blank');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
