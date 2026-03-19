<nav class="-mx-3 flex flex-1 justify-end">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="rounded-md px-3 py-2 text-slate-300 ring-1 ring-transparent transition hover:text-white hover:ring-indigo-500/50 focus:outline-none focus-visible:ring-indigo-500 dark:text-white dark:hover:text-white"
        >
            Dashboard
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="rounded-md px-3 py-2 text-slate-300 ring-1 ring-transparent transition hover:text-white hover:ring-indigo-500/50 focus:outline-none focus-visible:ring-indigo-500 dark:text-white dark:hover:text-white"
        >
            Iniciar Sesión
        </a>

        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="rounded-md px-3 py-2 text-slate-300 ring-1 ring-transparent transition hover:text-white hover:ring-indigo-500/50 focus:outline-none focus-visible:ring-indigo-500 dark:text-white dark:hover:text-white"
            >
                Registrarse
            </a>
        @endif
    @endauth
</nav>
