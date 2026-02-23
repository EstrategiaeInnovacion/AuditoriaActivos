<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear administrador por defecto si no existe
        User::updateOrCreate(
        ['email' => 'admin@activos.local'],
        [
            'name' => 'Administrador',
            'password' => bcrypt('password'), // Asegúrate de crear un hash válido
            'is_admin' => true,
        ]
        );
    }
}
