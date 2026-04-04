<x-app-layout>
    <x-slot name="title">Gestión de Usuarios</x-slot>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <h1 style="font-size:20px;font-weight:800;color:#0f172a;margin:0;">Gestión de Usuarios</h1>
            <p style="font-size:13px;color:#94a3b8;margin-top:3px;">{{ $users->total() }} usuario(s) registrados</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-primary">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Usuario
        </a>
    </div>

    <div class="card">
        <div class="table-header" style="grid-template-columns:2fr 2fr 1.2fr 1fr 0.8fr;">
            <span>Usuario</span>
            <span>Correo</span>
            <span>Rol</span>
            <span>Registro</span>
            <span>Acción</span>
        </div>

        @forelse ($users as $user)
            <div class="table-row" style="grid-template-columns:2fr 2fr 1.2fr 1fr 0.8fr;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:34px;height:34px;background:#eff6ff;border-radius:50%;
                        display:flex;align-items:center;justify-content:center;
                        font-size:12px;font-weight:700;color:#3b4fd8;flex-shrink:0;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <span style="font-weight:600;color:#0f172a;">{{ $user->name }}</span>
                </div>
                <span style="color:#64748b;font-size:13px;">{{ $user->email }}</span>
                <span class="badge-rol {{ $user->hasRole('admin') ? 'badge-admin' : 'badge-emp' }}">
                    {{ ucfirst($user->getRoleNames()->first() ?? 'Sin rol') }}
                </span>
                <span style="font-size:12px;color:#94a3b8;">
                    {{ $user->created_at->format('d/m/Y') }}
                </span>
                <a href="{{ route('users.edit', $user) }}" class="icon-btn icon-btn-edit" title="Editar">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </a>
            </div>
        @empty
            <div style="padding:40px;text-align:center;color:#94a3b8;font-size:13px;">
                No hay usuarios registrados.
            </div>
        @endforelse
    </div>

    @if ($users->hasPages())
        <div style="margin-top:20px;display:flex;justify-content:center;">
            {{ $users->links() }}
        </div>
    @endif

</x-app-layout>