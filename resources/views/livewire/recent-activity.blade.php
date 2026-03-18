<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-indigo-50/50 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl">
                <svg class="w-5 h-5 text-indigo-600" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Actividad Reciente</h3>
        </div>
        <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full font-medium">{{ $assignments->count() }} registros</span>
    </div>
    <div class="divide-y divide-slate-100">
        @forelse($assignments as $assignment)
            <div class="p-4 hover:bg-slate-50 transition-colors group">
                <div class="flex items-start gap-4">
                    <div class="relative mt-1">
                        @if($assignment->returned_at)
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center text-emerald-600 shadow-inner">
                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        @else
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-600 shadow-inner">
                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900 truncate group-hover:text-indigo-600 transition-colors">
                                    {{ $assignment->device->name }}
                                </p>
                                <p class="text-xs text-slate-500 truncate">
                                    {{ $assignment->user ? $assignment->user->name : $assignment->assigned_to }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                @if($assignment->returned_at)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <svg class="w-3 h-3 mr-1" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Devuelto
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        <svg class="w-3 h-3 mr-1" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Asignado
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-2 text-xs text-slate-400">
                            <span class="font-mono">{{ $assignment->device->serial_number }}</span>
                            <span>•</span>
                            <span>{{ $assignment->assigned_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-100 rounded-2xl mb-4">
                    <svg class="w-8 h-8 text-slate-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <p class="text-slate-500 font-medium">No hay actividad reciente</p>
                <p class="text-xs text-slate-400 mt-1">Los movimientos de activos aparecerán aquí</p>
            </div>
        @endforelse
    </div>
</div>
