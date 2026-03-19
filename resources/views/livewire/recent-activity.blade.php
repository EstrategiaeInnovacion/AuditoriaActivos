<div class="glass-light rounded-2xl overflow-hidden card-hover">
    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-gradient-to-br from-cyan-500/20 to-blue-500/20">
                <svg class="w-5 h-5 text-cyan-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-white">Actividad Reciente</h3>
        </div>
        <span class="text-xs text-slate-400 bg-slate-800/50 px-3 py-1.5 rounded-full font-medium border border-slate-700/50">{{ $assignments->count() }} registros</span>
    </div>
    <div class="divide-y divide-slate-700/30">
        @forelse($assignments as $assignment)
            <div class="p-4 hover:bg-slate-800/30 transition-colors group">
                <div class="flex items-start gap-4">
                    <div class="relative mt-1">
                        @if($assignment->returned_at)
                            <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 flex items-center justify-center text-emerald-400 border border-emerald-500/30">
                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        @else
                            <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center text-blue-400 border border-blue-500/30">
                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-white truncate group-hover:text-indigo-400 transition-colors">
                                    {{ $assignment->device->name }}
                                </p>
                                <p class="text-xs text-slate-400 truncate">
                                    {{ $assignment->user ? $assignment->user->name : $assignment->assigned_to }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                @if($assignment->returned_at)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        <svg class="w-3 h-3 mr-1.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Devuelto
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                        <svg class="w-3 h-3 mr-1.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Asignado
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-2 text-xs text-slate-500">
                            <span class="font-mono bg-slate-800/50 px-2 py-0.5 rounded">{{ $assignment->device->serial_number }}</span>
                            <span>•</span>
                            <span>{{ $assignment->assigned_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-800/50 rounded-2xl mb-4 border border-slate-700/50">
                    <svg class="w-8 h-8 text-slate-500" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <p class="text-slate-400 font-medium">No hay actividad reciente</p>
                <p class="text-xs text-slate-500 mt-1">Los movimientos de activos aparecerán aquí</p>
            </div>
        @endforelse
    </div>
</div>
