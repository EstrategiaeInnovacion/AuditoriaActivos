<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Device extends Model
{
    use HasFactory;

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

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Model $model): void {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function credential(): HasOne
    {
        return $this->hasOne(Credential::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function currentAssignment(): HasOne
    {
        return $this->hasOne(Assignment::class)->whereNull('returned_at')->latestOfMany();
    }

    public function photos(): HasMany
    {
        return $this->hasMany(DevicePhoto::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(DeviceDocument::class);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search): Builder {
            return $q->where('name', 'like', "%{$search}%")
                ->orWhere('serial_number', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%");
        });
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if (empty($status)) {
            return $query;
        }

        return $query->where('status', $status);
    }

    public function scopeType(Builder $query, ?string $type): Builder
    {
        if (empty($type)) {
            return $query;
        }

        return $query->where('type', $type);
    }

    public function scopeWarrantyExpiring(Builder $query, int $days = 30): Builder
    {
        return $query->whereNotNull('warranty_expiration')
            ->whereBetween('warranty_expiration', [now(), now()->addDays($days)]);
    }

    public function scopeWithCurrentAssignment(Builder $query): Builder
    {
        return $query->with('currentAssignment.user');
    }
}
