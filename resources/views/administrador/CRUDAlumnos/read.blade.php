@extends('layouts.encabezados')
@section('title', 'Gestión alumnos')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de alumnos</h2>
                <p class="crud-hero-subtitle">Listado</p>

                <nav class="crud-tabs">
                    <a href="{{ route('alumnos.create') }}" class="tab">Registrar</a>
                    <a href="{{ route('alumnos.index') }}" class="tab active">Listar alumnos</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Alumnos</h1>

                @if(session('ok'))
                    <div class="gm-ok">{{ session('ok') }}</div>
                @endif

                {{-- Estructura condicional para mostrar la tabla si hay datos o un mensaje de vacío. --}}
                @if($alumnos->count() === 0)
                    <div class="gm-empty">No hay alumnos registrados.</div>
                @else
                    <div class="table-responsive">
                        <table class="gm-table">
                            <thead>
                                <tr>
                                    <th>Matrícula</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Diplomado</th> 
                                    <th>Grupo</th> 
                                    <th>Estatus</th>
                                    <th class="th-actions">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                 {{-- Itera sobre la colección paginada de alumnos ($alumnos). --}}
                                @foreach($alumnos as $a)
                                    <tr>
                                        <td>{{ $a->matriculaA }}</td>
                                        {{-- Importante, acceso a la información del Usuario y Diplomado (relaciones Eloquent). --}}
                                        <td>
                                            {{ optional($a->usuario)->nombre }}
                                            {{ optional($a->usuario)->apellidoP }}
                                            {{ optional($a->usuario)->apellidoM }}
                                        </td>
                                        <td>{{ optional($a->usuario)->correo }}</td>
                                        <td>{{ optional($a->diplomado)->nombre }}</td>
                                        <td>{{ optional($a->diplomado)->grupo }}</td>
                                        <td>{{ $a->estatus }}</td>
                                        <td>
                                            <div class="table-actions">
                                                {{-- Muestra formulario de Actualización (link al formulario de edición). --}}
                                                <a href="{{ route('alumnos.edit', $a) }}" class="btn btn-ghost">Actualizar</a>

                                                {{-- Importante, formulario para la Eliminación (método DELETE). Incluye confirmación de JS. --}}
                                                <form action="{{ route('alumnos.destroy', $a) }}" method="POST" onsubmit="return confirm('¿Eliminar alumno y su usuario?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Muestra los enlaces de paginación de Laravel. --}}
                    <div class="pager">
                        {{ $alumnos->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection