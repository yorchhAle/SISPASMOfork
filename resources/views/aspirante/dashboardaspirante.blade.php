@extends('layouts.encabezadosAs')
@section('title', 'Mi información personal')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Mi información personal</h2>
                <p class="crud-hero-subtitle">Datos del aspirante</p>
            </header>

            <div class="crud-body">
                {{-- Bloque de datos, contenedor principal para mostrar la información del usuario/aspirante. --}}
                <div class="info-ficha">
                    <div class="info-item">
                        <span class="info-label">Nombre(s):</span>
                        {{-- Acceso directo a las propiedades del usuario autenticado (`auth()->user()`). --}}
                        <span class="info-value">{{ auth()->user()->nombre ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Apellido Paterno:</span>
                        <span class="info-value">{{ auth()->user()->apellidoP ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Apellido Materno:</span>
                        <span class="info-value">{{ auth()->user()->apellidoM ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Edad:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse(auth()->user()->fecha_nac)->age ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Interés:</span>
                        {{-- Accede al interés a través del modelo aspirante --}}
                        <span class="info-value">{{ auth()->user()->aspirante->interes ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Estatus:</span>
                        {{-- Accede al estatus a través del modelo aspirante --}}
                        <span class="info-value">{{ auth()->user()->aspirante->estatus ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection