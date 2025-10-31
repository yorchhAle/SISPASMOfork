@extends('layouts.encabezados')
@section('title', 'Gestión coordinadores')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de coordinadores</h2>
                <p class="crud-hero-subtitle">Registro</p>
                <nav class="crud-tabs">
                    <a href="{{ route('coordinadores.create') }}" class="tab active">Registrar</a>
                    <a href="{{ route('coordinadores.index') }}" class="tab">Listar coordinadores</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Nuevo coordinador</h1>

                @if ($errors->any())
                    <ul class="gm-errors">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                @endif

                @if (session('success'))
                    <div class="gm-ok">{{ session('success') }}</div>
                @endif

                <form class="gm-form" method="POST" action="{{ route('coordinadores.store') }}">
                    @csrf

                    <h3>Datos de usuario</h3>
                    <div class="form-section">
                        <div>
                            <label for="nombre">Nombre(s)</label>
                            <input id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre(s)" maxlength="100" required>
                        </div>

                        <div>
                            <label for="apellidoP">Apellido paterno</label>
                            <input id="apellidoP" name="apellidoP" value="{{ old('apellidoP') }}" placeholder="Apellido paterno" maxlength="50" required>
                        </div>

                        <div>
                            <label for="apellidoM">Apellido materno</label>
                            <input id="apellidoM" name="apellidoM" value="{{ old('apellidoM') }}" placeholder="Apellido materno" maxlength="50" required>
                        </div>

                        <div>
                            <label for="fecha_nac">Fecha de nacimiento</label>
                            <input id="fecha_nac" type="date" name="fecha_nac" value="{{ old('fecha_nac') }}" required>
                        </div>

                        <div>
                            <label for="usuario">Usuario</label>
                            <input id="usuario" name="usuario" value="{{ old('usuario') }}" placeholder="Usuario" maxlength="50" required>
                        </div>

                        <div>
                            <label for="pass">Contraseña</label>
                            <input id="pass" type="password" name="pass" placeholder="Contraseña" required>
                        </div>

                        <div>
                            <label for="genero">Género</label>
                            <select id="genero" name="genero" required>
                                <option value="">Selecciona un género</option>
                                <option value="M" {{ old('genero') === 'M' ? 'selected' : '' }}>M</option>
                                <option value="F" {{ old('genero') === 'F' ? 'selected' : '' }}>F</option>
                                <option value="Otro" {{ old('genero') === 'Otro' ? 'selected' : '' }}>Otro</option>
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


                    <h3>Datos de coordinador</h3>
                    <div class="form-section">
                        <div>
                            <label for="fecha_ingreso">Fecha de ingreso</label>
                            <input id="fecha_ingreso" type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" required>
                        </div>

                        <div>
                            @php $estatusSel = old('estatus'); @endphp
                            <label for="estatus">Estatus</label>
                            <select id="estatus" name="estatus" required>
                                <option value="">Selecciona un estatus</option>
                                <option value="activo" {{ $estatusSel === 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ $estatusSel === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>


                    <div class="actions">
                        <a href="{{ route('coordinadores.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
