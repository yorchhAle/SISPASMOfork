@extends('layouts.encabezados')
@section('title', 'Reporte aspirantes interesados')

@section('content')
<div id="reporteRoot"
     data-url-total="{{ route('reportes.aspirantes.total') }}"
     data-url-comparacion="{{ route('reportes.aspirantes.comparacion') }}">
  <div class="dash">
    <div class="crud-wrap">
      <div class="crud-card">

        <div class="crud-hero">
          <h1 class="crud-hero-title">Reporte de aspirantes</h1>

          {{-- Navegación de pestañas, controla la vista de reporte (total vs comparación). --}}
          <div class="crud-tabs" id="tabs">
            <a class="tab active" data-tab="total" href="javascript:void(0)">Total por tipo</a>
            <a class="tab" data-tab="comparacion" href="javascript:void(0)">Comparación</a>
          </div>
        </div>

        <div class="crud-body">
        {{-- Bloque de filtros para la pestaña "total por tipo", solo se muestra en la pestaña activa. --}}
        <div class="filter-forms" id="filtroTotal" 
               style="display: flex; align-items: center; gap: 15px; margin-top: 15px;">
            
            <div style="flex-grow: 1; max-width: 300px;">
              {{-- Selector para filtrar el reporte por tipo de diplomado (básico o intermedio/avanzado). --}}
                <select id="f_tipo_diplomado" style="width:100%; padding: 8px; border-radius: 4px;">
                    <option value="">Tipo de diplomado</option>
                    <option value="basico">Diplomado nivel básico</option>
                    <option value="intermedio y avanzado">Diplomado intermedio y avanzado</option>
                </select>
            </div>
            
            {{-- Botón principal para generar la gráfica de la pestaña "total". --}}
            <button id="btnGenerarTotal" class="submit-button">Generar</button>
          </div>

          @if(session('ok')) <div class="gm-ok">{{ session('ok') }}</div> @endif
          @if($errors->any())
            <div class="gm-errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
          @endif
                    
          {{-- Sección de gráfica 1, total por tipo (activa por defecto). --}}
          <section id="tab-total">
            <div class="chart-wrap" style="max-width:880px;margin:0 auto">
              <canvas id="chartTotal"></canvas>
            </div>
          </section>

          {{-- Sección de gráfica 2, comparación entre tipos (oculta por defecto). --}}
          <section id="tab-comparacion" style="display:none;">
            <div class="chart-wrap" style="max-width:880px;margin:0 auto">
              <canvas id="chartComparacion"></canvas>
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

  {{-- Se incluye el script de lógica para el manejo de filtros, gráficas y la interacción de pestañas. --}}
  @vite('resources/js/reporteAspirantes.js')
@endpush