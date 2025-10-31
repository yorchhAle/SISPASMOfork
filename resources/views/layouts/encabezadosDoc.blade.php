<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Panel de Docente')</title>

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
  @vite('resources/css/dashboard.css')
  @vite('resources/css/crud.css')
  @vite(['resources/css/sub.css', 'resources/js/dashboard.js'])
</head>
<body>
  {{-- Bloque de encabezado (header), contiene logo y navegación principal. --}}
  <header class="site-header">
    <div class="header-container">
      <div class="logo">
        <img src="{{ asset('images/logoprincipal.png') }}" alt="Grupo Morelos"/>
          <span>GRUPO MORELOS</span>
      </div>
        <nav>
          <ul class="nav-links">
            <li>
              <a href="{{ route('docente.dashboard') }}">Panel</a>
            </li>

            <li>
              {{-- Formulario de cierre de sesión (logout), utiliza post y es activado por javascript. --}}
              <form method="POST" action="{{ route('docente.logout') }}">
                @csrf
                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">Cerrar sesión</a>
              </form>
            </li>
          </ul>
        </nav>
    </div>
  </header>

  <div class="dash">
    {{-- Bloque de barra lateral (aside/sidebar), menú de navegación principal. --}}
    <aside class="sidebar">
      <div class="profile">
        <div class="avatar" aria-hidden="true">👤</div>
        <div class="who">
          <div class="name">
            {{ auth()->user()->nombre ?? 'Usuario' }}
            {{ auth()->user()->apellidoP ?? '' }}
          </div>
          <div class="role">{{ auth()->user()->rol->nombre_rol ?? '—' }}</div>
        </div>
      </div>

      {{-- Enlace, información personal. --}}
      <nav class="nav">
        <div class="group">
          <div class="group-title">MI INFORMACIÓN</div>
          <ul class="menu">
            <li><a href="{{route('docente.dashboard')}}">Información personal</a></li>
          </ul>
        </div>

        {{-- Grupo de enlaces, módulos. --}}
        <div class="divider"></div>
        <div class="group">
          <div class="group-title">MÓDULOS (MATERIAS)</div>
          <ul class="menu">
            <li><a href="{{ route('docente.horario') }}">Horarios</a></li>
          </ul>
        </div>

        {{-- Grupo de enlaces, alumnos. --}}
        <div class="divider"></div>
        <div class="group">
          <div class="group-title">ALUMNOS</div>
          <ul class="menu">
            <li><a href="{{route('calif.create')}}">Calificaciones</a></li>
          </ul>
        </div>

        {{-- Grupo de enlaces, sugerencias. --}}
        <div class="divider"></div>
        <div class="group">
          <div class="group-title">SUGERENCIAS</div>
          <ul class="menu">
            <li><a href="{{ route('quejas.create') }}">Nueva queja/sugerencia</a></li>
            <li><a href="{{ route('quejas.propias') }}">Mis quejas/sugerencias</a></li>  
          </ul>
        </div>
      </nav>
    </aside>

    <main class="content">
      @yield('content')
    </main>
  </div>
  <script src="https://cdn.userway.org/widget.js" data-account="kvnkkEfZx0"></script>
  @stack('scripts')

</body>
</html>