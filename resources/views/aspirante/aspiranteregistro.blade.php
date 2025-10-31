<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registro Aspirante</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
  @vite('resources/css/registro.css')
</head>
<body>
  <div class="layout">
    <div class="left"><img class="left-img" src="{{ asset('images/aspirante.png') }}" alt="Aspirantes"></div>
    <div class="right">
      <div class="card">
        <img class="logo" src="{{ asset('images/logosecundario.png') }}" alt="Grupo Morelos">
        <h1 class="title">Grupo Morelos Rescate Anfibio</h1>
        
        {{-- Bloque de errores mejorado --}}
        @if ($errors->any())
          <div class="error">
            <strong>Por favor corrige los siguientes errores:</strong>
            <ul style="margin-top: 10px; padding-left: 20px;">
              @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
              @endforeach
            </ul>
          </div>
        @endif
        
        <form class="form" method="POST" action="{{ route('aspirante.register') }}">
          @csrf

          {{-- 
            Agrupamos los campos en un grid para un layout más limpio 
          --}}
          <div class="form-grid">

            <div class="form-group">
              <label class="label" for="nombre">Nombre</label>
              <input class="input" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre(s)" required>
              @error('nombre') <small class="form-error-inline">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
              <label class="label" for="apellidoP">Apellido paterno</label>
              <input class="input" id="apellidoP" name="apellidoP" value="{{ old('apellidoP') }}" placeholder="Apellido paterno" required>
              @error('apellidoP') <small class="form-error-inline">{{ $message }}</small> @enderror
            </div>

            <div class="form-group span-2"> {{-- Este ocupará 2 columnas --}}
              <label class="label" for="apellidoM">Apellido materno</label>
              <input class="input" id="apellidoM" name="apellidoM" value="{{ old('apellidoM') }}" placeholder="Apellido materno" required>
              @error('apellidoM') <small class="form-error-inline">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
              <label class="label" for="fecha_nac">Fecha de nacimiento</label>
              <input class="input" id="fecha_nac" name="fecha_nac" type="date" value="{{ old('fecha_nac') }}" required>
              @error('fecha_nac') <small class="form-error-inline">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
              <label class="label" for="genero">Sexo</label>
              <select class="input" id="genero" name="genero">
                <option value="">Selecciona</option>
                <option value="M" {{ old('genero')=='M'?'selected':'' }}>Masculino</option>
                <option value="F" {{ old('genero')=='F'?'selected':'' }}>Femenino</option>
                <option value="Otro" {{ old('genero')=='Otro'?'selected':'' }}>Otro</option>
              </select>
              @error('genero') <small class="form-error-inline">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
              <label class="label" for="telefono">Teléfono</label>
              <input class="input" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="Teléfono" maxlength="10" required>
              @error('telefono') <small class="form-error-inline">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
              <label class="label" for="correo">Correo electrónico</label>
              <input class="input" id="correo" name="correo" type="email" value="{{ old('correo') }}" placeholder="Correo electrónico" required>
              @error('correo') <small class="form-error-inline">{{ $message }}</small> @enderror
            </div>
            
            <div class="form-group span-2">
              <label class="label" for="direccion">Dirección</label>
              <input class="input" id="direccion" name="direccion" value="{{ old('direccion') }}" placeholder="Domicilio" required>
              @error('direccion') <small class="form-error-inline">{{ $message }}</small> @enderror
            </div>

            <div class="form-group span-2">
              <label class="label" for="id_diplomado">Diplomado de interés</label>
                @if(($diplomados ?? collect())->isEmpty())
                  <select class="input" disabled>
                    <option>No hay diplomados registrados</option>
                  </select>
                @else
                  <select class="input" id="id_diplomado" name="id_diplomado" required>
                    <option value="">Selecciona</option>
                    @foreach($diplomados as $d)
                      <option value="{{ $d->id_diplomado }}" {{ old('id_diplomado') == $d->id_diplomado ? 'selected' : '' }}>
                        {{ $d->nombre }}
                      </option>
                    @endforeach
                  </select>
                  @error('id_diplomado') <small class="form-error-inline">{{ $message }}</small> @enderror
                @endif
            </div>

            <div class="form-group span-2">
              <label class="label" for="dia">Fecha preferente para cita</label>
              <input class="input" id="dia" name="dia" type="date" value="{{ old('dia') }}">
              @error('dia') <small class="form-error-inline">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
              <label class="label" for="password">Contraseña</label>
              <input class="input" id="password" name="password" type="password" required>
            </div>

            <div class="form-group">
              <label class="label" for="password_confirmation">Confirmar contraseña</label>
              <input class="input" id="password_confirmation" name="password_confirmation" type="password" required>
            </div>

            {{-- Error de contraseña (si no coinciden) --}}
            @error('password')
              <div class="form-group span-2" style="text-align: center;">
                <small class="form-error-inline">{{ $message }}</small>
              </div>
            @enderror
          </div>
          
          {{-- Bloque de acciones (Checkbox y botones) --}}
          <div class="form-actions">
            <label class="label-checkbox">
              <input type="checkbox" name="acepto" value="1" required>
              Aceptar aviso de privacidad y condiciones
            </label>
            @error('acepto') <small class="form-error-inline">{{ $message }}</small> @enderror
            
            <button type="submit" class="btn btn-primary">Registrarse</button>
            
            {{-- Usamos la clase .btn-ghost que ya tenías definida --}}
            <a href="{{ route('aspirante.select') }}" class="btn btn-ghost">Regresar</a>
          </div>

        </form>
      </div>
    </div>
  </div>
  </body>
</html>