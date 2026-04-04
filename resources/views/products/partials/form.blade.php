@props(['product' => null, 'categories' => collect()])

@php
    $isEdit = !is_null($product);
    $action = $isEdit
        ? route('products.update', $product)
        : route('products.store');
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        {{-- Nombre --}}
        <div style="grid-column:1/-1;">
            <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:6px;">
                Nombre del producto <span style="color:#ef4444;">*</span>
            </label>
            <input type="text" name="name"
                   value="{{ old('name', $product?->name) }}"
                   placeholder="Ej: Laptop Dell Inspiron 15"
                   style="width:100%;height:42px;border:1.5px solid {{ $errors->has('name') ? '#fca5a5' : '#e2e8f0' }};
                       border-radius:8px;padding:0 14px;font-size:14px;color:#0f172a;
                       background:{{ $errors->has('name') ? '#fef2f2' : '#f8fafc' }};outline:none;">
            @error('name')
                <p style="font-size:12px;color:#dc2626;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Precio --}}
        <div>
            <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:6px;">
                Precio <span style="color:#ef4444;">*</span>
            </label>
            <div style="position:relative;">
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);
                    font-size:14px;color:#64748b;">$</span>
                <input type="number" name="price" step="0.01" min="0.01"
                       value="{{ old('price', $product?->price) }}"
                       placeholder="0.00"
                       style="width:100%;height:42px;border:1.5px solid {{ $errors->has('price') ? '#fca5a5' : '#e2e8f0' }};
                           border-radius:8px;padding:0 14px 0 28px;font-size:14px;color:#0f172a;
                           background:{{ $errors->has('price') ? '#fef2f2' : '#f8fafc' }};outline:none;">
            </div>
            @error('price')
                <p style="font-size:12px;color:#dc2626;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Stock --}}
        <div>
            <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:6px;">
                Stock <span style="color:#ef4444;">*</span>
            </label>
            <input type="number" name="stock" min="0"
                   value="{{ old('stock', $product?->stock ?? 0) }}"
                   placeholder="0"
                   style="width:100%;height:42px;border:1.5px solid {{ $errors->has('stock') ? '#fca5a5' : '#e2e8f0' }};
                       border-radius:8px;padding:0 14px;font-size:14px;color:#0f172a;
                       background:{{ $errors->has('stock') ? '#fef2f2' : '#f8fafc' }};outline:none;">
            @error('stock')
                <p style="font-size:12px;color:#dc2626;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Categoría --}}
        <div style="grid-column:1/-1;">
            <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:6px;">
                Categoría <span style="color:#ef4444;">*</span>
            </label>
            <div style="display:flex;gap:10px;">
                <select name="category" id="categorySelect"
                    style="flex:1;height:42px;border:1.5px solid {{ $errors->has('category') ? '#fca5a5' : '#e2e8f0' }};
                        border-radius:8px;padding:0 14px;font-size:14px;color:#0f172a;
                        background:{{ $errors->has('category') ? '#fef2f2' : '#f8fafc' }};outline:none;"
                    onchange="toggleNewCategory(this.value)">
                    <option value="">-- Selecciona --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}"
                            {{ old('category', $product?->category) == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                    <option value="__nueva__">+ Nueva categoría...</option>
                </select>
                <input type="text" id="newCategoryInput" name="category_new"
                       placeholder="Escribe la nueva categoría"
                       style="display:none;flex:1;height:42px;border:1.5px solid #e2e8f0;
                           border-radius:8px;padding:0 14px;font-size:14px;color:#0f172a;
                           background:#f8fafc;outline:none;">
            </div>
            @error('category')
                <p style="font-size:12px;color:#dc2626;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Descripción --}}
        <div style="grid-column:1/-1;">
            <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:6px;">
                Descripción
            </label>
            <textarea name="description" rows="3"
                      placeholder="Descripción detallada del producto..."
                      style="width:100%;border:1.5px solid #e2e8f0;border-radius:8px;
                          padding:10px 14px;font-size:14px;color:#0f172a;
                          background:#f8fafc;outline:none;resize:vertical;font-family:inherit;">{{ old('description', $product?->description) }}</textarea>
            @error('description')
                <p style="font-size:12px;color:#dc2626;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Imagen --}}
        <div style="grid-column:1/-1;">
            <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:6px;">
                Imagen del producto
            </label>

            {{-- Preview imagen actual --}}
            @if ($isEdit && $product->image)
                <div style="margin-bottom:10px;display:flex;align-items:center;gap:12px;">
                    <img src="{{ Storage::url($product->image) }}"
                         alt="Imagen actual"
                         style="width:60px;height:60px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                    <p style="font-size:12px;color:#64748b;">Imagen actual. Sube una nueva para reemplazarla.</p>
                </div>
            @endif

            <div style="border:2px dashed #e2e8f0;border-radius:10px;padding:20px;text-align:center;background:#f8fafc;"
                 ondragover="event.preventDefault()"
                 ondrop="handleDrop(event)">
                <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5"
                     style="margin:0 auto 8px;display:block;">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <p style="font-size:13px;color:#64748b;margin:0;">
                    Arrastra una imagen aquí o
                    <label for="imageInput" style="color:#3b4fd8;cursor:pointer;font-weight:600;">
                        haz clic para seleccionar
                    </label>
                </p>
                <p style="font-size:11px;color:#94a3b8;margin-top:4px;">JPG, PNG o WEBP — máx. 2MB</p>
                <input type="file" id="imageInput" name="image" accept="image/*"
                       style="display:none;" onchange="previewImage(this)">
            </div>

            {{-- Preview de imagen nueva --}}
            <div id="imagePreview" style="display:none;margin-top:10px;">
                <img id="previewImg" src=""
                     style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                <button type="button" onclick="clearImage()"
                    style="margin-left:10px;font-size:12px;color:#dc2626;background:none;border:none;cursor:pointer;">
                    ✕ Quitar
                </button>
            </div>

            @error('image')
                <p style="font-size:12px;color:#dc2626;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

    </div>

    {{-- Botones --}}
    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:24px;padding-top:20px;border-top:1px solid #f1f5f9;">
        <a href="{{ route('products.index') }}" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">
            {{ $isEdit ? 'Guardar cambios' : 'Crear producto' }}
        </button>
    </div>
</form>

<script>
function toggleNewCategory(val) {
    const input  = document.getElementById('newCategoryInput');
    const select = document.getElementById('categorySelect');
    if (val === '__nueva__') {
        input.style.display = 'block';
        input.required = true;
        select.name = '_category_select';
        input.name  = 'category';
        input.focus();
    } else {
        input.style.display = 'none';
        input.required = false;
        select.name = 'category';
        input.name  = 'category_new';
    }
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'flex';
            document.getElementById('imagePreview').style.alignItems = 'center';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearImage() {
    document.getElementById('imageInput').value = '';
    document.getElementById('imagePreview').style.display = 'none';
}

function handleDrop(e) {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const input = document.getElementById('imageInput');
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        previewImage(input);
    }
}
</script>