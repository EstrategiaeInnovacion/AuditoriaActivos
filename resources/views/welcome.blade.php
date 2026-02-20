<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📦</text></svg>">
        <title>Control de Activos</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-slate-50 text-slate-900">
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-indigo-500 selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3 relative z-10">
                   <div class="flex items-center gap-2 lg:col-start-2 lg:justify-center">
                        <x-application-logo class="h-10 w-10 fill-current text-indigo-600" />
                        <span class="text-xl font-bold text-slate-800 tracking-tight">Control de Activos</span>
                   </div>
                    <nav class="-mx-3 flex flex-1 justify-end">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="rounded-full bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all"
                            >
                                Iniciar Sesión
                            </a>
                        @endauth
                    </nav>
                </header>

                <main class="mt-16 sm:mt-24 relative z-10">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-6xl">
                            Gestión Inteligente de <span class="text-indigo-600">Activos TI</span>
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-slate-600 max-w-2xl mx-auto">
                            Controla, asigna y monitorea el ciclo de vida de todo tu equipo tecnológico. Desde la asignación hasta el mantenimiento, todo en un solo lugar.
                        </p>
                        <div class="mt-10 flex items-center justify-center gap-x-6">
                             @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Ir al Panel</a>
                             @else
                                <a href="{{ route('login') }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Acceder al Sistema</a>
                             @endauth
                        </div>
                    </div>

                    <div class="mt-20 flow-root sm:mt-24">
                        <div class="-m-2 rounded-xl bg-slate-900/5 p-2 ring-1 ring-inset ring-slate-900/10 lg:-m-4 lg:rounded-2xl lg:p-4">
                            <img src="https://tailwindui.com/img/component-images/project-app-screenshot.png" alt="App screenshot" width="2432" height="1442" class="rounded-md shadow-2xl ring-1 ring-slate-900/10 opacity-50 grayscale blur-[2px]">
                             <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-slate-500 text-sm bg-white/80 px-4 py-2 rounded-full backdrop-blur-sm shadow-sm border border-slate-200">
                                    Vista previa del sistema seguro
                                </span>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-slate-500">
                    &copy; {{ date('Y') }} Control de Activos. Sistema Interno.
                </footer>
            </div>
        </div>
    </body>
</html>
