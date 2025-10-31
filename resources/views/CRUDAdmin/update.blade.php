@extends('layouts.encabezados')
@section('title', 'Gestión administradores')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de administradores</h2>
                <p class="crud-hero-subtitle">Actualización</p>

                <nav class="crud-tabs">
                    <a href="{{ route('admin.create') }}" class="tab">Registrar</a>
                    <a href="{{ route('admin.index') }}" class="tab active">Listar administradores</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Actualizar administrador</h1>

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
                <form class="gm-form" method="POST" action="{{ route('admin.update', $admin) }}">
                    @csrf
                    @method('PUT')

                    {{-- Bloque de datos de usuario, información personal. --}}
                    <h3>Datos de usuario</h3>
                    <div>
                        <input name="nombre" value="{{ old('nombre', optional($admin->usuario)->nombre) }}" placeholder="Nombre" required>
                        <input name="apellidoP" value="{{ old('apellidoP', optional($admin->usuario)->apellidoP) }}" placeholder="Apellido paterno" required>
                        <input name="apellidoM" value="{{ old('apellidoM', optional($admin->usuario)->apellidoM) }}" placeholder="Apellido materno" required>
                        <input type="date" name="fecha_nac" value="{{ old('fecha_nac', optional($admin->usuario)->fecha_nac) }}" required>
                    </div>

                    <div>
                        <input name="usuario" value="{{ old('usuario', optional($admin->usuario)->usuario) }}" placeholder="Usuario" required>
                        <input type="password" name="pass" placeholder="Nueva contraseña (opcional)">
                        <input type="password" name="pass_confirmation" placeholder="Confirmar nueva contraseña (si la cambias)">
                    </div>

                    <div>
                        @php $generoSel = old('genero', optional($admin->usuario)->genero); @endphp
                        <select name="genero" required>
                            <option value="">Género</option>
                            <option value="M" {{ $generoSel === 'M' ? 'selected' : '' }}>M</option>
                            <option value="F" {{ $generoSel === 'F' ? 'selected' : '' }}>F</option>
                            <option value="Otro" {{ $generoSel === 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        <input type="hidden" name="id_usuario" value="{{ $admin->id_usuario }}">

                        {{-- Bloque de credenciales, usuario y campos de contraseña opcionales. --}}
                        <input type="email" name="correo" value="{{ old('correo', optional($admin->usuario)->correo) }}" placeholder="Correo" required>
                        <input name="telefono" value="{{ old('telefono', optional($admin->usuario)->telefono) }}" placeholder="Teléfono" required>
                        <input name="direccion" value="{{ old('direccion', optional($admin->usuario)->direccion) }}" placeholder="Dirección" required>
                    </div>

                    {{-- Bloque de datos de administrador, fecha de ingreso, rol y estatus. --}}                    <h3>Datos de administrador</h3>
                    <div>
                        <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', $admin->fecha_ingreso) }}" placeholder="Fecha de ingreso" required>
                        <input name="rol" value="{{ old('rol', $admin->rol) }}" placeholder="Rol" required>
                        @php $estSel = old('estatus', $admin->estatus); @endphp
                        <select name="estatus" required>
                            <option value="activo" {{ $estSel === 'activo' ? 'selected' : '' }}>activo</option>
                            <option value="inactivo" {{ $estSel === 'inactivo' ? 'selected' : '' }}>inactivo</option>
                        </select>
                    </div>

                    {{-- Bloque de acciones, botón de actualizar y cancelar. --}}
                    <div class="actions">
                        <a href="{{ route('admin.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection