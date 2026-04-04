<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
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

    // ── Acceso a rutas ────────────────────────────────────────────────────

    /** @test */
    public function el_admin_puede_acceder_a_gestion_de_usuarios(): void // Verifica que el admin puede acceder a la gestión de usuarios
    {
        $response = $this->actingAs($this->admin)->get('/users');

        $response->assertStatus(200);
    }

    /** @test */
    public function el_empleado_no_puede_acceder_a_gestion_de_usuarios(): void // Verifica que el empleado no puede acceder a la gestión de usuarios
    {
        $response = $this->actingAs($this->empleado)->get('/users');

        $response->assertStatus(403);
    }

    /** @test */
    public function el_empleado_puede_acceder_al_catalogo_de_productos(): void // Verifica que el empleado puede acceder al catálogo de productos
    {
        $response = $this->actingAs($this->empleado)->get('/products');

        $response->assertStatus(200);
    }

    // ── Eliminar productos ────────────────────────────────────────────────

    /** @test */
    public function el_admin_puede_eliminar_un_producto(): void // Verifica que el admin puede eliminar un producto
    {
        $product = Product::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)
            ->delete("/products/{$product->id}");

        $response->assertRedirect('/products');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function el_empleado_no_puede_eliminar_un_producto(): void // Verifica que el empleado no puede eliminar un producto
    {
        $product = Product::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->empleado)
            ->delete("/products/{$product->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    // ── Eliminar usuarios ─────────────────────────────────────────────────

    /** @test */
    public function el_admin_puede_eliminar_un_empleado(): void // Verifica que el admin puede eliminar un empleado
    {
        $response = $this->actingAs($this->admin)
            ->delete("/users/{$this->empleado->id}");

        $response->assertRedirect('/users');
        $this->assertDatabaseMissing('users', ['id' => $this->empleado->id]);
    }

    /** @test */
    public function el_admin_no_puede_eliminarse_a_si_mismo(): void // Verifica que el admin no puede eliminarse a sí mismo
    {
        $response = $this->actingAs($this->admin)
            ->delete("/users/{$this->admin->id}");

        $response->assertRedirect('/users');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    /** @test */
    public function no_se_puede_eliminar_el_ultimo_admin(): void // Verifica que no se puede eliminar al último admin del sistema
    {
        // Solo hay un admin en el sistema
        $otroAdmin = User::factory()->create();
        $otroAdmin->assignRole('admin');

        // Eliminar el otro admin primero (ahora solo queda $this->admin)
        $otroAdmin->delete();

        $response = $this->actingAs($this->admin)
            ->delete("/users/{$this->admin->id}");

        // No puede eliminarse porque es el último admin
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    /** @test */
    public function el_empleado_no_puede_crear_usuarios(): void // Verifica que el empleado no puede acceder a la página de creación de usuarios
    {
        $response = $this->actingAs($this->empleado)->get('/users/create');

        $response->assertStatus(403);
    }
}