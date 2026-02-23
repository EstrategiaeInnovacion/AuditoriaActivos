@props(['title' => config('app.name', 'Laravel')])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📦</text></svg>">
        <title>{{ $title }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-300 antialiased bg-slate-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <!-- Glass/Glow Background -->
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-cyan-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40"></div>
            <div class="relative z-10 w-full flex justify-center">
                <a href="/" wire:navigate class="flex items-center gap-3 group">
                    <x-application-logo class="w-16 h-16 fill-current text-cyan-400 drop-shadow-md group-hover:scale-105 transition-transform" />
                    <span class="text-3xl font-extrabold text-white tracking-tight drop-shadow-sm group-hover:text-cyan-100 transition-colors">Activos TI</span>
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-8 px-8 py-8 bg-slate-800/80 backdrop-blur-xl shadow-2xl overflow-hidden sm:rounded-2xl border border-white/10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
