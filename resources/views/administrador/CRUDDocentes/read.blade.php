@extends('layouts.encabezados')
@section('title', 'Gestión docentes')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de docentes</h2>
                <p class="crud-hero-subtitle">Listado</p>

                {{-- Navegación de pestañas, el link "listar docentes" está marcado como activo. --}}
                <nav class="crud-tabs">
                    <a href="{{ route('docentes.create') }}" class="tab">Registrar</a>
                    <a href="{{ route('docentes.index') }}" class="tab active">Listar docentes</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Docentes</h1>

                {{-- Bloque de mensajes, muestra mensajes de éxito (`success` o `ok`) de la sesión. --}}
                @if (session('success'))
                    <div class="gm-ok">{{ session('success') }}</div>
                @endif
                @if (session('ok'))
                    <div class="gm-ok">{{ session('ok') }}</div>
                @endif

                {{-- Bloque de listado, muestra la tabla si hay datos o un mensaje de vacío. --}}
                @if ($docentes->count() === 0)
                    <div class="gm-empty">No hay docentes registrados.</div>
                @else
                    <div class="table-responsive">
                        <table class="gm-table">
                            <thead>
                                <tr>
                                    <th>Matrícula</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Especialidad</th>
                                    <th>Cédula</th>
                                    <th>Salario</th>
                                    <th class="th-actions">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            {{-- Bloque de datos (bucle), itera sobre la colección paginada de docentes ($docentes). --}}
                                @foreach ($docentes as $d)
                                    <tr>
                                        <td>{{ $d->matriculaD }}</td>
                                        <td>
                                            {{ optional($d->usuario)->nombre }}
                                            {{ optional($d->usuario)->apellidoP }}
                                            {{ optional($d->usuario)->apellidoM }}
                                        </td>
                                        <td>{{ optional($d->usuario)->correo }}</td>
                                        <td>{{ $d->especialidad }}</td>
                                        <td>{{ $d->cedula }}</td>
                                        <td>{{ is_numeric($d->salario) ? number_format($d->salario, 2) : $d->salario }}</td>
                                        <td>
                                            <div class="table-actions">
                                                {{-- Botón de acción, enlace al formulario de edición. --}}
                                                <a href="{{ route('docentes.edit', $d) }}" class="btn btn-ghost">Actualizar</a>

                                                {{-- Formulario de eliminación, utiliza el método delete y requiere confirmación de js. --}}
                                                <form action="{{ route('docentes.destroy', $d) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('¿Eliminar este docente y su usuario asociado?')">
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
                        {{ $docentes->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection