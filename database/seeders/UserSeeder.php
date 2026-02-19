<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador por defecto
        User::firstOrCreate(
            ['email' => 'admin@pharmasync.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Crear usuario regular de ejemplo
        User::firstOrCreate(
            ['email' => 'usuario@pharmasync.com'],
            [
                'name' => 'Usuario Demo',
                'password' => Hash::make('usuario123'),
                'role' => 'user',
            ]
        );
    }
}