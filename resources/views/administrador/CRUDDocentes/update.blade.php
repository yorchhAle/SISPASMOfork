@extends('layouts.encabezados')
@section('title', 'Gestión docentes')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de docentes</h2>
                <p class="crud-hero-subtitle">Actualización</p>

                {{-- Navegación de pestañas, el link "listar docentes" está marcado como activo. --}}
                <nav class="crud-tabs">
                    <a href="{{ route('docentes.create') }}" class="tab">Registrar</a>
                    <a href="{{ route('docentes.index') }}" class="tab active">Listar docentes</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Actualizar docente</h1>

                {{-- Bloque de errores, muestra los errores de validación de laravel (si los hay). --}}
                @if ($errors->any())
                    <ul class="gm-errors">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                @endif

                {{-- Bloque de mensajes, muestra mensaje de éxito (`ok`) de la sesión. --}}
                @if (session('ok'))
                    <div class="gm-ok">{{ session('ok') }}</div>
                @endif

                {{-- Formulario principal de actualización, utiliza el método put para enviar los datos a la ruta update. --}}
                <form class="gm-form" method="POST" action="{{ route('docentes.update', $docente) }}">
                    @csrf
                    @method('PUT')

                    <h3>Datos de usuario</h3>
                    {{-- Bloque de datos personales, campos rellenados con la información del usuario asociado al docente. --}}
                    <div class="form-section">
                        <div>
                            <label for="nombre">Nombre(s)</label>
                            <input id="nombre" name="nombre" value="{{ old('nombre', $docente->usuario->nombre) }}" placeholder="Nombre" required>
                        </div>

                        <div>
                            <label for="apellidoP">Apellido paterno</label>
                            <input id="apellidoP" name="apellidoP" value="{{ old('apellidoP', $docente->usuario->apellidoP) }}" placeholder="Apellido paterno" required>
                        </div>

                        <div>
                            <label for="apellidoM">Apellido materno</label>
                            <input id="apellidoM" name="apellidoM" value="{{ old('apellidoM', $docente->usuario->apellidoM) }}" placeholder="Apellido materno" required>
                        </div>

                        <div>
                            <label for="fecha_nac">Fecha de nacimiento</label>
                            <input id="fecha_nac" type="date" name="fecha_nac" value="{{ old('fecha_nac', \Carbon\Carbon::parse($docente->usuario->fecha_nac)->format('Y-m-d')) }}" required>
                        </div>

                        <div>
                            <label for="usuario">Usuario</label>
                            <input id="usuario" name="usuario" value="{{ old('usuario', $docente->usuario->usuario) }}" placeholder="Usuario" required>
                        </div>

                        <div>
                            <label for="pass">Nueva contraseña (opcional)</label>
                            <input id="pass" type="password" name="pass" placeholder="Nueva contraseña (opcional)">
                        </div>

                        <div>
                            <label for="pass_confirmation">Confirmar nueva contraseña</label>
                            <input id="pass_confirmation" type="password" name="pass_confirmation" placeholder="Confirmar nueva contraseña">
                        </div>
                        
                        <div>
                            @php $generoSel = old('genero', $docente->usuario->genero); @endphp
                            <label for="genero">Género</label>
                            <select id="genero" name="genero" required>
                                <option value="">Selecciona un género</option>
                                <option value="M" {{ $generoSel==='M' ? 'selected' : '' }}>M</option>
                                <option value="F" {{ $generoSel==='F' ? 'selected' : '' }}>F</option>
                                <option value="Otro" {{ $generoSel==='Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <div>
                            <label for="correo">Correo electrónico</label>
                            <input id="correo" type="email" name="correo" value="{{ old('correo', $docente->usuario->correo) }}" placeholder="Correo" required>
                        </div>

                        <div>
                            <label for="telefono">Teléfono</label>
                            <input id="telefono" name="telefono" value="{{ old('telefono', $docente->usuario->telefono) }}" placeholder="Teléfono" required>
                        </div>

                        <div>
                            <label for="direccion">Dirección</label>
                            <input id="direccion" name="direccion" value="{{ old('direccion', $docente->usuario->direccion) }}" placeholder="Dirección" required>
                        </div>
                        
                        {{-- Es mejor usar un campo oculto si el rol no debe ser editado por el usuario --}}
                        <input type="hidden" name="id_rol" value="{{ old('id_rol', $docente->usuario->id_rol) }}">
                        
                    </div>

                    <h3>Datos de docente</h3>
                    {{-- Bloque de datos de docente, campos específicos del modelo docente. --}}
                    <div class="form-section">
                        <div>
                            <label for="matriculaD">Matrícula Docente</label>
                            <input id="matriculaD" name="matriculaD" value="{{ old('matriculaD', $docente->matriculaD) }}" placeholder="Matrícula docente" required>
                        </div>
                        
                        <div>
                            <label for="especialidad">Especialidad</label>
                            <input id="especialidad" name="especialidad" value="{{ old('especialidad', $docente->especialidad) }}" placeholder="Especialidad" required>
                        </div>
                        
                        <div>
                            <label for="cedula">Cédula profesional</label>
                            <input id="cedula" name="cedula" value="{{ old('cedula', $docente->cedula) }}" placeholder="Cédula profesional" required>
                        </div>
                        
                        <div>
                            <label for="salario">Salario</label>
                            <input id="salario" type="number" step="0.01" min="0" name="salario" value="{{ old('salario', $docente->salario) }}" placeholder="Salario" required>
                        </div>
                    </div>

                    {{-- Bloque de acciones, botón de Actualizar y Cancelar. --}}
                    <div class="actions">
                        <a href="{{ route('docentes.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection