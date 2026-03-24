<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\Device;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assignment>
 */
class AssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'user_id' => null,
            'employee_id' => null,
            'assigned_to' => fake()->name(),
            'assigned_at' => now(),
            'returned_at' => null,
            'notes' => null,
        ];
    }

    public function forEmployee(Employee $employee): static
    {
        return $this->state(fn (array $attributes) => [
            'employee_id' => $employee->id,
            'assigned_to' => null,
        ]);
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
            'assigned_to' => $user->name,
        ]);
    }

    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'returned_at' => now(),
        ]);
    }
}
