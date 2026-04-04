<x-app-layout>
    <x-slot name="title">{{ $product->name }}</x-slot>

    {{-- Breadcrumb --}}
    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#94a3b8;margin-bottom:20px;">
        <a href="{{ route('products.index') }}" style="color:#3b4fd8;text-decoration:none;">Productos</a>
        <span>›</span>
        <span style="color:#0f172a;font-weight:500;">{{ $product->name }}</span>
    </div>

    <div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;max-width:900px;">

        {{-- Imagen --}}
        <div class="card" style="padding:20px;text-align:center;">
            @if ($product->image)
                <img src="{{ Storage::url($product->image) }}"
                     alt="{{ $product->name }}"
                     style="width:100%;max-width:240px;height:220px;object-fit:cover;
                         border-radius:10px;margin:0 auto;display:block;">
            @else
                <div style="width:100%;height:200px;background:#f1f5f9;border-radius:10px;
                    display:flex;align-items:center;justify-content:center;">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.2">
                        <path d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7"/>
                    </svg>
                </div>
            @endif

            {{-- Stock badge --}}
            <div style="margin-top:16px;">
                <span class="{{ $product->stock > 0 ? 'stock-ok' : 'stock-out' }}"
                      style="font-size:13px;padding:5px 14px;">
                    {{ $product->stock > 0 ? $product->stock . ' en stock' : 'Sin stock' }}
                </span>
            </div>
        </div>

        {{-- Detalles --}}
        <div class="card" style="padding:28px;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;">
                <div>
                    <h1 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">
                        {{ $product->name }}
                    </h1>
                    <span style="font-size:12px;background:#f1f5f9;color:#475569;
                        padding:3px 10px;border-radius:99px;font-weight:500;
                        display:inline-block;margin-top:8px;">
                        {{ $product->category }}
                    </span>
                </div>
                <p style="font-size:26px;font-weight:800;color:#3b4fd8;margin:0;">
                    ${{ number_format($product->price, 2) }}
                </p>
            </div>

            @if ($product->description)
                <p style="font-size:14px;color:#64748b;line-height:1.7;margin-bottom:20px;">
                    {{ $product->description }}
                </p>
            @else
                <p style="font-size:13px;color:#94a3b8;font-style:italic;margin-bottom:20px;">
                    Sin descripción.
                </p>
            @endif

            {{-- Meta info --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;
                padding-top:20px;border-top:1px solid #f1f5f9;margin-bottom:24px;">
                <div>
                    <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">Creado por</p>
                    <p style="font-size:13px;color:#374151;margin-top:3px;font-weight:500;">{{ $product->user->name }}</p>
                </div>
                <div>
                    <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">Última actualización</p>
                    <p style="font-size:13px;color:#374151;margin-top:3px;">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">Fecha de creación</p>
                    <p style="font-size:13px;color:#374151;margin-top:3px;">{{ $product->created_at->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">ID producto</p>
                    <p style="font-size:13px;color:#374151;margin-top:3px;">#{{ $product->id }}</p>
                </div>
            </div>

            {{-- Acciones --}}
            <div style="display:flex;gap:10px;">
                <a href="{{ route('products.edit', $product) }}" class="btn-primary">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Editar
                </a>

                <a href="{{ route('products.index') }}" class="btn-secondary">
                    ← Volver al catálogo
                </a>

                @role('admin')
                <form method="POST" action="{{ route('products.destroy', $product) }}"
                      onsubmit="return confirm('¿Eliminar «{{ $product->name }}»? Esta acción no se puede deshacer.')"
                      style="margin-left:auto;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                            <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                        </svg>
                        Eliminar
                    </button>
                </form>
                @endrole
            </div>
        </div>
    </div>

</x-app-layout>