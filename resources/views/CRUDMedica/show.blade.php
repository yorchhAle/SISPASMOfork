@extends('layouts.encabezados')
@section('title', 'Gestión fichas médicas')

@push('styles')
    @vite('resources/css/ficha-medica.css')
@endpush

@section('content')
    <div class="crud-wrap">
      <section class="crud-card">
        <header class="crud-hero">
          <h2 class="crud-hero-title">Gestión de fichas médicas</h2>
          <p class="crud-hero-subtitle">Detalle</p>

          <nav class="crud-tabs">
            <a href="{{ route('fichasmedicas.index') }}" class="tab">Listar fichas</a>
            <a href="#" class="tab active">Detalle</a>
          </nav>
        </header>

        <div class="crud-body">
          @if (session('ok'))
            <div class="gm-ok">{{ session('ok') }}</div>
          @endif

          <h1>Ficha #{{ $ficha->id_ficha }}</h1>

          {{-- Bloque de datos del alumno asociado a la ficha. --}}
          <h3>Alumno</h3>
          <div class="gm-kv">
            <div><span>Nombre:</span> {{ $ficha->alumno?->nombre }} {{ $ficha->alumno?->apellidoP }} {{ $ficha->alumno?->apellidoM }}</div>
            @isset($ficha->alumno?->matriculaA)
              <div><span>Matrícula:</span> {{ $ficha->alumno?->matriculaA }}</div>
            @endisset
            @isset($ficha->alumno?->grupo)
              <div><span>Grupo:</span> {{ $ficha->alumno?->grupo }}</div>
            @endisset
          </div>

          <hr class="gm-sep">

          {{-- Bloque de alergias, muestra todas las condiciones booleanas y sus detalles. --}}
          <h3>Alergias</h3>
          <div class="gm-grid-2">
            <div><span>Polvo:</span> {{ $ficha->alergias?->polvo ? 'Sí' : 'No' }}</div>
            <div><span>Polen:</span> {{ $ficha->alergias?->polen ? 'Sí' : 'No' }}</div>

            <div><span>Alimentos:</span> {{ $ficha->alergias?->alimentos ? 'Sí' : 'No' }}</div>
            <div><span>Detalle alimentos:</span> {{ $ficha->alergias?->alimentos_detalle ?? '—' }}</div>

            <div><span>Animales:</span> {{ $ficha->alergias?->animales ? 'Sí' : 'No' }}</div>
            <div><span>Detalle animales:</span> {{ $ficha->alergias?->animales_detalle ?? '—' }}</div>

            <div><span>Insectos:</span> {{ $ficha->alergias?->insectos ? 'Sí' : 'No' }}</div>
            <div><span>Detalle insectos:</span> {{ $ficha->alergias?->insectos_detalle ?? '—' }}</div>

            <div><span>Medicamentos:</span> {{ $ficha->alergias?->medicamentos ? 'Sí' : 'No' }}</div>
            <div><span>Detalle medicamentos:</span> {{ $ficha->alergias?->medicamentos_detalle ?? '—' }}</div>

            <div><span>Otro:</span> {{ $ficha->alergias?->otro ? 'Sí' : 'No' }}</div>
            <div><span>Detalle otro:</span> {{ $ficha->alergias?->otro_detalle ?? '—' }}</div>
          </div>

          <hr class="gm-sep">

          {{-- Bloque de enfermedades, muestra condiciones crónicas y medicación. --}}
          <h3>Enfermedades</h3>
          <div class="gm-grid-2">
            <div><span>Enfermedad crónica:</span> {{ $ficha->enfermedades?->enfermedad_cronica ? 'Sí' : 'No' }}</div>
            <div><span>Detalle crónica:</span> {{ $ficha->enfermedades?->enfermedad_cronica_detalle ?? '—' }}</div>

            <div><span>Toma medicamentos:</span> {{ $ficha->enfermedades?->toma_medicamentos ? 'Sí' : 'No' }}</div>
            <div><span>Detalle medicamentos:</span> {{ $ficha->enfermedades?->toma_medicamentos_detalle ?? '—' }}</div>

            <div><span>Visita al médico:</span> {{ $ficha->enfermedades?->visita_medico ? 'Sí' : 'No' }}</div>
            <div><span>Detalle visitas:</span> {{ $ficha->enfermedades?->visita_medico_detalle ?? '—' }}</div>

            <div><span>Nombre del médico:</span> {{ $ficha->enfermedades?->nombre_medico ?? '—' }}</div>
            <div><span>Teléfono del médico:</span> {{ $ficha->enfermedades?->telefono_medico ?? '—' }}</div>
          </div>

          <hr class="gm-sep">

          {{-- Bloque de contacto de emergencia: muestra los datos de la persona a contactar. --}}
          <h3>Contacto de emergencia</h3>
          <div class="gm-grid-2">
            <div><span>Nombre:</span> {{ $ficha->contacto?->nombre ?? '—' }} {{ $ficha->contacto?->apellidos ?? '' }}</div>
            <div><span>Parentesco:</span> {{ $ficha->contacto?->parentesco ?? '—' }}</div>

            <div><span>Teléfono:</span> {{ $ficha->contacto?->telefono ?? '—' }}</div>
            <div><span>Domicilio:</span> {{ $ficha->contacto?->domicilio ?? '—' }}</div>

            <div><span>Institución:</span> {{ $ficha->contacto?->institucion ?? '—' }}</div>
          </div>

          <div class="actions">
            {{-- Botón de acción: enlace para volver al listado. --}}
            <a href="{{ route('fichasmedicas.index') }}" class="btn btn-ghost">Volver</a>

            {{-- Formulario de eliminación de la ficha médica completa. --}}
            <form method="POST" action="{{ route('fichasmedicas.destroy', $ficha) }}" style="display:inline">
              @csrf @method('DELETE')
              <button class="btn btn-danger"
                onclick="return confirm('¿Eliminar esta ficha? Esta acción no se puede deshacer.')">
                Eliminar
              </button>
            </form>
          </div>
        </div>
      </section>
    </div>
@endsection