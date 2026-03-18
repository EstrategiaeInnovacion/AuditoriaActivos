<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Device;
use App\Models\User;
use App\Models\Assignment;

class QrScanner extends Component
{
    public $scannedCode = null;
    public $device = null;
    public $message = '';
    public $isScanning = false;
    public $quickMode = false;

    // Variables para el formulario de asignación
    public $showAssignForm = false;
    public $users = [];
    public $selectedUser = '';
    public $assignmentType = 'asignacion_fija';
    public $expectedReturnDate = '';
    public $deliveryConditions = '';

    public function startScanning()
    {
        $this->isScanning = true;
        $this->dispatch('scanner-started');
    }

    public function toggleQuickMode()
    {
        $this->quickMode = !$this->quickMode;
    }

    public function processQr($qrCode)
    {
        $this->isScanning = false; // Stop scanning after successful scan
        $this->scannedCode = $qrCode;

        // Extraer el UUID de la URL del QR de forma segura
        $parsed = parse_url($qrCode, PHP_URL_PATH);
        $uuid = $parsed ? basename($parsed) : basename($qrCode);

        $this->device = Device::with('currentAssignment.user')->where('uuid', $uuid)->first();

        if ($this->device) {
            $this->message = "¡Equipo encontrado!";
            $this->users = User::orderBy('name')->get();
        }
        else {
            $this->message = "Código QR no reconocido en el sistema.";
        }
    }

    // Muestra u oculta el formulario
    public function toggleAssignForm()
    {
        $this->showAssignForm = !$this->showAssignForm;
    }

    // Guarda la asignación en la base de datos
    public function saveAssignment()
    {
        // 1. Validamos que los datos estén correctos
        $this->validate([
            'selectedUser' => 'required|exists:users,id',
            'assignmentType' => 'required|in:asignacion_fija,prestamo_temporal',
            // La fecha solo es obligatoria si es un préstamo temporal
            'expectedReturnDate' => 'required_if:assignmentType,prestamo_temporal|nullable|date',
        ]);

        // 2. Buscar usuario asignado (una sola vez)
        $assignedUser = User::find($this->selectedUser);

        // 3. Creamos el registro en la tabla assignments
        $assignment = Assignment::create([
            'user_id' => $this->selectedUser,
            'device_id' => $this->device->id,
            'assigned_to' => $assignedUser->name,
            'assigned_at' => now(),
            'notes' => $this->deliveryConditions . ($this->assignmentType === 'prestamo_temporal' ? " (Devolución esperada: {$this->expectedReturnDate})" : ''),
        ]);

        // 4. Actualizamos el estado del equipo físico
        $this->device->update(['status' => 'assigned']);

        // 5. Notificar al usuario asignado
        if ($assignedUser) {
            $assignment->load('device');
            $assignedUser->notify(new \App\Notifications\DeviceAssigned($assignment));
        }

        // 4. Reiniciamos la pantalla con mensaje de éxito
        $this->resetScanner();
        $this->message = "¡Equipo asignado correctamente!";
        $this->scannedCode = 'success_screen';

        if ($this->quickMode) {
            $this->dispatch('auto-scan-next');
        }
    }

    public function returnDevice()
    {
        if (!$this->device)
            return;

        $currentAssignment = $this->device->currentAssignment;
        if ($currentAssignment) {
            $currentAssignment->update(['returned_at' => now()]);
        }

        $this->device->update(['status' => 'available']);

        $this->resetScanner();
        $this->message = "¡Equipo devuelto correctamente!";
        $this->scannedCode = 'success_screen';

        if ($this->quickMode) {
            $this->dispatch('auto-scan-next');
        }
    }

    public function resetScanner()
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