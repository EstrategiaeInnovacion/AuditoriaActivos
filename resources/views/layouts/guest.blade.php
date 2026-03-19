@props(['title' => config('app.name', 'Laravel')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Control de Activos TI — Sistema de gestión de activos tecnológicos.">

        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%234f46e5%22 width=%22100%22 height=%22100%22 rx=%2220%22/><path d=%22M30 50 L45 65 L70 35%22 stroke=%22white%22 stroke-width=%2210%22 fill=%22none%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/></svg>">
        <title>{{ $title }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(2deg); }
            }
            @keyframes gradient-shift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); }
                50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.6); }
            }
            .animate-float { animation: float 6s ease-in-out infinite; }
            .animate-gradient { 
                background-size: 200% 200%; 
                animation: gradient-shift 8s ease infinite; 
            }
            .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
            .glass {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .glass-card {
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .text-gradient {
                background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .text-gradient-cyan {
                background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 50%, #6366f1 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                opacity: 0.4;
                pointer-events: none;
            }
            .orb-purple { background: #8b5cf6; }
            .orb-blue { background: #3b82f6; }
            .orb-pink { background: #ec4899; }
            .input-glow:focus {
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3), 0 0 20px rgba(99, 102, 241, 0.2);
            }
            .noise {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                opacity: 0.03;
                z-index: 1000;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            }
        </style>
    </head>
    <body class="font-['Inter'] antialiased bg-slate-950 text-white min-h-screen">
        <div class="noise"></div>

        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-indigo-950/30 to-slate-950"></div>
            <div class="orb orb-purple w-[600px] h-[600px] -top-48 -left-48 animate-float"></div>
            <div class="orb orb-blue w-[500px] h-[500px] bottom-0 -right-48 animate-float" style="animation-delay: 2s;"></div>
            <div class="orb orb-pink w-[400px] h-[400px] top-1/2 left-1/3 animate-float" style="animation-delay: 4s;"></div>
        </div>

        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 relative">
            <header class="mb-8">
                <a href="/" wire:navigate class="flex items-center gap-3 group">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-shadow">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-2xl font-bold tracking-tight">Control<span class="text-gradient">Activos</span></span>
                        <p class="text-xs text-slate-500">Sistema de Gestión de Activos TI</p>
                    </div>
                </a>
            </header>

            <main class="w-full max-w-md">
                <div class="glass-card rounded-3xl p-8 shadow-2xl shadow-indigo-500/10">
                    {{ $slot }}
                </div>
            </main>

            <footer class="mt-8 text-center text-sm text-slate-500">
                <p>© {{ date('Y') }} ControlActivos. Todos los derechos reservados.</p>
            </footer>
        </div>
    </body>
</html>
