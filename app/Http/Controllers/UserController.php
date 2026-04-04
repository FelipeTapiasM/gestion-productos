<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // El controlador de usuarios maneja las operaciones relacionadas con la gestión de usuarios, incluyendo la visualización de la lista de usuarios, la creación de nuevos usuarios, la edición de usuarios existentes y la asignación de roles a los usuarios.
    // Utiliza el modelo User para interactuar con la base de datos y el modelo Role para gestionar los roles de los usuarios.
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    // El método 'store' se encarga de validar los datos recibidos del formulario de creación de usuario, crear un nuevo usuario en la base de datos y asignarle un rol específico. Utiliza la función 'Hash::make' para cifrar la contraseña antes de almacenarla en la base de datos, asegurando así la seguridad de las credenciales del usuario.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|exists:roles,name',
        ], [
            'name.required'      => 'El nombre es obligatorio.',
            'email.required'     => 'El correo es obligatorio.',
            'email.unique'       => 'Este correo ya está registrado.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'role.required'      => 'Debes asignar un rol.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    // El método 'edit' se encarga de mostrar el formulario de edición de un usuario específico, pasando el usuario a editar y la lista de roles disponibles a la vista. Esto permite al administrador modificar los detalles del usuario y cambiar su rol si es necesario.
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    // El método 'update' se encarga de validar los datos recibidos del formulario de edición de usuario, actualizar los detalles del usuario en la base de datos y sincronizar su rol. Utiliza la función 'syncRoles' para actualizar los roles del usuario, asegurando que el usuario tenga el rol correcto después de la actualización.
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|exists:roles,name',
        ], [
            'name.required'  => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique'   => 'Este correo ya está en uso.',
            'role.required'  => 'Debes asignar un rol.',
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}