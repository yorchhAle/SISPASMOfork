<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recibo #{{ $recibo->id_recibo }}</title>
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
  {{-- Bloque de encabezado, muestra el logo principal. --}}
  <div class="header-pdf">
    <img class="logo" src="{{ public_path('images/logoprincipal.png') }}" alt="Logo Principal">
  </div>

  <div class="wrap">
    <h1>GRUPO MORELOS</h1>
    <h3>Recibo de pago</h3>

    {{-- Bloque de datos de identificación: folio y alumno. --}}
    <p>
      <strong>Folio:</strong> #{{ $recibo->id_recibo }}
    </p>

    <p>
      <strong>Alumno:</strong> {{ $recibo->alumno->nombre ?? 'N/D' }} ({{ $recibo->alumno->matriculaA ?? '—' }})
    </p>

    <p>
      <strong>Fecha pago:</strong> {{ optional($recibo->fecha_pago)->format('Y-m-d') }}
    </p>

    {{-- Bloque de validación: muestra quién validó el recibo y cuándo. --}}
    <p>
      <strong>Validado por:</strong> {{ $recibo->validador->nombre ?? 'Sistema' }}
    </p>

    <p>
      <strong>Fecha validación:</strong> {{ optional($recibo->fecha_validacion)->format('Y-m-d H:i') }}
    </p>
    
    @if($recibo->comentarios)
    <p><strong>Notas:</strong> {{ $recibo->comentarios }}</p>
    @endif

    {{-- Tabla de desglose del pago --}}
    <table width="100%" cellspacing="0" cellpadding="6" style="border-collapse:collapse;">
      <tr>
        <th align="left">Concepto</th>
        <th align="right">Monto</th>
      </tr>

      <tr>
        <td>{{ $recibo->concepto }}</td>
        {{-- Muestra el monto con formato de moneda. --}}
        <td align="right">${{ number_format($recibo->monto,2) }}</td>
      </tr>

      <tr>
        <td><strong>Total</strong></td>
        <td align="right"><strong>${{ number_format($recibo->monto,2) }}</strong></td>
      </tr>
      
    </table>

  <p style="margin-top:14px;color:#666;font-size:11px">
    Documento generado automáticamente por SISPASMO.
  </p>
</div>
</body>
</html>
