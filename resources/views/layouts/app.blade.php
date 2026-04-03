<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'GestionTienda' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">

    {{-- Navbar --}}
    <nav class="bg-blue-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-semibold text-lg">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24">
                        <rect x="2" y="2" width="9" height="9" rx="2" fill="white" opacity="0.9"/>
                        <rect x="13" y="2" width="9" height="9" rx="2" fill="white" opacity="0.5"/>
                        <rect x="2" y="13" width="9" height="9" rx="2" fill="white" opacity="0.5"/>
                        <rect x="13" y="13" width="9" height="9" rx="2" fill="white" opacity="0.9"/>
                    </svg>
                    GestionTienda
                </a>

                {{-- Menú principal --}}
                <div class="hidden md:flex items-center gap-6 text-sm">
                    <a href="{{ route('dashboard') }}"
                       class="hover:text-blue-200 transition {{ request()->routeIs('dashboard') ? 'text-blue-300 font-semibold' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('products.index') }}"
                       class="hover:text-blue-200 transition {{ request()->routeIs('products.*') ? 'text-blue-300 font-semibold' : '' }}">
                        Productos
                    </a>

                    {{-- Solo Admin --}}
                    @role('admin')
                    <a href="{{ route('users.index') }}"
                       class="hover:text-blue-200 transition {{ request()->routeIs('users.*') ? 'text-blue-300 font-semibold' : '' }}">
                        Usuarios
                    </a>
                    @endrole
                </div>

                {{-- Usuario y logout --}}
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center font-semibold text-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:block">
                            <p class="font-medium text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-blue-300 capitalize">
                                {{ auth()->user()->getRoleNames()->first() ?? 'Sin rol' }}
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-blue-200 hover:text-white transition text-sm">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 rounded-lg px-4 py-3 flex items-center gap-2 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 rounded-lg px-4 py-3 flex items-center gap-2 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Contenido principal --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="text-center text-xs text-gray-400 py-6 mt-8 border-t border-gray-200">
        GestionTienda v1.0 &copy; {{ date('Y') }}
    </footer>

</body>
</html>