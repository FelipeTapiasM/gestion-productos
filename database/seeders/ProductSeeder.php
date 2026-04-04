<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@gestiontienda.com')->first();//El seeder de productos comienza obteniendo el usuario admin creado en el UserSeeder para asignar la propiedad 'user_id' a los productos que se van a crear, estableciendo una relacion entre los productos y el usuario que los creo.

        // Productos de ejemplo fijos
        $productos = [
            [
                'name'        => 'Laptop Dell Inspiron 15',
                'description' => 'Laptop de 15 pulgadas con procesador Intel Core i5, 8GB RAM y 512GB SSD.',
                'price'       => 2500000.00,
                'stock'       => 10,
                'category'    => 'Tecnologia',
            ],
            [
                'name'        => 'Mouse Inalambrico Logitech',
                'description' => 'Mouse ergonomico inalambrico con bateria de larga duracion.',
                'price'       => 85000.00,
                'stock'       => 50,
                'category'    => 'Accesorios',
            ],
            [
                'name'        => 'Teclado Mecanico Redragon',
                'description' => 'Teclado mecánico con switches Blue, retroiluminacion RGB.',
                'price'       => 175000.00,
                'stock'       => 30,
                'category'    => 'Accesorios',
            ],
            [
                'name'        => 'Monitor Samsung 24"',
                'description' => 'Monitor Full HD 1080p, panel IPS, 75Hz, HDMI y VGA.',
                'price'       => 850000.00,
                'stock'       => 8,
                'category'    => 'Tecnologia',
            ],
            [
                'name'        => 'Silla Ergonomica',
                'description' => 'Silla de oficina con soporte lumbar ajustable y apoyabrazos.',
                'price'       => 650000.00,
                'stock'       => 15,
                'category'    => 'Mobiliario',
            ],
        ];

        foreach ($productos as $producto) {//El bucle recorre cada producto definido en el array '$productos' y utiliza el metodo 'firstOrCreate' para crear el producto en la base de datos si no existe, asignando el 'user_id' del admin para establecer la relacion entre el producto y el usuario que lo creo.
            Product::firstOrCreate(
                ['name' => $producto['name']],
                array_merge($producto, ['user_id' => $admin->id])
            );
        }

        // Productos adicionales con factory
        Product::factory(15)->create(['user_id' => $admin->id]);
    }
}