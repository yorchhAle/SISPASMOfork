<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nueva queja/sugerencia</title>

  {{-- Se incluyen fuentes y estilos css específicos. --}}
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/dashboard.css', 'resources/css/crud.css'])
</head>
<body>

  {{-- Bloque de encabezado (header): contiene logo y navegación. --}}
  <header class="site-header">
    <div class="header-container">
      <div class="logo">
        <img src="{{ asset('images/logoprincipal.png') }}" alt="Grupo Morelos"/>
          <span>GRUPO MORELOS</span>
      </div>
        <nav>
          <ul class="nav-links">
          <li><a href="#" onclick="window.history.back(); return false;">Regresar</a></li>
          <li><a href="{{ route('inicio') }}">Cerrar sesión</a></li>
          </ul>
        </nav>
    </div>
  </header>

    <main class="content">
      <div class="crud-wrap">
        <div class="crud-card">
          <div class="crud-hero">
            <h1 class="crud-hero-title">Nueva queja o sugerencia</h1>
            <p class="crud-hero-subtitle">Gracias por ayudarnos a mejorar</p>
          </div>

          <div class="crud-body">
          {{-- Bloque de errores, muestra los errores de validación de laravel (si los hay). --}}
            @if ($errors->any())
              <div class="gm-errors">
                <ul>
                  @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            {{-- Formulario principal, envía la queja/sugerencia para su registro (método post). --}}
            <form class="gm-form" method="POST" action="{{ route('quejas.store') }}">
              @csrf
              <div>
                <div>
                  <label for="tipo"><strong>Tipo</strong></label>
                  <select id="tipo" name="tipo" required>
                    <option value="">Selecciona…</option>
                    <option value="queja"      {{ old('tipo')=='queja'?'selected':'' }}>Queja</option>
                    <option value="sugerencia" {{ old('tipo')=='sugerencia'?'selected':'' }}>Sugerencia</option>
                  </select>
                </div>

                <div>
                  <label for="contacto"><strong>Contacto (opcional)</strong></label>
                  {{-- Campo de contacto, opcional, para seguimiento. --}}
                  <input id="contacto" name="contacto" type="text" placeholder="Correo o teléfono"
                         value="{{ old('contacto') }}">
                </div>

                <div style="grid-column:1 / -1">
                  <label for="mensaje"><strong>Mensaje</strong></label>
                  <textarea id="mensaje" name="mensaje" rows="6" required
                            placeholder="Describe tu queja o sugerencia con detalle…">{{ old('mensaje') }}</textarea>
                </div>
              </div>

              {{-- Bloque de acciones, botón de envío y cancelar. --}}
              <div class="actions">
                <a class="btn btn-danger" href="{{ url()->previous() }}">Cancelar</a>
                <button class="btn btn-primary" type="submit">Enviar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>