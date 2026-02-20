<x-app-layout title="Detalles del Activo">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Dispositivo') }}: {{ $device->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2 border-b pb-1">Información del Dispositivo</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p><strong>Nombre:</strong> {{ $device->name }}</p>
                                <p><strong>Marca:</strong> {{ $device->brand }}</p>
                                <p><strong>Modelo:</strong> {{ $device->model }}</p>
                                <p><strong>No. Serie:</strong> {{ $device->serial_number }}</p>
                                <p><strong>Tipo:</strong> {{ ucfirst($device->type) }}</p>
                                <p><strong>Estado:</strong> 
                                    @php 
                                        $statuses = ['available' => 'Disponible', 'assigned' => 'Asignado', 'maintenance' => 'Mantenimiento', 'broken' => 'Averiado'];
                                    @endphp
                                    {{ $statuses[$device->status] ?? $device->status }}
                                </p>
                                <p><strong>Fecha Compra:</strong> {{ $device->purchase_date ? $device->purchase_date : 'N/A' }}</p>
                                <p><strong>Vencimiento Garantía:</strong> {{ $device->warranty_expiration ? $device->warranty_expiration : 'N/A' }}</p>
                            </div>
                            
                            @if($device->notes)
                            <div class="mt-4">
                                <h4 class="font-medium text-gray-900">Notas</h4>
                                <p class="text-gray-600 bg-gray-50 p-2 rounded">{{ $device->notes }}</p>
                            </div>
                            @endif
                        </div>

                        <div>
                            @if($device->credential)
                            <h3 class="text-lg font-medium text-gray-900 mb-2 border-b pb-1">Credenciales</h3>
                            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                                <p><strong>Usuario Equipo:</strong> {{ $device->credential->username }}</p>
                                <p><strong>Password Equipo:</strong> <span class="font-mono text-indigo-700 font-bold bg-white px-1 rounded">{{ $device->credential->password }}</span></p>
                                <hr class="my-2 border-indigo-200">
                                <p><strong>Correo:</strong> {{ $device->credential->email }}</p>
                                <p><strong>Password Correo:</strong> <span class="font-mono text-indigo-700 font-bold bg-white px-1 rounded">{{ $device->credential->email_password }}</span></p>
                            </div>
                            @endif

                            <div class="mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Acciones</h3>
                                <div class="flex flex-wrap gap-2">
                                     <a href="{{ route('devices.edit', $device) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded shadow">
                                        Editar Dispositivo
                                    </a>
                                    <a href="{{ route('devices.print-qr', $device) }}" target="_blank" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">
                                        Imprimir Código QR
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-2 border-b pb-1">Historial de Asignaciones</h3>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Asignado A</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Asignación</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Devolución</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Notas</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($device->assignments as $assignment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $assignment->user ? $assignment->user->name : $assignment->assigned_to }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->assigned_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($assignment->returned_at)
                                                {{ $assignment->returned_at->format('Y-m-d H:i') }}
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    ACTUAL
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $assignment->notes }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                            No hay historial de asignaciones para este equipo.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
