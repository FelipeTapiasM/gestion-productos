<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware 
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {//El middleware de roles comienza verificando si el usuario esta autenticado utilizando el metodo 'user()' del request. Si no hay un usuario autenticado, se redirige al usuario a la pagina de login utilizando 'redirect()->route('login')'.
            return redirect()->route('login');
        }

        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {//Si el usuario esta autenticado, el middleware recorre los roles especificados en los parametros y verifica si el usuario tiene alguno de esos roles utilizando el metodo 'hasRole()' del modelo User. Si el usuario tiene el rol requerido, se permite que la solicitud continue a la siguiente etapa del proceso utilizando 'return $next($request)'.
                return $next($request);
            }
        }

        abort(403, 'No tienes permiso para acceder a esta sección.');//Si el usuario no tiene ninguno de los roles requeridos, se aborta la solicitud con un codigo de estado 403 (Prohibido) y un mensaje de error indicando que el usuario no tiene permiso para acceder a esa seccion.
    }
}