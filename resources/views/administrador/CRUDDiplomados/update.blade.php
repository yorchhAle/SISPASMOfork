@extends('layouts.encabezados')
@section('title', 'Gestión de diplomados')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de diplomados</h2>
                <p class="crud-hero-subtitle">Actualización de datos</p>
                {{-- Navegación de pestañas, el link "listar diplomados" está marcado como activo. --}}
                <nav class="crud-tabs">
                    <a href="{{ route('admin.diplomados.create') }}" class="tab">Registrar</a>
                    <a href="{{ route('admin.diplomados.index') }}" class="tab active">Listar diplomados</a>
                </nav>
            </header>
            <div class="crud-body">
                <h1>Actualizar diplomado</h1>

                {{-- Bloque de errores, muestra los errores de validación de laravel (si los hay). --}}
                @if ($errors->any())
                    <ul class="gm-errors">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                @endif

                {{-- Formulario principal de actualización, utiliza el método put para enviar los datos a la ruta update. --}}
                <form class="gm-form" method="POST" action="{{ route('admin.diplomados.update', $diplomado) }}">
                    @csrf
                    @method('PUT')
                    {{-- Bloque de datos, campos rellenados con la información actual del diplomado ($diplomado). --}}
                    <div class="form-section">
                        <div>
                            <label for="nombre">Nombre del diplomado</label>
                            <input id="nombre" name="nombre" value="{{ old('nombre', $diplomado->nombre) }}" placeholder="Nombre del diplomado" required>
                        </div>
                        <div>
                            <label for="grupo">Grupo</label>
                            <input id="grupo" name="grupo" value="{{ old('grupo', $diplomado->grupo) }}" placeholder="Grupo" required>
                        </div>

                        {{-- Selección de tipo de diplomado, preselecciona el valor actual. --}}
                        <div>
                            @php $tipoSel = old('tipo', $diplomado->tipo); @endphp
                            <label for="tipo">Tipo de diplomado</label>
                            <select id="tipo" name="tipo" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="basico" {{ $tipoSel === 'basico' ? 'selected' : '' }}>Básico</option>
                                <option value="intermedio y avanzado" {{ $tipoSel === 'intermedio y avanzado' ? 'selected' : '' }}>Intermedio y avanzado</option>
                            </select>
                        </div>
                        <div>
                            <label for="capacidad">Capacidad de alumnos</label>
                            <input id="capacidad" type="number" name="capacidad" value="{{ old('capacidad', $diplomado->capacidad) }}" placeholder="Capacidad de alumnos" required>
                        </div>

                        {{-- Campo de fecha de inicio, se usa carbon para asegurar el formato 'y-m-d'. --}}
                        <div>
                            <label for="fecha_inicio">Fecha de inicio</label>
                            {{-- Se formatea la fecha a 'Y-m-d' para que el input la reconozca --}}
                            <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', \Carbon\Carbon::parse($diplomado->fecha_inicio)->format('Y-m-d')) }}" required>
                        </div>

                        {{-- Campo de fecha de fin, se usa carbon para asegurar el formato 'y-m-d'. --}}
                        <div>
                            <label for="fecha_fin">Fecha de fin</label>
                            {{-- Se formatea la fecha a 'Y-m-d' para que el input la reconozca --}}
                            <input type="date" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', \Carbon\Carbon::parse($diplomado->fecha_fin)->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    {{-- Bloque de acciones, botón de actualizar y cancelar. --}}
                    <div class="actions">
                        <a href="{{ route('admin.diplomados.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection