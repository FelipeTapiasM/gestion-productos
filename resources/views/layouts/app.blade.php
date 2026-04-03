<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'GestionTienda' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { display: flex; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f1f5f9; }
        .sidebar { width: 240px; min-height: 100vh; background: #0f172a; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 40; }
        .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 10px 16px; border-radius: 8px; font-size: 13.5px; color: #94a3b8; transition: all 0.15s; margin: 2px 10px; text-decoration: none; border: none; background: none; width: calc(100% - 20px); cursor: pointer; }
        .sidebar-link:hover { background: #1e293b; color: #e2e8f0; }
        .sidebar-link.active { background: #3b4fd8; color: #ffffff; }
        .sidebar-section { font-size: 10px; font-weight: 700; color: #475569; letter-spacing: 1px; text-transform: uppercase; padding: 16px 20px 6px; }
        .main-content { flex: 1; margin-left: 240px; width: calc(100% - 240px); min-height: 100vh; display: flex; flex-direction: column; }
        .topbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 14px 28px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 30; }
        .badge-rol { font-size: 11px; padding: 3px 11px; border-radius: 99px; font-weight: 600; }
        .badge-admin { background: #dbeafe; color: #1d4ed8; }
        .badge-emp   { background: #dcfce7; color: #15803d; }
        .btn-primary { background: #3b4fd8; color: #fff; border: none; border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s; }
        .btn-primary:hover { background: #3040c0; color: #fff; }
        .btn-secondary { background: #f1f5f9; color: #374151; border: 1px solid #e2e8f0; border-radius: 8px; padding: 7px 14px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s; }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-danger { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 8px; padding: 7px 14px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s; }
        .btn-danger:hover { background: #fee2e2; }
        .card { background: #fff; border-radius: 14px; box-shadow: 0 1px 6px rgba(0,0,0,0.06); overflow: hidden; }
        .card-header { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
        .card-header h3 { font-size: 15px; font-weight: 700; color: #0f172a; }
        .stat-card { background: #fff; border-radius: 14px; padding: 22px 24px; box-shadow: 0 1px 6px rgba(0,0,0,0.06); border-left: 4px solid; }
        .icon-btn { width: 30px; height: 30px; border-radius: 7px; border: none; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.12s; text-decoration: none; }
        .icon-btn-view { background: #eff6ff; color: #3b82f6; }
        .icon-btn-view:hover { background: #dbeafe; }
        .icon-btn-edit { background: #f0fdf4; color: #16a34a; }
        .icon-btn-edit:hover { background: #dcfce7; }
        .icon-btn-del  { background: #fef2f2; color: #dc2626; }
        .icon-btn-del:hover  { background: #fee2e2; }
        .table-header { display: grid; padding: 10px 24px; font-size: 11px; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
        .table-row { display: grid; padding: 14px 24px; border-bottom: 1px solid #f8fafc; align-items: center; font-size: 13px; color: #374151; }
        .table-row:hover { background: #f8fafc; }
        .flash-success { margin: 20px 28px 0; background: #f0fdf4; border: 1px solid #86efac; color: #15803d; border-radius: 10px; padding: 12px 16px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .flash-error   { margin: 20px 28px 0; background: #fef2f2; border: 1px solid #fca5a5; color: #b91c1c; border-radius: 10px; padding: 12px 16px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .page-content  { padding: 28px; flex: 1; }
        .stock-ok  { background: #dcfce7; color: #15803d; font-size: 11px; padding: 2px 8px; border-radius: 99px; font-weight: 600; }
        .stock-out { background: #fee2e2; color: #dc2626; font-size: 11px; padding: 2px 8px; border-radius: 99px; font-weight: 600; }
    </style>
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar">

    {{-- Logo --}}
    <div style="padding:22px 20px 16px; border-bottom:1px solid #1e293b;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:36px;height:36px;background:#3b4fd8;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24">
                    <rect x="2" y="2" width="9" height="9" rx="2" fill="white" opacity="0.9"/>
                    <rect x="13" y="2" width="9" height="9" rx="2" fill="white" opacity="0.5"/>
                    <rect x="2" y="13" width="9" height="9" rx="2" fill="white" opacity="0.5"/>
                    <rect x="13" y="13" width="9" height="9" rx="2" fill="white" opacity="0.9"/>
                </svg>
            </div>
            <span style="color:#f1f5f9;font-weight:700;font-size:16px;">GestionTienda</span>
        </div>
        <div style="margin-top:10px;">
            @role('admin')
                <span class="badge-rol badge-admin">● Administrador</span>
            @else
                <span class="badge-rol badge-emp">● Empleado</span>
            @endrole
        </div>
    </div>

    {{-- Navegación --}}
    <nav style="flex:1;padding:12px 0;overflow-y:auto;">
        <p class="sidebar-section">Principal</p>

        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            Panel de Control
        </a>

        <a href="{{ route('products.index') }}"
           class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7"/>
            </svg>
            Catálogo Completo
        </a>

        @role('admin')
        <p class="sidebar-section">Administración</p>

        <a href="{{ route('users.index') }}"
           class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Gestión de Usuarios
        </a>
        @endrole
    </nav>

    {{-- Cerrar sesión --}}
    <div style="padding:14px 10px;border-top:1px solid #1e293b;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link" style="color:#f87171;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                </svg>
                Salida Segura
            </button>
        </form>
    </div>
</aside>

{{-- ── CONTENIDO PRINCIPAL ── --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <h2 style="font-size:17px;font-weight:700;color:#0f172a;">
            {{ $title ?? 'Panel de Control' }}
        </h2>
        <div style="display:flex;align-items:center;gap:14px;">
            <span style="font-size:12px;color:#64748b;">
                Rol: <strong style="color:#1e293b;">
                    @role('admin') Administrador (Acceso Total) @else Empleado @endrole
                </strong>
            </span>
            <div style="width:34px;height:34px;background:#3b4fd8;border-radius:50%;
                display:flex;align-items:center;justify-content:center;
                color:#fff;font-weight:700;font-size:13px;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="flash-success">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flash-error">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Contenido --}}
    <div class="page-content">
        {{ $slot }}
    </div>
</div>

</body>
</html>