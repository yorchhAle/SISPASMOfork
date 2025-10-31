@extends('layouts.encabezadosAl')
@section('title', 'Mi información personal')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Mi información personal</h2>
                <p class="crud-hero-subtitle">Datos del alumno</p>
            </header>

            <div class="crud-body">
                {{-- Bloque de datos: contenedor principal para mostrar la información del usuario/alumno. --}}
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
                        {{-- Cálculo de la edad utilizando carbon a partir de la fecha de nacimiento. --}}
                        <span class="info-value">{{ \Carbon\Carbon::parse(auth()->user()->fecha_nac)->age ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Diplomado:</span>

                        {{-- Acceso a la relación anidada, usuario -> alumno -> diplomado -> nombre. --}}
                        <span class="info-value">{{ auth()->user()->alumno->diplomado->nombre ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Estatus:</span>
                        {{-- Acceso a la relación, usuario -> alumno -> estatus. --}}
                        <span class="info-value">{{ auth()->user()->alumno->estatus ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection