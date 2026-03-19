<?php

namespace App\Livewire;

use App\Models\Assignment;
use App\Models\Device;
use App\Models\User;
use App\Notifications\DeviceAssigned;
use App\Services\AuditService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class QrScanner extends Component
{
    public $scannedCode = null;

    public $device = null;

    public $message = '';

    public $isScanning = false;

    public $quickMode = false;

    public $showAssignForm = false;

    public $users = [];

    public $selectedUser = '';

    public $assignmentType = 'asignacion_fija';

    public $expectedReturnDate = '';

    public $deliveryConditions = '';

    public function mount(): void
    {
        $this->loadUsers();
    }

    public function loadUsers(): void
    {
        $this->users = Cache::remember('active_users_list', 3600, function () {
            return User::orderBy('name')->get();
        });
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
            'selectedUser' => 'required|exists:users,id',
            'assignmentType' => 'required|in:asignacion_fija,prestamo_temporal',
            'expectedReturnDate' => 'required_if:assignmentType,prestamo_temporal|nullable|date',
        ]);

        $assignedUser = User::find($this->selectedUser);

        $assignment = Assignment::create([
            'user_id' => $this->selectedUser,
            'device_id' => $this->device->id,
            'assigned_to' => $assignedUser->name,
            'assigned_at' => now(),
            'notes' => $this->deliveryConditions.($this->assignmentType === 'prestamo_temporal' ? " (Devolución esperada: {$this->expectedReturnDate})" : ''),
        ]);

        $this->device->update(['status' => 'assigned']);

        if ($assignedUser) {
            $assignment->load('device');
            $assignedUser->notify(new DeviceAssigned($assignment));
        }

        AuditService::deviceAssigned($this->device->id, $assignedUser->id, $assignedUser->name);

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
        $this->reset(['scannedCode', 'device', 'message', 'showAssignForm', 'selectedUser', 'assignmentType', 'expectedReturnDate', 'deliveryConditions']);
        $this->quickMode = $quickMode;
    }

    public function render()
    {
        return view('livewire.qr-scanner');
    }
}
