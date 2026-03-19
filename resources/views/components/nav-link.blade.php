@props(['active'])

@php
$classes = ($active ?? false)
            ? 'px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 text-indigo-400 bg-indigo-500/10 border border-indigo-500/20'
            : 'px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 text-slate-300 hover:text-white hover:bg-slate-800/50 transition-all';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
