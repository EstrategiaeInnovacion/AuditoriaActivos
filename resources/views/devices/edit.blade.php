<x-app-layout title="Editar Activo">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Editar Dispositivo') }}: {{ $device->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass rounded-2xl overflow-hidden">
                <div class="p-8">
                    <form method="POST" action="{{ route('devices.update', $device) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-medium text-slate-200 mb-4 border-b border-slate-700/50 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            Información General
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nombre del Dispositivo')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $device->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="brand" :value="__('Marca')" />
                                <x-text-input id="brand" class="block mt-1 w-full" type="text" name="brand" :value="old('brand', $device->brand)" required />
                                <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="model" :value="__('Modelo')" />
                                <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="old('model', $device->model)" required />
                                <x-input-error :messages="$errors->get('model')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="serial_number" :value="__('Número de Serie')" />
                                <x-text-input id="serial_number" class="block mt-1 w-full" type="text" name="serial_number" :value="old('serial_number', $device->serial_number)" required />
                                <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="type" :value="__('Tipo de Activo')" />
                                <select id="type" name="type" class="block mt-1 w-full bg-slate-800/50 border-slate-600/50 text-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm backdrop-blur-sm">
                                    <option value="computer" {{ old('type', $device->type) == 'computer' ? 'selected' : '' }}>Computadora / Laptop</option>
                                    <option value="peripheral" {{ old('type', $device->type) == 'peripheral' ? 'selected' : '' }}>Periférico</option>
                                    <option value="printer" {{ old('type', $device->type) == 'printer' ? 'selected' : '' }}>Impresora</option>
                                    <option value="other" {{ old('type', $device->type) == 'other' ? 'selected' : '' }}>Otro</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Estado Actual')" />
                                <select id="status" name="status" class="block mt-1 w-full bg-slate-800/50 border-slate-600/50 text-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm backdrop-blur-sm">
                                    <option value="available" {{ old('status', $device->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                                    <option value="assigned" {{ old('status', $device->status) == 'assigned' ? 'selected' : '' }}>Asignado</option>
                                    <option value="maintenance" {{ old('status', $device->status) == 'maintenance' ? 'selected' : '' }}>En Mantenimiento</option>
                                    <option value="broken" {{ old('status', $device->status) == 'broken' ? 'selected' : '' }}>Averiado</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="purchase_date" :value="__('Fecha de Compra')" />
                                <x-text-input id="purchase_date" class="block mt-1 w-full" type="date" name="purchase_date" :value="old('purchase_date', $device->purchase_date ? $device->purchase_date : '')" />
                                <x-input-error :messages="$errors->get('purchase_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="warranty_expiration" :value="__('Vencimiento de Garantía')" />
                                <x-text-input id="warranty_expiration" class="block mt-1 w-full" type="date" name="warranty_expiration" :value="old('warranty_expiration', $device->warranty_expiration ? $device->warranty_expiration : '')" />
                                <x-input-error :messages="$errors->get('warranty_expiration')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notas')" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full bg-slate-800/50 border-slate-600/50 text-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm backdrop-blur-sm">{{ old('notes', $device->notes) }}</textarea>
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
                                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username', $device->credential->username ?? '')" />
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>

                            <div x-data="{ show: false }">
                                <x-input-label for="password" :value="__('Contraseña del Equipo')" />
                                <div class="relative mt-1">
                                    <x-text-input id="password" class="block w-full pr-10" x-bind:type="show ? 'text' : 'password'" name="password" :value="old('password', $device->credential->password ?? '')" />
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-300 focus:outline-none">
                                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg x-cloak x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="email" :value="__('Cuenta de Correo')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $device->credential->email ?? '')" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div x-data="{ show: false }">
                                <x-input-label for="email_password" :value="__('Contraseña del Correo')" />
                                <div class="relative mt-1">
                                    <x-text-input id="email_password" class="block w-full pr-10" x-bind:type="show ? 'text' : 'password'" name="email_password" :value="old('email_password', $device->credential->email_password ?? '')" />
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
                            Fotos del Equipo
                        </h3>
                        <div x-data="{ previews: [] }" class="space-y-4">
                            @if($device->photos->count())
                                <div class="grid grid-cols-4 gap-3">
                                    @foreach($device->photos as $photo)
                                        <label class="relative group cursor-pointer">
                                            <input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" class="absolute top-1 right-1 z-10 rounded border-red-400 text-red-500 focus:ring-red-400 opacity-0 group-hover:opacity-100 transition">
                                            <img src="{{ route('device.photos.show', $photo) }}" alt="Foto" class="w-full h-24 object-cover rounded-lg border border-slate-700/50 group-hover:border-red-400/50 transition">
                                            <span class="absolute bottom-1 left-1 text-[10px] bg-red-500/80 text-white px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition backdrop-blur-sm">Eliminar</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-xs text-slate-500">Marca las fotos que quieras eliminar.</p>
                            @endif
                            <div>
                                <label class="text-sm text-slate-400 font-medium">Agregar más fotos:</label>
                                <input type="file" name="photos[]" multiple accept="image/*" class="mt-1 text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-500/20 file:text-indigo-300 hover:file:bg-indigo-500/30 file:transition-all"
                                    @change="previews = [...$event.target.files].map(f => URL.createObjectURL(f))">
                                <div x-show="previews.length" class="grid grid-cols-4 gap-3 mt-3">
                                    <template x-for="(src, i) in previews" :key="i">
                                        <img :src="src" alt="Vista previa" class="w-full h-24 object-cover rounded-lg border border-slate-700/50">
                                    </template>
                                </div>
                                <x-input-error :messages="$errors->get('photos')" class="mt-2" />
                                <x-input-error :messages="collect($errors->get('photos.*'))->flatten()->all()" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-slate-700/50 gap-4">
                            <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-device-deletion')" class="inline-flex items-center px-4 py-2 bg-slate-800/60 backdrop-blur-sm border border-red-500/30 rounded-lg justify-center font-semibold text-xs text-red-400 uppercase tracking-wider hover:bg-red-500/20 hover:border-red-500/50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                {{ __('Eliminar Dispositivo') }}
                            </button>

                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 border border-transparent rounded-xl justify-center font-bold text-sm text-white uppercase tracking-wider hover:from-amber-400 hover:to-orange-400 shadow-lg shadow-amber-500/30 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all hover:scale-[1.02]">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ __('Actualizar Dispositivo') }}
                            </button>
                        </div>
                    </form>

                     <x-modal name="confirm-device-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                        <form method="post" action="{{ route('devices.destroy', $device) }}" class="p-6">
                            @csrf
                            @method('delete')

                            <h2 class="text-lg font-medium text-slate-200">
                                {{ __('¿Estás seguro de que quieres eliminar este dispositivo?') }}
                            </h2>

                            <p class="mt-1 text-sm text-slate-400">
                                {{ __('Una vez eliminado, toda la información y asignaciones asociadas se eliminarán permanentemente.') }}
                            </p>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    {{ __('Cancelar') }}
                                </x-secondary-button>

                                <x-danger-button class="ml-3">
                                    {{ __('Eliminar Dispositivo') }}
                                </x-danger-button>
                            </div>
                        </form>
                    </x-modal>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
