<x-app-layout>
    <x-slot name="title">Acceso denegado</x-slot>

    <div class="flex flex-col items-center justify-center py-24 text-center">
        <div class="text-8xl font-bold text-blue-100 mb-4">403</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Acceso denegado</h1>
        <p class="text-gray-500 mb-8 max-w-md">
            No tienes permiso para acceder a esta sección.
            Si crees que esto es un error, contacta al administrador.
        </p>
        <a href="{{ route('dashboard') }}"
           class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium">
            Volver al dashboard
        </a>
    </div>

</x-app-layout>