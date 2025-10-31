@extends('layouts.encabezados')
@section('title', 'Reporte alumnos inscritos')

@section('content')
<div id="reporteRoot"
     data-url-totales="{{ route('reportes.inscritos.totales') }}"
     data-url-estatus="{{ route('reportes.inscritos.estatus') }}"
     data-pdf-url="{{ route('reportes.inscritos.pdf') }}">
  <div class="dash">
    <div class="crud-wrap">
      <div class="crud-card">

        <div class="crud-hero">
          <h1 class="crud-hero-title">Reporte de alumnos inscritos</h1>
          
          {{-- Navegación de pestañas, controla la vista de reporte (total vs estatus). --}}
          <div class="crud-tabs" id="tabs">
            <a class="tab active" data-tab="totales" href="javascript:void(0)">Total por diplomado</a>
            <a class="tab"  data-tab="estatus" href="javascript:void(0)">Estatus de alumnos</a>
          </div>
        </div>

        <div class="crud-body">
        {{-- Bloque de filtros, permite seleccionar un diplomado para acotar el reporte. --}}
        <div class="filter-forms" style="margin-top: 10px; display: flex; gap: 10px; align-items: center;">
            <div style="flex-grow: 1; max-width: 250px;">
                <select id="f_diplomado" style="width:100%">
                  <option value="">Diplomado</option>
                  @foreach($diplomados as $diplomado)
                    <option value="{{ $diplomado->id_diplomado }}">{{ $diplomado->nombre }}</option>
                  @endforeach
                </select>
            </div>
            {{-- Botón principal para generar o actualizar el reporte basado en el filtro. --}}
            <button id="btnGenerar" class="submit-button">Generar</button>
          </div>

          @if(session('ok')) <div class="gm-ok">{{ session('ok') }}</div> @endif
          @if($errors->any())
            <div class="gm-errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
          @endif

          {{-- Sección de gráfica 1, total de alumnos (activa por defecto). --}}
          <section id="tab-totales">
            <div class="chart-wrap" style="max-width:880px;margin:0 auto">
              <canvas id="chartTotales"></canvas>
            </div>
            {{-- Formulario de descarga pdf para la gráfica de totales. --}}
            <div class="actions" style="justify-content:flex-end;margin-top:14px">
              <form id="pdfFormTotales" method="POST" action="{{ route('reportes.inscritos.pdf') }}">
                @csrf
                <input type="hidden" name="chart_data_url" id="chart_data_url_totales">
                <input type="hidden" name="titulo" value="Total de Alumnos por Diplomado">
                <input type="hidden" name="subtitulo" id="subtituloTotales">
                <button type="button" class="btn btn-primary" id="btnPDFTotales">Descargar reporte (PDF)</button>
              </form>
            </div>
          </section>

          {{-- Sección de gráfica 2, estatus (oculta por defecto). --}}
          <section id="tab-estatus" style="display:none;">
            <div class="chart-wrap" style="max-width:880px;margin:0 auto">
              <canvas id="chartEstatus"></canvas>
            </div>

            {{-- Formulario de descarga pdf para la gráfica de estatus. --}}
            <div class="actions" style="justify-content:flex-end;margin-top:14px">
              <form id="pdfFormEstatus" method="POST" action="{{ route('reportes.inscritos.pdf') }}">
                @csrf
                <input type="hidden" name="chart_data_url" id="chart_data_url_estatus">
                <input type="hidden" name="titulo" value="Estatus de Alumnos por Diplomado">
                <input type="hidden" name="subtitulo" id="subtituloEstatus">
                <button type="button" class="btn btn-primary" id="btnPDFEstatus">Descargar reporte (PDF)</button>
              </form>
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

  {{-- Se incluye el script de lógica para el manejo de filtros, gráficas y la descarga de pdf. --}}
  @vite('resources/js/reporteInscritos.js')
@endpush