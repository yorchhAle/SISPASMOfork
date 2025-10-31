@extends('layouts.encabezados')
@section('title', 'Gestión de diplomados')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de diplomados</h2>
                <p class="crud-hero-subtitle">Registro</p>
                {{-- Navegación de pestañas, el link "Registrar" está marcado como activo. --}}
                <nav class="crud-tabs">
                    <a href="{{ route('admin.diplomados.create') }}" class="tab active">Registrar</a>
                    <a href="{{ route('admin.diplomados.index') }}" class="tab">Listar diplomados</a>
                </nav>
            </header>
            <div class="crud-body">
                <h1>Nuevo diplomado</h1>
                {{-- Bloque de errores, muestra los errores de validación de Laravel (si los hay). --}}
                @if ($errors->any())
                    <ul class="gm-errors">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                @endif

                {{-- Formulario principal, envía los datos para crear un nuevo diplomado (método POST). --}}
                <form class="gm-form" method="POST" action="{{ route('admin.diplomados.store') }}">
                    @csrf

                    {{-- Bloque de datos, campos para la información general y temporalidad del diplomado. --}}
                    <div class="form-section">
                        <div>
                            <label for="nombre">Número de diplomado</label>
                            <input id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: 51" required>
                        </div>
                        <div>
                            <label for="grupo">Grupo</label>
                            <input id="grupo" name="grupo" value="{{ old('grupo') }}" placeholder="Ej: Deltas" required>
                        </div>
                        <div>
                            <label for="tipo">Tipo de diplomado</label>
                            <select id="tipo" name="tipo" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="basico" {{ old('tipo') === 'basico' ? 'selected' : '' }}>Básico</option>
                                <option value="intermedio y avanzado" {{ old('tipo') === 'intermedio y avanzado' ? 'selected' : '' }}>Intermedio y avanzado</option>
                            </select>
                        </div>
                        <div>
                            <label for="capacidad">Capacidad de alumnos</label>
                            <input id="capacidad" type="number" name="capacidad" value="{{ old('capacidad') }}" placeholder="Capacidad de alumnos" required>
                        </div>

                        {{-- Campos de Fecha de Inicio y Fecha de Fin (la lógica valida que Fin > Inicio). --}}
                        <div>
                            <label for="fecha_inicio">Fecha de inicio</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                        </div>
                        <div>
                            <label for="fecha_fin">Fecha de fin</label>
                            <input type="date" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                        </div>
                    </div>

                    {{-- Bloque de acciones, botón de Guardar y Cancelar. --}}
                    <div class="actions">
                        <a href="{{ route('admin.diplomados.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection