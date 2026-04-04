<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Admin
        $admin = User::firstOrCreate(//El metodo 'firstOrCreate' se utiliza para buscar un usuario con el email 'admin@gestiontienda.com' y crearlo si no existe. 
            ['email' => 'admin@gestiontienda.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');//El metodo 'assignRole' se utiliza para asignar el rol 'admin' al usuario creado, otorgandole todos los permisos asociados a ese rol.

        // Usuario Empleado
        $empleado = User::firstOrCreate(//De manera similar, se crea un usuario con el email '
            ['email' => 'empleado@gestiontienda.com'],
            [
                'name'     => 'Empleado Demo',
                'password' => Hash::make('password'),
            ]
        );
        $empleado->assignRole('empleado');

        // Empleados adicionales con factory
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('empleado');
        });
    }
}