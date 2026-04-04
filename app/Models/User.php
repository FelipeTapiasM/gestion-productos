<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable 
{
    use HasFactory, Notifiable, HasRoles; //El trait 'HasRoles' se utiliza para agregar funcionalidades de roles y permisos al modelo User, permitiendo asignar roles a los usuarios y verificar sus permisos de manera sencilla.

    protected $fillable = [ //El atributo '$fillable' define los campos que se pueden asignar masivamente al crear o actualizar un usuario, en este caso 'name', 'email' y 'password'.
        'name',
        'email',
        'password',
    ];

    protected $hidden = [ //El atributo '$hidden' define los campos que se ocultaran al convertir el modelo a un array o JSON, en este caso 'password' y 'remember_token' para proteger la informacion sensible del usuario.
        'password',
        'remember_token',
    ];

    protected function casts(): array //El metodo 'casts' se utiliza para definir los tipos de datos de los campos del modelo, en este caso 'email_verified_at' se convierte a un objeto datetime y 'password' se marca como hashed para que se encripte automaticamente al asignarlo.
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Productos creados por este usuario.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}