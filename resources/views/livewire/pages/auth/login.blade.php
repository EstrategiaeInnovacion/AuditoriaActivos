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
    <div class="flex flex-col items-center mb-8">
        <div class="bg-indigo-100 dark:bg-indigo-900/30 p-4 rounded-full mb-4">
            <span class="material-symbols-outlined text-indigo-600 text-4xl">computer</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white text-center">¡Bienvenido de nuevo!</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm text-center mt-1">Ingresa tus credenciales para acceder</p>
    </div>

    <form wire:submit="login" class="space-y-5">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="email">
                Correo Electrónico
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">
                    mail
                </span>
                <x-text-input 
                    wire:model="form.email" 
                    id="email" 
                    class="pl-10 pr-4 py-3" 
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
            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="password">
                Contraseña
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">
                    lock
                </span>
                <x-text-input 
                    wire:model="form.password" 
                    id="password" 
                    class="pl-10 pr-12 py-3" 
                    x-bind:type="showPassword ? 'text' : 'password'" 
                    name="password" 
                    required 
                    autocomplete="current-password" 
                    placeholder="••••••••" />
                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition-colors">
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <svg x-cloak x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between text-sm">
            <label for="remember" class="flex items-center space-x-2 cursor-pointer group">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                <span class="text-slate-600 dark:text-slate-400 group-hover:text-indigo-600 transition-colors">Recordarme</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-indigo-600 font-medium hover:underline" href="{{ route('password.request') }}" wire:navigate>
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg flex items-center justify-center gap-2 transition-all shadow-lg shadow-indigo-600/20">
            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            Iniciar Sesión
        </button>
    </form>

    <div class="mt-8 text-center">
        <p class="text-slate-600 dark:text-slate-400 text-sm">
            ¿No tienes cuenta? 
            <span class="text-slate-400 cursor-not-allowed font-medium">Regístrate aquí</span>
        </p>
    </div>
</div>
