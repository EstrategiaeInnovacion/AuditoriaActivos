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
    <body class="antialiased font-sans bg-slate-900 text-slate-300">
        <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden selection:bg-cyan-500 selection:text-white">
            <!-- Background Gradients (Glow effects) -->
            <div class="absolute top-0 -left-64 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-64 w-96 h-96 bg-cyan-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-64 left-1/2 -translate-x-1/2 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-70 animate-blob animation-delay-4000"></div>

            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl z-10">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                   <div class="flex items-center gap-2 lg:col-start-2 lg:justify-center">
                        <x-application-logo class="h-10 w-10 fill-current text-cyan-400 drop-shadow-lg" />
                        <span class="text-2xl font-bold text-white tracking-tight drop-shadow-md">Control de Activos</span>
                   </div>
                    <nav class="-mx-3 flex flex-1 justify-end">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="rounded-full bg-white/10 backdrop-blur-md border border-white/20 px-6 py-2.5 text-sm font-semibold text-white shadow-lg hover:bg-white/20 hover:border-white/30 transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-cyan-500"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="rounded-full bg-gradient-to-r from-indigo-500 to-cyan-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-cyan-500/30 border border-transparent hover:from-indigo-400 hover:to-cyan-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cyan-400 transition-all hover:scale-105"
                            >
                                Iniciar Sesión
                            </a>
                        @endauth
                    </nav>
                </header>

                <main class="mt-16 sm:mt-24 relative z-10 w-full flex flex-col items-center">
                    <div class="text-center w-full max-w-4xl">
                        <h1 class="text-5xl font-extrabold tracking-tight text-white sm:text-7xl drop-shadow-lg">
                            Gestión Inteligente de <span class="bg-gradient-to-r from-cyan-400 to-indigo-400 bg-clip-text text-transparent">Activos TI</span>
                        </h1>
                        <p class="mt-6 text-xl leading-8 text-slate-300 max-w-2xl mx-auto font-medium drop-shadow">
                            Controla, asigna y monitorea el ciclo de vida de todo tu equipo tecnológico. Desde la asignación hasta el mantenimiento, todo en un solo lugar.
                        </p>
                        <div class="mt-12 flex items-center justify-center gap-x-6">
                             @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-full bg-gradient-to-r from-indigo-500 to-cyan-500 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-cyan-500/30 border border-transparent hover:from-indigo-400 hover:to-cyan-400 transition-all hover:scale-105">Ir al Panel de Control</a>
                             @else
                                <a href="{{ route('login') }}" class="rounded-full bg-gradient-to-r from-indigo-500 to-cyan-500 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-cyan-500/30 border border-transparent hover:from-indigo-400 hover:to-cyan-400 transition-all hover:scale-105">Acceder al Sistema</a>
                             @endauth
                        </div>
                    </div>

                    <div class="mt-20 flow-root sm:mt-32 w-full">
                        <div class="-m-2 rounded-xl bg-slate-800/50 p-2 ring-1 ring-inset ring-white/10 lg:-m-4 lg:rounded-2xl lg:p-4 backdrop-blur-md shadow-2xl">
                            <img src="https://tailwindui.com/img/component-images/project-app-screenshot.png" alt="App screenshot" width="2432" height="1442" class="rounded-md shadow-2xl ring-1 ring-white/10 opacity-60 grayscale-[50%] blur-[1px] mix-blend-screen">
                             <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-slate-200 text-sm font-semibold bg-slate-900/80 px-6 py-3 rounded-full backdrop-blur-md shadow-lg border border-white/20 tracking-wider uppercase">
                                    Vista previa del sistema seguro
                                </span>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-slate-500 border-t border-white/10 mt-20">
                    &copy; {{ date('Y') }} Control de Activos. Sistema Interno. Desarrollado con ❤️ para ti.
                </footer>
            </div>
        </div>
    </body>
</html>
