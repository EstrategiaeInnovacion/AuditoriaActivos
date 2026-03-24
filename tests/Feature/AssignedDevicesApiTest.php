<?php

namespace Tests\Feature;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Assignment;
use App\Models\Device;
use App\Models\DevicePhoto;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AssignedDevicesApiTest extends TestCase
{
    use RefreshDatabase;

    private const API_KEY = 'xpOaPnlnQirvPWiR2MDaBtNsur6j7m3Z4dnl0iK/lVc=';

    private function apiHeaders(): array
    {
        return ['X-API-Key' => self::API_KEY];
    }

    // ── Device Types ────────────────────────────────────────────────────────

    public function test_device_types_returns_only_computer_and_peripheral(): void
    {
        $response = $this->getJson('/api/v1/device-types', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonPath('success', true);

        $values = collect($response->json('data'))->pluck('value')->all();

        $this->assertContains(DeviceType::Computer->value, $values);
        $this->assertContains(DeviceType::Peripheral->value, $values);
        $this->assertNotContains(DeviceType::Printer->value, $values);
        $this->assertNotContains(DeviceType::Other->value, $values);
        $this->assertCount(2, $values);
    }

    public function test_device_types_requires_api_key(): void
    {
        $this->getJson('/api/v1/device-types')
            ->assertUnauthorized();
    }

    public function test_device_types_rejects_invalid_api_key(): void
    {
        $this->getJson('/api/v1/device-types', ['X-API-Key' => 'invalid'])
            ->assertUnauthorized();
    }

    // ── Assigned Devices ────────────────────────────────────────────────────

    public function test_assigned_devices_returns_active_assignments_by_assigned_to(): void
    {
        $device = Device::factory()->computer()->assigned()->create();
        Assignment::factory()->create([
            'device_id' => $device->id,
            'assigned_to' => 'Juan Pérez',
        ]);

        $response = $this->getJson('/api/v1/assigned-devices/Juan Pérez', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('total', 1)
            ->assertJsonPath('data.0.device.uuid', $device->uuid)
            ->assertJsonPath('data.0.device.type_label', DeviceType::Computer->label());
    }

    public function test_assigned_devices_returns_active_assignments_by_employee_name(): void
    {
        $employee = Employee::factory()->create(['name' => 'María López']);
        $device = Device::factory()->peripheral()->assigned()->create();
        Assignment::factory()->forEmployee($employee)->create(['device_id' => $device->id]);

        $response = $this->getJson('/api/v1/assigned-devices/María', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('total', 1)
            ->assertJsonPath('data.0.device.uuid', $device->uuid)
            ->assertJsonPath('data.0.employee.name', 'María López');
    }

    public function test_assigned_devices_returns_active_assignments_by_employee_id(): void
    {
        $employee = Employee::factory()->create(['employee_id' => 'EMP-1234']);
        $device = Device::factory()->assigned()->create();
        Assignment::factory()->forEmployee($employee)->create(['device_id' => $device->id]);

        $response = $this->getJson('/api/v1/assigned-devices/EMP-1234', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonPath('data.0.employee.employee_id', 'EMP-1234');
    }

    public function test_assigned_devices_excludes_returned_assignments(): void
    {
        $device = Device::factory()->create();
        Assignment::factory()->returned()->create([
            'device_id' => $device->id,
            'assigned_to' => 'Carlos Ruiz',
        ]);

        $response = $this->getJson('/api/v1/assigned-devices/Carlos Ruiz', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('total', 0)
            ->assertJsonPath('data', []);
    }

    public function test_assigned_devices_returns_empty_with_message_when_not_found(): void
    {
        $response = $this->getJson('/api/v1/assigned-devices/NoExiste', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data', [])
            ->assertJsonStructure(['message']);
    }

    public function test_assigned_devices_requires_api_key(): void
    {
        $this->getJson('/api/v1/assigned-devices/JuanPérez')
            ->assertUnauthorized();
    }

    public function test_assigned_devices_response_includes_device_type_info(): void
    {
        $device = Device::factory()->create(['type' => DeviceType::Printer->value]);
        Assignment::factory()->create([
            'device_id' => $device->id,
            'assigned_to' => 'Ana Torres',
        ]);

        $response = $this->getJson('/api/v1/assigned-devices/Ana Torres', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonPath('data.0.device.type', DeviceType::Printer->value)
            ->assertJsonPath('data.0.device.type_label', DeviceType::Printer->label());
    }

    public function test_assigned_devices_includes_photos_array(): void
    {
        Storage::fake('private');

        $device = Device::factory()->computer()->assigned()->create();
        $file = UploadedFile::fake()->image('photo.jpg');
        $path = $file->store('device-photos', 'private');

        $photo = DevicePhoto::create([
            'device_id' => $device->id,
            'file_path' => $path,
            'caption' => 'Vista frontal',
            'uploaded_by' => null,
        ]);

        Assignment::factory()->create([
            'device_id' => $device->id,
            'assigned_to' => 'Luis Gómez',
        ]);

        $response = $this->getJson('/api/v1/assigned-devices/Luis Gómez', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'device' => [
                            'photos' => [
                                '*' => ['id', 'caption', 'url'],
                            ],
                        ],
                    ],
                ],
            ])
            ->assertJsonPath('data.0.device.photos.0.id', $photo->id)
            ->assertJsonPath('data.0.device.photos.0.caption', 'Vista frontal');

        $this->assertStringContainsString(
            "/api/v1/device-photos/{$photo->id}",
            $response->json('data.0.device.photos.0.url')
        );
    }

    public function test_device_photo_endpoint_serves_file(): void
    {
        Storage::fake('private');

        $device = Device::factory()->create();
        $file = UploadedFile::fake()->image('device.jpg');
        $path = $file->store('device-photos', 'private');

        $photo = DevicePhoto::create([
            'device_id' => $device->id,
            'file_path' => $path,
            'caption' => null,
            'uploaded_by' => null,
        ]);

        $this->getJson("/api/v1/device-photos/{$photo->id}", $this->apiHeaders())
            ->assertOk();
    }

    public function test_device_photo_endpoint_requires_api_key(): void
    {
        Storage::fake('private');

        $device = Device::factory()->create();
        $path = UploadedFile::fake()->image('x.jpg')->store('device-photos', 'private');

        $photo = DevicePhoto::create([
            'device_id' => $device->id,
            'file_path' => $path,
            'caption' => null,
            'uploaded_by' => null,
        ]);

        $this->getJson("/api/v1/device-photos/{$photo->id}")
            ->assertUnauthorized();
    }

    public function test_device_photo_endpoint_returns_404_for_missing_file(): void
    {
        Storage::fake('private');

        $device = Device::factory()->create();

        $photo = DevicePhoto::create([
            'device_id' => $device->id,
            'file_path' => 'device-photos/nonexistent.jpg',
            'caption' => null,
            'uploaded_by' => null,
        ]);

        $this->getJson("/api/v1/device-photos/{$photo->id}", $this->apiHeaders())
            ->assertNotFound();
    }

    // ── All Devices ─────────────────────────────────────────────────────────

    public function test_all_devices_returns_computer_and_peripheral_only(): void
    {
        Device::factory()->computer()->create();
        Device::factory()->peripheral()->create();
        Device::factory()->create(['type' => DeviceType::Printer->value]);
        Device::factory()->create(['type' => DeviceType::Other->value]);

        $response = $this->getJson('/api/v1/devices', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('total', 2);

        $types = collect($response->json('data'))->pluck('type')->unique()->values()->all();
        sort($types);

        $this->assertEquals([DeviceType::Computer->value, DeviceType::Peripheral->value], $types);
    }

    public function test_all_devices_excludes_non_available_devices(): void
    {
        $available = Device::factory()->computer()->create(['status' => DeviceStatus::Available->value]);
        Device::factory()->peripheral()->create(['status' => DeviceStatus::Maintenance->value]);
        Device::factory()->computer()->assigned()->create();
        Device::factory()->computer()->create(['status' => DeviceStatus::Broken->value]);

        $response = $this->getJson('/api/v1/devices', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonPath('total', 1);

        $uuids = collect($response->json('data'))->pluck('uuid')->all();
        $this->assertContains($available->uuid, $uuids);
    }

    public function test_all_devices_shows_null_assignment_for_unassigned(): void
    {
        Device::factory()->computer()->create();

        $response = $this->getJson('/api/v1/devices', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonPath('data.0.assignment', null);
    }

    public function test_all_devices_response_structure_is_correct(): void
    {
        Device::factory()->computer()->create(['status' => DeviceStatus::Available->value]);

        $response = $this->getJson('/api/v1/devices', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'uuid', 'name', 'brand', 'model', 'serial_number',
                        'type', 'type_label', 'status', 'photos', 'assignment',
                    ],
                ],
            ]);
    }

    public function test_all_devices_requires_api_key(): void
    {
        $this->getJson('/api/v1/devices')
            ->assertUnauthorized();
    }

    // ── Assign Device (ERP) ─────────────────────────────────────────────────

    public function test_assign_device_creates_assignment_and_updates_status(): void
    {
        $device = Device::factory()->computer()->create(['status' => DeviceStatus::Available->value]);

        $response = $this->postJson("/api/v1/devices/{$device->uuid}/assign", [
            'assigned_to' => 'Roberto Sosa',
        ], $this->apiHeaders());

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('assigned_to', 'Roberto Sosa')
            ->assertJsonPath('status', DeviceStatus::Assigned->value)
            ->assertJsonPath('device.uuid', $device->uuid);

        $this->assertDatabaseHas('assignments', [
            'device_id' => $device->id,
            'assigned_to' => 'Roberto Sosa',
            'returned_at' => null,
        ]);

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'status' => DeviceStatus::Assigned->value,
        ]);
    }

    public function test_assign_device_links_employee_when_employee_id_provided(): void
    {
        $device = Device::factory()->peripheral()->create(['status' => DeviceStatus::Available->value]);
        $employee = Employee::factory()->create(['employee_id' => 'EMP-999']);

        $response = $this->postJson("/api/v1/devices/{$device->uuid}/assign", [
            'assigned_to' => $employee->name,
            'employee_id' => 'EMP-999',
        ], $this->apiHeaders());

        $response->assertCreated()
            ->assertJsonPath('employee.employee_id', 'EMP-999')
            ->assertJsonPath('employee.name', $employee->name);

        $this->assertDatabaseHas('assignments', [
            'device_id' => $device->id,
            'employee_id' => $employee->id,
        ]);
    }

    public function test_assign_device_returns_409_when_already_assigned(): void
    {
        $device = Device::factory()->computer()->assigned()->create();
        Assignment::factory()->create(['device_id' => $device->id, 'assigned_to' => 'Alguien']);

        $response = $this->postJson("/api/v1/devices/{$device->uuid}/assign", [
            'assigned_to' => 'Otro Usuario',
        ], $this->apiHeaders());

        $response->assertStatus(409)
            ->assertJsonPath('success', false);
    }

    public function test_assign_device_returns_404_for_unknown_uuid(): void
    {
        $this->postJson('/api/v1/devices/uuid-no-existe/assign', [
            'assigned_to' => 'Alguien',
        ], $this->apiHeaders())
            ->assertNotFound();
    }

    public function test_assign_device_returns_422_when_assigned_to_missing(): void
    {
        $device = Device::factory()->computer()->create();

        $this->postJson("/api/v1/devices/{$device->uuid}/assign", [], $this->apiHeaders())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['assigned_to']);
    }

    public function test_assign_device_requires_api_key(): void
    {
        $device = Device::factory()->computer()->create();

        $this->postJson("/api/v1/devices/{$device->uuid}/assign", [
            'assigned_to' => 'Alguien',
        ])->assertUnauthorized();
    }

    public function test_assign_device_rejects_printer_type(): void
    {
        $device = Device::factory()->create(['type' => DeviceType::Printer->value]);

        $this->postJson("/api/v1/devices/{$device->uuid}/assign", [
            'assigned_to' => 'Alguien',
        ], $this->apiHeaders())
            ->assertNotFound();
    }
}
