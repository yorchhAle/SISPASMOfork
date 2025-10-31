@extends('layouts.encabezados')
@section('title', 'Dashboard de Administrador')

@section('content')
  <h1>Bienvenido al panel de administrador</h1>

  {{-- Bloque de estadísticas principales: muestra los conteos totales de usuarios. --}}
  <section class="stats-grid">
    <article class="stat-card">
      <h3>Alumnos</h3>
      <div id="nAlumnos" class="stat-number">{{ $alumnosTotal ?? 0 }}</div>
    </article>

    <article class="stat-card">
      <h3>Docentes</h3>
      <div id="nDocentes" class="stat-number">{{ $docentesTotal ?? 0 }}</div>
    </article>

    <article class="stat-card">
      <h3>Aspirantes</h3>
      <div id="nAspirantes" class="stat-number">{{ $aspirantesTotal ?? 0 }}</div>
    </article>
  </section>

  {{-- Bloque de gráfica de estatus de alumnos (activos/baja). --}}
  <section class="panel">
    <div class="panel-header">
      <h2>Alumnos activos/baja</h2>
    </div>
    <div class="panel-body">
      {{-- Canvas de la gráfica, `data-activos` y `data-baja` inicializan la gráfica. --}}
      {{-- `data-metrics-url` proporciona la url para las actualizaciones asíncronas de javascript. --}}
      <canvas id="alumnosChart"
        data-activos="{{ $alumnosActivos ?? 0 }}"
        data-baja="{{ $alumnosBaja ?? 0 }}"
        data-metrics-url="{{ route('admin.dashboard.metrics') }}">
      </canvas>
    </div>
  </section>

  <section class="two-col">
    {{-- Bloque de calendario semanal: muestra los días de la semana con marcadores para actividades. --}}
    <div class="panel">
      <div class="panel-header">
        <h2>Calendario semanal</h2>
      </div>
      <div class="panel-body">
        <table class="mini-calendar">
          <thead>
            <tr>
              <th>L</th>
              <th>M</th>
              <th>M</th>
              <th>J</th>
              <th>V</th>
              <th>S</th>
              <th>D</th>
            </tr>
          </thead>
          <tbody>
            @php
              $startOfWeek = \Carbon\Carbon::now()->startOfWeek();
            @endphp
            @for($r=0; $r < 1; $r++)
              <tr>
                @for($c=0; $c < 7; $c++)
                  @php
                    $currentDate = $startOfWeek->copy()->addDays($r * 7 + $c);
                    $dateKey = $currentDate->format('Y-m-d');
                    $tipoActividad = $mapaActividades[$dateKey] ?? null;
                    $class = $tipoActividad ? 'has-' . $tipoActividad : '';
                  @endphp
                  <td class="{{ $class }}">
                    @if ($tipoActividad)
                        {{ $currentDate->day }}
                    @else
                        &nbsp;
                    @endif
                  </td>
                @endfor
              </tr>
            @endfor
          </tbody>
        </table>
      </div>
    </div>

    {{-- Bloque de actividades semanales: lista las actividades programadas con detalle. --}}
    <div class="panel">
      <div class="panel-header">
        <h2>Actividades semanales</h2>
      </div>
      <div class="panel-body">
        @forelse($actividadesSemanales as $actividad)
          <div class="activity-item">
            <span class="activity-date">
              {{ \Carbon\Carbon::parse($actividad->fecha)->locale('es')->isoFormat('dddd, D [de] MMMM') }}
            </span>
            <strong class="activity-name">
              {{ $actividad->nombre_act }}
            </strong>
          </div>
        @empty
          <p class="text-muted">No hay actividades programadas para esta semana.</p>
        @endforelse
      </div>
    </div>
</section>

{{-- Bloque de historial de notificaciones. --}}
<section class="panel">
    <div class="panel-header">
        <h2>Historial de notificaciones</h2>
    </div>

    @if($notificaciones->count() > 0)
        <div id="notificaciones-container">
        </div>

        <div id="notificaciones-pagination" class="mt-4">
            <button id="prevBtn" disabled>Anterior</button>
            <button id="nextBtn">Siguiente</button>
        </div>
    @else
        <p class="text-gray-500 text-center py-4">No hay notificaciones registradas.</p>
    @endif
</section>

{{-- Script tag oculto que proporciona los datos completos de notificaciones a javascript para la paginación local. --}}
<script type="application/json" id="notificacionesData">
    {!! $notificaciones->toJson() !!}
</script>
@endsection