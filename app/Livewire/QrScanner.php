<?php

namespace App\Livewire;

use App\Models\Assignment;
use App\Models\Device;
use App\Models\Employee;
use App\Notifications\DeviceAssigned;
use App\Services\AuditService;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class QrScanner extends Component
{
    public $scannedCode = null;

    public $device = null;

    public $message = '';

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
        $erpUrl = config('app.erp_api_url') . '/api/v1/users';

        try {
            $response = Http::timeout(10)->withHeaders([
                'X-API-Key' => $apiKey,
            ])->get($erpUrl);
            
            $data = $response->json();
            if ($response->successful() && $data !== null && isset($data['data'])) {
                // Sincronizar empleados con la base de datos local
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
            // Si falla la conexión, usar empleados locales
        }

        // Cargar empleados desde la base de datos local
        $this->employees = Employee::where('is_active', true)->orderBy('name')->get()->toArray();
    }

    public function startScanning(): void
    {
        // $this->authorize('accessQrScanner');
        $this->isScanning = true;
        $this->dispatch('scanner-started');
    }

    public function toggleQuickMode(): void
    {
        $this->quickMode = ! $this->quickMode;
    }

    public function processQr($qrCode): void
    {
        // $this->authorize('accessQrScanner');

        $this->isScanning = false;
        $this->scannedCode = $qrCode;

        $parsed = parse_url($qrCode, PHP_URL_PATH);
        $uuid = $parsed ? basename($parsed) : basename($qrCode);

        $this->device = Device::with('currentAssignment.user')->where('uuid', $uuid)->first();

        if ($this->device) {
            $this->message = '¡Equipo encontrado!';
        } else {
            $this->message = 'Código QR no reconocido en el sistema.';
        }
    }

    public function toggleAssignForm(): void
    {
        // $this->authorize('accessQrScanner');
        $this->showAssignForm = ! $this->showAssignForm;
    }

    public function saveAssignment(): void
    {
        // $this->authorize('accessQrScanner');

        $this->validate([
            'selectedEmployeeId' => 'required',
            'assignmentType' => 'required|in:asignacion_fija,prestamo_temporal',
            'expectedReturnDate' => 'required_if:assignmentType,prestamo_temporal|nullable|date',
        ]);

        $employee = collect($this->employees)->firstWhere('id', $this->selectedEmployeeId);

        $assignment = Assignment::create([
            'employee_id' => $this->selectedEmployeeId,
            'device_id' => $this->device->id,
            'assigned_to' => $employee['name'] ?? 'Empleado desconocido',
            'assigned_at' => now(),
            'notes' => $this->deliveryConditions . ($this->assignmentType === 'prestamo_temporal' ? " (Devolución esperada: {$this->expectedReturnDate})" : ''),
        ]);

        $this->device->update(['status' => 'assigned']);

        AuditService::deviceAssigned($this->device->id, $this->selectedEmployeeId, $employee['name'] ?? 'Desconocido');

        $this->resetScanner();
        $this->message = '¡Equipo asignado correctamente!';
        $this->scannedCode = 'success_screen';

        if ($this->quickMode) {
            $this->dispatch('auto-scan-next');
        }
    }

    public function returnDevice(): void
    {
        // $this->authorize('accessQrScanner');

        if (! $this->device) {
            return;
        }

        $currentAssignment = $this->device->currentAssignment;
        if ($currentAssignment) {
            $currentAssignment->update(['returned_at' => now()]);
        }

        $this->device->update(['status' => 'available']);

        AuditService::deviceReturned($this->device->id);

        $this->resetScanner();
        $this->message = '¡Equipo devuelto correctamente!';
        $this->scannedCode = 'success_screen';

        if ($this->quickMode) {
            $this->dispatch('auto-scan-next');
        }
    }

    public function resetScanner(): void
    {
        $quickMode = $this->quickMode;
        $this->reset(['scannedCode', 'device', 'message', 'showAssignForm', 'selectedEmployeeId', 'assignmentType', 'expectedReturnDate', 'deliveryConditions']);
        $this->quickMode = $quickMode;
    }

    public function render()
    {
        return view('livewire.qr-scanner');
    }
}
