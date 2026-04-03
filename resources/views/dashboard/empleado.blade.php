<x-app-layout>
    <x-slot name="title">Panel de Empleado</x-slot>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:28px;">

        <div class="stat-card" style="border-color:#3b4fd8;">
            <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">Total productos</p>
            <p style="font-size:32px;font-weight:800;color:#0f172a;margin:6px 0 4px;">
                {{ number_format($stats['total_productos']) }}
            </p>
            <a href="{{ route('products.index') }}" style="font-size:12px;color:#3b4fd8;text-decoration:none;">Ver catálogo →</a>
        </div>

        <div class="stat-card" style="border-color:#f59e0b;">
            <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">Mis productos</p>
            <p style="font-size:32px;font-weight:800;color:#0f172a;margin:6px 0 4px;">
                {{ $stats['mis_productos'] }}
            </p>
            <p style="font-size:12px;color:#94a3b8;">Registrados por ti</p>
        </div>

        <div class="stat-card" style="border-color:#dc2626;">
            <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">Sin stock</p>
            <p style="font-size:32px;font-weight:800;color:#0f172a;margin:6px 0 4px;">
                {{ $stats['sin_stock'] }}
            </p>
            @if($stats['sin_stock'] > 0)
                <p style="font-size:12px;color:#dc2626;">Requieren atención</p>
            @else
                <p style="font-size:12px;color:#16a34a;">Todo en stock ✓</p>
            @endif
        </div>

    </div>

    {{-- Accesos rápidos --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:28px;">

        <a href="{{ route('products.create') }}"
           style="display:flex;align-items:center;gap:16px;background:#3b4fd8;
                  border-radius:14px;padding:20px 24px;text-decoration:none;
                  transition:background 0.15s;"
           onmouseover="this.style.background='#3040c0'"
           onmouseout="this.style.background='#3b4fd8'">
            <div style="width:44px;height:44px;background:rgba(255,255,255,0.15);border-radius:10px;
                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <p style="color:#fff;font-weight:700;font-size:15px;margin:0;">Registrar producto</p>
                <p style="color:rgba(255,255,255,0.65);font-size:12px;margin-top:2px;">Agregar al catálogo</p>
            </div>
        </a>

        <a href="{{ route('products.index') }}"
           style="display:flex;align-items:center;gap:16px;background:#fff;
                  border-radius:14px;padding:20px 24px;text-decoration:none;
                  border:1px solid #e2e8f0;transition:background 0.15s;"
           onmouseover="this.style.background='#f8fafc'"
           onmouseout="this.style.background='#fff'">
            <div style="width:44px;height:44px;background:#eff6ff;border-radius:10px;
                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#3b4fd8" stroke-width="2">
                    <path d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </div>
            <div>
                <p style="color:#0f172a;font-weight:700;font-size:15px;margin:0;">Ver catálogo</p>
                <p style="color:#94a3b8;font-size:12px;margin-top:2px;">Consultar todos los productos</p>
            </div>
        </a>

    </div>

    {{-- Tabla productos recientes --}}
    <div class="card">
        <div class="card-header">
            <h3>Productos Recientes</h3>
        </div>
        <div class="table-header" style="grid-template-columns:2fr 1.5fr 1fr 1.2fr 0.8fr;">
            <span>Producto</span><span>Categoría</span><span>Stock</span><span>Precio</span><span>Acción</span>
        </div>
        @forelse ($productos_recientes as $producto)
            <div class="table-row" style="grid-template-columns:2fr 1.5fr 1fr 1.2fr 0.8fr;">
                <div>
                    <p style="font-weight:600;color:#0f172a;">{{ $producto->name }}</p>
                    <p style="font-size:11px;color:#94a3b8;">por {{ $producto->user->name }}</p>
                </div>
                <span style="color:#64748b;">{{ $producto->category }}</span>
                <span class="{{ $producto->stock > 0 ? 'stock-ok' : 'stock-out' }}">
                    {{ str_pad($producto->stock, 2, '0', STR_PAD_LEFT) }}
                </span>
                <span style="font-weight:600;color:#0f172a;">${{ number_format($producto->price, 2) }}</span>
                <a href="{{ route('products.edit', $producto) }}" class="btn-secondary" style="padding:5px 12px;">
                    Editar
                </a>
            </div>
        @empty
            <div style="padding:32px;text-align:center;color:#94a3b8;font-size:13px;">
                No hay productos registrados aún.
            </div>
        @endforelse
        <div style="padding:14px 24px;border-top:1px solid #f1f5f9;text-align:right;">
            <a href="{{ route('products.index') }}" style="font-size:13px;color:#3b4fd8;text-decoration:none;font-weight:500;">
                Ver todos →
            </a>
        </div>
    </div>

</x-app-layout>