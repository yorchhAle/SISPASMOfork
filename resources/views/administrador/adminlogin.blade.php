<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
  @vite('resources/css/login.css')
</head>
<body>
  <div class="layout">
    <div class="left">
      <img
        class="left-img"
        src="{{ asset('images/loginadministrador.png') }}"
        alt="Grupo Morelos — Formación en campo">
    </div>

    <div class="right">
      <div class="card">
        <img class="logo" src="{{ asset('images/logosecundario.png') }}" alt="Grupo Morelos">
        <h2 class="title">Grupo Morelos Rescate Anfibio</h2>

        {{-- Formulario de login, envía las credenciales a la ruta de procesamiento (post). --}}
        <form method="POST" action="{{ route('admin.login.post') }}" class="form">
          @csrf

          <label class="label" for="usuario">Usuario (Coordinador/Administrador)</label>
          <input class="input" id="usuario" name="usuario" type="text" value="{{ old('usuario') }}" placeholder="Correo electrónico" required autofocus>
          {{-- Muestra errores de validación específicos de laravel para el campo 'usuario'. --}}
          @error('usuario')
            <div class="error">{{ $message }}</div>
          @enderror

          <label class="label" for="password">Contraseña</label>
          <input class="input" id="password" name="password" type="password" placeholder="Contraseña" required>
          {{-- Muestra errores de validación específicos de laravel para el campo 'password'. --}}
          @error('password')
            <div class="error">{{ $message }}</div>
          @enderror

          {{-- Muestra un error genérico si las credenciales son inválidas (error de autenticación). --}}
          @if ($errors->has('usuario') && $errors->first('usuario') === 'Credenciales inválidas.')
            <div class="error">{{ $errors->first('usuario') }}</div>
          @endif

          <button type="submit" class="btn btn-primary">Ingresar</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
