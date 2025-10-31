@extends('layouts.encabezadosDoc')
@section('title', 'Gestión calificaciones')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de calificaciones</h2>
                <p class="crud-hero-subtitle">Captura</p>

                <nav class="crud-tabs">
                    <a href="{{ route('calif.create') }}" class="tab active">Registrar</a>
                    <a href="{{ route('calif.docente.index') }}" class="tab">Mis calificaciones</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Nueva calificación</h1>

                {{-- Bloque de mensajes, muestra mensajes de éxito (`ok`) de la sesión. --}}
                @if (session('ok'))
                    <div class="gm-ok">{{ session('ok') }}</div>
                @endif

                {{-- Bloque de errores, muestra los errores de validación de laravel (si los hay). --}}
                @if ($errors->any())
                    <ul class="gm-errors">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                @endif

                {{-- Formulario principal, envía los datos de la calificación al controlador (método post). --}}
                <form class="gm-form" method="POST" action="{{ route('calif.store') }}">
                    @csrf

                    <h3>Datos</h3>
                    {{-- Bloque de selección de módulo y alumno. --}}
                    <div class="grid-2">
                        <div>
                            <label for="id_modulo">Módulo</label>
                            {{-- Pasamos la URL a un atributo data para usarla en JS --}}
                            <select id="id_modulo" name="id_modulo" required data-url="{{ route('calif.alumnosPorModulo', ['modulo' => 0]) }}">
                                <option value="">Selecciona un módulo</option>
                                @foreach($modulos as $m)
                                    <option value="{{ $m->id_modulo }}" {{ old('id_modulo') == $m->id_modulo ? 'selected' : '' }}>
                                        Mód. {{ $m->numero_modulo }} — {{ $m->nombre_modulo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_modulo') <small class="gm-error">{{ $message }}</small> @enderror
                        </div>
                        <div>
                            <label for="id_alumno">Alumno</label>
                            {{-- El select de alumnos empieza deshabilitado --}}
                            <select id="id_alumno" name="id_alumno" required disabled>
                                <option value="">Selecciona un módulo primero</option>
                            </select>
                            @error('id_alumno') <small class="gm-error">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    {{-- Bloque de tipo de calificación y valor. --}}
                    <div class="grid-2">
                        <div>
                            <label for="tipo">Tipo</label>
                            <input list="tipos" id="tipo" name="tipo" value="{{ old('tipo') }}" placeholder="Parcial 1, Parcial 2, Final, Práctica…" required>
                            <datalist id="tipos">
                                <option value="Parcial 1"></option>
                                <option value="Parcial 2"></option>
                                <option value="Final"></option>
                                <option value="Práctica"></option>
                                <option value="Taller"></option>
                            </datalist>
                            @error('tipo') <small class="gm-error">{{ $message }}</small> @enderror
                        </div>

                        <div>
                            <label for="calificacion">Calificación</label>
                            <input type="number" step="0.01" min="0" max="100" id="calificacion" name="calificacion" value="{{ old('calificacion') }}" placeholder="0 - 100" required>
                            @error('calificacion') <small class="gm-error">{{ $message }}</small> @enderror
                            <small class="gm-help">Rango: 0 a 100. Usa punto decimal (ej. 89.50).</small>
                        </div>
                    </div>

                    <div>
                        <label for="observacion">Observación (opcional)</label>
                        <textarea id="observacion" name="observacion" rows="3" placeholder="Comentarios, evidencias, correcciones…">{{ old('observacion') }}</textarea>
                        @error('observacion') <small class="gm-error">{{ $message }}</small> @enderror
                    </div>

                    {{-- Bloque de acciones, botón de guardar y cancelar. --}}
                    <div class="actions">
                        <a href="{{ route('calif.docente.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
  {{-- Se incluyen scripts específicos para manejar la lógica de carga dinámica de alumnos y la validación de calificación. --}}
  @vite('resources/js/calificacionCreate.js')
  @vite('resources/js/calificacion.js')
@endpush
