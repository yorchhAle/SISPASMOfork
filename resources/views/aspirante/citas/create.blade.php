@extends('layouts.encabezadosAs')
@section('title', 'Citas')

@section('content')
  <div class="crud-wrap">
    <div class="crud-card">
      <div class="crud-hero">
        <h1 class="crud-hero-title">Citas</h1>
        <div class="crud-tabs">
          <a class="tab" href="{{ route('aspirante.citas.index') }}">Listado</a>
          <a class="tab active" href="{{ route('aspirante.citas.create') }}">Agendar</a>
        </div>
      </div>

      <div class="crud-body">
        {{-- Bloque de mensajes, muestra mensajes de éxito (`ok`) y errores de validación. --}}
        @if(session('ok')) <div class="gm-ok">{{ session('ok') }}</div> @endif
        @if($errors->any())
          <div class="gm-errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        {{-- Formulario principal: envía los datos de la nueva cita para su registro. --}}
        <form class="gm-form" method="POST" action="{{ route('aspirante.citas.store') }}">
          @csrf

          <h3>Datos de la cita</h3>
          <div>
            <div>
              <label>Fecha</label>
              {{-- El campo de fecha utiliza 'min' para evitar seleccionar fechas pasadas. --}}
              <input type="date" name="fecha_cita" value="{{ old('fecha_cita') }}" min="{{ now()->toDateString() }}" required>
            </div>

            <div>
              <label>Hora</label>
              <input type="time" name="hora_cita" value="{{ old('hora_cita') }}" required>
            </div>

            <div>
              <label>Lugar</label>
              {{-- Campo de lugar, tiene un valor fijo y está deshabilitado. --}}
              <input type="text" value="Facultad de Medicina de la UAEM" disabled>
            </div>
          </div>

          {{-- Bloque de acciones, botón de Guardar y Cancelar. --}}
          <div class="actions">
            <a class="btn btn-danger" href="{{ route('aspirante.citas.index') }}">Cancelar</a>
            <button class="btn btn-primary" type="submit">Guardar</button>
          </div>
        </form>
      </div>
    </div> 
  </div>  
@endsection