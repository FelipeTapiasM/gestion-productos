<x-app-layout>
    <x-slot name="title">Dashboard — Empleado</x-slot>

    {{-- Encabezado --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">
            Bienvenido, {{ auth()->user()->name }} 👋
        </h1>
        <p class="text-gray-500 text-sm mt-1">Panel de empleado — gestión de productos</p>
    </div>

    {{-- Tarjetas de estadísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">

        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-600">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total productos</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_productos'] }}</p>
            <a href="{{ route('products.index') }}" class="text-xs text-blue-600 mt-2 inline-block hover:underline">Ver catálogo →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-yellow-400">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Mis productos</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['mis_productos'] }}</p>
            <p class="text-xs text-gray-400 mt-2">Registrados por ti</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-red-400">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Sin stock</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['sin_stock'] }}</p>
            <p class="text-xs text-red-400 mt-2">Requieren atención</p>
        </div>

    </div>

    {{-- Accesos rápidos --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
        <a href="{{ route('products.create') }}"
           class="flex items-center gap-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-5 transition shadow-sm">
            <div class="bg-white/20 rounded-lg p-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold">Registrar producto</p>
                <p class="text-sm text-blue-200">Agregar un nuevo ítem al catálogo</p>
            </div>
        </a>

        <a href="{{ route('products.index') }}"
           class="flex items-center gap-4 bg-white hover:bg-gray-50 text-gray-800 rounded-xl p-5 transition shadow-sm border border-gray-200">
            <div class="bg-blue-100 rounded-lg p-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold">Ver catálogo</p>
                <p class="text-sm text-gray-400">Consultar todos los productos</p>
            </div>
        </a>
    </div>

    {{-- Productos recientes --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-700">Productos recientes</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse ($productos_recientes as $producto)
                <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $producto->name }}</p>
                        <p class="text-xs text-gray-400">{{ $producto->category }} · Por {{ $producto->user->name }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-gray-700">
                            ${{ number_format($producto->price, 0, ',', '.') }}
                        </span>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $producto->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            Stock: {{ $producto->stock }}
                        </span>
                        <a href="{{ route('products.edit', $producto) }}"
                           class="text-xs text-blue-600 hover:underline">Editar</a>
                    </div>
                </div>
            @empty
                <p class="px-6 py-4 text-sm text-gray-400">No hay productos registrados aún.</p>
            @endforelse
        </div>
    </div>

</x-app-layout>