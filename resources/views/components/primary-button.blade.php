<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-cyan-500 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:from-indigo-400 hover:to-cyan-400 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 shadow-lg shadow-cyan-500/30 transition-all hover:scale-105']) }}>
    {{ $slot }}
</button>
