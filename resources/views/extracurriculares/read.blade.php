@extends('layouts.encabezadosAl')
@section('title', 'Actividades extracurriculares')

@section('content')
    {{-- Contenedor principal, usamos la nueva clase card-grid-view --}}
    <div class="crud-wrap card-grid-view">
        <section class="crud-card grid-container">
            <div class="crud-body">

                {{-- Bloque de mensajes, muestra mensajes de éxito o error después de intentar inscribir/cancelar. --}}
                @if (session('success'))
                    <div class="gm-ok">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="gm-errors">{{ session('error') }}</div>
                @endif

                <h1>Mis inscripciones</h1>
                
                {{-- Bloque 1, mis inscripciones (actividades ya inscritas). --}}
                <div class="activity-grid">
                    @forelse ($misInscripciones as $actividad)
                        <div class="activity-card">
                            
                            <div class="card-header-activity">
                                <h3>{{ $actividad->nombre_act }}</h3>
                                <span class="badge badge-tipo">{{ $actividad->tipo }}</span>
                            </div>
                            
                            <div class="card-body-activity">
                                <p>{{ $actividad->descripcion }}</p>
                                
                                <ul class="activity-meta">
                                    <li><strong>Fecha:</strong> {{ $actividad->fecha->format('d/m/Y') }}</li>
                                    <li><strong>Lugar:</strong> {{ $actividad->lugar }}</li>
                                    <li><strong>Modalidad:</strong> {{ $actividad->modalidad }}</li>
                                </ul>
                            </div>
                            
                            {{-- Formulario de cancelación de inscripción (método delete). --}}
                            <div class="card-footer-activity">
                                <form action="{{ route('extracurriculares.cancelar', $actividad->id_extracurricular) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger btn-action"
                                        onclick="return confirm('¿Estás seguro de que quieres cancelar tu inscripción?')">
                                        Cancelar inscripción
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="gm-empty">Aún no te has inscrito a ninguna actividad.</div>
                    @endforelse
                </div>

                <h1>Actividades extracurriculares disponibles</h1>
                {{-- Bloque 2, actividades disponibles para inscripción. --}}
                <div class="activity-grid">
                    @forelse ($extracurricularesDisponibles as $actividad)
                        <div class="activity-card">
                            
                            <div class="card-header-activity">
                                <h3>{{ $actividad->nombre_act }}</h3>
                                <span class="badge badge-tipo">{{ $actividad->tipo }}</span>
                            </div>
                            
                            <div class="card-body-activity">
                                <p>{{ $actividad->descripcion }}</p>
                                
                                <ul class="activity-meta">
                                    <li><strong>Fecha:</strong> {{ $actividad->fecha->format('d/m/Y') }}</li>
                                    <li><strong>Lugar:</strong> {{ $actividad->lugar }}</li>
                                    <li>
                                        {{-- Muestra los cupos disponibles en tiempo real (capacidad - alumnos inscritos). --}}
                                        <strong>Cupos disponibles:</strong>
                                        <span class="badge badge-cupos">{{ $actividad->capacidad - $actividad->alumnos()->count() }} / {{ $actividad->capacidad }}</span>
                                    </li>
                                </ul>
                            </div>
                            
                            {{-- Formulario de inscripción (método post). --}}
                            <div class="card-footer-activity">
                                <form action="{{ route('extracurriculares.inscribir', $actividad->id_extracurricular) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn-primary btn-action">Inscribirme</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="gm-empty">No hay nuevas actividades disponibles en este momento.</div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
@endsection