@extends('layouts.encabezadosAl')

@section('title', 'Ficha médica')
@push('styles')
    @vite('resources/css/ficha-medica.css')
@endpush

@section('content')
<div class="crud-wrap">
  <section class="crud-card">
    <header class="crud-hero">
      <h2 class="crud-hero-title">Mi ficha médica</h2>
      <p class="crud-hero-subtitle">Resumen</p>
      <nav class="crud-tabs">
        <a href="{{ route('mi_ficha.show') }}" class="tab active">Ver</a>
        <a href="{{ route('mi_ficha.edit') }}" class="tab">Editar</a>
      </nav>
    </header>

    <div class="crud-body medical-record">

      @if (session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
      @endif

      {{-- Bloque de información del alumno: muestra nombre, matrícula y grupo. --}}
      <div class="student-info-header">
        <div class="student-avatar">
          <span>{{ substr($ficha->alumno?->nombre, 0, 1) }}{{ substr($ficha->alumno?->apellidoP, 0, 1) }}</span>
        </div>
        <div class="student-details">
          <h3 class="student-name">{{ $ficha->alumno?->nombre }} {{ $ficha->alumno?->apellidoP }} {{ $ficha->alumno?->apellidoM }}</h3>
          <div class="student-meta">
            @isset($ficha->alumno?->matriculaA)
              <span>Matrícula: <strong>{{ $ficha->alumno?->matriculaA }}</strong></span>
            @endisset
            @isset($ficha->alumno?->grupo)
              <span>Grupo: <strong>{{ $ficha->alumno?->grupo }}</strong></span>
            @endisset
          </div>
        </div>
      </div>

      <div class="medical-sections-container">
        
        {{-- Bloque de alergias: lista las condiciones booleanas y de detalle. --}}
        <div class="medical-section">
          <h4 class="section-title">Alergias</h4>
          <div class="info-grid">
            <div class="info-item">
              <span class="info-label">Polvo</span>
              <span class="info-value {{ $ficha->alergias?->polvo ? 'status-yes' : 'status-no' }}">{{ $ficha->alergias?->polvo ? 'Sí' : 'No' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Polen</span>
              <span class="info-value {{ $ficha->alergias?->polen ? 'status-yes' : 'status-no' }}">{{ $ficha->alergias?->polen ? 'Sí' : 'No' }}</span>
            </div>
            
            @php
                $alergiasConDetalle = [
                    'alimentos' => 'Alimentos',
                    'animales' => 'Animales',
                    'insectos' => 'Insectos',
                    'medicamentos' => 'Medicamentos',
                    'otro' => 'Otro tipo'
                ];
            @endphp

            {{-- Bucle para alergias con detalle (alimentos, medicamentos, etc.). --}}
            @foreach ($alergiasConDetalle as $key => $label)
              <div class="info-item-full">
                <div class="info-item-header">
                  <span class="info-label">{{ $label }}</span>
                  <span class="info-value {{ $ficha->alergias?->{$key} ? 'status-yes' : 'status-no' }}">{{ $ficha->alergias?->{$key} ? 'Sí' : 'No' }}</span>
                </div>
                @if($ficha->alergias?->{$key.'_detalle'})
                  <div class="info-detail">{{ $ficha->alergias?->{$key.'_detalle'} }}</div>
                @endif
              </div>
            @endforeach
          </div>
        </div>

        {{-- Bloque de enfermedades y condiciones médicas. --}}
        <div class="medical-section">
          <h4 class="section-title">Enfermedades y Condiciones Médicas</h4>
          <div class="info-grid">
            <div class="info-item-full">
              <div class="info-item-header"><span class="info-label">Enfermedad Crónica</span><span class="info-value {{ $ficha->enfermedades?->enfermedad_cronica ? 'status-yes' : 'status-no' }}">{{ $ficha->enfermedades?->enfermedad_cronica ? 'Sí' : 'No' }}</span></div>
              @if($ficha->enfermedades?->enfermedad_cronica_detalle)<div class="info-detail">{{ $ficha->enfermedades?->enfermedad_cronica_detalle }}</div>@endif
            </div>

            <div class="info-item-full">
              <div class="info-item-header"><span class="info-label">Toma Medicamentos</span><span class="info-value {{ $ficha->enfermedades?->toma_medicamentos ? 'status-yes' : 'status-no' }}">{{ $ficha->enfermedades?->toma_medicamentos ? 'Sí' : 'No' }}</span></div>
              @if($ficha->enfermedades?->toma_medicamentos_detalle)<div class="info-detail">{{ $ficha->enfermedades?->toma_medicamentos_detalle }}</div>@endif
            </div>

            <div class="info-item-full">
              <div class="info-item-header"><span class="info-label">Visita al Médico Regularmente</span><span class="info-value {{ $ficha->enfermedades?->visita_medico ? 'status-yes' : 'status-no' }}">{{ $ficha->enfermedades?->visita_medico ? 'Sí' : 'No' }}</span></div>
              @if($ficha->enfermedades?->visita_medico_detalle)<div class="info-detail">{{ $ficha->enfermedades?->visita_medico_detalle }}</div>@endif
            </div>
            
            {{-- Datos del médico de contacto. --}}
            <div class="info-item"><span class="info-label">Nombre del Médico</span><span class="info-value">{{ $ficha->enfermedades?->nombre_medico ?? '—' }}</span></div>
            <div class="info-item"><span class="info-label">Teléfono del Médico</span><span class="info-value">{{ $ficha->enfermedades?->telefono_medico ?? '—' }}</span></div>
          </div>
        </div>

        {{-- Bloque de contacto de emergencia. --}}
        <div class="medical-section">
          <h4 class="section-title">Contacto de Emergencia</h4>
          <div class="info-grid">
            <div class="info-item"><span class="info-label">Nombre Completo</span><span class="info-value">{{ $ficha->contacto?->nombre ?? '—' }} {{ $ficha->contacto?->apellidos ?? '' }}</span></div>
            <div class="info-item"><span class="info-label">Parentesco</span><span class="info-value">{{ $ficha->contacto?->parentesco ?? '—' }}</span></div>
            <div class="info-item"><span class="info-label">Teléfono</span><span class="info-value">{{ $ficha->contacto?->telefono ?? '—' }}</span></div>
            <div class="info-item"><span class="info-label">Institución (Trabajo)</span><span class="info-value">{{ $ficha->contacto?->institucion ?? '—' }}</span></div>
            <div class="info-item-full"><span class="info-label">Domicilio: </span><span class="info-value">{{ $ficha->contacto?->domicilio ?? '—' }}</span></div>
          </div>
        </div>
      </div>

      {{-- Bloque de acciones: enlace al formulario de edición de la ficha médica. --}}
      <div class="crud-actions">
        <a class="btn btn-primary" href="{{ route('mi_ficha.edit') }}">Editar</a>
      </div>
    </div>
  </section>
</div>
@endsection
@section('scripts')
    @vite(['resources/js/fichaValidacion.js'])
@endsection