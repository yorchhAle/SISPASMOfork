@extends('layouts.encabezados')
@section('title', 'Reporte de adeudos')

@section('content')
    <div id="reporteAdeudosRoot"
        data-url-chart-mes="{{ route('reportes.adeudos.chart.mes') }}"
        data-url-chart-alumno="{{ route('reportes.adeudos.chart.alumno') }}"
        data-url-exportar="{{ route('reportes.adeudos.exportar') }}">
        <div class="dash">
            <div class="crud-wrap">
                <div class="crud-card">

                    <div class="crud-hero">
                        <h1 class="crud-hero-title">Reporte de adeudos</h1>
                        {{-- Navegación de pestañas, controla la vista de reporte (por mes o por alumno). --}}
                        <div class="crud-tabs" id="tabs">
                            <a class="tab active" data-tab="mes" href="javascript:void(0)">Por mes/año</a>
                            <a class="tab" data-tab="alumno" href="javascript:void(0)">Por matrícula</a>
                        </div>
                    </div>

                    <div class="crud-body">
                        {{-- Bloque de mensajes, muestra mensajes de éxito y errores de validación. --}}
                        @if(session('ok')) <div class="gm-ok">{{ session('ok') }}</div> @endif
                        @if($errors->any())
                            <div class="gm-errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                        @endif

                        {{-- Bloque de filtros, contiene los selectores de fecha y el input de matrícula. --}}
                        <div class="gm-filter" style="margin-bottom:12px">
                            <div class="grid-3">
                                <select id="f_mes">
                                    <option value="">Mes</option>
                                    @for ($i=1;$i<=12;$i++)
                                        <option value="{{ $i }}">{{ \Carbon\Carbon::createFromDate(null,$i,1)->locale('es')->monthName }}</option>
                                    @endfor
                                </select>
                                <select id="f_anio">
                                    <option value="">Año</option>
                                    @for ($y=date('Y')-5;$y<=date('Y');$y++)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                                {{-- Campo de matrícula, solo es relevante para la pestaña "por matrícula". --}}
                                <input id="f_matricula" type="text" placeholder="Matrícula (para la pestaña de matrícula)">
                            </div>
                            {{-- Botón principal para generar el reporte basado en los filtros y la pestaña activa. --}}
                            <div class="actions" style="margin-top:8px">
                                <button id="btnGenerar" class="btn btn-primary">Generar</button>
                            </div>
                        </div>

                        {{-- Sección de reporte por mes/año: contiene la gráfica de barras por diplomado. --}}
                        <section id="tab-mes">
                            <div class="chart-wrap" style="max-width:880px;margin:0 auto">
                                <canvas id="chartMes"></canvas>
                            </div>
                            {{-- Botón de descarga, solo visible cuando hay datos de reporte por mes. --}}
                            <div class="actions" style="margin-top:16px; text-align:right;">
                                <button id="btnDownloadMes" class="btn btn-primary" style="display:none;">Descargar excel</button>
                            </div>
                        </section>

                        {{-- Sección de reporte por alumno: contiene la gráfica de dona (pie/doughnut) por matrícula. --}}
                        <section id="tab-alumno" style="display:none">
                            <div class="chart-wrap" style="max-width:880px;margin:0 auto">
                                <canvas id="chartAlumno"></canvas>
                            </div>
                            {{-- Botón de descarga, solo visible cuando hay datos de reporte por alumno. --}}
                            <div class="actions" style="margin-top:16px; text-align:right;">
                                <button id="btnDownloadAlumno" class="btn btn-primary" style="display:none;">Descargar excel</button>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Se incluye la librería chart.js para la renderización de gráficas. --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Se incluye el script de lógica para el manejo de filtros y llamadas a la api. --}}
    @vite('resources/js/reporteAdeudos.js')
@endpush