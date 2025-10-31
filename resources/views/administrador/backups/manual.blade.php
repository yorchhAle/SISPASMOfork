@extends('layouts.encabezados')
@section('title', 'Respaldo y restauraciónn de base de datos')

@section('content')
<div class="crud-wrap dash"> 
    <div class="crud-wrap">

    <div class="crud-card">
        <div class="crud-hero">
            <h1 class="crud-hero-title">Administración de la base de datos</h1>
            <p class="crud-hero-subtitle">
                Crea, restaura y administra los archivos de la base de datos de SISPASMO.
            </p>
        </div>
            <div class="crud-body">
                <h1>Respaldos realizados</h1>

        <div class="crud-body">
            
            @if (session('success'))
                <div class="gm-ok">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="gm-errors">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                </div>
            @endif

            <div class="row g-4 mb-5">
                
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-success">
                        <div class="card-body d-flex flex-column">
                            <h2> Generar nuevo respaldo</h2>
                            <p class="card-text text-muted small">
                                Crea un archivo .sql de la base de datos, lo almacena y lo descarga inmediatamente.
                            </p>
                        </div>
                    </div>
                </div>
                @if (empty($backups)) 
                    <div class="gm-empty">No hay respaldos disponibles.</div>
                @else
                    <div class="table-responsive">
                        <table class="gm-table">
                            <thead>
                                <tr>
                                    <th>Archivo</th>
                                    <th>Tamaño</th>
                                    <th>Última modificación</th>
                                    <th class="th-actions">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $backup)
                                    <tr>
                                        <td>{{ $backup['file_name'] ?? 'N/A' }}</td>
                                        <td>{{ $backup['file_size'] ?? 'N/A' }}</td>
                                        <td>{{ $backup['last_modified'] ?? 'N/A' }}</td>
                                        <td>
                                            <div class="table-actions">
                                                <form action="{{ route('admin.backup.restore') }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    <input type="hidden" name="backup_file" value="{{ $backup['file_name'] }}">
                                                    <button type="submit" class="btn btn-ghost" onclick="return confirm('ATENCIÓN: ¿Restaurar este respaldo? Esto sobrescribirá PERMANENTEMENTE la base de datos actual.')">Restaurar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <form action="{{ route('admin.backup.create') }}" method="POST" style="margin-bottom:20px;">
                            @csrf
                            <button type="submit" class="btn btn-primary">Crear nuevo respaldo</button>
                        </form>
                @endif
            </div>
        </div>
        </section>
    </div>
</div>
@endsection