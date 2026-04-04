<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [//El atributo '$fillable' define los campos que se pueden asignar masivamente al crear o actualizar un producto, en este caso 'name', 'description', 'price', 'stock', 'category', 'image' y 'user_id'.
        'name',
        'description',
        'price',
        'stock',
        'category',
        'image',
        'user_id',
    ];

    protected function casts(): array//El metodo 'casts' se utiliza para definir los tipos de datos de los campos del modelo, en este caso 'price' se convierte a un decimal con 2 decimales y 'stock' se convierte a un entero.
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    /**
     * Usuario que creó este producto.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para filtrar por categoría.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope para búsqueda por nombre.
     */
    public function scopeSearch($query, string $term)
    {
        return $query->where('name', 'like', "%{$term}%")
                     ->orWhere('description', 'like', "%{$term}%");
    }

    /**
     * Devuelve true si el producto tiene stock disponible.
     */
    public function inStock(): bool
    {
        return $this->stock > 0;
    }
}