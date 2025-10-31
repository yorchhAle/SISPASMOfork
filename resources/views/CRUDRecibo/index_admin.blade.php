@extends('layouts.encabezados')
@section('title', 'Gestión recibos')

@section('content')
    <div class="crud-wrap">
        <section class="crud-card">
            <header class="crud-hero">
                <h2 class="crud-hero-title">Gestión de recibos</h2>
                <p class="crud-hero-subtitle">Listado (administración)</p>

                <nav class="crud-tabs">
                    <a href="{{ route('recibos.admin.index') }}" class="tab active">Listar recibos</a>
                </nav>

                {{-- Formulario de filtro, permite buscar por concepto/alumno y filtrar por estatus. --}}
                <form method="GET" class="filter-form">
                    <div class="filter-group">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Concepto o Alumno" class="filter-input">
                        @php $e = request('estatus'); @endphp
                        <select name="estatus" class="filter-select">
                            <option value="">Estatus</option>
                            <option value="pendiente" {{ $e==='pendiente'?'selected':'' }}>Pendiente</option>
                            <option value="validado"  {{ $e==='validado'?'selected':'' }}>Validado</option>
                            <option value="rechazado" {{ $e==='rechazado'?'selected':'' }}>Rechazado</option>
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button class="submit-button" type="submit">Filtrar</button>
                        {{-- Botón para limpiar filtros, visible solo si hay filtros aplicados. --}}
                        @if(request()->hasAny(['q','estatus','f1','f2']))
                            <a class="ghost-button" href="{{ route('recibos.admin.index') }}">Limpiar</a>
                        @endif
                    </div>
                </form>
            </header>

            <div class="crud-body">
                @if (session('ok'))
                    <div class="gm-ok">{{ session('ok') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="gm-table">
                        <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Matricula</th>
                            <th>Fecha pago</th>
                            <th>Concepto</th>
                            <th>Monto</th>
                            <th>Estatus</th>
                            <th>Validado por</th>
                            <th>Comprobante</th>
                            <th>Acciones</th>
                            <th>Recibo</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recibos as $r)
                            <tr>
                                <td>{{ $r->id_recibo }}</td>
                                <td>{{ $r->alumno->matriculaA ?? '—' }}</td>
                                <td>{{ optional($r->fecha_pago)->format('Y-m-d') }}</td>
                                <td>{{ $r->concepto }}</td>
                                <td>${{ number_format($r->monto, 2) }}</td>
                                {{-- Badge visual que refleja el estatus del recibo. --}}
                                <td>
                                    <span class="badge badge-{{ $r->estatus }}">{{ ucfirst($r->estatus) }}</span>
                                </td>
                                <td>
                                    {{-- Muestra el nombre del validador y la fecha de validación si aplica. --}}
                                    @if($r->validado_por)
                                        {{ $r->validador->nombre ?? '—' }}
                                        <small class="muted" style="display:block">
                                            {{ optional($r->fecha_validacion)->format('Y-m-d H:i') }}
                                        </small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    {{-- Enlace para ver el archivo de comprobante subido por el alumno. --}}
                                    @if($r->comprobante_path)
                                        <a class="btn btn-ghost" target="_blank" href="{{ Storage::disk('public')->url($r->comprobante_path) }}">Ver</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="actions">
                                    {{-- Formulario de eliminación del recibo. --}}
                                    <form action="{{ route('recibos.destroy', $r->id_recibo) }}" method="POST" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger" onclick="return confirm('¿Eliminar recibo #{{ $r->id_recibo }}?')">Eliminar</button>
                                        <br></br>
                                    </form>
                                    
                                    {{-- Botón que abre el modal de validación/rechazo. --}}
                                    <button class="btn btn-ghost" data-open="#v{{ $r->id_recibo }}">Validar</button>
                                    <div id="v{{ $r->id_recibo }}" class="gm-modal" style="display:none">
                                        <div class="gm-modal-card">
                                            <h3>Validar recibo #{{ $r->id_recibo }}</h3>
                                            <form method="POST" action="{{ route('recibos.validar', $r->id_recibo) }}">
                                                @csrf
                                                <div class="grid-2">
                                                    <div>
                                                        <label>Estatus</label>
                                                        <select name="estatus" required>
                                                            <option value="validado"  {{ $r->estatus==='validado'?'selected':'' }}>Validado</option>
                                                            <option value="rechazado" {{ $r->estatus==='rechazado'?'selected':'' }}>Rechazado</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label>Comentarios</label>
                                                        <input name="comentarios" value="{{ old('comentarios', $r->comentarios) }}" placeholder="Notas">
                                                    </div>
                                                </div>
                                                <div class="actions" style="margin-top:12px">
                                                    <button type="button" class="btn btn-danger" data-close="#v{{ $r->id_recibo }}">Cancelar</button>
                                                    <button class="btn btn-primary">Guardar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{-- Enlace para descargar el pdf oficial del recibo (solo si está validado). --}}
                                    @if($r->pdf_path)
                                        <a class="btn btn-ghost" target="_blank" href="{{ Storage::disk('public')->url($r->pdf_path) }}">PDF</a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9">No hay recibos.</td></tr>
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

@push('scripts')
  @vite('resources/js/recibo.js')
@endpush
