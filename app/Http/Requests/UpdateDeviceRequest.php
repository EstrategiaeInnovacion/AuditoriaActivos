<?php

namespace App\Http\Requests;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'serial_number' => ['required', 'string', Rule::unique('devices', 'serial_number')->ignore($this->route('device'))],
            'type' => ['required', 'string', Rule::in(DeviceType::values())],
            'status' => ['required', 'string', Rule::in(DeviceStatus::values())],
            'purchase_date' => ['nullable', 'date'],
            'warranty_expiration' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'email_password' => ['nullable', 'string', 'max:255'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['nullable', 'image', 'max:20480'],
            'delete_photos' => ['nullable', 'array'],
            'delete_photos.*' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'serial_number.unique' => 'Ya existe un dispositivo con este número de serie.',
            'type.in' => 'El tipo de dispositivo seleccionado no es válido.',
            'status.in' => 'El estado seleccionado no es válido.',
        ];
    }
}
