@extends('layouts.encabezados')
@section('title', 'Gestión talleres/prácticas')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de extracurriculares</h2>
                <p class="crud-hero-subtitle">Listado</p>

                <nav class="crud-tabs">
                    <a href="{{ route('extracurricular.create') }}" class="tab">Registrar</a>
                    <a href="{{ route('extracurricular.index') }}" class="tab active">Listar actividades</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Actividades extracurriculares</h1>

                {{-- Bloque de mensajes, muestra mensajes de éxito (`success` o `ok`) de la sesión. --}}
                @if(session('success'))
                    <div class="gm-ok">{{ session('success') }}</div>
                @endif
                @if(session('ok'))
                    <div class="gm-ok">{{ session('ok') }}</div>
                @endif

                {{-- Bloque de listado, muestra la tabla si hay datos o un mensaje de vacío. --}}
                @if($talleres->count() === 0)
                    <div class="gm-empty">No hay actividades registradas.</div>
                @else
                    <div class="table-responsive">
                        <table class="gm-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Responsable</th>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Lugar</th>
                                    <th>Modalidad</th>
                                    <th>Estatus</th>
                                    <th>Capacidad</th>
                                    <th>Material</th>
                                    <th>URL</th>
                                    <th class="th-actions">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Bloque de datos (bucle), itera sobre la colección paginada de talleres/extracurriculares ($talleres). --}}
                                @foreach($talleres as $e)
                                    <tr>
                                        <td>{{ $e->nombre_act }}</td>
                                        <td>{{ $e->responsable }}</td>
                                        <td>{{ date('Y-m-d', strtotime($e->fecha)) }}</td>
                                        <td>{{ $e->tipo }}</td>
                                        <td>{{ $e->hora_inicio }}</td>
                                        <td>{{ $e->hora_fin }}</td>
                                        <td>{{ $e->lugar }}</td>
                                        <td>{{ $e->modalidad }}</td>
                                        <td>{{ $e->estatus }}</td>
                                        <td>{{ $e->capacidad }}</td>
                                        <td>{{ $e->material }}</td>
                                        <td>
                                            {{-- Muestra un botón de enlace si la url está registrada. --}}
                                            @if(!empty($e->url))
                                                <a href="{{ $e->url }}" target="_blank" rel="noopener" class="btn btn-ghost">Abrir</a>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                {{-- Botón de acción, enlace al formulario de edición. --}}
                                                <a href="{{ route('extracurricular.edit', $e) }}" class="btn btn-ghost">Actualizar</a>

                                                {{-- Formulario de eliminación, utiliza el método delete y requiere confirmación de js. --}}
                                                <form action="{{ route('extracurricular.destroy', $e) }}" method="POST" onsubmit="return confirm('¿Eliminar la actividad {{ $e->nombre_act }}?')">
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
                        {{ $talleres->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection