@extends('layouts.encabezados')
@section('title', 'Gestión aspirantes')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de aspirantes</h2>
                <p class="crud-hero-subtitle">Actualización</p>

                {{-- Navegación, el link de listado de aspirantes está activo. --}}
                <nav class="crud-tabs">
                    <a href="{{ route('aspirantes.index') }}" class="tab active">Listar aspirantes</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Actualizar aspirante</h1>

                {{-- Bloque de errores, muestra los errores de validación de Laravel (si los hay). --}}
                @if ($errors->any())
                    <ul class="gm-errors">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                @endif

                {{-- Bloque de mensajes, muestra mensaje de éxito de sesión. --}}
                @if (session('ok'))
                    <div class="gm-ok">{{ session('ok') }}</div>
                @endif

                {{-- Formulario principal de actualización (método PUT) --}}
                <form class="gm-form" method="POST" action="{{ route('aspirantes.update', $aspirante) }}">
                    @csrf
                    @method('PUT')

                    <h3>Datos de Usuario</h3>
                    {{-- Bloque de datos personales y credenciales, campos rellenos con la información del aspirante (`$aspirante->usuario`). --}}
                    <div class="form-section">
                        <div>
                            <label for="nombre">Nombre(s)</label>
                            <input id="nombre" name="nombre" value="{{ old('nombre', $aspirante->usuario->nombre) }}" placeholder="Nombre" maxlength="100" required>
                        </div>
                        <div>
                            <label for="apellidoP">Apellido paterno</label>
                            <input id="apellidoP" name="apellidoP" value="{{ old('apellidoP', $aspirante->usuario->apellidoP) }}" placeholder="Apellido paterno" maxlength="100" required>
                        </div>
                        <div>
                            <label for="apellidoM">Apellido aterno</label>
                            <input id="apellidoM" name="apellidoM" value="{{ old('apellidoM', $aspirante->usuario->apellidoM) }}" placeholder="Apellido materno" maxlength="100" required>
                        </div>
                        <div>
                            <label for="fecha_nac">Fecha de Nacimiento</label>
                            <input id="fecha_nac" type="date" name="fecha_nac" value="{{ old('fecha_nac', optional($aspirante->usuario->fecha_nac)->format('Y-m-d')) }}" required>
                        </div>
                        <div>
                            <label for="usuario">Usuario</label>
                            <input id="usuario" name="usuario" value="{{ old('usuario', $aspirante->usuario->usuario) }}" placeholder="Usuario" maxlength="50" required>
                        </div>
                        <div>
                            <label for="pass">Nueva contraseña (opcional)</label>
                            <input id="pass" type="password" name="pass" placeholder="Nueva contraseña (opcional)">
                        </div>
                        <div>
                            <label for="pass_confirmation">ConfirmarnNueva contraseña</label>
                            <input id="pass_confirmation" type="password" name="pass_confirmation" placeholder="Confirmar nueva contraseña">
                        </div>
                        <div>
                            @php $generoSel = old('genero', $aspirante->usuario->genero); @endphp
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
                            <input id="correo" type="email" name="correo" value="{{ old('correo', $aspirante->usuario->correo) }}" placeholder="Correo" maxlength="100" required>
                        </div>
                        <div>
                            <label for="telefono">Teléfono</label>
                            <input id="telefono" name="telefono" value="{{ old('telefono', $aspirante->usuario->telefono) }}" placeholder="Teléfono" maxlength="20" required>
                        </div>
                        <div>
                            <label for="direccion">Dirección</label>
                            <input id="direccion" name="direccion" value="{{ old('direccion', $aspirante->usuario->direccion) }}" placeholder="Dirección" maxlength="100" required>
                        </div>
                    </div>
                    
                    {{-- El ID de rol es mejor mantenerlo oculto para que no se edite accidentalmente --}}
                    <input type="hidden" name="id_rol" value="{{ old('id_rol', $aspirante->usuario->id_rol) }}">

                    <h3>Datos de aspirante</h3>
                    <div class="form-section">
                        <div>
                            <label for="interes">Interés del aspirante</label>
                            <textarea id="interes" name="interes" placeholder="Interés" rows="3" maxlength="50" required>{{ old('interes', $aspirante->interes) }}</textarea>
                        </div>
                        <div>
                            <label for="dia">Fecha de cita</label>
                            <input type="date" name="dia" value="{{ old('dia', $aspirante->dia) }}" required>
                        </div>
                        <div>
                            @php $estatusSel = old('estatus', $aspirante->estatus); @endphp
                            <label for="estatus">Estatus</label>
                            <select id="estatus" name="estatus" required>
                                <option value="activo" {{ $estatusSel === 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="rechazado" {{ $estatusSel === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                <option value="aceptado" {{ $estatusSel === 'aceptado' ? 'selected' : '' }}>Aceptado</option>
                            </select>
                        </div>
                    </div>

                    {{-- Bloque de acciones, botón de Guardar y Cancelar. --}}
                    <div class="actions">
                        <a href="{{ route('aspirantes.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection