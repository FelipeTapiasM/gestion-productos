<x-guest-layout>

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="error-msg">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Status (ej: contraseña reseteada) --}}
    @if (session('status'))
        <div class="status-msg">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="field">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="correo@ejemplo.com"
                   required autofocus autocomplete="username">
        </div>

        {{-- Contraseña --}}
        <div class="field">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password"
                   placeholder="••••••••"
                   required autocomplete="current-password">
        </div>

        {{-- Recordar / Olvidé --}}
        <div class="field-row">
            <label class="check-label">
                <input type="checkbox" name="remember"> Recordarme
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        {{-- Botón --}}
        <button type="submit" class="btn-login">
            Iniciar sesión
        </button>
    </form>

</x-guest-layout>