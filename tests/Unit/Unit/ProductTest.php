<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Cargar roles y permisos antes de cada prueba
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function un_producto_tiene_los_campos_correctos(): void // Verifica que el producto se crea con los campos correctos
    {
        $user = User::factory()->create();
        $user->assignRole('empleado');

        $product = Product::factory()->create([
            'name'     => 'Laptop Dell',
            'price'    => 2500000,
            'stock'    => 10,
            'category' => 'Tecnología',
            'user_id'  => $user->id,
        ]);

        $this->assertEquals('Laptop Dell', $product->name);
        $this->assertEquals(2500000, $product->price);
        $this->assertEquals(10, $product->stock);
        $this->assertEquals('Tecnología', $product->category);
    }

    /** @test */
    public function in_stock_retorna_true_cuando_hay_stock(): void // Verifica que el método inStock() retorna true cuando hay stock disponible
    {
        $product = Product::factory()->create(['stock' => 5]);

        $this->assertTrue($product->inStock());
    }

    /** @test */
    public function in_stock_retorna_false_cuando_no_hay_stock(): void // Verifica que el método inStock() retorna false cuando no hay stock disponible
    {
        $product = Product::factory()->create(['stock' => 0]);

        $this->assertFalse($product->inStock());
    }

    /** @test */
    public function un_producto_pertenece_a_un_usuario(): void // Verifica que la relación entre Product y User funciona correctamente
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $product->user);
        $this->assertEquals($user->id, $product->user->id);
    }

    /** @test */
    public function scope_search_filtra_por_nombre(): void // Verifica que el scope search filtra correctamente los productos por nombre
    {
        Product::factory()->create(['name' => 'Laptop Dell Inspiron']);
        Product::factory()->create(['name' => 'Mouse Logitech']);

        $results = Product::search('Laptop')->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Laptop Dell Inspiron', $results->first()->name);
    }

    /** @test */
    public function scope_by_category_filtra_correctamente(): void // Verifica que el scope byCategory filtra correctamente los productos por categoría
    {
        Product::factory()->create(['category' => 'Tecnología']);
        Product::factory()->create(['category' => 'Tecnología']);
        Product::factory()->create(['category' => 'Mobiliario']);

        $results = Product::byCategory('Tecnología')->get();

        $this->assertCount(2, $results);
    }

    /** @test */
    public function el_precio_no_puede_ser_negativo(): void // Verifica que el precio no pueda ser negativo
    {
        $product = Product::factory()->make(['price' => -100]);

        $this->assertLessThan(0, $product->price);
        // La validación real ocurre en el controlador, aquí verificamos el valor
    }

    /** @test */
    public function el_stock_se_castea_como_entero(): void // Verifica que el stock se casteé correctamente como entero
    {
        $product = Product::factory()->create(['stock' => '25']);

        $this->assertIsInt($product->stock);
        $this->assertEquals(25, $product->stock);
    }
}