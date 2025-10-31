@extends('layouts.encabezadosDoc')
@section('title', 'Mi información personal')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Mi información personal</h2>
                <p class="crud-hero-subtitle">Datos del docente</p>
            </header>

            <div class="crud-body">
                {{-- Bloque de datos: contenedor principal para mostrar la información del usuario/docente. --}}
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
                        <span class="info-label">Matrícula:</span>
                        <span class="info-value">{{ auth()->user()->docente->matriculaD ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Especialidad:</span>
                        <span class="info-value">{{ auth()->user()->docente->especialidad ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Cédula Profesional:</span>
                        <span class="info-value">{{ auth()->user()->docente->cedula ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Salario:</span>
                        <span class="info-value">${{ number_format(auth()->user()->docente->salario ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection