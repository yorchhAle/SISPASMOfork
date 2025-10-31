<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Aspirantes</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
  @vite('resources/css/login.css')
  </head><body>
  <div class="layout">
    <div class="left"><img class="left-img" src="{{ asset('images/aspirante.png') }}" alt="Aspirantes"></div>
    <div class="right">
      <div class="card">
        <img class="logo" src="{{ asset('images/logosecundario.png') }}" alt="Grupo Morelos">
        <h1 class="title">Aspirantes</h1>
        
        {{-- Bloque de acciones, botones principales para navegar al login, registro o regresar al inicio. --}}
        <div class="form" style="gap:10px">
          <a class="btn btn-primary" href="{{ route('aspirante.login') }}">Ingresar</a>
          <a class="btn btn-primary" href="{{ route('aspirante.register.show') }}">Registrarme</a>
          <a class="btn btn-primary" href="{{ route('inicio') }}">Regresar al inicio</a>
        </div>
      </div>
    </div>
  </div>
  </body>
</html>
