<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            margin: 0; 
            padding: 0;
            color: #333;
        }

        .header-pdf {
            display: flex; 
            justify-content: center;
            align-items: center; 
            padding: 15px 40px; 
            gap: 25px; 
            border-bottom: 3px solid #112543;
            margin-bottom: 25px;
        }

        .header-pdf .logo {
            max-height: 55px;
            width: auto;
            margin: 0; 
        }

        .header-pdf .title-fixed {
            font-size: 1.6em; 
            font-weight: bold;
            color: #112543; 
            margin: 0;
        }

        .report-title {
            text-align: center; 
            color: #112543; 
            font-size: 1.8em; 
            margin: 25px 0 5px 0;
        }

        .report-subtitle { 
            text-align: center; 
            color: #666; 
            font-size: 1.1em;
            font-style: italic;
            margin-top: 0;
            margin-bottom: 30px;
        }
        
        .chart-img { 
            display: block; 
            margin: 30px auto; 
            max-width: 95%; 
            height: auto;
            border: 1px solid #ddd; 
            padding: 5px;
        }
    </style>
</head>
<body>
    
    {{-- Bloque de encabezado, muestra el logo y el título fijo en la parte superior de cada página. --}}
    <div class="header-pdf">
        <img class="logo" src="{{ public_path('images/logoprincipal.png') }}" alt="Logo Principal">
        <h2 class="title-fixed">Reportes académicos</h2>
    </div>

    {{-- Bloque de título, muestra el título del reporte recibido del controlador. --}}
    <h1 class="report-title">{{ $titulo }}</h1>
    
    {{-- Bloque de gráfico, incrusta la imagen base64 de la gráfica capturada por javascript. --}}
    {{-- La variable $imagedata o $chart_data_url contiene la imagen en formato base64. --}}
    <img class="chart-img" src="{{ $imageData ?? $chart_data_url }}" alt="Gráfica del reporte"> 
    
</body>
</html>