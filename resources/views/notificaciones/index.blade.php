@extends($layout)

@section('content')
<div class="crud-wrap">
  <div class="crud-card">
    <div class="crud-hero">
      <h2 class="crud-hero-title">Centro de notificaciones</h2>
      <p class="crud-hero-subtitle">Tus avisos del sistema</p>

      {{-- Navegación de pestañas, permite filtrar la vista por estatus de lectura ('all', 'unread', 'read'). --}}
      <nav class="crud-tabs" style="margin-top:10px">
        <a href="{{ route('notificaciones.index', ['estado' => 'all']) }}"
           class="tab {{ ($estado ?? 'all')==='all' ? 'active' : '' }}">Todas</a>
        <a href="{{ route('notificaciones.index', ['estado' => 'unread']) }}"
           class="tab {{ ($estado ?? '')==='unread' ? 'active' : '' }}">No leídas</a>
        <a href="{{ route('notificaciones.index', ['estado' => 'read']) }}"
           class="tab {{ ($estado ?? '')==='read' ? 'active' : '' }}">Leídas</a>
      </nav>
    </div>

    <div class="crud-body">
      @if($notifications->isEmpty())
        <div class="gm-empty">No tienes notificaciones por el momento.</div>
      @else
        <div style="display:grid;gap:12px">
          {{-- Bloque de datos (bucle), itera sobre la colección paginada de notificaciones ($notifications). --}}
          @foreach($notifications as $notification)
            @php
              $data = $notification->data ?? [];
              $tipo = $data['tipo'] ?? 'general';
              $titulo = $data['titulo'] ?? $data['observaciones'] ?? 'Notificación';
              $mensaje = $data['mensaje'] ?? null;
              $url = $data['url'] ?? null;
            @endphp

            <article style="border:1px solid rgba(0,0,0,.06);border-radius:16px;padding:14px 16px">
              <header style="display:flex;justify-content:space-between;align-items:start;gap:12px">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                {{-- Bloque condicional para mostrar un badge visual según el campo 'tipo' dentro del payload de la notificación. --}}
                  @if($tipo === 'adeudo_vencido')
                    <span class="badge badge-rechazado">Vencido</span>
                  @elseif($tipo === 'adeudo_por_vencer')
                    <span class="badge badge-pendiente">Adeudo</span>
                  @elseif($tipo === 'horario')
                    <span class="badge badge-validado">Horario</span>
                  @elseif($tipo === 'taller')
                    <span class="badge badge-validado" style="background:#e0e7ff;color:#3730a3;border:1px solid #c7d2fe">Taller</span>
                  @else
                    <span class="badge" style="background:#f8fafc;color:#334155;border:1px solid #e2e8f0">General</span>
                  @endif

                  <h3 style="margin:0;font-size:1rem;font-weight:800;color:var(--color-principal)">
                    {{ $titulo }}
                  </h3>
                </div>
                <small style="opacity:.8">{{ $notification->created_at->diffForHumans() }}</small>
              </header>

              @if($mensaje)
                <p style="margin:.25rem 0">{{ $mensaje }}</p>
              @endif

              <div style="display:flex;gap:10px;align-items:center;margin-top:10px">
                {{-- Botón condicional para ver detalles si existe una url asociada al payload. --}}
                @if($url)
                  <a href="{{ $url }}" class="btn btn-ghost" target="_blank">Ver detalles</a>
                @endif

                {{-- Formulario para marcar como leída (solo visible si no está leída). --}}
                @if(!$notification->read_at)
                  <form action="{{ route('notificaciones.markOne', $notification->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-primary">Marcar como leída</button>
                  </form>
                @else
                  <span class="btn btn-primary">Leída</span>
                @endif

                {{-- Formulario para eliminar la notificación. --}}
                <form action="{{ route('notificaciones.destroy', $notification->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar notificación?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger">Eliminar</button>
                </form>
              </div>
            </article>
          @endforeach
        </div>

        {{-- Bloque de paginación. --}}
        <div class="pager">
          {{ $notifications->links() }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection