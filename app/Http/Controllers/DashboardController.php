<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Redirige al dashboard según el rol del usuario autenticado.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }

        return $this->empleadoDashboard();
    }

    /**
     * Dashboard del Administrador.
     */
    private function adminDashboard()
    {
        $stats = [//El array '$stats' contiene las estadísticas clave que se mostrarán en el dashboard del administrador, como el total de productos, total de usuarios, productos sin stock y el número de categorías distintas.
            'total_productos'  => Product::count(),
            'total_usuarios'   => User::count(),
            'sin_stock'        => Product::where('stock', 0)->count(),
            'categorias'       => Product::distinct('category')->count('category'),
        ];// Estas estadísticas se obtienen utilizando consultas a la base de datos a través de los modelos Product y User

        $productos_recientes = Product::with('user')//El array '$productos_recientes' obtiene los 5 productos más recientes de la base de datos, incluyendo la relación con el usuario que los creó utilizando 'with('user')'. Esto permite mostrar información adicional sobre el creador del producto en el dashboard.
            ->latest()
            ->take(5)
            ->get();

        $usuarios_recientes = User::latest()//El array '$usuarios_recientes' obtiene los 5 usuarios más recientes de la base de datos utilizando el método 'latest()' para ordenar por fecha de creación y 'take(5)' para limitar el resultado a los 5 usuarios más recientes.
            ->take(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'productos_recientes', 'usuarios_recientes'));
    }

    /**
     * Dashboard del Empleado.
     */
    private function empleadoDashboard()
    {
        $stats = [//El array '$stats' para el dashboard del empleado contiene estadísticas relevantes para el usuario autenticado, como el total de productos disponibles, productos sin stock y el número de productos creados por el usuario.
            'total_productos' => Product::count(),
            'sin_stock'       => Product::where('stock', 0)->count(),
            'mis_productos'   => Product::where('user_id', auth()->id())->count(),
        ];// Estas estadísticas se obtienen utilizando consultas a la base de datos a través del modelo Product, filtrando por el 'user_id' del usuario autenticado para obtener solo los productos relacionados con ese usuario.

        $productos_recientes = Product::with('user')//El array '$productos_recientes' obtiene los 5 productos más recientes de la base de datos, incluyendo la relación con el usuario que los creó utilizando 'with('user')'. Esto permite mostrar información adicional sobre el creador del producto en el dashboard.
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.empleado', compact('stats', 'productos_recientes'));
    }
}