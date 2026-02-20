<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-100">
        <h3 class="text-lg font-semibold text-slate-800">Actividad Reciente</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs uppercase font-medium text-slate-500">
                <tr>
                    <th class="px-6 py-4">Dispositivo</th>
                    <th class="px-6 py-4 hidden sm:table-cell">Usuario</th>
                    <th class="px-6 py-4">Acción</th>
                    <th class="px-6 py-4 hidden sm:table-cell">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($assignments as $assignment)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-900">{{ $assignment->device->name }}</span>
                                <span class="text-xs text-slate-400">{{ $assignment->device->serial_number }}</span>
                                <!-- Mobile only info -->
                                <div class="sm:hidden mt-1 text-xs text-slate-500">
                                    {{ $assignment->user ? $assignment->user->name : $assignment->assigned_to }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell font-medium text-slate-900">
                            {{ $assignment->user ? $assignment->user->name : $assignment->assigned_to }}
                        </td>
                        <td class="px-6 py-4">
                            @if($assignment->returned_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    Devuelto
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Asignado
                                </span>
                            @endif
                             <!-- Mobile only date -->
                             <div class="sm:hidden mt-1 text-xs text-slate-400">
                                {{ $assignment->assigned_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell text-slate-500">
                            {{ $assignment->assigned_at->diffForHumans() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-slate-400">
                            No hay actividad reciente.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
