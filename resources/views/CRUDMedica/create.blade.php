@extends('layouts.encabezadosAl')
@section('title', 'Ficha médica')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Mi ficha médica</h2>
                <p class="crud-hero-subtitle">Registro</p>

                {{-- Navegación de pestañas, el link "registrar" está marcado como activo. --}}
                <nav class="crud-tabs">
                    <a href="{{ route('mi_ficha.create') }}" class="tab active">Registrar</a>
                    <a href="{{ route('mi_ficha.show') }}" class="tab">Ver mi ficha</a>
                </nav>
            </header>

            <div class="crud-body">
                <h1>Nueva ficha médica</h1>

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

                {{-- Formulario principal, envía los datos para crear la ficha médica (método post). --}}
                <form class="gm-form" method="POST" action="{{ route('mi_ficha.store') }}">
                    @csrf

                    <h3>Alergias (Marque las alergias que padece y detalle en los campos correspondientes)</h3> 
                    {{-- Bloque de alergias: campos booleanos y de detalle agrupados bajo `alergias[]`. --}}
                    <div>
                        <input type="hidden" name="alergias[polvo]" value="0">
                        <label class="chk">
                            <input type="checkbox" name="alergias[polvo]" value="1" {{ old('alergias.polvo') ? 'checked' : '' }}>
                            Polvo
                        </label>

                        <input type="hidden" name="alergias[polen]" value="0">
                        <label class="chk">
                            <input type="checkbox" name="alergias[polen]" value="1" {{ old('alergias.polen') ? 'checked' : '' }}>
                            Polen
                        </label>

                        <input type="hidden" name="alergias[alimentos]" value="0">
                        <label class="chk">
                            <input type="checkbox" name="alergias[alimentos]" value="1" {{ old('alergias.alimentos') ? 'checked' : '' }}>
                            Alimentos
                        </label>
                        <input name="alergias[alimentos_detalle]" value="{{ old('alergias.alimentos_detalle') }}" placeholder="Detalle alimentos" maxlength="255">

                        <input type="hidden" name="alergias[animales]" value="0">
                        <label class="chk">
                            <input type="checkbox" name="alergias[animales]" value="1" {{ old('alergias.animales') ? 'checked' : '' }}>
                            Animales
                        </label>
                        <input name="alergias[animales_detalle]" value="{{ old('alergias.animales_detalle') }}" placeholder="Detalle animales" maxlength="255">

                        <input type="hidden" name="alergias[insectos]" value="0">
                        <label class="chk">
                            <input type="checkbox" name="alergias[insectos]" value="1" {{ old('alergias.insectos') ? 'checked' : '' }}>
                            Insectos
                        </label>
                        <input name="alergias[insectos_detalle]" value="{{ old('alergias.insectos_detalle') }}" placeholder="Detalle insectos" maxlength="255">

                        <input type="hidden" name="alergias[medicamentos]" value="0">
                        <label class="chk">
                            <input type="checkbox" name="alergias[medicamentos]" value="1" {{ old('alergias.medicamentos') ? 'checked' : '' }}>
                            Medicamentos
                        </label>
                        <input name="alergias[medicamentos_detalle]" value="{{ old('alergias.medicamentos_detalle') }}" placeholder="Detalle medicamentos" maxlength="255">

                        <input type="hidden" name="alergias[otro]" value="0">
                        <label class="chk">
                            <input type="checkbox" name="alergias[otro]" value="1" {{ old('alergias.otro') ? 'checked' : '' }}>
                            Otro
                        </label>
                        <input name="alergias[otro_detalle]" value="{{ old('alergias.otro_detalle') }}" placeholder="Detalle otro" maxlength="255">
                    </div>

                    <h3>Enfermedades</h3>
                    {{-- Bloque de enfermedades: incluye preguntas booleanas sobre condiciones crónicas y medicación. --}}
                    <div>
                        <input type="hidden" name="enfermedades[enfermedad_cronica]" value="0">
                        <label class="chk">
                            <input type="checkbox" name="enfermedades[enfermedad_cronica]" value="1" {{ old('enfermedades.enfermedad_cronica') ? 'checked' : '' }}>
                            ¿Enfermedad crónica?
                        </label>
                        <input name="enfermedades[enfermedad_cronica_detalle]" value="{{ old('enfermedades.enfermedad_cronica_detalle') }}" placeholder="Detalle enfermedad crónica" maxlength="255">

                        <input type="hidden" name="enfermedades[toma_medicamentos]" value="0">
                        <label class="chk">
                            <input type="checkbox" name="enfermedades[toma_medicamentos]" value="1" {{ old('enfermedades.toma_medicamentos') ? 'checked' : '' }}>
                            ¿Toma medicamentos?
                        </label>
                        <input name="enfermedades[toma_medicamentos_detalle]" value="{{ old('enfermedades.toma_medicamentos_detalle') }}" placeholder="Detalle medicamentos que toma" maxlength="255">

                        <input type="hidden" name="enfermedades[visita_medico]" value="0">
                        <label class="chk">
                            <input type="checkbox" name="enfermedades[visita_medico]" value="1" {{ old('enfermedades.visita_medico') ? 'checked' : '' }}>
                            ¿Visita al médico?
                        </label>
                        <input name="enfermedades[visita_medico_detalle]" value="{{ old('enfermedades.visita_medico_detalle') }}" placeholder="Detalle de visitas al médico" maxlength="255">

                        <input name="enfermedades[nombre_medico]" value="{{ old('enfermedades.nombre_medico') }}" placeholder="Nombre del médico" maxlength="100">
                        <input name="enfermedades[telefono_medico]" value="{{ old('enfermedades.telefono_medico') }}" placeholder="Teléfono del médico" maxlength="20">
                    </div>

                    <h3>Contacto de emergencia</h3>
                    {{-- Bloque de contacto de emergencia: todos los campos de este bloque son requeridos por el script js (aunque no tienen required="true" en html). --}}
                    <div>
                        <input name="contacto[nombre]" value="{{ old('contacto.nombre') }}" placeholder="Nombre" maxlength="50">
                        <input name="contacto[apellidos]" value="{{ old('contacto.apellidos') }}" placeholder="Apellidos" maxlength="50">
                        <input name="contacto[domicilio]" value="{{ old('contacto.domicilio') }}" placeholder="Domicilio" maxlength="100">
                        <input name="contacto[telefono]" value="{{ old('contacto.telefono') }}" placeholder="Teléfono" maxlength="50">
                        <input name="contacto[parentesco]" value="{{ old('contacto.parentesco') }}" placeholder="Parentesco" maxlength="50">

                        @php $inst = old('contacto.institucion'); @endphp
                        <select name="contacto[institucion]" required>
                            <option value="">Institución</option>
                            <option value="IMSS"      {{ $inst==='IMSS' ? 'selected' : '' }}>IMSS</option>
                            <option value="Cruz Roja" {{ $inst==='Cruz Roja' ? 'selected' : '' }}>Cruz Roja</option>
                            <option value="Privado"   {{ $inst==='Privado' ? 'selected' : '' }}>Privado</option>
                        </select>
                    </div>

                    {{-- Bloque de acciones, botón de Guardar y Cancelar. --}}
                    <div class="actions">
                        <a href="{{ route('mi_ficha.show') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-ghosts">Guardar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    @vite(['resources/js/fichaValidacion.js'])
@endsection