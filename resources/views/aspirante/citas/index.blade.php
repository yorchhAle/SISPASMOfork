@extends('layouts.encabezadosAs')
@section('title', 'Citas')

@section('content')
  <div class="crud-wrap">
    <div class="crud-card">
      <div class="crud-hero">
        <h1 class="crud-hero-title">Mis citas</h1>
        <div class="crud-tabs">
          <a class="tab active" href="{{ route('aspirante.citas.index') }}">Listado</a>
          <a class="tab" href="{{ route('aspirante.citas.create') }}">Agendar</a>
        </div>
      </div>

      <div class="crud-body">
        {{-- Bloque de mensajes, muestra mensajes de éxito (`ok`) y errores de validación. --}}
        @if(session('ok')) <div class="gm-ok">{{ session('ok') }}</div> @endif
        @if($errors->any())
          <div class="gm-errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="table-responsive">
          <table class="gm-table">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estatus</th>
                <th>Lugar</th>
                <th class="th-actions">Acciones</th>
              </tr>
            </thead>
            <tbody>
              {{-- Bloque de datos (bucle): itera sobre la colección paginada de citas ($citas). --}}
              @forelse($citas as $c)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($c->fecha_cita)->format('d/m/Y') }}</td>
                  <td>{{ \Carbon\Carbon::parse($c->hora_cita)->format('H:i') }}</td>
                  <td>{{ $c->estatus }}</td>
                  <td>{{ $c->lugar }}</td>
                  <td>
                    <div class="table-actions">
                    {{-- Bloque condicional de cancelación, solo muestra el formulario si el estatus es 'pendiente' o 'aprobada'. --}}
                      @if(in_array($c->estatus,['Pendiente','Aprobada']))
                        {{-- Formulario de cancelación, utiliza el método delete para cambiar el estatus a 'cancelada'. --}}
                        <form method="POST" action="{{ route('aspirante.citas.cancel',$c) }}">
                          @csrf @method('DELETE')
                          <button class="btn-danger" onclick="return confirm('¿Cancelar la cita?')">Cancelar</button>
                        </form>
                      @else
                        <span class="btn-ghost">—</span>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5">Sin citas registradas.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        {{-- Bloque de paginación, muestra los enlaces de paginación de laravel. --}}
        <div class="pager">{{ $citas->links() }}</div>
      </div>
    </div>
  </div>
@endsection