<?php

namespace Tests\Unit\Services;

use App\Models\Assignment;
use App\Models\Device;
use App\Models\User;
use App\Services\DeviceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use RuntimeException;
use Tests\TestCase;

class DeviceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DeviceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DeviceService;
    }

    public function test_delete_device_with_active_assignments_throws_exception(): void
    {
        $device = Device::factory()->create();
        $user = User::factory()->create();

        Assignment::factory()->create([
            'device_id' => $device->id,
            'user_id' => $user->id,
            'returned_at' => null,
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No se puede eliminar un dispositivo con asignaciones activas');

        $this->service->deleteDevice($device);
    }

    public function test_delete_device_without_assignments_succeeds(): void
    {
        $device = Device::factory()->create(['status' => 'available']);

        $result = $this->service->deleteDevice($device);

        $this->assertTrue($result);
        $this->assertNull(Device::find($device->id));
    }

    public function test_validate_photo_upload_with_invalid_mime(): void
    {
        Storage::fake('private');

        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tipo de archivo no permitido');

        $this->service->validatePhotoUpload($file, 20 * 1024 * 1024, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function test_validate_photo_upload_with_too_large_file(): void
    {
        Storage::fake('private');

        $file = UploadedFile::fake()->create('photo.jpg', 25 * 1024, 'image/jpeg');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('excede el tamaño máximo');

        $this->service->validatePhotoUpload($file, 20 * 1024 * 1024, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function test_validate_photo_upload_with_valid_file(): void
    {
        Storage::fake('private');

        $file = UploadedFile::fake()->create('photo.jpg', 1024, 'image/jpeg');

        $this->service->validatePhotoUpload($file, 20 * 1024 * 1024, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

        $this->assertTrue(true);
    }
}
