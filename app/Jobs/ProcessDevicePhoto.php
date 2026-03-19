<?php

namespace App\Jobs;

use App\Models\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessDevicePhoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public int $maxExceptions = 2;

    public function __construct(
        public Device $device,
        public UploadedFile $file,
        public ?int $uploadedBy = null
    ) {}

    public function handle(): void
    {
        $path = $this->file->store('device-photos/'.$this->device->id, 'private');

        $this->device->photos()->create([
            'file_path' => $path,
            'uploaded_by' => $this->uploadedBy,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        if (Storage::disk('private')->exists('device-photos/'.$this->device->id.'/'.$this->file->hashName())) {
            Storage::disk('private')->delete('device-photos/'.$this->device->id.'/'.$this->file->hashName());
        }
    }
}
