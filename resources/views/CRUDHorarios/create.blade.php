@extends('layouts.encabezados')
@section('title', 'Gestión de horarios')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de horarios</h2>
                <p class="crud-hero-subtitle">Registro</p>
                <nav class="crud-tabs">
                    <a href="{{ route('admin.horarios.create') }}" class="tab active">Registrar</a>
                    <a href="{{ route('admin.horarios.index') }}" class="tab">Listar horarios</a>
                </nav>
            </header>
            <div class="crud-body">
                <h1>Nuevo horario</h1>
                {{-- Bloque de errores, muestra los errores de validación de laravel (si los hay). --}}
                @if ($errors->any())
                    <ul class="gm-errors">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                @endif

                {{-- Formulario principal, envía los datos para crear un nuevo horario (método post). --}}
                <form class="gm-form" method="POST" action="{{ route('admin.horarios.store') }}">
                    @csrf

                    {{-- Bloque de datos: campos para la fecha, hora, modalidad y asignaciones. --}}
                    <div class="form-section">
                        <div>
                            <label for="fecha">Fecha</label>
                            <input id="fecha" type="date" name="fecha" value="{{ old('fecha') }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div>
                            <label for="modalidad">Modalidad</label>
                            <select id="modalidad" name="modalidad" required>
                                <option value="">Selecciona una modalidad</option>
                                <option value="Presencial" {{ old('modalidad') === 'Presencial' ? 'selected' : '' }}>
                                    Presencial</option>
                                <option value="Virtual" {{ old('modalidad') === 'Virtual' ? 'selected' : '' }}>Virtual
                                </option>
                                <option value="Práctica" {{ old('modalidad') === 'Práctica' ? 'selected' : '' }}>Práctica
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="hora_inicio">Hora de inicio</label>
                            <input id="hora_inicio" type="time" name="hora_inicio" value="{{ old('hora_inicio') }}"
                                required>
                        </div>

                        <div>
                            <label for="hora_fin">Hora de fin</label>
                            <input id="hora_fin" type="time" name="hora_fin" value="{{ old('hora_fin') }}" required>
                        </div>

                        <div>
                            <label for="aula">Aula / Ubicación</label>
                            <input id="aula" type="text" name="aula" value="{{ old('aula') }}"
                                placeholder="Aula/Ubicación" required>
                        </div>

                        <div>
                            <label for="id_diplomado">Diplomado</label>
                            <select id="id_diplomado" name="id_diplomado" required>
                                <option value="">Selecciona un diplomado</option>
                                @foreach ($diplomados as $diplomado)
                                    <option value="{{ $diplomado->id_diplomado }}"
                                        {{ old('id_diplomado') == $diplomado->id_diplomado ? 'selected' : '' }}>
                                        {{ $diplomado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="id_modulo">Módulo</label>
                            <select id="id_modulo" name="id_modulo" required>
                                <option value="">Selecciona un módulo</option>
                                @foreach ($modulos as $modulo)
                                    <option value="{{ $modulo->id_modulo }}"
                                        {{ old('id_modulo') == $modulo->id_modulo ? 'selected' : '' }}>
                                        {{ $modulo->nombre_modulo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="id_docente">Docente</label>
                            <select id="id_docente" name="id_docente" required>
                                <option value="">Selecciona un docente</option>
                                @foreach ($docentes as $docente)
                                    <option value="{{ $docente->id_docente }}"
                                        {{ old('id_docente') == $docente->id_docente ? 'selected' : '' }}>
                                        {{ $docente->usuario->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Bloque de acciones, botón de Guardar y Cancelar. --}}
                    <div class="actions">
                        <a href="{{ route('admin.horarios.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
