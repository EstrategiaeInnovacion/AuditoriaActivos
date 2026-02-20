<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'device_id',
        'username',
        'password',
        'email',
        'email_password',
    ];

    protected $casts = [
        'password' => 'encrypted',
        'email_password' => 'encrypted',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
