@extends('layouts.encabezados')
@section('title', 'Gestión queja/sugerencia')

@section('content')
    <div class="crud-wrap">
        <div class="crud-card">
            <div class="crud-hero">
                <h1 class="crud-hero-title">Estatus de #{{ $queja->id_queja }}</h1>
            </div>

            <div class="crud-body">
                {{-- Bloque de errores, muestra los errores de validación de laravel (si los hay). --}}
                @if ($errors->any())
                    <div class="gm-errors">
                        <ul>
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Formulario principal de actualización, utiliza el método put para enviar la actualización de estatus. --}}
                <form class="gm-form" method="POST" action="{{ route('quejas.update', $queja) }}">
                    @csrf @method('PUT')

                    <div>
                        {{-- Bloque de datos: muestra el mensaje original de la queja/sugerencia (solo lectura). --}}
                        <div style="grid-column:1 / -1">
                            <label><strong>Mensaje</strong></label>
                            <div class="gm-empty">{{ $queja->mensaje }}</div>
                        </div>

                        {{-- Campo de estatus, permite cambiar entre 'pendiente' y 'atendido'. --}}
                        <div>
                            <label for="Estatus"><strong>Estatus</strong></label>
                            <select id="estatus" name="estatus" required>
                                <option value="Pendiente" @selected($queja->estatus === 'Pendiente')>Pendiente</option>
                                <option value="Atendido"  @selected($queja->estatus === 'Atendido')>Atendido</option>
                            </select>
                        </div>

                        {{-- Campo de contacto, permite editar la información de contacto proporcionada originalmente. --}}
                        <div>
                            <label for="contacto"><strong>Contacto (opcional)</strong></label>
                            <input id="contacto" name="contacto" type="text" value="{{ old('contacto', $queja->contacto) }}">
                        </div>
                    </div>

                    {{-- Bloque de acciones, botón de Guardar y Cancelar. --}}
                    <div class="actions">
                        <a class="btn btn-danger" href="{{ route('quejas.index') }}">Cancelar</a>
                        <button class="btn btn-primary" type="submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection