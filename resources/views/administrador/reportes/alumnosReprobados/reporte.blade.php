@extends('layouts.encabezados')
@section('title', 'Reporte alumnos Reprobados')

@section('content')
<div id="reporteRoot"
     {{-- Eliminamos data-url-modulos --}}
     data-url-total="{{ route('reportes.reprobados.total') }}"
     data-url-calificaciones="{{ route('reportes.reprobados.calificaciones') }}"
     data-url-excel="{{ route('reportes.reprobados.exportar') }}">
  <div class="dash">
    <div class="crud-wrap">
      <div class="crud-card">

        <div class="crud-hero">
          <h1 class="crud-hero-title">Reporte de alumnos reprobados</h1>
          {{-- Navegación de pestañas, controla la vista de reporte (total por módulo vs comparación global). --}}
          <div class="crud-tabs" id="tabs">
            <a class="tab active" data-tab="total" href="javascript:void(0)">Reprobados por módulo</a>
            <a class="tab" data-tab="calificaciones" href="javascript:void(0)">Comparación global</a>
          </div>
        </div>

        <div class="crud-body">
          {{-- Bloque de mensajes, muestra mensajes de éxito y errores de validación/sesión. --}}
          @if(session('ok')) <div class="gm-ok">{{ session('ok') }}</div> @endif
          @if(session('error')) <div class="gm-errors">{{ session('error') }}</div> @endif
          @if($errors->any())
            <div class="gm-errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
          @endif

          {{-- Bloque de filtros, select de diplomados y botón generar. --}}
          <div class="gm-filter" style="margin-bottom:12px">
            <div class="grid-2">
                <select id="f_diplomado" style="width:100%">
                    <option value="">Selecciona un diplomado</option>
                    @foreach($diplomados as $diplomado)
                      <option value="{{ $diplomado->id_diplomado }}">{{ $diplomado->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="actions" style="margin-top:8px">
              <button id="btnGenerar" class="btn btn-primary">Generar reporte</button>
            </div>
          </div>
          
          {{-- Sección de gráfica 1, reprobados por módulo (activa por defecto). --}}
          <section id="tab-total">
            <div class="chart-wrap" style="max-width:880px;margin:0 auto">
              <canvas id="chartTotal"></canvas>
            </div>

            {{-- Formulario de descarga excel para la gráfica total. --}}
            <div class="actions" style="justify-content:flex-end;margin-top:14px">
              {{-- Pasamos id_diplomado en lugar de id_modulo --}}
              <form id="excelFormTotal" method="POST" action="{{ route('reportes.reprobados.exportar') }}">
                @csrf
                <input type="hidden" name="id_diplomado" id="diplomado_excel_total">
                <button type="submit" class="btn btn-primary" id="btnExcelTotal">Descargar excel</button>
              </form>
            </div>
          </section>

          {{-- Sección de gráfica 2, comparación global de calificaciones reprobatorias (oculta por defecto). --}}
          <section id="tab-calificaciones" style="display:none;">
            <div class="chart-wrap" style="max-width:880px;margin:0 auto">
              <canvas id="chartCalificaciones"></canvas>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  {{-- Se incluye el script de lógica para el manejo de filtros, gráficas y la exportación. --}}
  @vite('resources/js/reporteReprobados.js')
@endpush