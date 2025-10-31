@extends('layouts.encabezados')

@section('content')
<div class="crud-wrap">
    <div class="crud-card">
        <div class="crud-hero">
            <h2 class="crud-hero-title">Gestión de horarios</h2>
            <p class="crud-hero-subtitle">Actualización</p>
            
            <nav class="crud-tabs">
                <a href="{{ route('admin.horarios.create') }}" class="tab">Registrar</a>
                <a href="{{ route('admin.horarios.index') }}" class="tab active">Listar horarios</a>
            </nav>
        </div>
        <div class="crud-body">
            {{-- Bloque de errores, muestra los errores de validación de laravel (si los hay). --}}
            @if ($errors->any())
                <div class="gm-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulario principal de actualización, utiliza el método put para enviar los datos a la ruta update. --}}
            <form action="{{ route('admin.horarios.update', $horario->id_horario) }}" method="POST" class="gm-form">
                @csrf
                @method('PUT')
                
                {{-- Bloque de datos, contiene todos los campos de asignación y temporalidad del horario. --}}
                <div class="form-section">
                    <div class="form-group">
                        <label for="id_diplomado">Diplomado</label>
                        <select name="id_diplomado" id="id_diplomado" required>
                            @foreach($diplomados as $diplomado)
                                <option value="{{ $diplomado->id_diplomado }}" {{ (old('id_diplomado', $horario->id_diplomado) == $diplomado->id_diplomado) ? 'selected' : '' }}>
                                    {{ $diplomado->nombre }} (Grupo: {{ $diplomado->grupo }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Select de módulo, preseleccionado con el valor actual. --}}
                    <div class="form-group">
                        <label for="id_modulo">Módulo</label>
                        <select name="id_modulo" id="id_modulo" required>
                            @foreach($modulos as $modulo)
                                <option value="{{ $modulo->id_modulo }}" {{ (old('id_modulo', $horario->id_modulo) == $modulo->id_modulo) ? 'selected' : '' }}>
                                    {{ $modulo->nombre_modulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Select de docente, preseleccionado con el valor actual. --}}
                    <div class="form-group">
                        <label for="id_docente">Docente</label>
                        <select name="id_docente" id="id_docente" required>
                            <option value="">Selecciona un docente</option>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->id_docente }}" {{ old('id_docente', $horario->id_docente) == $docente->id_docente ? 'selected' : '' }}>
                                    {{ $docente->usuario->nombre }} {{ $docente->usuario->apellidoP }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" name="fecha" id="fecha" 
                               value="{{ old('fecha', \Carbon\Carbon::parse($horario->fecha)->format('Y-m-d')) }}" 
                               min="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="hora_inicio">Hora de inicio</label>
                        {{-- Se formatea la hora a 'H:i' (Horas:minutos) --}}
                        <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio', \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i')) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="hora_fin">Hora de fin</label>
                        {{-- Se formatea la hora a 'H:i' (Horas:minutos) --}}
                        <input type="time" name="hora_fin" id="hora_fin" value="{{ old('hora_fin', \Carbon\Carbon::parse($horario->hora_fin)->format('H:i')) }}" required>
                    </div>
                    
                    {{-- Select de modalidad, preseleccionado con el valor actual. --}}
                    <div class="form-group">
                        <label for="modalidad">Modalidad</label>
                        <select name="modalidad" id="modalidad" required>
                            <option value="Presencial" {{ (old('modalidad', $horario->modalidad) == 'Presencial') ? 'selected' : '' }}>Presencial</option>
                            <option value="Virtual" {{ (old('modalidad', $horario->modalidad) == 'Virtual') ? 'selected' : '' }}>Virtual</option>
                            <option value="Práctica" {{ (old('modalidad', $horario->modalidad) == 'Práctica') ? 'selected' : '' }}>Práctica</option>
                        </select>
                    </div>

                    {{-- Campo de aula/ubicación, preseleccionado con el valor actual. --}}
                    <div class="form-group">
                        <label for="aula">Aula</label>
                        <input type="text" name="aula" id="aula" maxlength="20" value="{{ old('aula', $horario->aula) }}" required>
                    </div>
                </div>

                {{-- Bloque de acciones, botón de Actualizar y Cancelar. --}}
                <div class="actions">
                    <button type="submit" class="btn btn-primary">Actualizar horario</button>
                    <a href="{{ route('admin.horarios.index') }}" class="btn btn-danger">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection