<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

use Livewire\Attributes\Title;

new #[Layout('layouts.guest')] #[Title('Iniciar Sesión')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-black text-white mb-2">¡Bienvenido de nuevo!</h1>
        <p class="text-slate-400">Ingresa tus credenciales para acceder</p>
    </div>

    <form wire:submit="login" class="space-y-6">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-300 ml-1" for="email">
                Correo Electrónico
            </label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </span>
                <x-text-input 
                    wire:model="form.email" 
                    id="email" 
                    class="pl-12 pr-4 py-3.5 bg-slate-800/50 border-slate-700 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500 input-glow rounded-xl" 
                    type="email" 
                    name="email" 
                    required 
                    autofocus 
                    autocomplete="username" 
                    placeholder="correo@ejemplo.com" />
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <div class="space-y-2" x-data="{ showPassword: false }">
            <label class="text-sm font-semibold text-slate-300 ml-1" for="password">
                Contraseña
            </label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <x-text-input 
                    wire:model="form.password" 
                    id="password" 
                    class="pl-12 pr-12 py-3.5 bg-slate-800/50 border-slate-700 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500 input-glow rounded-xl" 
                    x-bind:type="showPassword ? 'text' : 'password'" 
                    name="password" 
                    required 
                    autocomplete="current-password" 
                    placeholder="••••••••" />
                <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-400 transition-colors cursor-pointer">
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-cloak x-show="showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between text-sm">
            <label for="remember" class="flex items-center space-x-2 cursor-pointer group">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0 h-4 w-4 cursor-pointer">
                <span class="text-slate-400 group-hover:text-slate-300 transition-colors">Recordarme</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-indigo-400 font-medium hover:text-indigo-300 transition-colors" href="{{ route('password.request') }}" wire:navigate>
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full relative overflow-hidden bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-4 rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 group">
            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
            <svg class="w-5 h-5 relative z-10" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            <span class="relative z-10">Iniciar Sesión</span>
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-slate-700/50 text-center">
        <p class="text-slate-400 text-sm">
            ¿No tienes cuenta? 
            <a class="font-semibold text-gradient hover:text-purple-400 transition-colors" href="{{ route('register') }}" wire:navigate>
                Regístrate aquí
            </a>
        </p>
    </div>

    <div class="mt-6 flex items-center justify-center gap-4 text-xs text-slate-500">
        <a href="/" class="flex items-center gap-1 hover:text-slate-300 transition-colors" wire:navigate>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al inicio
        </a>
    </div>
</div>
