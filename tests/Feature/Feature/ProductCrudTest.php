<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $empleado;

    protected function setUp(): void // Cargar roles y permisos antes de cada prueba
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->empleado = User::factory()->create();
        $this->empleado->assignRole('empleado');
    }

    // ── Listar ────────────────────────────────────────────────────────────

    /** @test */
    public function se_pueden_listar_los_productos(): void // Verifica que se pueden listar los productos en la página de catálogo
    {
        Product::factory(3)->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Catálogo de Productos');
    }

    /** @test */
    public function se_pueden_buscar_productos_por_nombre(): void // Verifica que se pueden buscar productos por su nombre utilizando el campo de búsqueda
    {
        Product::factory()->create([
            'name'    => 'Laptop Dell',
            'user_id' => $this->admin->id,
        ]);
        Product::factory()->create([
            'name'    => 'Mouse Logitech',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/products?search=Laptop');

        $response->assertStatus(200);
        $response->assertSee('Laptop Dell');
        $response->assertDontSee('Mouse Logitech');
    }

    // ── Crear ─────────────────────────────────────────────────────────────

    /** @test */
    public function el_admin_puede_ver_el_formulario_de_crear_producto(): void // Verifica que el admin puede acceder al formulario de creación de productos
    {
        $response = $this->actingAs($this->admin)->get('/products/create');

        $response->assertStatus(200);
        $response->assertSee('Registrar nuevo producto');
    }

    /** @test */
    public function se_puede_crear_un_producto_con_datos_validos(): void // Verifica que se puede crear un producto con datos válidos
    {
        $response = $this->actingAs($this->admin)->post('/products', [
            'name'        => 'Teclado Mecánico',
            'description' => 'Teclado con switches blue',
            'price'       => 175000,
            'stock'       => 20,
            'category'    => 'Accesorios',
        ]);

        $response->assertRedirect('/products');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name'  => 'Teclado Mecánico',
            'price' => 175000,
            'stock' => 20,
        ]);
    }

    /** @test */
    public function el_empleado_puede_crear_un_producto(): void // Verifica que un usuario con rol empleado puede crear un producto
    {
        $response = $this->actingAs($this->empleado)->post('/products', [
            'name'     => 'Monitor LG',
            'price'    => 850000,
            'stock'    => 5,
            'category' => 'Tecnología',
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['name' => 'Monitor LG']);
    }

    /** @test */
    public function no_se_puede_crear_un_producto_sin_nombre(): void // Verifica que no se puede crear un producto sin un nombre, ya que es un campo obligatorio
    {
        $response = $this->actingAs($this->admin)->post('/products', [
            'name'     => '',
            'price'    => 100000,
            'stock'    => 5,
            'category' => 'Tecnología',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('products', 0);
    }

    /** @test */
    public function no_se_puede_crear_un_producto_con_precio_cero(): void // Verifica que no se puede crear un producto con un precio de cero, ya que el precio debe ser un valor positivo
    {
        $response = $this->actingAs($this->admin)->post('/products', [
            'name'     => 'Producto inválido',
            'price'    => 0,
            'stock'    => 5,
            'category' => 'Tecnología',
        ]);

        $response->assertSessionHasErrors('price');
    }

    /** @test */
    public function no_se_puede_crear_un_producto_con_stock_negativo(): void // verifica que no se puede crear un producto con un stock negativo, ya que el stock debe ser un valor entero no negativo
    {
        $response = $this->actingAs($this->admin)->post('/products', [
            'name'     => 'Producto inválido',
            'price'    => 50000,
            'stock'    => -1,
            'category' => 'Tecnología',
        ]);

        $response->assertSessionHasErrors('stock');
    }

    /** @test */
    public function se_puede_crear_un_producto_con_imagen(): void // Verifica que se puede crear un producto con una imagen adjunta y que la imagen se almacena correctamente
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post('/products', [
            'name'     => 'Producto con imagen',
            'price'    => 50000,
            'stock'    => 10,
            'category' => 'Tecnología',
            'image'    => UploadedFile::fake()->image('producto.jpg', 800, 600),
        ]);

        $response->assertRedirect('/products');

        $product = Product::where('name', 'Producto con imagen')->first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    // ── Editar ────────────────────────────────────────────────────────────

    /** @test */
    public function se_puede_ver_el_formulario_de_editar_producto(): void // Verifica que se puede acceder al formulario de edición de un producto existente
    {
        $product = Product::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)
            ->get("/products/{$product->id}/edit");

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    /** @test */
    public function se_puede_actualizar_un_producto(): void // verifica que se puede actualizar un producto con datos validos y que los cambios se reflejan en la base de datos
    {
        $product = Product::factory()->create([
            'user_id' => $this->admin->id,
            'price'   => 100000,
        ]);

        $response = $this->actingAs($this->admin)
            ->put("/products/{$product->id}", [
                'name'     => 'Nombre actualizado',
                'price'    => 200000,
                'stock'    => 15,
                'category' => 'Tecnología',
            ]);

        $response->assertRedirect('/products');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id'    => $product->id,
            'name'  => 'Nombre actualizado',
            'price' => 200000,
        ]);
    }

    // ── Eliminar ──────────────────────────────────────────────────────────

    /** @test */
    public function el_admin_puede_eliminar_un_producto(): void // Verifica que el admin puede eliminar un producto y que el producto se elimina de la base de datos
    {
        $product = Product::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)
            ->delete("/products/{$product->id}");

        $response->assertRedirect('/products');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function al_eliminar_un_producto_se_borra_su_imagen(): void // Verifica que al eliminar un producto que tiene una imagen asociada, la imagen también se elimina del almacenamiento
    {
        Storage::fake('public');

        $image   = UploadedFile::fake()->image('test.jpg');
        $path    = $image->store('products', 'public');
        $product = Product::factory()->create([
            'user_id' => $this->admin->id,
            'image'   => $path,
        ]);

        $this->actingAs($this->admin)
            ->delete("/products/{$product->id}");

        Storage::disk('public')->assertMissing($path);
    }

    // ── Ver detalle ───────────────────────────────────────────────────────

    /** @test */
    public function se_puede_ver_el_detalle_de_un_producto(): void // Verifica que se puede acceder a la página de detalle de un producto y que se muestra la información correcta del producto
    {
        $product = Product::factory()->create([
            'name'    => 'Laptop Gamer',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('Laptop Gamer');
    }
}