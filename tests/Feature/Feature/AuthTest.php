<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void // Cargar roles y permisos antes de cada prueba
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function la_pantalla_de_login_se_muestra_correctamente(): void // Verifica que la pantalla de login se muestra correctamente
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /** @test */
    public function un_usuario_puede_iniciar_sesion_con_credenciales_correctas(): void // Verifica que un usuario puede iniciar sesión con credenciales correctas
    {
        $user = User::factory()->create([
            'email'    => 'admin@gestiontienda.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('admin');

        $response = $this->post('/login', [
            'email'    => 'admin@gestiontienda.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function un_usuario_no_puede_iniciar_sesion_con_credenciales_incorrectas(): void // Verifica que un usuario no puede iniciar sesión con credenciales incorrectas
    {
        User::factory()->create([
            'email'    => 'admin@gestiontienda.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'admin@gestiontienda.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /** @test */
    public function un_usuario_no_autenticado_es_redirigido_al_login(): void // Verifica que un usuario no autenticado es redirigido a la página de login al intentar acceder al dashboard
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function un_usuario_autenticado_puede_cerrar_sesion(): void // Verifica que un usuario autenticado puede cerrar sesión correctamente
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function el_admin_es_redirigido_al_dashboard_correcto(): void // Verifica que un usuario con rol admin es redirigido al dashboard de administración
    {
        $admin = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $this->post('/login', [
            'email'    => $admin->email,
            'password' => 'password',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Panel de Administración');
    }

    /** @test */
    public function el_empleado_es_redirigido_al_dashboard_correcto(): void // Verifica que un usuario con rol empleado es redirigido al dashboard de empleado
    {
        $empleado = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        $empleado->assignRole('empleado');

        $response = $this->actingAs($empleado)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Panel de Empleado');
    }
}