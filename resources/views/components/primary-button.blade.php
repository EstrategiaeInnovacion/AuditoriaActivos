<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-cyan-500 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider hover:from-indigo-400 hover:to-cyan-400 focus:from-indigo-600 focus:to-cyan-600 active:from-indigo-700 active:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-900 shadow-lg shadow-cyan-500/25 transition-all hover:scale-[1.02] active:scale-[0.98]']) }}>
    {{ $slot }}
</button>
