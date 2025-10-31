@extends('layouts.encabezados')
@section('title', 'Gestión de diplomados')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de diplomados</h2>
                <p class="crud-hero-subtitle">Listado</p>

                {{-- Navegación de pestañas, el link "listar diplomados" está marcado como activo. --}}
                <nav class="crud-tabs">
                    <a href="{{ route('admin.diplomados.create') }}" class="tab">Registrar</a>
                    <a href="{{ route('admin.diplomados.index') }}" class="tab active">Listar diplomados</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Diplomados</h1>
                {{-- Bloque de mensajes, muestra mensaje de éxito (`ok`) de la sesión. --}}
                @if(session('ok'))
                    <div class="gm-ok">{{ session('ok') }}</div>
                @endif

                {{-- Bloque de listado, muestra la tabla si hay datos o un mensaje de vacío. --}}
                @if($diplomados->isEmpty())
                    <div class="gm-empty">No hay diplomados registrados.</div>
                @else
                    <div class="table-responsive">
                        <table class="gm-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Grupo</th>
                                    <th>Tipo</th>
                                    <th>Capacidad</th>
                                    <th>Fecha de inicio</th>
                                    <th>Fecha de fin</th>
                                    <th class="th-actions">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Bloque de datos (bucle), itera sobre la colección paginada de diplomados ($diplomados). --}}
                                @foreach($diplomados as $diplomado)
                                    <tr>
                                        <td>{{ $diplomado->nombre }}</td>
                                        <td>{{ $diplomado->grupo }}</td>
                                        <td>{{ $diplomado->tipo }}</td>
                                        <td>{{ $diplomado->capacidad }}</td>
                                        <td>{{ $diplomado->fecha_inicio }}</td>
                                        <td>{{ $diplomado->fecha_fin }}</td>
                                        <td>
                                            <div class="table-actions">
                                                {{-- Botón de acción, enlace al formulario de edición. --}}
                                                <a href="{{ route('admin.diplomados.edit', $diplomado) }}" class="btn btn-ghost">Actualizar</a>

                                                {{-- Formulario de eliminación, utiliza el método delete y requiere confirmación de js. --}}
                                                <form action="{{ route('admin.diplomados.destroy', $diplomado) }}" method="POST" onsubmit="return confirm('¿Eliminar este diplomado?')">
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
                        {{ $diplomados->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection