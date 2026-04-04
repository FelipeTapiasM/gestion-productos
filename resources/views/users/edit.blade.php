<x-app-layout>
    <x-slot name="title">Editar Usuario</x-slot>

    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#94a3b8;margin-bottom:20px;">
        <a href="{{ route('users.index') }}" style="color:#3b4fd8;text-decoration:none;">Usuarios</a>
        <span>›</span>
        <span style="color:#0f172a;font-weight:500;">{{ $user->name }}</span>
    </div>

    <div class="card" style="max-width:560px;">
        <div class="card-header"><h3>Editar usuario</h3></div>
        <div style="padding:24px;">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf @method('PUT')

                <div style="margin-bottom:18px;">
                    <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:6px;">
                        Nombre completo <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           style="width:100%;height:42px;border:1.5px solid {{ $errors->has('name') ? '#fca5a5' : '#e2e8f0' }};
                               border-radius:8px;padding:0 14px;font-size:14px;color:#0f172a;background:#f8fafc;outline:none;">
                    @error('name') <p style="font-size:12px;color:#dc2626;margin-top:4px;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:18px;">
                    <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:6px;">
                        Correo electrónico <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           style="width:100%;height:42px;border:1.5px solid {{ $errors->has('email') ? '#fca5a5' : '#e2e8f0' }};
                               border-radius:8px;padding:0 14px;font-size:14px;color:#0f172a;background:#f8fafc;outline:none;">
                    @error('email') <p style="font-size:12px;color:#dc2626;margin-top:4px;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:24px;">
                    <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:6px;">
                        Rol <span style="color:#ef4444;">*</span>
                    </label>
                    <select name="role"
                            style="width:100%;height:42px;border:1.5px solid {{ $errors->has('role') ? '#fca5a5' : '#e2e8f0' }};
                                border-radius:8px;padding:0 14px;font-size:14px;color:#0f172a;background:#f8fafc;outline:none;">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <p style="font-size:12px;color:#dc2626;margin-top:4px;">{{ $message }}</p> @enderror
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:16px;border-top:1px solid #f1f5f9;">
                    <a href="{{ route('users.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>