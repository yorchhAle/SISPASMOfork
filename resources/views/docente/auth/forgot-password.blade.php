@vite('resources/css/crud.css')

<div class="crud-wrap">
    <section class="crud-card">
        <header class="crud-hero">
            <h2 class="crud-hero-title">Recuperar contraseña</h2>
            <p class="crud-hero-subtitle">Ingresa tu correo</p>
        </header>

        {{-- Bloque de estatus, muestra el mensaje de éxito si el enlace fue enviado correctamente. --}}
        <div class="crud-body">
            @if (session('status'))
                <div class="gm-ok">{{ session('status') }}</div>
            @endif

            {{-- Bloque de errores, muestra los errores de validación de laravel (si los hay). --}}
            @if ($errors->any())
                <ul class="gm-errors">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            @endif

            {{-- Formulario principal, envía el correo para solicitar el enlace de restablecimiento (ruta por defecto de laravel). --}}
            <form method="POST" action="{{ route('password.email') }}" class="gm-form">
                @csrf
                <div>
                    <input type="email" name="correo" value="{{ old('correo') }}" placeholder="Correo" required>
                </div>
                
                {{-- Bloque de acciones, botón de envío y enlace para volver al login. --}}
                <div class="actions">
                    <a href="{{ route('docente.login') }}" class="btn btn-ghost">Volver</a>
                    <button class="btn btn-primary" type="submit">Enviar enlace</button>
                </div>
            </form>
        </div>
    </section>
</div>