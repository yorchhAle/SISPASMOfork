@extends('layouts.encabezados')
@section('title', 'Gestión de horarios')

@section('content')
<div class="crud-wrap">
    <div class="crud-card">
        <div class="crud-hero">
            <h2 class="crud-hero-title">Gestión de horarios</h2>
            <p class="crud-hero-subtitle">Listado</p>

            <nav class="crud-tabs">
                <a href="{{ route('admin.horarios.create') }}" class="tab">Registrar</a>
                <a href="{{ route('admin.horarios.index') }}" class="tab active">Listar horarios</a>
            </nav>
        </div>

        <div class="crud-body">
            <h1>Horarios</h1>
            {{-- Bloque de mensajes, muestra mensaje de éxito (`success`) de la sesión. --}}
            @if (session('success'))
                <div class="gm-ok">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Bloque de listado, muestra la tabla si hay datos o un mensaje de vacío. --}}
            @if ($horarios->isEmpty())
                <div class="gm-empty">
                    No hay horarios registrados.
                </div>
            @else
                <div class="table-responsive">
                    <table class="gm-table">
                        <thead>
                            <tr>
                                <th>Diplomado</th>
                                <th>Módulo</th>
                                <th>Docente</th>
                                <th>Fecha</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Modalidad</th>
                                <th>Aula</th>
                                <th class="th-actions">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Bloque de datos (bucle), itera sobre la colección paginada de horarios ($horarios). --}}
                            @foreach ($horarios as $horario)
                                <tr>
                                    <td>{{ $horario->diplomado?->nombre }} ({{ $horario->diplomado?->grupo }})</td>
                                    <td>{{ $horario->modulo->nombre_modulo }}</td>
                                    <td>{{ $horario->docente?->usuario?->nombre }} {{ $horario->docente?->usuario?->apellidoP }}</td>                                    <td>{{ \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</td>
                                    <td>
                                        @switch($horario->modalidad)
                                            @case('Presencial')
                                                <span class="badge badge-validado">Presencial</span>
                                                @break
                                            @case('Virtual')
                                                <span class="badge badge-pendiente">Virtual</span>
                                                @break
                                            @case('Práctica')
                                                <span class="badge badge-rechazado">Práctica</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $horario->aula }}</td>
                                    <td>
                                        <div class="table-actions">
                                            {{-- Botón de acción, enlace al formulario de edición. --}}
                                            <a href="{{ route('admin.horarios.edit', $horario->id_horario) }}" class="btn btn-ghost">Editar</a>
                                            {{-- Formulario de eliminación, utiliza el método delete y requiere confirmación de js. --}}
                                            <form action="{{ route('admin.horarios.destroy', $horario->id_horario) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este horario?')">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection