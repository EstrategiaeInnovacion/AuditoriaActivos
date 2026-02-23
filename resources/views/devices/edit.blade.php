<x-app-layout title="Editar Activo">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Dispositivo') }}: {{ $device->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('devices.update', $device) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Información General</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Nombre del Dispositivo')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $device->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Brand -->
                            <div>
                                <x-input-label for="brand" :value="__('Marca')" />
                                <x-text-input id="brand" class="block mt-1 w-full" type="text" name="brand" :value="old('brand', $device->brand)" required />
                                <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                            </div>

                            <!-- Model -->
                            <div>
                                <x-input-label for="model" :value="__('Modelo')" />
                                <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="old('model', $device->model)" required />
                                <x-input-error :messages="$errors->get('model')" class="mt-2" />
                            </div>

                            <!-- Serial Number -->
                            <div>
                                <x-input-label for="serial_number" :value="__('Número de Serie')" />
                                <x-text-input id="serial_number" class="block mt-1 w-full" type="text" name="serial_number" :value="old('serial_number', $device->serial_number)" required />
                                <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
                            </div>

                            <!-- Type -->
                            <div>
                                <x-input-label for="type" :value="__('Tipo de Activo')" />
                                <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="computer" {{ old('type', $device->type) == 'computer' ? 'selected' : '' }}>Computadora / Laptop</option>
                                    <option value="peripheral" {{ old('type', $device->type) == 'peripheral' ? 'selected' : '' }}>Periférico</option>
                                    <option value="printer" {{ old('type', $device->type) == 'printer' ? 'selected' : '' }}>Impresora</option>
                                    <option value="other" {{ old('type', $device->type) == 'other' ? 'selected' : '' }}>Otro</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Estado Actual')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="available" {{ old('status', $device->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                                    <option value="assigned" {{ old('status', $device->status) == 'assigned' ? 'selected' : '' }}>Asignado</option>
                                    <option value="maintenance" {{ old('status', $device->status) == 'maintenance' ? 'selected' : '' }}>En Mantenimiento</option>
                                    <option value="broken" {{ old('status', $device->status) == 'broken' ? 'selected' : '' }}>Averiado</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Purchase Date -->
                            <div>
                                <x-input-label for="purchase_date" :value="__('Fecha de Compra')" />
                                <x-text-input id="purchase_date" class="block mt-1 w-full" type="date" name="purchase_date" :value="old('purchase_date', $device->purchase_date ? $device->purchase_date : '')" />
                                <x-input-error :messages="$errors->get('purchase_date')" class="mt-2" />
                            </div>

                            <!-- Warranty Expiration -->
                            <div>
                                <x-input-label for="warranty_expiration" :value="__('Vencimiento de Garantía')" />
                                <x-text-input id="warranty_expiration" class="block mt-1 w-full" type="date" name="warranty_expiration" :value="old('warranty_expiration', $device->warranty_expiration ? $device->warranty_expiration : '')" />
                                <x-input-error :messages="$errors->get('warranty_expiration')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notas')" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $device->notes) }}</textarea>
                             <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <hr class="my-6 border-gray-300">

                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Credenciales (Opcional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Username -->
                            <div>
                                <x-input-label for="username" :value="__('Usuario del Equipo')" />
                                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username', $device->credential->username ?? '')" />
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div>
                                <x-input-label for="password" :value="__('Contraseña del Equipo')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" :value="old('password', $device->credential->password ?? '')" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Cuenta de Correo')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $device->credential->email ?? '')" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Email Password -->
                            <div>
                                <x-input-label for="email_password" :value="__('Contraseña del Correo')" />
                                <x-text-input id="email_password" class="block mt-1 w-full" type="password" name="email_password" :value="old('email_password', $device->credential->email_password ?? '')" />
                                <x-input-error :messages="$errors->get('email_password')" class="mt-2" />
                            </div>
                        </div>

                        <hr class="my-6 border-gray-300">

                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Fotos del Equipo</h3>
                        <div x-data="{ previews: [] }" class="space-y-4">
                            @if($device->photos->count())
                                <div class="grid grid-cols-4 gap-3">
                                    @foreach($device->photos as $photo)
                                        <label class="relative group cursor-pointer">
                                            <input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" class="absolute top-1 right-1 z-10 rounded border-red-400 text-red-500 focus:ring-red-400 opacity-0 group-hover:opacity-100 transition">
                                            <img src="{{ Storage::disk('private')->url($photo->file_path) }}" alt="Foto" class="w-full h-24 object-cover rounded-lg border border-slate-200 group-hover:border-red-300 transition">
                                            <span class="absolute bottom-1 left-1 text-[10px] bg-red-500 text-white px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition">Marcar para eliminar</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-xs text-slate-400">Marca las fotos que quieras eliminar.</p>
                            @endif
                            <div>
                                <label class="text-sm text-slate-600 font-medium">Agregar más fotos:</label>
                                <input type="file" name="photos[]" multiple accept="image/*" class="mt-1 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                    @change="previews = [...$event.target.files].map(f => URL.createObjectURL(f))">
                                <div x-show="previews.length" class="grid grid-cols-4 gap-3 mt-3">
                                    <template x-for="(src, i) in previews" :key="i">
                                        <img :src="src" class="w-full h-24 object-cover rounded-lg border border-slate-200">
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-device-deletion')" class="mr-3">
                                {{ __('Eliminar Dispositivo') }}
                            </x-secondary-button>

                            <x-primary-button class="ml-4">
                                {{ __('Actualizar Dispositivo') }}
                            </x-primary-button>
                        </div>
                    </form>

                     <x-modal name="confirm-device-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                        <form method="post" action="{{ route('devices.destroy', $device) }}" class="p-6">
                            @csrf
                            @method('delete')

                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('¿Estás seguro de que quieres eliminar este dispositivo?') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
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
