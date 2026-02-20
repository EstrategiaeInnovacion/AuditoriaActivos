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
    }

    public function processQr($qrCode)
    {
        $this->isScanning = false; // Stop scanning after successful scan
        $this->scannedCode = $qrCode;

        // Intentar extraer el UUID de la URL si es una URL válida
        $uuid = basename($qrCode);

        // Si no parece un UUID (ej. es una URL completa), intentamos buscar por UUID directamente si el string es solo el UUID
        // Pero asumimos que el QR tiene la URL completa route('devices.show', uuid)

        $this->device = Device::where('uuid', $uuid)->first();

        if ($this->device) {
            $this->message = "¡Equipo encontrado!";
            // Cargamos todos los usuarios para el select del formulario
            $this->users = User::orderBy('name')->get();
        }
        else {
            $this->message = "Código no reconocido en el sistema (UUID: $uuid).";
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

        // 2. Creamos el registro en la tabla assignments
        Assignment::create([
            'user_id' => $this->selectedUser,
            'device_id' => $this->device->id,
            'assigned_to' => User::find($this->selectedUser)->name, // Redundante pero guarda el nombre histórico
            'assigned_at' => now(),
            'notes' => $this->deliveryConditions . ($this->assignmentType === 'prestamo_temporal' ? " (Devolución esperada: {$this->expectedReturnDate})" : ''),
        ]);

        // 3. Actualizamos el estado del equipo físico
        $newStatus = 'assigned'; // El modelo usa 'assigned'
        $this->device->update(['status' => $newStatus]);

        // 4. Reiniciamos la pantalla con mensaje de éxito
        $this->resetScanner();
        $this->message = "¡Equipo asignado correctamente!";
        // Forzamos a que el mensaje se quede visible un momento en la pantalla principal
        $this->scannedCode = 'success_screen';
    }

    public function resetScanner()
    {
        $this->reset(['scannedCode', 'device', 'message', 'showAssignForm', 'selectedUser', 'assignmentType', 'expectedReturnDate', 'deliveryConditions']);
    }

    public function render()
    {
        return view('livewire.qr-scanner');
    }
}