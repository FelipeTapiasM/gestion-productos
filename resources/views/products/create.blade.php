<x-app-layout>
    <x-slot name="title">Nuevo Producto</x-slot>

    {{-- Breadcrumb --}}
    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#94a3b8;margin-bottom:20px;">
        <a href="{{ route('products.index') }}" style="color:#3b4fd8;text-decoration:none;">Productos</a>
        <span>›</span>
        <span style="color:#0f172a;font-weight:500;">Nuevo producto</span>
    </div>

    <div class="card" style="max-width:760px;">
        <div class="card-header">
            <h3>Registrar nuevo producto</h3>
        </div>
        <div style="padding:24px;">
            @include('products.partials.form', ['categories' => $categories])
        </div>
    </div>

</x-app-layout>