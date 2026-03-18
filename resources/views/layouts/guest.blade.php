@props(['title' => config('app.name', 'Laravel')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Control de Activos TI — Sistema de gestión de activos tecnológicos.">

        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>💻</text></svg>">
        <title>{{ $title }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 min-h-screen">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative p-4">
            <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 focus:z-[100] focus:px-6 focus:py-3 focus:bg-indigo-600 focus:text-white focus:font-semibold focus:rounded-br-lg focus:shadow-lg">Saltar al contenido principal</a>
            
            <header class="w-full max-w-md mb-6">
                <a href="/" wire:navigate class="flex items-center justify-center gap-3">
                    <x-application-logo class="w-10 h-10 fill-current text-indigo-600" />
                    <div>
                        <span class="text-2xl font-bold text-slate-900 tracking-tight">Control de Activos</span>
                        <p class="text-xs text-slate-500">Sistema de Gestión de Activos TI</p>
                    </div>
                </a>
            </header>

            <main id="main-content" class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8 border border-slate-100">
                {{ $slot }}
            </main>

            <footer class="w-full max-w-md mt-8 text-center text-sm text-slate-500">
                <p>&copy; {{ date('Y') }} Control de Activos. Todos los derechos reservados.</p>
            </footer>

            <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none opacity-50">
                <div class="absolute -top-1/4 -left-1/4 w-1/2 h-1/2 bg-indigo-200 blur-[120px] rounded-full"></div>
                <div class="absolute -bottom-1/4 -right-1/4 w-1/2 h-1/2 bg-indigo-200 blur-[120px] rounded-full"></div>
            </div>
        </div>
    </body>
</html>
