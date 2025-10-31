@extends('layouts.encabezados')
@section('title', 'Gestión alumnos')

@section('content')
<div class="crud-wrap">
  <section class="crud-card">

    {{-- Encabezado --}}
    <header class="crud-hero">
      <h2 class="crud-hero-title">Gestión de alumnos</h2>
      <p class="crud-hero-subtitle">Actualización de datos</p>

      {{-- Navegación de pestañas --}}
      <nav class="crud-tabs">
        <a href="{{ route('alumnos.create') }}" class="tab">Registrar</a>
        <a href="{{ route('alumnos.index') }}" class="tab active">Listar alumnos</a>
      </nav>
    </header>

    <div class="crud-body">
      <h1>Actualización de datos</h1>

      {{-- Mensajes y validaciones --}}
      @if ($errors->any())
        <ul class="gm-errors">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      @endif

      @if (session('ok'))
        <div class="gm-ok">{{ session('ok') }}</div>
      @endif

      {{-- Formulario principal de actualización --}}
      <form class="gm-form" method="POST" action="{{ route('alumnos.update', $alumno) }}">
        @csrf
        @method('PUT')

        {{-- === Datos de usuario === --}}
        <h3>Datos de Usuario</h3>
        <div class="form-section">
          <div>
            <label for="nombre">Nombre(s)</label>
            <input id="nombre" name="nombre" value="{{ old('nombre', $alumno->usuario->nombre) }}" placeholder="Nombre(s)" required>
          </div>

          <div>
            <label for="apellidoP">Apellido paterno</label>
            <input id="apellidoP" name="apellidoP" value="{{ old('apellidoP', $alumno->usuario->apellidoP) }}" placeholder="Apellido paterno" required>
          </div>

          <div>
            <label for="apellidoM">Apellido materno</label>
            <input id="apellidoM" name="apellidoM" value="{{ old('apellidoM', $alumno->usuario->apellidoM) }}" placeholder="Apellido materno" required>
          </div>

          <div>
            <label for="fecha_nac">Fecha de nacimiento</label>
            <input id="fecha_nac" type="date" name="fecha_nac"
              value="{{ old('fecha_nac', \Carbon\Carbon::parse($alumno->usuario->fecha_nac)->format('Y-m-d')) }}"
              required>
          </div>

          <div>
            <label for="usuario">Usuario</label>
            <input id="usuario" name="usuario" value="{{ old('usuario', $alumno->usuario->usuario) }}" placeholder="Usuario" required>
          </div>

          <div>
            <label for="pass">Contraseña</label>
            <input id="pass" type="password" name="pass" placeholder="Nueva contraseña (opcional)">
          </div>

          <div>
            <label for="pass_confirmation">Confirmar contraseña</label>
            <input id="pass_confirmation" type="password" name="pass_confirmation" placeholder="Confirmar nueva contraseña (si la cambias)">
          </div>

          <div>
            @php $generoSel = old('genero', $alumno->usuario->genero); @endphp
            <label for="genero">Género</label>
            <select id="genero" name="genero" required>
              <option value="">Selecciona un género</option>
              <option value="M" {{ $generoSel === 'M' ? 'selected' : '' }}>M</option>
              <option value="F" {{ $generoSel === 'F' ? 'selected' : '' }}>F</option>
              <option value="Otro" {{ $generoSel === 'Otro' ? 'selected' : '' }}>Otro</option>
            </select>
          </div>

          <div>
            <label for="correo">Correo electrónico</label>
            <input id="correo" type="email" name="correo" value="{{ old('correo', $alumno->usuario->correo) }}" placeholder="Correo" required>
          </div>

          <div>
            <label for="telefono">Teléfono</label>
            <input id="telefono" name="telefono" value="{{ old('telefono', $alumno->usuario->telefono) }}" placeholder="Teléfono" required>
          </div>

          <div>
            <label for="direccion">Dirección</label>
            <input id="direccion" name="direccion" value="{{ old('direccion', $alumno->usuario->direccion) }}" placeholder="Dirección" required>
          </div>
        </div>

        {{-- Campo oculto: rol de usuario --}}
        <input type="hidden" name="id_rol" value="{{ old('id_rol', $alumno->usuario->id_rol) }}">

        {{-- === Datos de alumno === --}}
        <h3>Datos de Alumno</h3>
        <div class="form-section">
          <div>
            <label for="matriculaA">Matrícula</label>
            <input id="matriculaA" name="matriculaA" value="{{ old('matriculaA', $alumno->matriculaA) }}" placeholder="Matrícula" required>
          </div>

          <div>
            <label for="id_diplomado">Diplomado</label>
            <select name="id_diplomado" id="id_diplomado" required>
              <option value="">Selecciona un diplomado</option>
              @foreach ($diplomados as $diplomado)
                <option value="{{ $diplomado->id_diplomado }}"
                  {{ old('id_diplomado', $alumno->id_diplomado) == $diplomado->id_diplomado ? 'selected' : '' }}>
                  {{ $diplomado->nombre }} ({{ $diplomado->grupo }})
                </option>
              @endforeach
            </select>
          </div>

          <div>
            @php $estatusSel = old('estatus', $alumno->estatus); @endphp
            <label for="estatus">Estatus</label>
            <select id="estatus" name="estatus" required>
              <option value="activo"   {{ $estatusSel === 'activo' ? 'selected' : '' }}>Activo</option>
              <option value="baja"     {{ $estatusSel === 'baja' ? 'selected' : '' }}>Baja</option>
              <option value="egresado" {{ $estatusSel === 'egresado' ? 'selected' : '' }}>Egresado</option>
            </select>
          </div>
        </div>

        {{-- === Acciones === --}}
        <div class="actions">
          <a href="{{ route('alumnos.index') }}" class="btn btn-danger">Cancelar</a>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
      </form>
    </div>
  </section>
</div>
@endsection
