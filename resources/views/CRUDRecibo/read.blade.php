@extends('layouts.encabezadosAl')
@section('title', 'Recibos')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de recibos</h2>
                <p class="crud-hero-subtitle">Listado (alumno)</p>

                <nav class="crud-tabs">
                    <a href="{{ route('recibos.create') }}" class="tab">Registrar</a>
                    <a href="{{ route('recibos.index') }}" class="tab active">Listar recibos</a>
                </nav>
            </header>

            <div class="crud-body">
                @if (session('ok'))
                    <div class="gm-ok">{{ session('ok') }}</div>
                @endif

                {{-- Formulario de filtro, permite buscar por concepto y filtrar por estatus. --}}
                <form method="GET" class="filter-form">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por concepto">
                    <select name="estatus">
                        @php $e = request('estatus'); @endphp
                        <option value=""Estatus</option>
                        <option value="pendiente" {{ $e==='pendiente'?'selected':'' }}>Pendiente</option>
                        <option value="validado"  {{ $e==='validado'?'selected':'' }}>Validado</option>
                        <option value="rechazado" {{ $e==='rechazado'?'selected':'' }}>Rechazado</option>
                    </select>
                    <button class="btn">Filtrar</button>
                    {{-- Botón para limpiar filtros, visible solo si hay filtros aplicados. --}}
                    @if(request()->hasAny(['q','estatus']))
                        <a class="btn btn-ghost" href="{{ route('recibos.index') }}">Limpiar</a>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="gm-table">
                        <thead>
                            <tr>
                                <th>Fecha pago</th>
                                <th>Concepto</th>
                                <th>Monto</th>
                                <th>Estatus</th>
                                <th>Comprobante</th>
                                <th>PDF</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            {{-- Bloque de datos (bucle), itera sobre la colección paginada de recibos ($recibos). --}}
                            @forelse($recibos as $r)
                                <tr>
                                    <td>{{ optional($r->fecha_pago)->format('Y-m-d') }}</td>
                                    <td>{{ $r->concepto }}</td>
                                    <td>${{ number_format($r->monto, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $r->estatus }}">
                                            {{ ucfirst($r->estatus) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- Enlace para ver el comprobante subido por el alumno. --}}
                                        @if($r->comprobante_path)
                                            <a class="btn btn-ghost" target="_blank" href="{{ Storage::disk('public')->url($r->comprobante_path) }}">Ver</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Enlace para descargar el pdf oficial (solo si el estatus es 'validado'). --}}
                                        @if($r->estatus === 'validado' && $r->pdf_path)
                                            <a class="btn btn-ghost" target="_blank" href="{{ Storage::disk('public')->url($r->pdf_path) }}">Descargar</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">No hay recibos registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Bloque de paginación. --}}
                <div class="pagination-wrap">
                    {{ $recibos->links() }}
                </div>
            </div>
        </section>
    </div>
@endsection