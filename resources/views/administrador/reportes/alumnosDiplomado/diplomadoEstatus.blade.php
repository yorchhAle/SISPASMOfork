<!DOCTYPE html>
<html>
<head>
    <title>Reporte de estatus de alumnos {{ $year }}</title>
    <style>
        body { font-family: sans-serif; }
        .report-header { text-align: center; margin-bottom: 20px; }
        .chart-box { text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Reporte de comparación de estatus de alumnos ({{ $year }})</h1>
    </div>

    {{-- Bloque de gráfico, incrusta la imagen base64 de la gráfica generada por chart.js. --}}
    <div class="chart-box">
        @if (isset($chartImage))
            <img src="{{ $chartImage }}" alt="Gráfico de Comparación de Estatus" style="width: 100%;">
        @else
            <p>No se pudo generar la gráfica.</p>
        @endif
    </div>

    {{-- Bloque de datos tabulares, muestra el detalle del reporte. --}}
    <table>
        <thead>
            <tr>
                <th>Diplomado</th>
                <th>Activos</th>
                <th>Egresados</th>
            </tr>
        </thead>
        <tbody>
            {{-- Bucle de datos, itera sobre la colección de resultados del reporte ($data). --}}
            @foreach ($data as $d)
                <tr>
                    <td>{{ $d->nombre }} ({{ $d->grupo }})</td>
                    <td>{{ $d->activos }}</td>
                    <td>{{ $d->egresados }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>