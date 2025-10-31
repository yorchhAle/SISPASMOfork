@extends('layouts.encabezados')
@section('title', 'Gestión talleres/prácticas')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de extracurriculares</h2>
                <p class="crud-hero-subtitle">Actualización</p>

                <nav class="crud-tabs">
                    <a href="{{ route('extracurricular.create') }}" class="tab">Registrar</a>
                    <a href="{{ route('extracurricular.index') }}" class="tab active">Listar actividades</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Actualizar actividad</h1>

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

                {{-- Formulario principal de actualización, utiliza el método put para enviar los datos a la ruta update. --}}
                <form class="gm-form" method="POST" action="{{ route('extracurricular.update', $taller) }}">
                    @csrf
                    @method('PUT')

                    <h3>Datos de la actividad</h3>
                    {{-- Bloque de datos principal: todos los campos rellenados con la información actual del taller ($taller). --}}
                    <div class="form-section">
                        <div>
                            <label for="nombre_act">Nombre de la actividad</label>
                            <input id="nombre_act" name="nombre_act" value="{{ old('nombre_act', $taller->nombre_act) }}"
                                placeholder="Nombre de la actividad" maxlength="50" required>
                        </div>

                        <div>
                            <label for="responsable">Responsable</label>
                            <input id="responsable" name="responsable"
                                value="{{ old('responsable', $taller->responsable) }}" placeholder="Responsable"
                                maxlength="100" required>
                        </div>

                        <div>
                            <label for="fecha">Fecha de la Actividad</label>
                            {{-- Campo de fecha prellenado y con restricción mínima para no seleccionar fechas pasadas. --}}
                            <input id="fecha" type="date" name="fecha"
                                value="{{ old('fecha', \Carbon\Carbon::parse($taller->fecha)->format('Y-m-d')) }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div>
                            @php $tipoSel = old('tipo', $taller->tipo); @endphp
                            <label for="tipo">Tipo</label>
                            <select id="tipo" name="tipo" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="Taller" {{ $tipoSel === 'Taller' ? 'selected' : '' }}>Taller</option>
                                <option value="Practica" {{ $tipoSel === 'Practica' ? 'selected' : '' }}>Práctica</option>
                            </select>
                        </div>

                        {{-- Campos de hora de inicio y fin, prellenados con formato h:i. --}}
                        <div>
                            <label for="hora_inicio">Hora de inicio</label>
                            <input id="hora_inicio" type="time" name="hora_inicio"
                                value="{{ old('hora_inicio', \Carbon\Carbon::parse($taller->hora_inicio)->format('H:i')) }}"
                                required>
                        </div>

                        <div>
                            <label for="hora_fin">Hora de fin</label>
                            <input id="hora_fin" type="time" name="hora_fin"
                                value="{{ old('hora_fin', \Carbon\Carbon::parse($taller->hora_fin)->format('H:i')) }}"
                                required>
                        </div>

                        <div>
                            <label for="lugar">Lugar</label>
                            <input id="lugar" name="lugar" value="{{ old('lugar', $taller->lugar) }}"
                                placeholder="Lugar" maxlength="100" required>
                        </div>

                        <div>
                            @php $modalidadSel = old('modalidad', $taller->modalidad); @endphp
                            <label for="modalidad">Modalidad</label>
                            <select id="modalidad" name="modalidad" required>
                                <option value="">Selecciona una modalidad</option>
                                <option value="Presencial" {{ $modalidadSel === 'Presencial' ? 'selected' : '' }}>
                                    Presencial</option>
                                <option value="Virtual" {{ $modalidadSel === 'Virtual' ? 'selected' : '' }}>Virtual
                                </option>
                            </select>
                        </div>

                        <div>
                            @php $estatusSel = old('estatus', $taller->estatus); @endphp
                            <label for="estatus">Estatus</label>
                            {{-- Selector de estatus, preseleccionado con el valor actual. --}}
                            <select id="estatus" name="estatus" required>
                                <option value="">Selecciona un estatus</option>
                                <option value="Finalizada" {{ $estatusSel === 'Finalizada' ? 'selected' : '' }}>Finalizada
                                </option>
                                <option value="Convocatoria" {{ $estatusSel === 'Convocatoria' ? 'selected' : '' }}>
                                    Convocatoria</option>
                                <option value="En proceso" {{ $estatusSel === 'En proceso' ? 'selected' : '' }}>En proceso
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="capacidad">Capacidad</label>
                            {{-- Solo permite números enteros positivos --}}
                            <input id="capacidad" type="number" name="capacidad" min="1" step="1"
                                inputmode="numeric" pattern="[0-9]*" value="{{ old('capacidad', $taller->capacidad) }}"
                                placeholder="Capacidad" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,5);">
                        </div>

                        <div>
                            <label for="material">Material requerido</label>
                            <input id="material" name="material" value="{{ old('material', $taller->material) }}"
                                placeholder="Material requerido" maxlength="150" required>
                        </div>

                        <div>
                            <label for="url">URL (opcional)</label>
                            <input id="url" name="url" value="{{ old('url', $taller->url) }}"
                                placeholder="URL (opcional)" maxlength="200">
                        </div>

                        <div>
                            <label for="descripcion">Descripción</label>
                            {{-- Cambiado a textarea para consistencia y mejor usabilidad --}}
                            <textarea id="descripcion" name="descripcion" rows="3" placeholder="Descripción (máx. 200)" maxlength="200"
                                required>{{ old('descripcion', $taller->descripcion) }}</textarea>
                        </div>
                    </div>

                    <div class="actions">
                        <a href="{{ route('extracurricular.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
