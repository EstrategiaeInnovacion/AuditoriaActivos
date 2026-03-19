<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-slate-700/60 backdrop-blur-sm border border-slate-600/50 rounded-lg font-semibold text-xs text-slate-200 uppercase tracking-wider hover:bg-slate-600/80 hover:border-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:opacity-25 transition-all duration-200']) }}>
    {{ $slot }}
</button>
