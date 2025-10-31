@extends('layouts.encabezadosDoc')
@section('title', 'Gestión calificaciones')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de calificaciones</h2>
                <p class="crud-hero-subtitle">Editar</p>

                <nav class="crud-tabs">
                    <a href="{{ route('calif.create') }}" class="tab">Registrar</a>
                    <a href="{{ route('calif.docente.index') }}" class="tab">Listado</a>
                    <a href="#" class="tab active" onclick="return false;">Editar</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Editar calificación #{{ $calif->id_calif }}</h1>

                {{-- Bloque de mensajes y errores de validación. --}}
                @if (session('ok'))
                    <div class="gm-ok">{{ session('ok') }}</div>
                @endif

                @if ($errors->any())
                    <ul class="gm-errors">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                @endif

                {{-- Formulario principal de actualización, utiliza el método put. --}}
                <form class="gm-form" method="POST" action="{{ route('calif.update', $calif->id_calif) }}">
                    @csrf
                    @method('PUT')

                    <h3>Datos</h3>

                    {{-- Bloque de selección de alumno. --}}
                    <div class="grid-2">
                        <div>
                            <label for="id_alumno">Alumno</label>
                            <select id="id_alumno" name="id_alumno" required>
                                <option value="">Selecciona un alumno</option>
                                @foreach($alumnos as $a)
                                    @php
                                        $nombre = optional($a->usuario)->nombre.' '.optional($a->usuario)->apellidoP.' '.optional($a->usuario)->apellidoM;
                                        $nombre = trim($nombre) ?: ('Alumno #'.$a->id_alumno);
                                        $sel = old('id_alumno', $calif->id_alumno) == $a->id_alumno ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $a->id_alumno }}" {{ $sel }}>
                                        {{ $nombre }} — Grupo: {{ $a->diplomado->grupo }} — Diplomado: {{ $a->diplomado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_alumno') <small class="gm-error">{{ $message }}</small> @enderror
                        </div>

                        {{-- Bloque de selección de módulo. --}}
                        <div>
                            <label for="id_modulo">Módulo</label>
                            <select id="id_modulo" name="id_modulo" required>
                                <option value="">Selecciona un módulo</option>
                                @foreach($modulos as $m)
                                    @php
                                        $sel = old('id_modulo', $calif->id_modulo) == $m->id_modulo ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $m->id_modulo }}" {{ $sel }}>
                                        Mód. {{ $m->numero_modulo }} — {{ $m->nombre_modulo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_modulo') <small class="gm-error">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    {{-- Bloque de tipo de calificación y valor. --}}
                    <div class="grid-2">
                        <div>
                            <label for="tipo">Tipo</label>
                            <input list="tipos" id="tipo" name="tipo"
                                   value="{{ old('tipo', $calif->tipo) }}"
                                   placeholder="Parcial 1, Parcial 2, Final, Práctica…" required>
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
                            <input type="number" step="0.01" min="0" max="100" id="calificacion" name="calificacion"
                                   value="{{ old('calificacion', number_format($calif->calificacion, 2, '.', '')) }}"
                                   placeholder="0 - 100" required>
                            @error('calificacion') <small class="gm-error">{{ $message }}</small> @enderror
                            <small class="gm-help">Rango: 0 a 100. Usa punto decimal (ej. 89.50).</small>
                        </div>
                    </div>

                    <div>
                        <label for="observacion">Observación (opcional)</label>
                        <textarea id="observacion" name="observacion" rows="3"
                                  placeholder="Comentarios, evidencias, correcciones…">{{ old('observacion', $calif->observacion) }}</textarea>
                        @error('observacion') <small class="gm-error">{{ $message }}</small> @enderror
                    </div>

                    {{-- Bloque de acciones, botón de actualizar y cancelar. --}}
                    <div class="actions">
                        <a href="{{ route('calif.docente.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    {{-- Script de validación de js: asegura que el campo de calificación se mantenga en el rango 0-100. --}}
    <script>
        const inputCalif = document.getElementById('calificacion');
        inputCalif?.addEventListener('input', () => {
            const v = parseFloat(inputCalif.value);
            if (isNaN(v)) return;
            if (v < 0) inputCalif.value = 0;
            if (v > 100) inputCalif.value = 100;
        });
    </script>
@endsection