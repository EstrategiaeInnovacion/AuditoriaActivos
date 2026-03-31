<?php

namespace App\Livewire;

use App\Enums\DeviceStatus;
use App\Models\Assignment;
use App\Models\Device;
use App\Models\Employee;
use App\Services\AuditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class QrScanner extends Component
{
    public $scannedCode = null;

    public $device = null;

    public $message = '';

    public $messageType = 'info';

    public $isScanning = false;

    public $quickMode = false;

    public $showAssignForm = false;

    public $employees = [];

    public $selectedEmployeeId = '';

    public $assignmentType = 'asignacion_fija';

    public $expectedReturnDate = '';

    public $deliveryConditions = '';

    public function mount(): void
    {
        $this->loadEmployees();
    }

    public function loadEmployees(): void
    {
        $apiKey = config('app.erp_api_key');
        $erpUrl = config('app.erp_api_url').'/api/v1/users';

        try {
            $response = Http::timeout(10)->withHeaders([
                'X-API-Key' => $apiKey,
            ])->get($erpUrl);

            $data = $response->json();
            if ($response->successful() && $data !== null && isset($data['data'])) {
                foreach ($data['data'] as $emp) {
                    Employee::updateOrCreate(
                        ['employee_id' => $emp['employee_id'] ?? $emp['id']],
                        [
                            'name' => $emp['name'],
                            'department' => $emp['department'] ?? null,
                            'position' => $emp['position'] ?? null,
                            'phone' => $emp['phone'] ?? null,
                            'is_active' => true,
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            Log::warning('ERP API connection failed, using local employees', [
                'url' => $erpUrl,
                'error' => $e->getMessage(),
            ]);
        }

        $this->employees = Employee::where('is_active', true)->orderBy('name')->get()->toArray();
    }

    public function startScanning(): void
    {
        $this->isScanning = true;
        $this->dispatch('scanner-started');
    }

    public function toggleQuickMode(): void
    {
        $this->quickMode = ! $this->quickMode;
    }

    public function processQr(?string $qrCode): void
    {
        try {
            $this->isScanning = false;

            if (empty($qrCode)) {
                $this->setMessage('Código QR vacío o inválido.', 'error');

                return;
            }

            $uuid = $this->extractUuidFromQr($qrCode);

            if ($uuid === null) {
                $this->setMessage('El código QR no tiene un formato válido.', 'error');
                Log::warning('Invalid QR code format', ['qr_code' => $qrCode]);

                return;
            }

            $this->device = Device::with('currentAssignment.user')->where('uuid', $uuid)->first();

            if ($this->device) {
                $this->scannedCode = $qrCode;
                $this->setMessage('¡Equipo encontrado!', 'success');
                Log::info('Device scanned successfully', ['device_uuid' => $uuid]);
            } else {
                $this->setMessage('Código QR no reconocido en el sistema.', 'error');
                Log::warning('Unknown device UUID scanned', ['uuid' => $uuid]);
            }
        } catch (\Exception $e) {
            $this->setMessage('Error al procesar el código QR. Intente nuevamente.', 'error');
            Log::error('QR processing error', [
                'qr_code' => $qrCode ?? 'null',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    protected function extractUuidFromQr(string $qrCode): ?string
    {
        $trimmed = trim($qrCode);

        if (filter_var($trimmed, FILTER_VALIDATE_URL)) {
            $parsed = parse_url($trimmed, PHP_URL_PATH);

            return $parsed ? basename($parsed) : null;
        }

        if (str_starts_with($trimmed, '/')) {
            return basename($trimmed);
        }

        if (preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $trimmed)) {
            return $trimmed;
        }

        return basename($qrCode);
    }

    public function toggleAssignForm(): void
    {
        $this->showAssignForm = ! $this->showAssignForm;
    }

    public function saveAssignment(): void
    {
        try {
            if (! $this->device) {
                $this->setMessage('No hay dispositivo seleccionado.', 'error');

                return;
            }

            if (! $this->validateDeviceStateForAssignment()) {
                return;
            }

            $this->validate([
                'selectedEmployeeId' => 'required',
                'assignmentType' => 'required|in:asignacion_fija,prestamo_temporal',
                'expectedReturnDate' => 'required_if:assignmentType,prestamo_temporal|nullable|date|after_or_equal:today',
            ]);

            $employee = collect($this->employees)->firstWhere('id', $this->selectedEmployeeId);

            DB::transaction(function () use ($employee) {
                Assignment::create([
                    'employee_id' => $this->selectedEmployeeId,
                    'device_id' => $this->device->id,
                    'assigned_to' => $employee['name'] ?? 'Empleado desconocido',
                    'assigned_at' => now(),
                    'notes' => $this->deliveryConditions.($this->assignmentType === 'prestamo_temporal' ? " (Devolución esperada: {$this->expectedReturnDate})" : ''),
                ]);

                $this->device->update(['status' => DeviceStatus::Assigned->value]);
            });

            AuditService::deviceAssigned($this->device->id, $this->selectedEmployeeId, $employee['name'] ?? 'Desconocido');
            Log::info('Device assigned via QR scanner', [
                'device_id' => $this->device->id,
                'employee_id' => $this->selectedEmployeeId,
            ]);

            $this->resetScanner();
            $this->setMessage('¡Equipo asignado correctamente!', 'success');
            $this->scannedCode = 'success_screen';

            if ($this->quickMode) {
                $this->dispatch('auto-scan-next');
            }
        } catch (ValidationException $e) {
            $this->setMessage('Verifique los datos ingresados.', 'error');
            throw $e;
        } catch (\Exception $e) {
            $this->setMessage('Error al asignar el equipo. Intente nuevamente.', 'error');
            Log::error('Assignment error', [
                'device_id' => $this->device?->id,
                'employee_id' => $this->selectedEmployeeId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function returnDevice(): void
    {
        try {
            if (! $this->device) {
                $this->setMessage('No hay dispositivo seleccionado.', 'error');

                return;
            }

            if (! $this->validateDeviceStateForReturn()) {
                return;
            }

            DB::transaction(function () {
                $currentAssignment = $this->device->currentAssignment;
                if ($currentAssignment) {
                    $currentAssignment->update(['returned_at' => now()]);
                }

                $this->device->update(['status' => DeviceStatus::Available->value]);
            });

            AuditService::deviceReturned($this->device->id);
            Log::info('Device returned via QR scanner', ['device_id' => $this->device->id]);

            $this->resetScanner();
            $this->setMessage('¡Equipo devuelto correctamente!', 'success');
            $this->scannedCode = 'success_screen';

            if ($this->quickMode) {
                $this->dispatch('auto-scan-next');
            }
        } catch (\Exception $e) {
            $this->setMessage('Error al devolver el equipo. Intente nuevamente.', 'error');
            Log::error('Return device error', [
                'device_id' => $this->device?->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function validateDeviceStateForAssignment(): bool
    {
        if (! $this->device) {
            return false;
        }

        $currentStatus = DeviceStatus::tryFrom($this->device->status);

        if ($currentStatus === null) {
            $this->setMessage('Estado del dispositivo desconocido.', 'error');
            Log::warning('Unknown device status', [
                'device_id' => $this->device->id,
                'status' => $this->device->status,
            ]);

            return false;
        }

        if ($currentStatus !== DeviceStatus::Available) {
            $statusLabel = $currentStatus->label();
            $this->setMessage("No se puede asignar: el dispositivo está '{$statusLabel}'.", 'error');
            Log::warning('Assignment blocked - device not available', [
                'device_id' => $this->device->id,
                'current_status' => $currentStatus->value,
            ]);

            return false;
        }

        return true;
    }

    protected function validateDeviceStateForReturn(): bool
    {
        if (! $this->device) {
            return false;
        }

        $currentStatus = DeviceStatus::tryFrom($this->device->status);

        if ($currentStatus === null) {
            $this->setMessage('Estado del dispositivo desconocido.', 'error');

            return false;
        }

        if ($currentStatus !== DeviceStatus::Assigned) {
            $statusLabel = $currentStatus->label();
            $this->setMessage("El dispositivo no puede ser devuelto: estado actual '{$statusLabel}'.", 'error');
            Log::warning('Return blocked - device not assigned', [
                'device_id' => $this->device->id,
                'current_status' => $currentStatus->value,
            ]);

            return false;
        }

        return true;
    }

    protected function setMessage(string $message, string $type = 'info'): void
    {
        $this->message = $message;
        $this->messageType = $type;
    }

    public function resetScanner(): void
    {
        $quickMode = $this->quickMode;
        $this->reset(['scannedCode', 'device', 'message', 'messageType', 'showAssignForm', 'selectedEmployeeId', 'assignmentType', 'expectedReturnDate', 'deliveryConditions']);
        $this->quickMode = $quickMode;
    }

    public function render()
    {
        return view('livewire.qr-scanner');
    }
}
