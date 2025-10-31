@extends('layouts.encabezados')
@section('title', 'Gestión administradores')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de administradores</h2>
                <p class="crud-hero-subtitle">Listado</p>

                <nav class="crud-tabs">
                    <a href="{{ route('admin.create') }}" class="tab">Registrar</a>
                    <a href="{{ route('admin.index') }}" class="tab active">Listar
                        administradores</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Administradores</h1>

                {{-- Bloque de mensajes, muestra mensajes de éxito (`success` o `ok`) de la sesión. --}}
                @if (session('success'))
                    <div class="gm-ok">{{ session('success') }}</div>
                @endif
                @if (session('ok'))
                    <div class="gm-ok">{{ session('ok') }}</div>
                @endif

                {{-- Bloque de listado, muestra la tabla si hay datos o un mensaje de vacío. --}}
                @if ($admin->count() === 0)
                    <div class="gm-empty">No hay administradores registrados.</div>
                @else
                    <div class="table-responsive">
                        <table class="gm-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Usuario</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Fecha ingreso</th>
                                    <th>Rol</th>
                                    <th>Estatus</th>
                                    <th class="th-actions">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Bloque de datos (bucle), itera sobre la colección paginada de administradores ($admin). --}}
                                @foreach ($admin as $a)
                                    <tr>
                                        {{-- Acceso a la relación 'usuario' para obtener el nombre completo. --}}
                                        <td>
                                            {{ optional($a->usuario)->nombre }}
                                            {{ optional($a->usuario)->apellidoP }}
                                            {{ optional($a->usuario)->apellidoM }}
                                        </td>
                                        <td>{{ optional($a->usuario)->usuario }}</td>
                                        <td>{{ optional($a->usuario)->correo }}</td>
                                        <td>{{ optional($a->usuario)->telefono }}</td>
                                        <td>{{ $a->fecha_ingreso }}</td>
                                        <td>{{ $a->rol }}</td>
                                        <td>{{ $a->estatus }}</td>
                                        <td>
                                            <div class="table-actions">
                                                {{-- Botón de acción, enlace al formulario de edición. --}}
                                                <a href="{{ route('admin.edit', $a) }}" class="btn btn-ghost">Actualizar</a>

                                                {{-- Formulario de eliminación, utiliza el método delete y requiere confirmación de js. --}}
                                                <form action="{{ route('admin.destroy', $a) }}"
                                                    method="POST" onsubmit="return confirm('¿Eliminar este administrador y su usuario asociado?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Bloque de paginación, muestra los enlaces de paginación de laravel. --}}
                    <div class="pager">
                        {{ $admin->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection