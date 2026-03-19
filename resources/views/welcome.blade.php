<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%234f46e5%22 width=%22100%22 height=%22100%22 rx=%2220%22/><path d=%22M30 50 L45 65 L70 35%22 stroke=%22white%22 stroke-width=%2210%22 fill=%22none%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/></svg>">
        <title>Control de Activos - Gestión Inteligente de TI</title>
        <meta name="description" content="Sistema de control y gestión de activos tecnológicos. Administra dispositivos, asignaciones y préstamos de equipos de TI con tecnología de vanguardia.">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(2deg); }
            }
            @keyframes float-delayed {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-15px) rotate(-2deg); }
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
            @keyframes shimmer {
                0% { background-position: -200% 0; }
                100% { background-position: 200% 0; }
            }
            @keyframes spin-slow {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            @keyframes fade-in-up {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes count-up {
                from { opacity: 0; transform: scale(0.5); }
                to { opacity: 1; transform: scale(1); }
            }
            .animate-float { animation: float 6s ease-in-out infinite; }
            .animate-float-delayed { animation: float-delayed 7s ease-in-out infinite 1s; }
            .animate-gradient { 
                background-size: 200% 200%; 
                animation: gradient-shift 8s ease infinite; 
            }
            .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
            .animate-shimmer {
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                background-size: 200% 100%;
                animation: shimmer 2s infinite;
            }
            .animate-spin-slow { animation: spin-slow 20s linear infinite; }
            .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; }
            .animate-count-up { animation: count-up 0.6s ease-out forwards; }
            .animation-delay-100 { animation-delay: 100ms; }
            .animation-delay-200 { animation-delay: 200ms; }
            .animation-delay-300 { animation-delay: 300ms; }
            .animation-delay-400 { animation-delay: 400ms; }
            .animation-delay-500 { animation-delay: 500ms; }
            .glass {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            .glass-dark {
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
                opacity: 0.5;
                pointer-events: none;
            }
            .orb-purple { background: #8b5cf6; }
            .orb-blue { background: #3b82f6; }
            .orb-pink { background: #ec4899; }
            .orb-cyan { background: #06b6d4; }
            .card-hover {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .card-hover:hover {
                transform: translateY(-8px);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            .btn-primary {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
                transform: translateY(-2px);
                box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.5);
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
    <body class="bg-slate-950 text-white font-['Inter'] antialiased overflow-x-hidden">
        <div class="noise"></div>

        <!-- Animated Background -->
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-indigo-950/30 to-slate-950"></div>
            <div class="orb orb-purple w-[600px] h-[600px] -top-48 -left-48 animate-float"></div>
            <div class="orb orb-blue w-[500px] h-[500px] top-1/3 -right-48 animate-float-delayed"></div>
            <div class="orb orb-pink w-[400px] h-[400px] bottom-0 left-1/3 animate-float" style="animation-delay: 2s;"></div>
            <div class="orb orb-cyan w-[300px] h-[300px] top-1/2 left-1/4 animate-float-delayed" style="animation-delay: 3s;"></div>
        </div>

        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)" :class="scrolled ? 'glass-dark shadow-2xl' : 'bg-transparent'">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <a href="/" class="flex items-center gap-3 group">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-shadow">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl blur opacity-30 group-hover:opacity-50 transition-opacity -z-10"></div>
                        </div>
                        <span class="text-xl font-bold tracking-tight">Control<span class="text-gradient">Activos</span></span>
                    </a>

                    <nav class="hidden md:flex items-center gap-8">
                        <a href="#features" class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Características</a>
                        <a href="#how-it-works" class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Cómo Funciona</a>
                        <a href="#testimonials" class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Testimonios</a>
                    </nav>

                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary px-6 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-slate-300 hover:text-white transition-colors text-sm font-medium hidden sm:block">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="btn-primary px-6 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2">
                                Comenzar
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main>
            <!-- Hero Section -->
            <section class="relative min-h-screen flex items-center pt-20 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
                </div>

                <div class="max-w-7xl mx-auto px-6 lg:px-8 py-20 lg:py-32 relative z-10">
                    <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                        <div class="text-center lg:text-left">
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass mb-6 animate-fade-in-up">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                <span class="text-sm text-slate-300">+500 activos gestionados exitosamente</span>
                            </div>

                            <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-black tracking-tight mb-6 animate-fade-in-up animation-delay-100">
                                <span class="text-white">Gestión </span>
                                <span class="text-gradient">Inteligente</span>
                                <br>
                                <span class="text-white">de Activos </span>
                                <span class="text-gradient-cyan">TI</span>
                            </h1>

                            <p class="text-lg lg:text-xl text-slate-400 max-w-xl mx-auto lg:mx-0 mb-8 animate-fade-in-up animation-delay-200">
                                Controla y monitorea el ciclo de vida completo de tu infraestructura tecnológica con auditorías automáticas, asignaciones precisas y control de préstamos en tiempo real.
                            </p>

                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start animate-fade-in-up animation-delay-300">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn-primary px-8 py-4 rounded-2xl text-white font-bold text-lg flex items-center justify-center gap-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        Ir al Panel
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="btn-primary px-8 py-4 rounded-2xl text-white font-bold text-lg flex items-center justify-center gap-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        Comenzar Gratis
                                    </a>
                                    <a href="{{ route('login') }}" class="glass px-8 py-4 rounded-2xl text-white font-semibold text-lg flex items-center justify-center gap-3 hover:bg-white/20 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Ver Demo
                                    </a>
                                @endauth
                            </div>

                            <div class="mt-10 flex items-center gap-6 justify-center lg:justify-start animate-fade-in-up animation-delay-400">
                                <div class="flex -space-x-3">
                                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face" alt="Usuario" class="w-10 h-10 rounded-full border-2 border-slate-900">
                                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=face" alt="Usuario" class="w-10 h-10 rounded-full border-2 border-slate-900">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face" alt="Usuario" class="w-10 h-10 rounded-full border-2 border-slate-900">
                                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&crop=face" alt="Usuario" class="w-10 h-10 rounded-full border-2 border-slate-900">
                                </div>
                                <div class="text-left">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    </div>
                                    <p class="text-sm text-slate-400">+200 usuarios satisfechos</p>
                                </div>
                            </div>
                        </div>

                        <!-- Hero Visual -->
                        <div class="relative animate-fade-in-up animation-delay-300">
                            <div class="relative">
                                <!-- Main Dashboard Preview -->
                                <div class="glass-dark rounded-3xl p-1 shadow-2xl shadow-indigo-500/20">
                                    <div class="bg-slate-800/80 rounded-3xl overflow-hidden">
                                        <div class="flex items-center gap-2 px-4 py-3 bg-slate-900/50 border-b border-slate-700/50">
                                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                            <div class="flex-1 text-center text-xs text-slate-500">ControlActivos Dashboard</div>
                                        </div>
                                        <div class="p-6">
                                            <div class="grid grid-cols-3 gap-4 mb-6">
                                                <div class="bg-gradient-to-br from-indigo-500/20 to-purple-500/20 rounded-xl p-4">
                                                    <p class="text-xs text-slate-400 mb-1">Activos</p>
                                                    <p class="text-2xl font-bold text-white">523</p>
                                                    <p class="text-xs text-green-400">+12%</p>
                                                </div>
                                                <div class="bg-gradient-to-br from-cyan-500/20 to-blue-500/20 rounded-xl p-4">
                                                    <p class="text-xs text-slate-400 mb-1">Asignados</p>
                                                    <p class="text-2xl font-bold text-white">412</p>
                                                    <p class="text-xs text-green-400">+8%</p>
                                                </div>
                                                <div class="bg-gradient-to-br from-pink-500/20 to-rose-500/20 rounded-xl p-4">
                                                    <p class="text-xs text-slate-400 mb-1">Disponibles</p>
                                                    <p class="text-2xl font-bold text-white">111</p>
                                                    <p class="text-xs text-yellow-400">Estables</p>
                                                </div>
                                            </div>
                                            <div class="space-y-3">
                                                <div class="flex items-center justify-between p-3 bg-slate-700/30 rounded-lg">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-lg bg-indigo-500/30 flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-white">Dell Latitude 5520</p>
                                                            <p class="text-xs text-slate-400">Asignado a M. García</p>
                                                        </div>
                                                    </div>
                                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Activo</span>
                                                </div>
                                                <div class="flex items-center justify-between p-3 bg-slate-700/30 rounded-lg">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-lg bg-cyan-500/30 flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-white">iPhone 14 Pro</p>
                                                            <p class="text-xs text-slate-400">En préstamo</p>
                                                        </div>
                                                    </div>
                                                    <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 text-xs rounded-full">Préstamo</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Floating Elements -->
                                <div class="absolute -top-6 -right-6 glass rounded-2xl p-4 shadow-xl animate-float">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-green-500 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-white">Auditoría Completa</p>
                                            <p class="text-xs text-slate-400">Última hace 2 días</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="absolute -bottom-4 -left-6 glass rounded-2xl p-4 shadow-xl animate-float-delayed">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-500 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-white">100% Seguro</p>
                                            <p class="text-xs text-slate-400">Datos encriptados</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scroll Indicator -->
                <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-bounce">
                    <span class="text-xs text-slate-500">Scroll</span>
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </div>
            </section>

            <!-- Trusted By -->
            <section class="py-16 border-y border-slate-800/50">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <p class="text-center text-sm text-slate-500 mb-8">Compatible con las principales marcas de tecnología</p>
                    <div class="flex flex-wrap justify-center items-center gap-8 lg:gap-16 opacity-60">
                        <div class="flex items-center gap-2 text-slate-400">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h11.5v11.5H0V0zm12.5 0H24v11.5H12.5V0zM0 12.5h11.5V24H0V12.5zm12.5 0H24V24H12.5V12.5z"/></svg>
                            <span class="font-semibold">Dell</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-400">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                            <span class="font-semibold">Apple</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-400">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M20 18c1.1 0 1.99-.9 1.99-2L22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 6h16v10H4V6z"/></svg>
                            <span class="font-semibold">Lenovo</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-400">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M1.95 21.6c1.6.9 3.4 1.4 5.05 1.4h.2c3.7 0 7-2.7 7.8-6.3.2-.8.2-1.6.2-2.4 0-.8-.1-1.5-.3-2.3-.8-3.7-4.1-6.4-7.8-6.4h-.2c-1.7 0-3.4.5-5 1.4l.95 2.2c1.1-.6 2.3-.9 3.6-.9h.1c2.2 0 4.1 1.5 4.6 3.6.1.4.2.8.2 1.3 0 .4-.1.9-.2 1.3-.5 2.1-2.4 3.6-4.6 3.6h-.1c-1.3 0-2.5-.3-3.6-.9l1-2.2z"/><path d="M13.9 17.5c1.1 0 2.1-.6 2.7-1.4l-1.9-.8c-.1.1-.3.2-.5.2-.4 0-.7-.3-.7-.7s.3-.7.7-.7c.2 0 .4.1.5.2l1.9-.8c-.6-.9-1.6-1.4-2.7-1.4-1.5 0-2.8 1.2-2.8 2.8s1.2 2.8 2.8 2.8z"/></svg>
                            <span class="font-semibold">HP</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-400">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 12c0-1.4-1.1-2.5-2.5-2.5S12.5 10.6 12.5 12s1.1 2.5 2.5 2.5 2.5-1.1 2.5-2.5zm3-8c0-1.4-1.1-2.5-2.5-2.5S15.5 2.6 15.5 4s1.1 2.5 2.5 2.5 2.5-1.1 2.5-2.5zM4 17c0-1.4 1.1-2.5 2.5-2.5s2.5 1.1 2.5 2.5-1.1 2.5-2.5 2.5S4 18.4 4 17zm14.5 0c0-1.4 1.1-2.5 2.5-2.5V14c0 1.4-1.1 2.5-2.5 2.5s-2.5-1.1-2.5-2.5 1.1-2.5 2.5-2.5V7c-1.4 0-2.5 1.1-2.5 2.5s1.1 2.5 2.5 2.5c-1.4 0-2.5 1.1-2.5 2.5z"/></svg>
                            <span class="font-semibold">Samsung</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="py-24 lg:py-32">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <span class="inline-block px-4 py-1.5 rounded-full bg-indigo-500/10 text-indigo-400 text-sm font-semibold mb-4 border border-indigo-500/20">Características</span>
                        <h2 class="text-3xl lg:text-5xl font-black tracking-tight mb-6">
                            Todo lo que necesitas para una <span class="text-gradient">gestión excepcional</span>
                        </h2>
                        <p class="text-lg text-slate-400">
                            Herramientas completas diseñadas para simplificar la administración de activos tecnológicos de tu organización.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Feature 1 -->
                        <div class="group glass-dark rounded-3xl p-8 card-hover">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mb-6 shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-shadow">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Inventario Completo</h3>
                            <p class="text-slate-400 leading-relaxed">
                                Registro detallado de hardware y software con información de ubicación, estado y historial de cada activo.
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="group glass-dark rounded-3xl p-8 card-hover">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center mb-6 shadow-lg shadow-cyan-500/30 group-hover:shadow-cyan-500/50 transition-shadow">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Asignaciones Precisas</h3>
                            <p class="text-slate-400 leading-relaxed">
                                Control total de quién tiene cada equipo asignado con seguimiento de ubicaciones y cambios de responsable.
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="group glass-dark rounded-3xl p-8 card-hover">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center mb-6 shadow-lg shadow-pink-500/30 group-hover:shadow-pink-500/50 transition-shadow">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Control de Préstamos</h3>
                            <p class="text-slate-400 leading-relaxed">
                                Gestión eficiente de préstamos temporales con alertas de vencimiento y notificaciones de devolución.
                            </p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="group glass-dark rounded-3xl p-8 card-hover">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center mb-6 shadow-lg shadow-green-500/30 group-hover:shadow-green-500/50 transition-shadow">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Auditorías Automáticas</h3>
                            <p class="text-slate-400 leading-relaxed">
                                Programación de auditorías periódicas con verificación QR para confirmar la existencia física de activos.
                            </p>
                        </div>

                        <!-- Feature 5 -->
                        <div class="group glass-dark rounded-3xl p-8 card-hover">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center mb-6 shadow-lg shadow-orange-500/30 group-hover:shadow-orange-500/50 transition-shadow">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Mantenimiento Programado</h3>
                            <p class="text-slate-400 leading-relaxed">
                                Agenda revisiones, reparaciones y gestiona garantías de fabricantes con recordatorios automáticos.
                            </p>
                        </div>

                        <!-- Feature 6 -->
                        <div class="group glass-dark rounded-3xl p-8 card-hover">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center mb-6 shadow-lg shadow-violet-500/30 group-hover:shadow-violet-500/50 transition-shadow">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Reportes Detallados</h3>
                            <p class="text-slate-400 leading-relaxed">
                                Informes personalizables y exportaciones en PDF con métricas clave y análisis de tendencias.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works -->
            <section id="how-it-works" class="py-24 lg:py-32 bg-gradient-to-b from-transparent via-indigo-950/20 to-transparent">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <span class="inline-block px-4 py-1.5 rounded-full bg-cyan-500/10 text-cyan-400 text-sm font-semibold mb-4 border border-cyan-500/20">Proceso</span>
                        <h2 class="text-3xl lg:text-5xl font-black tracking-tight mb-6">
                            Implementación <span class="text-gradient-cyan">en 3 pasos</span>
                        </h2>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                        <div class="relative text-center">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mx-auto mb-6 text-3xl font-black shadow-lg shadow-indigo-500/30">
                                1
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Registra tus Activos</h3>
                            <p class="text-slate-400">
                                Importa tu inventario existente o regístralos manualmente. Compatible con códigos QR y barras.
                            </p>
                            <div class="hidden md:block absolute top-10 left-[60%] w-[80%] border-t-2 border-dashed border-slate-700"></div>
                        </div>

                        <div class="relative text-center">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center mx-auto mb-6 text-3xl font-black shadow-lg shadow-cyan-500/30">
                                2
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Asigna y Controla</h3>
                            <p class="text-slate-400">
                                Asigna equipos a usuarios, gestiona préstamos y programa auditorías con recordatorios automáticos.
                            </p>
                            <div class="hidden md:block absolute top-10 left-[60%] w-[80%] border-t-2 border-dashed border-slate-700"></div>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center mx-auto mb-6 text-3xl font-black shadow-lg shadow-pink-500/30">
                                3
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">Reporta y Optimiza</h3>
                            <p class="text-slate-400">
                                Genera reportes detallados, identifica oportunidades de mejora y optimiza tu infraestructura TI.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="py-24 lg:py-32">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="glass-dark rounded-[2rem] p-8 lg:p-16 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl"></div>
                        
                        <div class="relative z-10">
                            <div class="text-center max-w-3xl mx-auto mb-16">
                                <h2 class="text-3xl lg:text-5xl font-black tracking-tight mb-6">
                                    Los números <span class="text-gradient">hablan por nosotros</span>
                                </h2>
                            </div>

                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                                <div class="text-center">
                                    <div class="text-4xl lg:text-6xl font-black text-gradient mb-2">500+</div>
                                    <p class="text-slate-400 font-medium">Activos Gestionados</p>
                                </div>
                                <div class="text-center">
                                    <div class="text-4xl lg:text-6xl font-black text-gradient-cyan mb-2">200+</div>
                                    <p class="text-slate-400 font-medium">Usuarios Activos</p>
                                </div>
                                <div class="text-center">
                                    <div class="text-4xl lg:text-6xl font-black text-gradient mb-2">50+</div>
                                    <p class="text-slate-400 font-medium">Empresas Confían</p>
                                </div>
                                <div class="text-center">
                                    <div class="text-4xl lg:text-6xl font-black text-gradient-cyan mb-2">99%</div>
                                    <p class="text-slate-400 font-medium">Satisfacción</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials -->
            <section id="testimonials" class="py-24 lg:py-32">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <span class="inline-block px-4 py-1.5 rounded-full bg-pink-500/10 text-pink-400 text-sm font-semibold mb-4 border border-pink-500/20">Testimonios</span>
                        <h2 class="text-3xl lg:text-5xl font-black tracking-tight mb-6">
                            Lo que dicen nuestros <span class="text-gradient">clientes</span>
                        </h2>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="glass-dark rounded-3xl p-8 card-hover">
                            <div class="flex items-center gap-1 mb-4">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <p class="text-slate-300 mb-6 leading-relaxed">
                                "Desde que implementamos ControlActivos, redujimos el tiempo de auditoría de semanas a horas. La gestión de préstamos es ahora impecable."
                            </p>
                            <div class="flex items-center gap-4">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face" alt="Carlos Mendoza" class="w-12 h-12 rounded-full">
                                <div>
                                    <p class="font-semibold text-white">Carlos Mendoza</p>
                                    <p class="text-sm text-slate-400">CTO, TechCorp MX</p>
                                </div>
                            </div>
                        </div>

                        <div class="glass-dark rounded-3xl p-8 card-hover">
                            <div class="flex items-center gap-1 mb-4">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <p class="text-slate-300 mb-6 leading-relaxed">
                                "La mejor inversión que hicimos para nuestra área de TI. El escaneo QR facilita auditorías mensuales sin complicaciones."
                            </p>
                            <div class="flex items-center gap-4">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=face" alt="Ana Sofía Ruiz" class="w-12 h-12 rounded-full">
                                <div>
                                    <p class="font-semibold text-white">Ana Sofía Ruiz</p>
                                    <p class="text-sm text-slate-400">Directora de Sistemas, Grupo ODS</p>
                                </div>
                            </div>
                        </div>

                        <div class="glass-dark rounded-3xl p-8 card-hover">
                            <div class="flex items-center gap-1 mb-4">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <p class="text-slate-300 mb-6 leading-relaxed">
                                "La interfaz es súper intuitiva. Nuestro equipo adoptó la plataforma en días sin necesidad de capacitación extensiva."
                            </p>
                            <div class="flex items-center gap-4">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face" alt="Roberto Vega" class="w-12 h-12 rounded-full">
                                <div>
                                    <p class="font-semibold text-white">Roberto Vega</p>
                                    <p class="text-sm text-slate-400">IT Manager, StartupHub</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-24 lg:py-32">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="relative rounded-[2rem] overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 animate-gradient"></div>
                        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1557682250-33bd709cbe85?auto=format&fit=crop&q=80')] bg-cover bg-center opacity-10"></div>
                        
                        <div class="relative px-8 py-16 lg:px-16 lg:py-24 text-center">
                            <h2 class="text-3xl lg:text-5xl font-black tracking-tight text-white mb-6 max-w-3xl mx-auto">
                                ¿Listo para optimizar tu gestión de activos TI?
                            </h2>
                            <p class="text-lg lg:text-xl text-white/80 mb-10 max-w-2xl mx-auto">
                                Únete a más de 200 organizaciones que ya transformaron su infraestructura tecnológica con ControlActivos.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-white text-indigo-600 font-bold text-lg rounded-2xl hover:bg-slate-100 transition-all hover:scale-105 hover:shadow-2xl shadow-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        Ir al Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-white text-indigo-600 font-bold text-lg rounded-2xl hover:bg-slate-100 transition-all hover:scale-105 hover:shadow-2xl shadow-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        Comenzar Gratuitamente
                                    </a>
                                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-white/10 backdrop-blur-sm text-white font-semibold text-lg rounded-2xl border border-white/30 hover:bg-white/20 transition-all hover:scale-105">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        Hablar con Ventas
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-800/50 py-12 lg:py-16">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 lg:gap-12 mb-12">
                    <div class="col-span-2 md:col-span-1">
                        <a href="/" class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <span class="text-lg font-bold text-white">ControlActivos</span>
                        </a>
                        <p class="text-sm text-slate-400">
                            Gestión inteligente de activos tecnológicos para empresas modernas.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-4">Producto</h4>
                        <ul class="space-y-2 text-sm text-slate-400">
                            <li><a href="#features" class="hover:text-white transition-colors">Características</a></li>
                            <li><a href="#how-it-works" class="hover:text-white transition-colors">Cómo Funciona</a></li>
                            <li><a href="#testimonials" class="hover:text-white transition-colors">Testimonios</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Precios</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-4">Empresa</h4>
                        <ul class="space-y-2 text-sm text-slate-400">
                            <li><a href="#" class="hover:text-white transition-colors">Sobre Nosotros</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Carreras</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Contacto</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-4">Legal</h4>
                        <ul class="space-y-2 text-sm text-slate-400">
                            <li><a href="#" class="hover:text-white transition-colors">Privacidad</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Términos</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Cookies</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-slate-800/50 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-slate-500">
                        © {{ date('Y') }} ControlActivos. Todos los derechos reservados.
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="p-2 rounded-lg bg-slate-800/50 text-slate-400 hover:text-white hover:bg-slate-700/50 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="p-2 rounded-lg bg-slate-800/50 text-slate-400 hover:text-white hover:bg-slate-700/50 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                        <a href="#" class="p-2 rounded-lg bg-slate-800/50 text-slate-400 hover:text-white hover:bg-slate-700/50 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Alpine.js for interactivity -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>
</html>
