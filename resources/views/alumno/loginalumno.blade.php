<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Alumno</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
  @vite('resources/css/login.css')
</head>
<body>
  <div class="layout">
    <div class="left">
      <img class="left-img" src="{{ asset('images/loginalumno.png') }}" alt="Acceso Alumno">
    </div>

    <div class="right">
      <div class="card">
        <img class="logo" src="{{ asset('images/logosecundario.png') }}" alt="Grupo Morelos">
        <h1 class="title">Grupo Morelos Rescate Anfibio</h1>

        {{-- Formulario de login: envía las credenciales (matrícula y contraseña) a la ruta de procesamiento. --}}
        <form class="form" method="POST" action="{{ route('alumno.login.post') }}">
          @csrf

          {{-- Campo de matrícula --}}
          <label class="label" for="matricula">Matrícula</label>
          <input class="input" id="matricula" name="matricula" type="text" value="{{ old('matricula') }}" placeholder="Matrícula" required autofocus>

          {{-- Muestra errores de validación específicos de laravel para el campo 'matricula'. --}}
          @error('matricula')
            <div class="error">{{ $message }}</div>
          @enderror

          {{-- Campo de contraseña --}}
          <label class="label" for="password">Contraseña</label>
          <input class="input" id="password" name="password" type="password" placeholder="Contraseña" required>

          {{-- Muestra errores de validación específicos de laravel para el campo 'password'. --}}
          @error('password')
            <div class="error">{{ $message }}</div>
          @enderror

          {{-- Botón de acción para ingresar. --}}
          <button type="submit" class="btn btn-primary">Ingresar</button>

          {{-- Enlace de acción para restablecer la contraseña. --}}
          <a href="{{ route('password.request') }}" class="btn btn-danger">Olvidé mi contraseña</a>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
