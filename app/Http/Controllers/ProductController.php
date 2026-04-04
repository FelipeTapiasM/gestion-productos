<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Listado de productos con busqueda y filtro por categoria.
     */
    public function index(Request $request)
    {
        $query = Product::with('user')->latest();

        // Busqueda por nombre o descripcion
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtro por categoria
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        $products   = $query->paginate(10)->withQueryString();
        $categories = Product::distinct()->pluck('category')->sort()->values();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Formulario para crear un nuevo producto.
     */
    public function create()
    {
        $categories = Product::distinct()->pluck('category')->sort()->values();
        return view('products.create', compact('categories'));
    }

    /**
     * Guardar nuevo producto en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|numeric|min:0.01',
            'stock'       => 'required|integer|min:0',
            'category'    => 'required|string|max:100',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'     => 'El nombre del producto es obligatorio.',
            'name.max'          => 'El nombre no puede superar 255 caracteres.',
            'price.required'    => 'El precio es obligatorio.',
            'price.numeric'     => 'El precio debe ser un número.',
            'price.min'         => 'El precio debe ser mayor a cero.',
            'stock.required'    => 'El stock es obligatorio.',
            'stock.integer'     => 'El stock debe ser un número entero.',
            'stock.min'         => 'El stock no puede ser negativo.',
            'category.required' => 'La categoría es obligatoria.',
            'image.image'       => 'El archivo debe ser una imagen.',
            'image.mimes'       => 'La imagen debe ser JPG, PNG o WEBP.',
            'image.max'         => 'La imagen no puede superar 2MB.',
        ]);

        // Manejo de imagen
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['user_id'] = auth()->id();

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /**
     * Ver detalle de un producto.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Formulario para editar un producto existente.
     */
    public function edit(Product $product)
    {
        $categories = Product::distinct()->pluck('category')->sort()->values();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Actualizar producto en la base de datos.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|numeric|min:0.01',
            'stock'       => 'required|integer|min:0',
            'category'    => 'required|string|max:100',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'     => 'El nombre del producto es obligatorio.',
            'price.required'    => 'El precio es obligatorio.',
            'price.min'         => 'El precio debe ser mayor a cero.',
            'stock.min'         => 'El stock no puede ser negativo.',
            'category.required' => 'La categoría es obligatoria.',
            'image.image'       => 'El archivo debe ser una imagen.',
            'image.max'         => 'La imagen no puede superar 2MB.',
        ]);

        // Manejo de imagen: reemplazar si se sube una nueva
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Eliminar producto (solo Admin).
     */
    public function destroy(Product $product)
    {
        // Eliminar imagen si existe
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}