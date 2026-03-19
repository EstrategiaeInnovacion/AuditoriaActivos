<x-app-layout title="Crear Activo">
    <x-slot name="header">
        <h2 class="font-bold text-xl text-white leading-tight">
            {{ __('Agregar Nuevo Activo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass rounded-2xl overflow-hidden">
                <div class="p-8">
                    <form method="POST" action="{{ route('devices.store') }}" enctype="multipart/form-data">
                        @csrf

                        <h3 class="text-lg font-medium text-slate-200 mb-4 border-b border-slate-700/50 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            Información General
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nombre del Dispositivo')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="brand" :value="__('Marca')" />
                                <x-text-input id="brand" class="block mt-1 w-full" type="text" name="brand" :value="old('brand')" required />
                                <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="model" :value="__('Modelo')" />
                                <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="old('model')" required />
                                <x-input-error :messages="$errors->get('model')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="serial_number" :value="__('Número de Serie')" />
                                <x-text-input id="serial_number" class="block mt-1 w-full" type="text" name="serial_number" :value="old('serial_number')" required />
                                <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="type" :value="__('Tipo de Activo')" />
                                <select id="type" name="type" class="block mt-1 w-full bg-slate-800/50 border-slate-600/50 text-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm backdrop-blur-sm">
                                    <option value="computer" {{ old('type') == 'computer' ? 'selected' : '' }}>Computadora / Laptop</option>
                                    <option value="peripheral" {{ old('type') == 'peripheral' ? 'selected' : '' }}>Periférico</option>
                                    <option value="printer" {{ old('type') == 'printer' ? 'selected' : '' }}>Impresora</option>
                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Otro</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Estado Inicial')" />
                                <select id="status" name="status" class="block mt-1 w-full bg-slate-800/50 border-slate-600/50 text-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm backdrop-blur-sm">
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                                    <option value="assigned" {{ old('status') == 'assigned' ? 'selected' : '' }}>Asignado</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>En Mantenimiento</option>
                                    <option value="broken" {{ old('status') == 'broken' ? 'selected' : '' }}>Averiado</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="purchase_date" :value="__('Fecha de Compra')" />
                                <x-text-input id="purchase_date" class="block mt-1 w-full" type="date" name="purchase_date" :value="old('purchase_date')" />
                                <x-input-error :messages="$errors->get('purchase_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="warranty_expiration" :value="__('Vencimiento de Garantía')" />
                                <x-text-input id="warranty_expiration" class="block mt-1 w-full" type="date" name="warranty_expiration" :value="old('warranty_expiration')" />
                                <x-input-error :messages="$errors->get('warranty_expiration')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notas Adicionales')" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full bg-slate-800/50 border-slate-600/50 text-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm backdrop-blur-sm">{{ old('notes') }}</textarea>
                             <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <hr class="my-6 border-slate-700/50">

                        <h3 class="text-lg font-medium text-slate-200 mb-4 border-b border-slate-700/50 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            Credenciales (Opcional)
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <x-input-label for="username" :value="__('Usuario del Equipo')" />
                                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" />
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>

                            <div x-data="{ show: false }">
                                <x-input-label for="password" :value="__('Contraseña del Equipo')" />
                                <div class="relative mt-1">
                                    <x-text-input id="password" class="block w-full pr-10" x-bind:type="show ? 'text' : 'password'" name="password" :value="old('password')" />
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-300 focus:outline-none">
                                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg x-cloak x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="email" :value="__('Cuenta de Correo')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div x-data="{ show: false }">
                                <x-input-label for="email_password" :value="__('Contraseña del Correo')" />
                                <div class="relative mt-1">
                                    <x-text-input id="email_password" class="block w-full pr-10" x-bind:type="show ? 'text' : 'password'" name="email_password" :value="old('email_password')" />
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-300 focus:outline-none">
                                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg x-cloak x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('email_password')" class="mt-2" />
                            </div>
                        </div>

                        <hr class="my-6 border-slate-700/50">

                        <h3 class="text-lg font-medium text-slate-200 mb-4 border-b border-slate-700/50 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Fotos del Equipo (Opcional)
                        </h3>
                        <div x-data="{ previews: [] }" class="space-y-4">
                            <input type="file" name="photos[]" multiple accept="image/*" class="text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-500/20 file:text-indigo-300 hover:file:bg-indigo-500/30 file:transition-all"
                                @change="previews = [...$event.target.files].map(f => URL.createObjectURL(f))">
                            <div x-show="previews.length" class="grid grid-cols-4 gap-3">
                                <template x-for="(src, i) in previews" :key="i">
                                    <img :src="src" alt="Vista previa" class="w-full h-24 object-cover rounded-lg border border-slate-700/50">
                                </template>
                            </div>
                            <x-input-error :messages="$errors->get('photos')" class="mt-2" />
                            <x-input-error :messages="collect($errors->get('photos.*'))->flatten()->all()" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-slate-700/50">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-cyan-500 border border-transparent rounded-xl justify-center font-bold text-sm text-white uppercase tracking-wider hover:from-indigo-400 hover:to-cyan-400 shadow-lg shadow-cyan-500/30 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all hover:scale-[1.02]">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ __('Guardar Dispositivo') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
