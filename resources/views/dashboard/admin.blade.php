<x-app-layout>
    <x-slot name="title">Dashboard — Admin</x-slot>

    {{-- Encabezado --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">
            Bienvenido, {{ auth()->user()->name }} 👋
        </h1>
        <p class="text-gray-500 text-sm mt-1">Panel de administración — visión general del sistema</p>
    </div>

    {{-- Tarjetas de estadísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-600">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total productos</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_productos'] }}</p>
            <a href="{{ route('products.index') }}" class="text-xs text-blue-600 mt-2 inline-block hover:underline">Ver todos →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Usuarios registrados</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_usuarios'] }}</p>
            <a href="{{ route('users.index') }}" class="text-xs text-green-600 mt-2 inline-block hover:underline">Ver todos →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-red-400">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Sin stock</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['sin_stock'] }}</p>
            <p class="text-xs text-red-400 mt-2">Requieren atención</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Categorías</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['categorias'] }}</p>
            <p class="text-xs text-gray-400 mt-2">Categorías activas</p>
        </div>

    </div>

    {{-- Tablas recientes --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Productos recientes --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">Productos recientes</h2>
                <a href="{{ route('products.create') }}"
                   class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 transition">
                    + Nuevo
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse ($productos_recientes as $producto)
                    <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $producto->name }}</p>
                            <p class="text-xs text-gray-400">{{ $producto->category }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-700">
                                ${{ number_format($producto->price, 0, ',', '.') }}
                            </p>
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ $producto->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                Stock: {{ $producto->stock }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-4 text-sm text-gray-400">No hay productos registrados.</p>
                @endforelse
            </div>
        </div>

        {{-- Usuarios recientes --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">Usuarios recientes</h2>
                <a href="{{ route('users.create') }}"
                   class="text-xs bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700 transition">
                    + Nuevo
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse ($usuarios_recientes as $usuario)
                    <div class="px-6 py-3 flex items-center gap-3 hover:bg-gray-50 transition">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-semibold text-xs flex-shrink-0">
                            {{ strtoupper(substr($usuario->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $usuario->name }}</p>
                            <p class="text-xs text-gray-400">{{ $usuario->email }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full capitalize
                            {{ $usuario->hasRole('admin') ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $usuario->getRoleNames()->first() ?? 'Sin rol' }}
                        </span>
                    </div>
                @empty
                    <p class="px-6 py-4 text-sm text-gray-400">No hay usuarios registrados.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>