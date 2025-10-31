<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mis quejas/sugerencias</title>
  {{-- Se incluyen fuentes y estilos css específicos. --}}
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/dashboard.css', 'resources/css/crud.css'])
</head>
<body>

  {{-- Bloque de encabezado (header), navegación de usuario (regresar/cerrar sesión). --}}
  <header class="site-header">
    <div class="header-container">
      <div class="logo">
        <img src="{{ asset('images/logoprincipal.png') }}" alt="Grupo Morelos"/>
          <span>GRUPO MORELOS</span>
      </div>
        <nav>
          <ul class="nav-links">
          {{-- Enlace para regresar a la página anterior en el historial. --}}
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
            <h1 class="crud-hero-title">Mis quejas y sugerencias</h1>
          </div>

          <div class="crud-body">
            @if (session('ok'))
              <div class="gm-ok">{{ session('ok') }}</div>
            @endif

            {{-- Enlace para regresar a la página anterior en el historial. --}}
            @if ($quejas->count() === 0)
              <div class="gm-empty">Aún no has enviado ninguna.</div>
              <div class="actions" style="justify-content:flex-start;margin-top:10px">
                <a class="btn btn-primary" href="{{ route('quejas.create') }}">Enviar una</a>
              </div>
            @else
              <div class="table-responsive">
                <table class="gm-table">
                  <thead>
                    <tr>
                      <th>Tipo</th>
                      <th>Mensaje</th>
                      <th>Contacto</th>
                      <th>Estatus</th>
                    </tr>
                  </thead>
                  <tbody>
                    {{-- Bloque de datos (bucle), itera sobre la colección paginada de quejas ($quejas) del usuario. --}}
                    @foreach ($quejas as $q)
                    <tr>
                      <td style="text-transform:capitalize">{{ $q->tipo }}</td>
                      <td>{{ Str::limit($q->mensaje, 80) }}</td>
                      <td>{{ $q->contacto ?: '—' }}</td>
                      <td>
                        {{-- Bloque de estatus, resalta visualmente si está 'atendido' o 'pendiente'. --}}
                        @if($q->estatus === 'Atendido')
                          <span style="color:#065f46;font-weight:800">Atendido</span>
                        @else
                          <span style="color:#b45309;font-weight:800">Pendiente</span>
                        @endif
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              {{-- Bloque de paginación. --}}
              <div class="pager">{{ $quejas->links() }}</div>
            @endif
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>