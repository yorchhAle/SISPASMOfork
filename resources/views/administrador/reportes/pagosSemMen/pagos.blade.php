@extends('layouts.encabezados')
@section('title', 'Reporte de pagos')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Reporte de pagos</h2>
            </header>

            <div class="crud-body">
                {{-- Formulario de filtro: permite seleccionar el rango de fechas y el período (semanal/mensual). --}}
                <form method="GET" class="filter-form" 
                      action="{{ route('reportes.pagos') }}" 
                      style="display: flex; align-items: center; gap: 15px; margin-top: 15px;">

                    <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" 
                           placeholder="Fecha de inicio" required 
                           style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                           
                    <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" 
                           placeholder="Fecha de fin" required
                           style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                           
                    <select name="periodo" required 
                            style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; height: 38px;">
                        <option value="semanal" {{ request('periodo') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                        <option value="mensual" {{ request('periodo') == 'mensual' ? 'selected' : '' }}>Mensual</option>
                    </select>
                    
                    <button class="submit-button">Generar</button>
                </form>

                {{-- Bloque condicional: se muestra solo después de enviar el formulario de filtro. --}}
                @if(request()->hasAny(['fecha_inicio', 'fecha_fin']))
                    <div class="report-section">
                        <h3>Pagos del periodo: {{ request('fecha_inicio') }} al {{ request('fecha_fin') }}</h3>
                        
                        {{-- Contenedor de la gráfica --}}
                        <div style="margin-top: 20px; text-align: center;">
                            @if($pagos->isEmpty())
                                <div class="gm-info">No se encontraron pagos validados en el período seleccionado.</div>
                            @else
                                <canvas id="pagosChart" style="max-height: 400px;"></canvas>
                                
                                <div id="pagos-data" style="display:none;">{!! $pagos->toJson() !!}</div>

                                {{-- Formulario de exportación excel (el botón descarga un archivo xml/xlsx). --}}
                                <form action="{{ route('reportes.exportar') }}" method="GET" style="margin-top: 15px;">
                                    <input type="hidden" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                                    <input type="hidden" name="fecha_fin" value="{{ request('fecha_fin') }}">
                                    <button type="submit" class="btn btn-primary">Descargar reporte (XML)</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="gm-info">Selecciona un rango de fechas y un tipo de periodo para generar el reporte.</div>
                @endif
            </div>
        </section>
    </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  {{-- Se incluye el script de lógica para el manejo de filtros y la renderización de la gráfica. --}}
  @vite('resources/js/reportePagosSemMen.js')
@endpush