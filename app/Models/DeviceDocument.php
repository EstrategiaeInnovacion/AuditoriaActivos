<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceDocument extends Model
{
    protected $fillable = [
        'device_id',
        'file_path',
        'original_name',
        'type',
        'uploaded_by',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class , 'uploaded_by');
    }
}
