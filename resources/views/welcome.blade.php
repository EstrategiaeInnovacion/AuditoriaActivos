<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>💻</text></svg>">
        <title>Control de Activos - Gestión Inteligente de TI</title>
        <meta name="description" content="Sistema de control y gestión de activos tecnológicos. Administra dispositivos, asignaciones y préstamos de equipos de TI.">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 text-slate-900 font-sans antialiased">
        <div class="min-h-screen flex flex-col">
            <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/50 shadow-sm">
                <div class="max-w-7xl mx-auto px-6 lg:px-20 xl:px-40">
                    <div class="flex items-center justify-between h-16 lg:h-20">
                        <div class="flex items-center gap-3">
                            <x-application-logo class="h-8 w-8 fill-current text-indigo-600" />
                            <span class="text-xl lg:text-2xl font-bold text-slate-900 tracking-tight">Control de Activos</span>
                        </div>
                        <div class="flex items-center gap-8">
                            <nav class="hidden md:flex items-center gap-8">
                                <a href="#features" class="text-slate-600 hover:text-indigo-600 transition-colors text-sm font-medium">Características</a>
                                <a href="#stats" class="text-slate-600 hover:text-indigo-600 transition-colors text-sm font-medium">Estadísticas</a>
                            </nav>
                            @auth
                                <a href="{{ url('/dashboard') }}" class="flex items-center justify-center rounded-lg h-10 px-5 bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-all shadow-md shadow-indigo-600/20">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center justify-center rounded-lg h-10 px-5 bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-all shadow-md shadow-indigo-600/20">
                                    Acceder
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1">
                <section class="px-6 py-12 lg:px-20 xl:px-40">
                    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-10 lg:gap-16">
                        <div class="flex-1 text-center lg:text-left">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-4">
                                Innovación en IT
                            </span>
                            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-tight mb-6">
                                Gestión Inteligente de <span class="text-indigo-600">Activos TI</span>
                            </h1>
                            <p class="text-slate-600 text-lg leading-relaxed max-w-xl mx-auto lg:mx-0 mb-8">
                                Controla y monitorea el ciclo de vida de tu equipamiento tecnológico de manera eficiente con nuestra plataforma centralizada.
                            </p>
                            <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="flex items-center justify-center rounded-lg h-12 px-6 bg-indigo-600 text-white text-base font-bold hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-600/30 transition-all">
                                        Ir al Panel
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="flex items-center justify-center rounded-lg h-12 px-6 bg-indigo-600 text-white text-base font-bold hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-600/30 transition-all">
                                        Comenzar Ahora
                                    </a>

                                @endauth
                            </div>
                        </div>
                        <div class="flex-1 w-full">
                            <div class="w-full aspect-video bg-gradient-to-br from-indigo-600/20 to-indigo-100 rounded-2xl border border-indigo-200 shadow-2xl relative overflow-hidden">
                                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&q=80')] bg-cover bg-center opacity-60"></div>
                                <div class="absolute inset-0 bg-indigo-600/10 mix-blend-overlay"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="features" class="px-6 py-20 lg:px-20 xl:px-40 bg-white">
                    <div class="max-w-7xl mx-auto">
                        <div class="text-center max-w-2xl mx-auto mb-12">
                            <h2 class="text-3xl lg:text-4xl font-black text-slate-900 tracking-tight mb-4">
                                Nuestras Soluciones
                            </h2>
                            <p class="text-slate-600">
                                Todo lo que necesitas para un control total de tu infraestructura tecnológica en un solo lugar.
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="group flex flex-col gap-4 p-8 rounded-xl border border-slate-200 bg-slate-50 hover:border-indigo-300 hover:shadow-lg transition-all">
                                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined">inventory_2</span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <h3 class="text-slate-900 text-lg font-bold">Gestión de Activos</h3>
                                    <p class="text-slate-600 text-sm leading-relaxed">
                                        Inventario completo de hardware y software en tiempo real con auditorías automáticas.
                                    </p>
                                </div>
                            </div>
                            <div class="group flex flex-col gap-4 p-8 rounded-xl border border-slate-200 bg-slate-50 hover:border-indigo-300 hover:shadow-lg transition-all">
                                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined">person_search</span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <h3 class="text-slate-900 text-lg font-bold">Asignación</h3>
                                    <p class="text-slate-600 text-sm leading-relaxed">
                                        Seguimiento preciso de quién tiene cada equipo asignado y su ubicación física actual.
                                    </p>
                                </div>
                            </div>
                            <div class="group flex flex-col gap-4 p-8 rounded-xl border border-slate-200 bg-slate-50 hover:border-indigo-300 hover:shadow-lg transition-all">
                                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined">handshake</span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <h3 class="text-slate-900 text-lg font-bold">Control de Préstamos</h3>
                                    <p class="text-slate-600 text-sm leading-relaxed">
                                        Gestión eficiente de préstamos temporales, alertas de vencimiento y devoluciones rápidas.
                                    </p>
                                </div>
                            </div>
                            <div class="group flex flex-col gap-4 p-8 rounded-xl border border-slate-200 bg-slate-50 hover:border-indigo-300 hover:shadow-lg transition-all">
                                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined">build</span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <h3 class="text-slate-900 text-lg font-bold">Mantenimiento</h3>
                                    <p class="text-slate-600 text-sm leading-relaxed">
                                        Programación proactiva de revisiones, reparaciones y gestión de garantías de fabricantes.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="stats" class="px-6 py-16 lg:px-20 xl:px-40">
                    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="flex flex-col items-center gap-2 p-8 rounded-2xl bg-white shadow-sm border border-slate-100">
                            <p class="text-indigo-600 text-sm font-bold uppercase tracking-widest">Activos Gestionados</p>
                            <p class="text-slate-900 text-5xl font-black">500+</p>
                        </div>
                        <div class="flex flex-col items-center gap-2 p-8 rounded-2xl bg-white shadow-sm border border-slate-100">
                            <p class="text-indigo-600 text-sm font-bold uppercase tracking-widest">Usuarios Activos</p>
                            <p class="text-slate-900 text-5xl font-black">200+</p>
                        </div>
                        <div class="flex flex-col items-center gap-2 p-8 rounded-2xl bg-white shadow-sm border border-slate-100">
                            <p class="text-indigo-600 text-sm font-bold uppercase tracking-widest">Sedes Conectadas</p>
                            <p class="text-slate-900 text-5xl font-black">50+</p>
                        </div>
                    </div>
                </section>

                <section class="px-6 py-20 lg:px-20 xl:px-40">
                    <div class="max-w-7xl mx-auto relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-800 px-8 py-16 text-center shadow-2xl">
                        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-60 h-60 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="relative z-10 flex flex-col items-center gap-8">
                            <h2 class="text-white text-3xl md:text-5xl font-black tracking-tight max-w-2xl">
                                Optimiza tu infraestructura hoy mismo
                            </h2>
                            <p class="text-white/80 text-lg max-w-xl">
                                Únete a las empresas que ya transformaron su gestión técnica y redujeron costos operativos.
                            </p>
                            @auth
                                <a href="{{ url('/dashboard') }}" class="flex items-center justify-center rounded-xl h-14 px-8 bg-white text-indigo-600 text-lg font-bold hover:scale-105 transition-transform shadow-xl">
                                    Ir al Panel de Control
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center justify-center rounded-xl h-14 px-8 bg-white text-indigo-600 text-lg font-bold hover:scale-105 transition-transform shadow-xl">
                                    Acceder al Sistema
                                </a>
                            @endauth
                        </div>
                    </div>
                </section>
            </main>

            <footer class="border-t border-slate-200 px-6 py-12 lg:px-20 xl:px-40 bg-white text-slate-500">
                <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex items-center gap-2">
                        <x-application-logo class="h-6 w-6 fill-current text-indigo-600" />
                        <span class="font-bold text-slate-900">Control de Activos</span>
                    </div>
                    <p class="text-sm">© {{ date('Y') }} Control de Activos. Todos los derechos reservados.</p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="p-2 rounded-lg hover:bg-slate-100 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="p-2 rounded-lg hover:bg-slate-100 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                        <a href="#" class="p-2 rounded-lg hover:bg-slate-100 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4h-3.17l-1.24-1.35c-.37-.41-.91-.65-1.47-.65h-6.24c-.56 0-1.1.24-1.47.65l-1.24 1.35h-3.17c-1.21 0-2.2.99-2.2 2.2v11c0 1.21.99 2.2 2.2 2.2h16c1.21 0 2.2-.99 2.2-2.2v-11c0-1.21-.99-2.2-2.2-2.2zm-10 11.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg>
                        </a>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
