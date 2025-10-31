<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login aspirante</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
  @vite('resources/css/login.css')
  </head><body>
  <div class="layout">
    <div class="left"><img class="left-img" src="{{ asset('images/loginaspirantes.png') }}" alt="Aspirantes"></div>
    <div class="right">
      <div class="card">
        <img class="logo" src="{{ asset('images/logosecundario.png') }}" alt="Grupo Morelos">
        <h1 class="title">Ingresar</h1>

        {{-- Bloque de errores, muestra los errores de validación de laravel (si los hay), unidos por salto de línea. --}}
        @if ($errors->any())
          <div class="error">{!! implode('<br>', $errors->all()) !!}</div>
        @endif

        {{-- Formulario de login, envía las credenciales (correo y contraseña) a la ruta de procesamiento. --}}
        <form class="form" method="POST" action="{{ route('aspirante.login.post') }}">
          @csrf
          {{-- Campo de correo electrónico --}}
          <label class="label" for="correo">Correo electrónico</label>
          <input class="input" id="correo" name="correo" type="email" value="{{ old('correo') }}" autofocus>
          {{-- Campo de contraseña --}}
          <label class="label" for="password">Contraseña</label>
          <input class="input" id="password" name="password" type="password">
          {{-- Botón de acción para ingresar. --}}
          <button type="submit" class="btn btn-primary">Ingresar</button>
          <a href="{{ route('aspirante.select') }}" class="btn btn-primary">Regresar</a>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
