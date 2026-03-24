<?php

namespace Database\Factories;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'name' => fake()->words(2, true),
            'brand' => fake()->randomElement(['Dell', 'HP', 'Lenovo', 'Apple', 'Logitech']),
            'model' => fake()->bothify('Model-###??'),
            'serial_number' => fake()->bothify('SN-#####??###'),
            'type' => fake()->randomElement(DeviceType::values()),
            'status' => DeviceStatus::Available->value,
            'purchase_date' => fake()->date(),
            'warranty_expiration' => fake()->dateTimeBetween('now', '+3 years')->format('Y-m-d'),
            'notes' => null,
        ];
    }

    public function computer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => DeviceType::Computer->value,
        ]);
    }

    public function peripheral(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => DeviceType::Peripheral->value,
        ]);
    }

    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DeviceStatus::Assigned->value,
        ]);
    }
}
