@extends('layouts.encabezados')
@section('title', 'Gestión coordinadores')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de coordinadores</h2>
                <p class="crud-hero-subtitle">Actualización</p>

                {{-- Navegación de pestañas, el link "listar coordinadores" está marcado como activo. --}}
                <nav class="crud-tabs">
                    <a href="{{ route('coordinadores.create') }}" class="tab">Registrar</a>
                    <a href="{{ route('coordinadores.index') }}" class="tab active">Listar coordinadores</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Actualizar coordinador</h1>

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
                <form class="gm-form" method="POST" action="{{ route('coordinadores.update', $coordinador) }}">
                    @csrf
                    @method('PUT')

                    <h3>Datos de usuario</h3>
                    {{-- Bloque de datos personales, campos rellenados con la información del usuario asociado al coordinador. --}}
                    <div class="form-section">
                        <div>
                            <label for="nombre">Nombre(s)</label>
                            <input id="nombre" name="nombre" value="{{ old('nombre', $coordinador->usuario->nombre) }}" placeholder="Nombre" required>
                        </div>

                        <div>
                            <label for="apellidoP">Apellido paterno</label>
                            <input id="apellidoP" name="apellidoP" value="{{ old('apellidoP', $coordinador->usuario->apellidoP) }}" placeholder="Apellido paterno" required>
                        </div>

                        <div>
                            <label for="apellidoM">Apellido materno</label>
                            <input id="apellidoM" name="apellidoM" value="{{ old('apellidoM', $coordinador->usuario->apellidoM) }}" placeholder="Apellido materno" required>
                        </div>

                        <div>
                            <label for="fecha_nac">Fecha de nacimiento</label>
                            <input id="fecha_nac" type="date" name="fecha_nac" value="{{ old('fecha_nac', \Carbon\Carbon::parse($coordinador->usuario->fecha_nac)->format('Y-m-d')) }}" required>
                        </div>

                        <div>
                            <label for="usuario">Usuario</label>
                            <input id="usuario" name="usuario" value="{{ old('usuario', $coordinador->usuario->usuario) }}" placeholder="Usuario" required>
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
                            @php $generoSel = old('genero', $coordinador->usuario->genero); @endphp
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
                            <input id="correo" type="email" name="correo" value="{{ old('correo', $coordinador->usuario->correo) }}" placeholder="Correo" required>
                        </div>

                        <div>
                            <label for="telefono">Teléfono</label>
                            <input id="telefono" name="telefono" value="{{ old('telefono', $coordinador->usuario->telefono) }}" placeholder="Teléfono" required>
                        </div>

                        <div>
                            <label for="direccion">Dirección</label>
                            <input id="direccion" name="direccion" value="{{ old('direccion', $coordinador->usuario->direccion) }}" placeholder="Dirección" required>
                        </div>

                        {{-- Es mejor usar un campo oculto si el rol no debe ser editado por el usuario --}}
                        <input type="hidden" name="id_rol" value="{{ old('id_rol', $coordinador->usuario->id_rol) }}">
                    </div>

                    <h3>Datos de coordinador</h3>
                    {{-- Bloque de datos específicos del modelo coordinador. --}}
                    <div class="form-section">
                        <div>
                            <label for="fecha_ingreso">Fecha de ingreso</label>
                            <input id="fecha_ingreso" type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', $coordinador->fecha_ingreso) }}" required>
                        </div>

                        <div>
                            @php $estatusSel = old('estatus', $coordinador->estatus); @endphp
                            <label for="estatus">Estatus</label>
                            <select id="estatus" name="estatus" required>
                                <option value="">Selecciona un estatus</option>
                                <option value="activo" {{ $estatusSel === 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ $estatusSel === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    {{-- Bloque de acciones, botón de Actualizar y Cancelar. --}}
                    <div class="actions">
                        <a href="{{ route('coordinadores.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
