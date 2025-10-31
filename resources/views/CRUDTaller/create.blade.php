@extends('layouts.encabezados')
@section('title', 'Gestión talleres/prácticas')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de extracurriculares</h2>
                <p class="crud-hero-subtitle">Registro</p>

                <nav class="crud-tabs">
                    <a href="{{ route('extracurricular.create') }}" class="tab active">Registrar</a>
                    <a href="{{ route('extracurricular.index') }}" class="tab">Listar actividades</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Nueva actividad</h1>

                {{-- Bloque de errores y mensajes de sesión. --}}
                @if ($errors->any())
                    <ul class="gm-errors">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                @endif

                @if (session('ok'))
                    <div class="gm-ok">{{ session('ok') }}</div>
                @endif

                {{-- Formulario principal: envía los datos para crear una nueva actividad (método post). --}}
                <form class="gm-form" method="POST" action="{{ route('extracurricular.store') }}">
                    @csrf

                    <h3>Datos de la actividad</h3>
                    {{-- Bloque de datos principal: contiene toda la información de la actividad. --}}
                    <div class="form-section">
                        <div>
                            <label for="nombre_act">Nombre de la actividad</label>
                            <input id="nombre_act" name="nombre_act" value="{{ old('nombre_act') }}"
                                placeholder="Nombre de la actividad" maxlength="50" required>
                        </div>

                        <div>
                            <label for="responsable">Responsable</label>
                            <input id="responsable" name="responsable" value="{{ old('responsable') }}"
                                placeholder="Responsable" maxlength="100" required>
                        </div>

                        <div>
                            <label for="fecha">Fecha de la actividad</label>
                            {{-- El campo de fecha utiliza 'min' para evitar seleccionar fechas pasadas. --}}
                            <input id="fecha" type="date" name="fecha" value="{{ old('fecha') }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div>
                            <label for="tipo">Tipo</label>
                            {{-- Selector de tipo (taller o práctica). --}}
                            <select id="tipo" name="tipo" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="Taller" {{ old('tipo') === 'Taller' ? 'selected' : '' }}>Taller</option>
                                <option value="Practica" {{ old('tipo') === 'Practica' ? 'selected' : '' }}>Práctica</option>
                            </select>
                        </div>

                        {{-- Campos de hora de inicio y fin. --}}
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
                            <label for="lugar">Lugar</label>
                            <input id="lugar" name="lugar" value="{{ old('lugar') }}" placeholder="Lugar"
                                maxlength="100" required>
                        </div>

                        <div>
                            <label for="modalidad">Modalidad</label>
                            {{-- Selector de modalidad (presencial o virtual). --}}
                            <select id="modalidad" name="modalidad" required>
                                <option value="">Selecciona una modalidad</option>
                                <option value="Presencial" {{ old('modalidad') === 'Presencial' ? 'selected' : '' }}>
                                    Presencial</option>
                                <option value="Virtual" {{ old('modalidad') === 'Virtual' ? 'selected' : '' }}>Virtual
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="estatus">Estatus</label>
                            {{-- Selector de estatus (finalizada, convocatoria, en proceso). --}}
                            <select id="estatus" name="estatus" required>
                                <option value="">Selecciona un estatus</option>
                                <option value="Finalizada" {{ old('estatus') === 'Finalizada' ? 'selected' : '' }}>Finalizada
                                </option>
                                <option value="Convocatoria" {{ old('estatus') === 'Convocatoria' ? 'selected' : '' }}>
                                    Convocatoria</option>
                                <option value="En proceso" {{ old('estatus') === 'En proceso' ? 'selected' : '' }}>En proceso
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="capacidad">Capacidad</label>
                            {{-- Campo de capacidad, solo números positivos --}}
                            <input id="capacidad" type="number" name="capacidad" min="1" step="1"
                                inputmode="numeric" pattern="[0-9]*" value="{{ old('capacidad') }}" placeholder="Capacidad"
                                required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,5);">
                        </div>

                        <div>
                            <label for="material">Material requerido</label>
                            <input id="material" name="material" value="{{ old('material') }}"
                                placeholder="Material requerido" maxlength="150" required>
                        </div>

                        <div>
                            <label for="url">URL (opcional)</label>
                            <input id="url" name="url" value="{{ old('url') }}" placeholder="URL (opcional)"
                                maxlength="200">
                        </div>

                        <div>
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="3" placeholder="Descripción (máx. 200)" maxlength="200">{{ old('descripcion') }}</textarea>
                        </div>
                    </div>

                    {{-- Bloque de acciones, botón de Guardar y Cancelar. --}}
                    <div class="actions">
                        <a href="{{ route('extracurricular.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
