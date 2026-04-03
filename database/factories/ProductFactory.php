<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $categorias = [//El array '$categorias' define las categorias disponibles para los productos, que se utilizan para asignar una categoria aleatoria a cada producto creado por la factory.
            'Tecnología',
            'Accesorios',
            'Mobiliario',
            'Papeleria',
            'Limpieza',
            'Alimentos',
        ];

        return [//El metodo 'definition' devuelve un array con los campos necesarios para crear un producto, utilizando el generador de datos falsos 'fake()' para generar valores aleatorios para cada campo, como el nombre, descripcion, precio, stock y categoria. El campo 'user_id' se asigna a un nuevo usuario creado por la factory de User para establecer la relacion entre el producto y su creador.
            'name'        => fake()->words(3, true),
            'description' => fake()->sentence(12),
            'price'       => fake()->randomFloat(2, 5000, 3000000),
            'stock'       => fake()->numberBetween(0, 100),
            'category'    => fake()->randomElement($categorias),
            'image'       => null,
            'user_id'     => User::factory(),
        ];
    }

    /**
     * Producto sin stock.
     */
    public function sinStock(): static
    {
        return $this->state(['stock' => 0]);
    }

    /**
     * Producto con stock alto.
     */
    public function conStock(): static
    {
        return $this->state(['stock' => fake()->numberBetween(10, 100)]);
    }
}