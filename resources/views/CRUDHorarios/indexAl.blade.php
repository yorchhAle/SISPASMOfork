@extends('layouts.encabezadosAl')

@section('content')
<div class="crud-wrap">
  <div class="crud-card">
    <div class="crud-hero">
      <h2 class="crud-hero-title">Mi horario</h2>
      <p class="crud-hero-subtitle">Clases próximas</p>
    </div>

    <div class="crud-body">
      {{-- Bloque de mensajes, muestra mensaje de éxito (`success`) de la sesión. --}}
      @if (session('success'))
        <div class="gm-ok">{{ session('success') }}</div>
      @endif

      {{-- Bloque condicional, muestra la tabla si hay horarios o un mensaje de vacío. --}}
      @if ($horarios->isEmpty())
        <div class="gm-empty">No tienes clases programadas.</div>
      @else
        <div class="table-responsive">
          <table class="gm-table">
            <thead>
              <tr>
                <th>Diplomado</th>
                <th>Módulo</th>
                <th>Docente</th>
                <th>Fecha</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Modalidad</th>
                <th>Aula</th>
              </tr>
            </thead>
            <tbody>

              {{-- Bloque de datos (bucle), itera sobre la colección de horarios ($horarios). --}}
              @foreach ($horarios as $horario)
                <tr>
                  <td>
                    {{-- Usa los mismos campos que tu index admin --}}
                    {{ optional($horario->diplomado)->nombre ?? '—' }}
                    @if(optional($horario->diplomado)->grupo)
                      ({{ $horario->diplomado->grupo }})
                    @endif
                  </td>
                  <td>
                    {{ optional($horario->modulo)->nombre_modulo ?? '—' }}
                  </td>
                  <td>
                    {{ optional(optional($horario->docente)->usuario)->nombre ?? '—' }}
                    {{ optional(optional($horario->docente)->usuario)->apellidoP ?? '' }}
                  </td>
                  <td>{{ \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') }}</td>
                  <td>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                  <td>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</td>
                  <td>
                    {{-- Bloque switch, muestra un badge visual según la modalidad (presencial, virtual, práctica). --}}
                    @switch($horario->modalidad)
                      @case('Presencial')
                        <span class="badge badge-validado">Presencial</span>
                        @break
                      @case('Virtual')
                        <span class="badge badge-pendiente">Virtual</span>
                        @break
                      @case('Práctica')
                        <span class="badge badge-rechazado">Práctica</span>
                        @break
                      @default
                        <span class="badge">{{ $horario->modalidad }}</span>
                    @endswitch
                  </td>
                  <td>{{ $horario->aula }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
