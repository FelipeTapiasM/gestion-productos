<x-app-layout>
    <x-slot name="title">Catálogo de Productos</x-slot>

    {{-- Encabezado --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <h1 style="font-size:20px;font-weight:800;color:#0f172a;margin:0;">Catálogo de Productos</h1>
            <p style="font-size:13px;color:#94a3b8;margin-top:3px;">
                {{ $products->total() }} producto(s) encontrado(s)
            </p>
        </div>
        <a href="{{ route('products.create') }}" class="btn-primary">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Producto
        </a>
    </div>

    {{-- Filtros --}}
    <div class="card" style="margin-bottom:20px;padding:16px 20px;">
        <form method="GET" action="{{ route('products.index') }}"
              style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">

            <div style="flex:1;min-width:200px;">
                <label style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;
                    letter-spacing:0.5px;display:block;margin-bottom:5px;">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nombre o descripción..."
                    style="width:100%;height:38px;border:1.5px solid #e2e8f0;border-radius:8px;
                        padding:0 12px;font-size:13px;color:#0f172a;background:#f8fafc;outline:none;">
            </div>

            <div style="min-width:160px;">
                <label style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;
                    letter-spacing:0.5px;display:block;margin-bottom:5px;">Categoría</label>
                <select name="category"
                    style="width:100%;height:38px;border:1.5px solid #e2e8f0;border-radius:8px;
                        padding:0 12px;font-size:13px;color:#0f172a;background:#f8fafc;outline:none;">
                    <option value="">Todas</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-primary" style="height:38px;">Filtrar</button>

            @if(request('search') || request('category'))
                <a href="{{ route('products.index') }}" class="btn-secondary" style="height:38px;">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="table-header"
             style="grid-template-columns:2.5fr 1.2fr 1fr 1.2fr 1fr;">
            <span>Producto</span>
            <span>Categoría</span>
            <span>Stock</span>
            <span>Precio</span>
            <span>Acciones</span>
        </div>

        @forelse ($products as $product)
            <div class="table-row"
                 style="grid-template-columns:2.5fr 1.2fr 1fr 1.2fr 1fr;">

                {{-- Nombre + imagen --}}
                <div style="display:flex;align-items:center;gap:12px;">
                    @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}"
                             alt="{{ $product->name }}"
                             style="width:38px;height:38px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                    @else
                        <div style="width:38px;height:38px;background:#f1f5f9;border-radius:8px;
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5">
                                <path d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <p style="font-weight:600;color:#0f172a;font-size:13px;">{{ $product->name }}</p>
                        <p style="font-size:11px;color:#94a3b8;">Por {{ $product->user->name }}</p>
                    </div>
                </div>

                {{-- Categoría --}}
                <span style="font-size:12px;background:#f1f5f9;color:#475569;
                    padding:3px 10px;border-radius:99px;font-weight:500;display:inline-block;">
                    {{ $product->category }}
                </span>

                {{-- Stock --}}
                <span class="{{ $product->stock > 0 ? 'stock-ok' : 'stock-out' }}">
                    {{ $product->stock > 0 ? $product->stock . ' uds.' : 'Sin stock' }}
                </span>

                {{-- Precio --}}
                <span style="font-weight:700;color:#0f172a;">
                    ${{ number_format($product->price, 2) }}
                </span>

                {{-- Acciones --}}
                <div style="display:flex;align-items:center;gap:6px;">
                    <a href="{{ route('products.show', $product) }}"
                       class="icon-btn icon-btn-view" title="Ver detalle">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </a>
                    <a href="{{ route('products.edit', $product) }}"
                       class="icon-btn icon-btn-edit" title="Editar">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </a>

                    @role('admin')
                    <form method="POST" action="{{ route('products.destroy', $product) }}"
                          onsubmit="return confirm('¿Seguro que deseas eliminar «{{ $product->name }}»? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn-del" title="Eliminar">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                            </svg>
                        </button>
                    </form>
                    @endrole
                </div>
            </div>
        @empty
            <div style="padding:48px;text-align:center;">
                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.2"
                     style="margin:0 auto 12px;display:block;">
                    <path d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7"/>
                </svg>
                <p style="font-size:14px;color:#94a3b8;font-weight:500;">No se encontraron productos.</p>
                <a href="{{ route('products.create') }}" class="btn-primary"
                   style="display:inline-flex;margin-top:14px;">+ Crear el primero</a>
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    @if ($products->hasPages())
        <div style="margin-top:20px;display:flex;justify-content:center;">
            {{ $products->links() }}
        </div>
    @endif

</x-app-layout>