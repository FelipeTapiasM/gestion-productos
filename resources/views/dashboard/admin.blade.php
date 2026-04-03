<x-app-layout>
    <x-slot name="title">Panel de Administración</x-slot>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:28px;">

        <div class="stat-card" style="border-color:#3b4fd8;">
            <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">Total productos</p>
            <p style="font-size:32px;font-weight:800;color:#0f172a;margin:6px 0 4px;">
                {{ number_format($stats['total_productos']) }}
            </p>
            <a href="{{ route('products.index') }}" style="font-size:12px;color:#3b4fd8;text-decoration:none;">Ver catálogo →</a>
        </div>

        <div class="stat-card" style="border-color:#16a34a;">
            <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">Usuarios registrados</p>
            <p style="font-size:32px;font-weight:800;color:#0f172a;margin:6px 0 4px;">
                {{ number_format($stats['total_usuarios']) }}
            </p>
            <a href="{{ route('users.index') }}" style="font-size:12px;color:#16a34a;text-decoration:none;">Ver usuarios →</a>
        </div>

        <div class="stat-card" style="border-color:#9333ea;">
            <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">Categorías</p>
            <p style="font-size:32px;font-weight:800;color:#0f172a;margin:6px 0 4px;">
                {{ $stats['categorias'] }}
            </p>
            @if($stats['sin_stock'] > 0)
                <p style="font-size:12px;color:#dc2626;">⚠ {{ $stats['sin_stock'] }} sin stock</p>
            @else
                <p style="font-size:12px;color:#16a34a;">✓ Todo con stock</p>
            @endif
        </div>

    </div>

    {{-- Tabla actividad reciente --}}
    <div class="card">
        <div class="card-header">
            <h3>Actividad Reciente del Inventario</h3>
            <a href="{{ route('products.create') }}" class="btn-primary">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                + Nuevo Producto
            </a>
        </div>

        {{-- Encabezado tabla --}}
        <div class="table-header" style="grid-template-columns:2fr 2fr 1fr 1.2fr 1fr;">
            <span>Producto</span>
            <span>Último cambio</span>
            <span>Stock</span>
            <span>Precio</span>
            <span>Acciones</span>
        </div>

        {{-- Filas --}}
        @forelse ($productos_recientes as $producto)
            <div class="table-row" style="grid-template-columns:2fr 2fr 1fr 1.2fr 1fr;">
                <div>
                    <p style="font-weight:600;color:#0f172a;font-size:13px;">{{ $producto->name }}</p>
                    <p style="font-size:11px;color:#94a3b8;">{{ $producto->category }}</p>
                </div>
                <div>
                    <p style="font-size:13px;color:#374151;">
                        {{ $producto->updated_at->diffForHumans() }}
                    </p>
                    <p style="font-size:11px;color:#94a3b8;">por {{ $producto->user->name }}</p>
                </div>
                <div>
                    <span class="{{ $producto->stock > 0 ? 'stock-ok' : 'stock-out' }}">
                        {{ str_pad($producto->stock, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <div style="font-weight:600;color:#0f172a;">
                    ${{ number_format($producto->price, 2) }}
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <a href="{{ route('products.show', $producto) }}" class="icon-btn icon-btn-view" title="Ver">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </a>
                    <a href="{{ route('products.edit', $producto) }}" class="icon-btn icon-btn-edit" title="Editar">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('products.destroy', $producto) }}"
                          onsubmit="return confirm('¿Eliminar este producto?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn-del" title="Eliminar">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="padding:32px;text-align:center;color:#94a3b8;font-size:13px;">
                No hay productos registrados aún.
            </div>
        @endforelse

        {{-- Ver todos --}}
        <div style="padding:14px 24px;border-top:1px solid #f1f5f9;text-align:right;">
            <a href="{{ route('products.index') }}" style="font-size:13px;color:#3b4fd8;text-decoration:none;font-weight:500;">
                Ver todos los productos →
            </a>
        </div>
    </div>

    {{-- Usuarios recientes --}}
    <div class="card" style="margin-top:24px;">
        <div class="card-header">
            <h3>Usuarios Recientes</h3>
            <a href="{{ route('users.create') }}" class="btn-primary" style="background:#16a34a;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                + Nuevo Usuario
            </a>
        </div>
        <div class="table-header" style="grid-template-columns:2fr 2fr 1fr;">
            <span>Nombre</span><span>Correo</span><span>Rol</span>
        </div>
        @forelse ($usuarios_recientes as $usuario)
            <div class="table-row" style="grid-template-columns:2fr 2fr 1fr;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:30px;height:30px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#3b4fd8;flex-shrink:0;">
                        {{ strtoupper(substr($usuario->name, 0, 2)) }}
                    </div>
                    <span style="font-weight:600;color:#0f172a;">{{ $usuario->name }}</span>
                </div>
                <span style="color:#64748b;">{{ $usuario->email }}</span>
                <span class="badge-rol {{ $usuario->hasRole('admin') ? 'badge-admin' : 'badge-emp' }}">
                    {{ ucfirst($usuario->getRoleNames()->first() ?? 'Sin rol') }}
                </span>
            </div>
        @empty
            <div style="padding:24px;text-align:center;color:#94a3b8;font-size:13px;">Sin usuarios.</div>
        @endforelse
    </div>

</x-app-layout>