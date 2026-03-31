<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Volt\Volt;
use Tests\TestCase;

class QrScannerRobustnessTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_qr_with_invalid_format_handles_gracefully(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $component = Volt::test('qr-scanner');

        $component->call('processQr', 'invalid-format-not-uuid');

        $this->assertStringContainsString('Código QR no reconocido en el sistema.', $component->get('message'));
        $this->assertEquals('error', $component->get('messageType'));

        $component->call('processQr', '');

        $this->assertStringContainsString('Código QR vacío o inválido.', $component->get('message'));
        $this->assertEquals('error', $component->get('messageType'));

        $component->call('processQr', null);

        $this->assertStringContainsString('Código QR vacío o inválido.', $component->get('message'));
        $this->assertEquals('error', $component->get('messageType'));
    }

    public function test_save_assignment_validates_device_status(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $device = Device::factory()->create(['status' => 'assigned']);

        Employee::factory()->create(['is_active' => true, 'id' => 1]);

        $component = Volt::test('qr-scanner');
        $component->set('device', $device);
        $component->set('selectedEmployeeId', '1');
        $component->set('assignmentType', 'asignacion_fija');

        $component->call('saveAssignment');

        $this->assertStringContainsString("No se puede asignar: el dispositivo está 'Asignado'.", $component->get('message'));
        $this->assertEquals('error', $component->get('messageType'));
    }

    public function test_return_device_only_works_for_assigned_devices(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $device = Device::factory()->create(['status' => 'available']);

        $component = Volt::test('qr-scanner');
        $component->set('device', $device);

        $result = $component->call('returnDevice');

        $this->assertStringContainsString('no puede ser devuelto', $component->get('message'));
        $this->assertEquals('error', $component->get('messageType'));
    }

    public function test_load_employees_fallback_to_local_on_api_failure(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Http::fake([
            config('app.erp_api_url').'/api/v1/users' => Http::throw(),
        ]);

        $localEmployee = Employee::factory()->create(['name' => 'Local Employee', 'is_active' => true]);

        $component = Volt::test('qr-scanner');

        $component->call('loadEmployees');

        $employees = $component->get('employees');
        $this->assertNotEmpty($employees);
        $this->assertTrue(
            collect($employees)->contains('name', 'Local Employee'),
            'Expected to find Local Employee in fallback employees'
        );
    }
}
