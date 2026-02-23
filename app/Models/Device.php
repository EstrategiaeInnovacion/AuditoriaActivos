<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Device extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'brand',
        'model',
        'serial_number',
        'type',
        'status',
        'purchase_date',
        'warranty_expiration',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string)Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function credential()
    {
        return $this->hasOne(Credential::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function currentAssignment()
    {
        return $this->hasOne(Assignment::class)->whereNull('returned_at')->latestOfMany();
    }

    public function photos()
    {
        return $this->hasMany(DevicePhoto::class);
    }

    public function documents()
    {
        return $this->hasMany(DeviceDocument::class);
    }
}
