<x-app-layout title="Crear Activo">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Nuevo Activo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-8 text-slate-900">
                    <form method="POST" action="{{ route('devices.store') }}" enctype="multipart/form-data">
                        @csrf

                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Información General</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Nombre del Dispositivo')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Brand -->
                            <div>
                                <x-input-label for="brand" :value="__('Marca')" />
                                <x-text-input id="brand" class="block mt-1 w-full" type="text" name="brand" :value="old('brand')" required />
                                <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                            </div>

                            <!-- Model -->
                            <div>
                                <x-input-label for="model" :value="__('Modelo')" />
                                <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="old('model')" required />
                                <x-input-error :messages="$errors->get('model')" class="mt-2" />
                            </div>

                            <!-- Serial Number -->
                            <div>
                                <x-input-label for="serial_number" :value="__('Número de Serie')" />
                                <x-text-input id="serial_number" class="block mt-1 w-full" type="text" name="serial_number" :value="old('serial_number')" required />
                                <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
                            </div>

                            <!-- Type -->
                            <div>
                                <x-input-label for="type" :value="__('Tipo de Activo')" />
                                <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="computer" {{ old('type') == 'computer' ? 'selected' : '' }}>Computadora / Laptop</option>
                                    <option value="peripheral" {{ old('type') == 'peripheral' ? 'selected' : '' }}>Periférico</option>
                                    <option value="printer" {{ old('type') == 'printer' ? 'selected' : '' }}>Impresora</option>
                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Otro</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Estado Inicial')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                                    <option value="assigned" {{ old('status') == 'assigned' ? 'selected' : '' }}>Asignado</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>En Mantenimiento</option>
                                    <option value="broken" {{ old('status') == 'broken' ? 'selected' : '' }}>Averiado</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Purchase Date -->
                            <div>
                                <x-input-label for="purchase_date" :value="__('Fecha de Compra')" />
                                <x-text-input id="purchase_date" class="block mt-1 w-full" type="date" name="purchase_date" :value="old('purchase_date')" />
                                <x-input-error :messages="$errors->get('purchase_date')" class="mt-2" />
                            </div>

                            <!-- Warranty Expiration -->
                            <div>
                                <x-input-label for="warranty_expiration" :value="__('Vencimiento de Garantía')" />
                                <x-text-input id="warranty_expiration" class="block mt-1 w-full" type="date" name="warranty_expiration" :value="old('warranty_expiration')" />
                                <x-input-error :messages="$errors->get('warranty_expiration')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notas Adicionales')" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                             <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <hr class="my-6 border-gray-300">

                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Credenciales (Opcional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Username -->
                            <div>
                                <x-input-label for="username" :value="__('Usuario del Equipo')" />
                                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" />
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div>
                                <x-input-label for="password" :value="__('Contraseña del Equipo')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" :value="old('password')" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Cuenta de Correo')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Email Password -->
                            <div>
                                <x-input-label for="email_password" :value="__('Contraseña del Correo')" />
                                <x-text-input id="email_password" class="block mt-1 w-full" type="password" name="email_password" :value="old('email_password')" />
                                <x-input-error :messages="$errors->get('email_password')" class="mt-2" />
                            </div>
                        </div>

                        <hr class="my-6 border-gray-300">

                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Fotos del Equipo (Opcional)</h3>
                        <div x-data="{ previews: [] }" class="space-y-4">
                            <input type="file" name="photos[]" multiple accept="image/*" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                @change="previews = [...$event.target.files].map(f => URL.createObjectURL(f))">
                            <div x-show="previews.length" class="grid grid-cols-4 gap-3">
                                <template x-for="(src, i) in previews" :key="i">
                                    <img :src="src" class="w-full h-24 object-cover rounded-lg border border-slate-200">
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-slate-100">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-cyan-500 border border-transparent rounded-lg justify-center font-bold text-sm text-white uppercase tracking-widest hover:from-indigo-400 hover:to-cyan-400 shadow-lg shadow-cyan-500/30 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-all hover:scale-105">
                                {{ __('Guardar Dispositivo') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
