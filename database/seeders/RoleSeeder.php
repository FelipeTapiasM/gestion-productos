<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            'ver productos',
            'crear productos',
            'editar productos',
            'eliminar productos',
            'ver usuarios',
            'crear usuarios',
            'asignar roles',
        ];

        foreach ($permissions as $permission) { //El bucle recorre cada permiso definido en el array '$permissions' y utiliza el metodo 'firstOrCreate' para crear el permiso en la base de datos si no existe, asegurando que cada permiso se cree solo una vez.
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Rol Admin — todos los permisos
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        // Rol Empleado — permisos limitados
        $empleado = Role::firstOrCreate(['name' => 'empleado']);
        $empleado->syncPermissions([
            'ver productos',
            'crear productos',
            'editar productos',
        ]);
    }
}