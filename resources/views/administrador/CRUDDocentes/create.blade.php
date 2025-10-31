@extends('layouts.encabezados')
@section('title', 'Gestión docentes')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de docentes</h2>
                <p class="crud-hero-subtitle">Registro</p>

                {{-- Navegación de pestañas, el link "registrar" está marcado como activo. --}}
                <nav class="crud-tabs">
                    <a href="{{ route('docentes.create') }}" class="tab active">Registrar</a>
                    <a href="{{ route('docentes.index') }}" class="tab">Listar docentes</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Nuevo docente</h1>

                {{-- Bloque de errores, muestra los errores de validación de laravel (si los hay). --}}
                @if ($errors->any())
                    <ul class="gm-errors">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                @endif

                {{-- Bloque de mensajes, muestra mensaje de éxito (`success`) de la sesión. --}}
                @if (session('success'))
                    <div class="gm-ok">{{ session('success') }}</div>
                @endif

                {{-- Formulario principal, envía los datos para crear un nuevo docente (método post). --}}
                <form class="gm-form" method="POST" action="{{ route('docentes.store') }}">
                    @csrf

                    <h3>Datos de usuario</h3>
                    {{-- Bloque de datos personales y credenciales, contiene campos requeridos para el usuario asociado al docente. --}}
                    <div class="form-section">
                        <div>
                            <label for="nombre">Nombre(s)</label>
                            <input id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre(s)" required>
                        </div>

                        <div>
                            <label for="apellidoP">Apellido paterno</label>
                            <input id="apellidoP" name="apellidoP" value="{{ old('apellidoP') }}" placeholder="Apellido paterno" required>
                        </div>

                        <div>
                            <label for="apellidoM">Apellido materno</label>
                            <input id="apellidoM" name="apellidoM" value="{{ old('apellidoM') }}" placeholder="Apellido materno" required>
                        </div>

                        <div>
                            <label for="fecha_nac">Fecha de nacimiento</label>
                            <input id="fecha_nac" type="date" name="fecha_nac" value="{{ old('fecha_nac') }}" required>
                        </div>

                        <div>
                            <label for="usuario">Usuario</label>
                            <input id="usuario" name="usuario" value="{{ old('usuario') }}" placeholder="Usuario" required>
                        </div>

                        <div>
                            <label for="pass">Contraseña</label>
                            <input id="pass" type="password" name="pass" placeholder="Contraseña" required>
                        </div>

                        <div>
                            <label for="pass_confirmation">Confirmar contraseña</label>
                            <input id="pass_confirmation" type="password" name="pass_confirmation" placeholder="Confirmar contraseña" required>
                        </div>

                        <div>
                            <label for="genero">Género</label>
                            <select id="genero" name="genero" required>
                                <option value="">Selecciona un género</option>
                                <option value="M" {{ old('genero')==='M' ? 'selected' : '' }}>M</option>
                                <option value="F" {{ old('genero')==='F' ? 'selected' : '' }}>F</option>
                                <option value="Otro" {{ old('genero')==='Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <div>
                            <label for="correo">Correo electrónico</label>
                            <input id="correo" type="email" name="correo" value="{{ old('correo') }}" placeholder="Correo" maxlength="100" required>
                        </div>

                        <div>
                            <label for="telefono">Teléfono</label>
                            <input id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="Teléfono" maxlength="10" required>
                        </div>

                        <div>
                            <label for="direccion">Dirección</label>
                            <input id="direccion" name="direccion" value="{{ old('direccion') }}" placeholder="Dirección" maxlength="100" required>
                        </div>
                    </div>

                    <h3>Datos de docente</h3>
                    {{-- Bloque de datos de docente, campos específicos del modelo docente. --}}
                    <div class="form-section">
                        <div>
                            <label for="matriculaD">Matrícula docente</label>
                            <input id="matriculaD" name="matriculaD" value="{{ old('matriculaD') }}" placeholder="Matrícula docente" required>
                        </div>

                        <div>
                            <label for="especialidad">Especialidad</label>
                            <input id="especialidad" name="especialidad" value="{{ old('especialidad') }}" placeholder="Especialidad" required>
                        </div>

                        <div>
                            <label for="cedula">Cédula profesional</label>
                            <input id="cedula" name="cedula" value="{{ old('cedula') }}" placeholder="Cédula profesional" maxlength="7" required>
                        </div>

                        <div>
                            <label for="salario">Salario</label>
                            <input id="salario" type="number" name="salario" value="{{ old('salario') }}" placeholder="Salario (Sin $)" step="0.01" min="0" required>
                        </div>
                    </div>

                    {{-- Bloque de acciones, botón de Guardar y Cancelar. --}}
                    <div class="actions">
                        <a href="{{ route('docentes.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection