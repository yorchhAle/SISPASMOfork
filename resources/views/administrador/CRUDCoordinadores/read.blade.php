@extends('layouts.encabezados')
@section('title', 'Gestión coordinadores')

@section('content')
  <div class="crud-wrap">
    <section class="crud-card">
      <div class="crud-hero">
        <h2 class="crud-hero-title">Gestión de coordinadores</h2>
        <p class="crud-hero-subtitle">Listado</p>

        <nav class="crud-tabs">
          <a href="{{ route('coordinadores.create') }}" class="tab">Registrar</a>
          <a href="{{ route('coordinadores.index') }}" class="tab active">Listar coordinadores</a>
        </nav>
      </div>
      
      <div class="crud-body">
        <h1>Coordinadores</h1>

        @if (session('success'))
        
        <div class="gm-ok">{{ session('success') }}</div>
        @endif
        @if (session('ok'))
        
        <div class="gm-ok">{{ session('ok') }}</div>
        @endif
        @if ($coordinadores->count() === 0)
        
        <div class="gm-empty">No hay coordinadores registrados.</div>
        @else
        <div class="table-responsive">
          <table class="gm-table">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Fecha ingreso</th>
                <th>Estatus</th>
                <th class="th-actions">Acciones</th>
              </tr>
            </thead>
            
            <tbody>
              @foreach ($coordinadores as $c)
              <tr>
                <td>
                  {{ optional($c->usuario)->nombre }}
                  {{ optional($c->usuario)->apellidoP }}
                  {{ optional($c->usuario)->apellidoM }}
                </td>
                
                <td>{{ optional($c->usuario)->usuario }}</td>
                  <td>{{ optional($c->usuario)->correo }}</td>
                  <td>{{ optional($c->usuario)->telefono }}</td>
                  <td>{{ $c->fecha_ingreso }}</td>
                  <td>{{ $c->estatus }}</td>
                  <td>
                    <div class="table-actions">
                      <a href="{{ route('coordinadores.edit', $c) }}" class="btn btn-ghost">Actualizar</a>
                        <form action="{{ route('coordinadores.destroy', ['coordinador' => $c->id_coordinador]) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn-danger" onclick="return confirm('¿Eliminar al coordinador {{ optional($c->usuario)->nombre }} {{ optional($c->usuario)->apellidoP }}?')"> Eliminar </button>
                        </form>
                    </div>
                  </td>
              </tr>
              @endforeach
            </tbody>
          </table>
      </div>

      <div class="pager">
        {{ $coordinadores->links() }}
      </div>
      
      @endif
    </div>
  </section>
</div>
@endsection