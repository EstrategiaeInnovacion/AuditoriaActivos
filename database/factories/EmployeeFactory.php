<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'employee_id' => fake()->unique()->numerify('EMP-####'),
            'department' => fake()->randomElement(['IT', 'Contabilidad', 'Recursos Humanos', 'Ventas', 'Operaciones']),
            'position' => fake()->jobTitle(),
            'phone' => fake()->phoneNumber(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
