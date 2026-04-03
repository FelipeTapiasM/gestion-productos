<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//Esta migracion crea la tabla 'products' con los campos necesarios para almacenar informacion sobre los productos, incluyendo su nombre, descripcion, precio, stock, categoria, imagen y la relacion con el usuario que lo creo.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);// El campo 'price' es un decimal con 10 digitos en total y 2 decimales para almacenar el precio del producto.
            $table->integer('stock')->default(0);
            $table->string('category', 100);//El campo 'category' es una cadena de texto con un maximo de 100 caracteres para almacenar la categoria del producto.
            $table->string('image')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');//El campo 'user_id' es una clave foranea que hace referencia al id del usuario que creo el producto, y se eliminara en cascada si el usuario es eliminado.
            $table->timestamps();//Los campos 'created_at' y 'updated_at' se agregan automaticamente para almacenar la fecha de creacion y actualizacion del producto.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');//El metodo 'down' se encarga de eliminar la tabla 'products' si es necesario revertir la migracion.
    }
};