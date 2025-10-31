<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\DocenteLoginController;
use App\Http\Controllers\Auth\AlumnoLoginController;
use App\Http\Controllers\Auth\AspiranteAuthController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AspiranteController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\QuejaController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\TallerController;
use App\Http\Controllers\FichaMedicaController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\CitaController; 
use App\Http\Controllers\DiplomadoController; 
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\Aspirante\CitaController as AspiranteCitaController;
use App\Http\Controllers\Admin\CitaController as AdminCitaController;
use App\Http\Controllers\ReporteAlumnosEdadController;
use App\Http\Controllers\ReporteAlumnosDipConcluidoController;
use App\Http\Controllers\ReportePagosSemMenController;
use App\Http\Controllers\ReporteAdeudosController;
use App\Http\Controllers\ReporteAlumnosReprobadosController;
use App\Http\Controllers\ReporteAspirantesController;
use App\Http\Controllers\ReporteAlumnosInscritosController;
use App\Http\Controllers\Auth\AlumnoPasswordResetLinkController;
use App\Http\Controllers\Auth\AlumnoNewPasswordController;
use App\Http\Controllers\Auth\DocentePasswordResetLinkController;
use App\Http\Controllers\Auth\DocenteNewPasswordController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\NotificacionController; 


/*--------------------------------------------------------------------------
| Rutas Públicas
|-------------------------------------------------------------------------- */
Route::view('/', 'inicio')->name('inicio');
Route::view('/oferta', 'oferta')->name('oferta');
Route::view('/contacto', 'contacto')->name('contacto');

/* --------------------------------------------------------------------------
| Rutas de Autenticación
|-------------------------------------------------------------------------- */
// Rutas de autenticación para Administrador
Route::prefix('administrador')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});

// Rutas de autenticación para Docente
Route::prefix('docente')->group(function () {
    Route::get('/login', [DocenteLoginController::class, 'showLoginForm'])->name('docente.login');
    Route::post('/login', [DocenteLoginController::class, 'login'])->name('docente.login.post');
    Route::post('/logout', [DocenteLoginController::class, 'logout'])->name('docente.logout');
});

// Rutas de autenticación para Alumno
Route::prefix('alumno')->group(function () {
    Route::get('/login', [AlumnoLoginController::class, 'showLoginForm'])->name('alumno.login');
    Route::post('/login', [AlumnoLoginController::class, 'login'])->name('alumno.login.post');
    Route::post('/logout', [AlumnoLoginController::class, 'logout'])->name('alumno.logout');
});

// Rutas de autenticación y registro para Aspirante
Route::prefix('aspirante')->group(function () {
    Route::get('/', [AspiranteAuthController::class, 'select'])->name('aspirante.select');
    Route::get('/registro',  [AspiranteAuthController::class, 'showRegisterForm'])->name('aspirante.register.show');
    Route::post('/registro', [AspiranteAuthController::class, 'register'])->name('aspirante.register');
    Route::get('/login',  [AspiranteAuthController::class, 'showLoginForm'])->name('aspirante.login');
    Route::post('/login', [AspiranteAuthController::class, 'login'])->name('aspirante.login.post');
    Route::post('/logout', [AspiranteAuthController::class, 'logout'])->name('aspirante.logout');
});

/* --------------------------------------------------------------------------
| Rutas Protegidas por Middleware
|-------------------------------------------------------------------------- */
// Rutas para Administrador y Coordinador
Route::middleware(['auth','role:Administrador,Coordinador'])->prefix('administrador')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/metrics', [DashboardController::class, 'metrics'])->name('admin.dashboard.metrics');

    Route::resource('alumnos', AlumnoController::class)->names('alumnos');
    Route::resource('docentes', DocenteController::class)->names('docentes');
    Route::resource('aspirantes', AspiranteController::class)->names('aspirantes');
    Route::resource('coordinadores', CoordinadorController::class)->parameters(['coordinadores' => 'coordinador']);
    Route::resource('admin', AdminController::class)->names('admin');
    Route::resource('modulos', ModuloController::class)->names('modulos');
    Route::resource('extracurricular', TallerController::class)->names('extracurricular');

    Route::view('/reportes', 'administrador.reportes.reportes')->name('admin.reportes');
});

// Rutas para Docente
Route::middleware(['auth', 'role:Docente'])->prefix('docente')->group(function () {
    Route::get('/dashboard', [DocenteController::class, 'dashboard'])->name('docente.dashboard');
});

// Rutas para Alumno
Route::middleware(['auth', 'role:Alumno'])->prefix('alumno')->group(function () {
    Route::get('/dashboard', [AlumnoController::class, 'dashboard'])->name('alumno.dashboard');
});

Route::middleware(['auth', 'role:Alumno'])->prefix('alumno')->group(function () {
    Route::view('/dashboard', 'alumno.dashboardalumno')->name('alumno.dashboard');
});

// Rutas para Aspirante
Route::middleware(['auth', 'role:Aspirante'])->prefix('aspirante')->group(function () {
    Route::get('/dashboard', [AspiranteController::class, 'dashboard'])->name('aspirante.dashboard');
});

/* GESTIÓN QUEJAS */
// Rutas generales para usuarios autenticados
Route::middleware(['auth'])->group(function () {
    Route::get('/quejas/nueva', [QuejaController::class, 'create'])->name('quejas.create');
    Route::post('/quejas', [QuejaController::class, 'store'])->name('quejas.store');
    Route::get('/quejas/mis', [QuejaController::class, 'mine'])->name('quejas.propias');
    Route::get('/quejas/{queja}', [QuejaController::class, 'show'])->name('quejas.read');
});

// Rutas para Administrador
Route::middleware(['auth','role:Administrador'])->prefix('admin')->group(function () {
    Route::get('/quejas', [QuejaController::class, 'index'])->name('quejas.index');
    Route::get('/quejas/{queja}/edit', [QuejaController::class, 'edit'])->name('quejas.edit');
    Route::put('/quejas/{queja}', [QuejaController::class, 'update'])->name('quejas.update');
    Route::delete('/quejas/{queja}', [QuejaController::class, 'destroy'])->name('quejas.destroy');
});

/* GESTIÓN FICHA MÉDICA */
// Rutas para Administrador y Coordinador
Route::middleware(['auth','role:administrador,coordinador'])->group(function () {
    Route::get('/fichas', [FichaMedicaController::class, 'index'])->name('fichasmedicas.index');
    Route::get('/fichas/{ficha}', [FichaMedicaController::class, 'show'])->name('fichasmedicas.show');
    Route::delete('/fichas/{ficha}', [FichaMedicaController::class, 'destroy'])->name('fichasmedicas.destroy');
});

// Rutas para Alumno
Route::middleware(['auth','role:alumno'])->prefix('mi-ficha')->name('mi_ficha.')->group(function () {
    Route::get('/crear',  [FichaMedicaController::class, 'createMine'])->name('create');
    Route::post('/',      [FichaMedicaController::class, 'storeMine'])->name('store');
    Route::get('/editar', [FichaMedicaController::class, 'editMine'])->name('edit');
    Route::put('/',       [FichaMedicaController::class, 'updateMine'])->name('update');
    Route::get('/',       [FichaMedicaController::class, 'showMine'])->name('show');
});

/* GESTIÓN RECIBOS */
// Rutas para Alumno
Route::middleware(['auth','role:alumno'])->group(function () {
    Route::get('/recibos', [ReciboController::class, 'indexAlumno'])->name('recibos.index');
    Route::get('/recibos/crear', [ReciboController::class, 'create'])->name('recibos.create');
    Route::post('/recibos', [ReciboController::class, 'store'])->name('recibos.store');
    Route::get('/recibos/{recibo}', [ReciboController::class, 'show'])->name('recibos.show');
});

// Rutas para Administrador, Coordinador y Superadmin
Route::middleware(['auth','role:administrador,coordinador,superadmin'])->prefix('admin')->group(function () {
    Route::get('/recibos', [ReciboController::class, 'indexAdmin'])->name('recibos.admin.index');
    Route::get('/recibos/{recibo}/editar', [ReciboController::class, 'edit'])->name('recibos.edit');
    Route::put('/recibos/{recibo}', [ReciboController::class, 'update'])->name('recibos.update');
    Route::delete('/recibos/{recibo}', [ReciboController::class, 'destroy'])->name('recibos.destroy');
    Route::post('/recibos/{recibo}/validar', [ReciboController::class, 'validar'])->name('recibos.validar');
});

/* GESTIÓN CALIFICACIONES */
// Rutas para Alumno
Route::middleware(['auth','role:alumno'])->group(function () {
    Route::get('/calificaciones', [CalificacionController::class, 'indexAlumno'])->name('calif.alumno.index');
});

// Rutas para Docente
Route::middleware(['auth','role:docente'])->group(function () {
    Route::get('/docente/calificaciones', [CalificacionController::class, 'indexDocente'])->name('calif.docente.index');
    Route::get('/docente/calificaciones/crear', [CalificacionController::class, 'create'])->name('calif.create');
    Route::post('/docente/calificaciones', [CalificacionController::class, 'store'])->name('calif.store');
    Route::get('/docente/calificaciones/{calif}/edit',[CalificacionController::class, 'edit'])->name('calif.edit');
    Route::put('/docente/calificaciones/{calif}', [CalificacionController::class, 'update'])->name('calif.update');
    Route::delete('/docente/calificaciones/{calif}', [CalificacionController::class, 'destroy'])->name('calif.destroy');
});

Route::get('/calificaciones/alumnos-por-modulo/{modulo}', [App\Http\Controllers\CalificacionController::class, 'getAlumnosPorModulo'])
    ->name('calif.alumnosPorModulo')
    ->middleware('auth'); // O el middleware que uses para docentes

// Rutas para Administrador, Coordinador y Superadmin
Route::middleware(['auth','role:administrador,coordinador,superadmin'])->group(function () {
    Route::get('/admin/calificaciones', [CalificacionController::class, 'indexAdmin'])->name('calif.admin.index');
});

/* GESTIÓN CITAS */
Route::middleware(['auth'])->group(function () {
    // Rutas para Aspirante
    Route::prefix('aspirante/citas')->name('aspirante.citas.')->group(function () {
        Route::get('/', [AspiranteCitaController::class, 'index'])->name('index'); 
        Route::get('/crear', [AspiranteCitaController::class, 'create'])->name('create');
        Route::post('/', [AspiranteCitaController::class, 'store'])->name('store');
        Route::delete('/{cita}', [AspiranteCitaController::class, 'cancel'])->name('cancel');
    });

    // Rutas para Administrador
    Route::prefix('admin/citas')->name('admin.citas.')->group(function () {
        Route::get('/', [AdminCitaController::class, 'index'])->name('index');
        Route::patch('/{cita}/estatus', [AdminCitaController::class, 'updateStatus'])->name('updateStatus');
    });
});

Route::patch('citas/{cita}/status', [CitaController::class, 'updateStatus'])->name('admin.citas.updateStatus');
Route::resource('citas', CitaController::class)->names('citas');

/*GESTIÓN DIPLOMADOS*/
Route::middleware(['auth','role:Administrador,Coordinador'])->prefix('admin/diplomados')->name('admin.diplomados.')->controller(DiplomadoController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/crear', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{diplomado}/editar', 'edit')->name('edit');
        Route::put('/{diplomado}', 'update')->name('update');
        Route::delete('/{diplomado}', 'destroy')->name('destroy');
    });

/* GESTIÓN HORARIOS */
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios.index');
        Route::get('/horarios/create', [HorarioController::class, 'create'])->name('horarios.create');
        Route::post('/horarios', [HorarioController::class, 'store'])->name('horarios.store');
        Route::get('/horarios/{horario}/edit', [HorarioController::class, 'edit'])->name('horarios.edit');
        Route::put('/horarios/{horario}', [HorarioController::class, 'update'])->name('horarios.update');
        Route::delete('/horarios/{horario}', [HorarioController::class, 'destroy'])->name('horarios.destroy');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/alumno/horario', [HorarioController::class, 'horarioAlumno'])->name('alumno.horario');

    Route::get('/docente/horario', [HorarioController::class, 'horarioDocente'])->name('docente.horario'); 
});

/* RUTAS REPORTES*/
// Reporte por edades
Route::middleware(['auth'])->prefix('admin/reportes/alumnos-edad')->name('admin.reportes.alumnosEdad.')->group(function () {
        Route::get('/', [ReporteAlumnosEdadController::class, 'index'])->name('index');
        Route::get('/chart-data', [ReporteAlumnosEdadController::class, 'chartData'])->name('chartData');
        Route::get('/table', [ReporteAlumnosEdadController::class, 'table'])->name('table');
        Route::post('/pdf', [ReporteAlumnosEdadController::class, 'pdf'])->name('pdf');
        Route::get('/excel', [ReporteAlumnosEdadController::class, 'excel'])->name('excel');
    });
Route::get('/admin/reportes/alumnos-edad/chart-data-exact', [ReporteAlumnosEdadController::class, 'chartDataExact'])->name('admin.reportes.alumnosEdad.chartDataExact');

// Reporte de diplomados concluidos
Route::get('reportes/alumnos-diplomados-concluidos', [ReporteAlumnosDipConcluidoController::class, 'index'])->name('reportes.alumnos.concluidos');
Route::get('reportes/excel-egresados-anual', [ReporteAlumnosDipConcluidoController::class, 'downloadExcel'])->name('reportes.excel.egresados.anual');
Route::get('reportes/excel-comparacion-estatus', [ReporteAlumnosDipConcluidoController::class, 'downloadExcel'])->name('reportes.excel.comparacion.estatus');

// Reporte de pagos semanles y mensuales
Route::get('/reportes/pagos', [ReportePagosSemMenController::class, 'mostrarReporte'])->name('reportes.pagos');
Route::get('/reportes/exportar', [ReportePagosSemMenController::class, 'exportarExcel']) ->name('reportes.exportar');

// Reporte adeudos
Route::get('/reportes/adeudos', [ReporteAdeudosController::class, 'mostrarReporte'])->name('reportes.adeudos');
Route::get('/reportes/adeudos/chart-mes', [ReporteAdeudosController::class, 'generarGraficaMes'])->name('reportes.adeudos.chart.mes');
Route::get('/reportes/adeudos/chart-alumno', [ReporteAdeudosController::class, 'generarGraficaAlumno'])->name('reportes.adeudos.chart.alumno');
Route::get('/reportes/adeudos/exportar', [ReporteAdeudosController::class, 'exportarExcel'])->name('reportes.adeudos.exportar');

// Reporte de alumnos inscritos por diplomado
Route::get('/reportes/inscritos', [ReporteAlumnosInscritosController::class, 'mostrarReporte'])->name('reportes.inscritos.index');
Route::get('/reportes/inscritos/alumnos-totales', [ReporteAlumnosInscritosController::class, 'alumnosTotales'])->name('reportes.inscritos.totales');
Route::get('/reportes/inscritos/estatus-alumnos', [ReporteAlumnosInscritosController::class, 'estatusAlumnos'])->name('reportes.inscritos.estatus');
Route::post('/reportes/inscritos/pdf', [ReporteAlumnosInscritosController::class, 'generarPdf'])->name('reportes.inscritos.pdf');

// Reporte de alumnos reprobados
Route::get('/reportes/reprobados', [ReporteAlumnosReprobadosController::class, 'mostrarReporte'])->name('reportes.reprobados.index');
Route::get('/api/modulos', [ReporteAlumnosReprobadosController::class, 'cargarModulos'])->name('api.modulos');
Route::get('/reportes/reprobados/total', [ReporteAlumnosReprobadosController::class, 'totalReprobados'])->name('reportes.reprobados.total');
Route::get('/reportes/reprobados/calificaciones', [ReporteAlumnosReprobadosController::class, 'calificacionesReprobados'])->name('reportes.reprobados.calificaciones');
Route::post('/reportes/reprobados/exportar', [ReporteAlumnosReprobadosController::class, 'exportarExcel'])->name('reportes.reprobados.exportar');

// Reporte de aspirantes interesados
Route::get('/reportes/aspirantes', [ReporteAspirantesController::class, 'mostrarReporte'])->name('reportes.aspirantes.index');
Route::get('/reportes/aspirantes/total', [ReporteAspirantesController::class, 'totalPorDiplomado'])->name('reportes.aspirantes.total');
Route::get('/reportes/aspirantes/comparacion', [ReporteAspirantesController::class, 'comparacionTipos'])->name('reportes.aspirantes.comparacion');
Route::get('/reportes/aspirantes/exportar', [ReporteAspirantesController::class, 'exportarExcel'])->name('reportes.aspirantes.exportar');

// Recuperación de contraseña alumno
Route::prefix('alumno')->group(function () {
    Route::get('forgot-password', [AlumnoPasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [AlumnoPasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [AlumnoNewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [AlumnoNewPasswordController::class, 'store'])->name('password.update');
});

// Recuperación de contraseña docente
Route::prefix('docente')->group(function () {
    Route::get('forgot-password', [DocentePasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [DocentePasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [DocenteNewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [DocenteNewPasswordController::class, 'store'])->name('password.update');
});


Route::middleware(['auth'/*, 'can:admin' */])->group(function () {
    Route::post('/aspirantes/{aspirante}/convertir', [AspiranteController::class, 'convertirAAlumno'])
        ->name('aspirantes.convertir');
});


Route::middleware('auth')->group(function () {
    Route::get('/extracurriculares', [InscripcionController::class, 'index'])->name('extracurriculares.disponibles');
    Route::post('/extracurriculares/{extracurricular}/inscribir', [InscripcionController::class, 'store'])->name('extracurriculares.inscribir');
    Route::delete('/extracurriculares/{extracurricular}/cancelar', [InscripcionController::class, 'destroy'])->name('extracurriculares.cancelar');

});


/*Respaldo y restauración */
Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('backup/manual', [BackupController::class, 'backup'])->name('backup.manual');
    Route::post('backup/create', [BackupController::class, 'createBackup'])->name('backup.create');
    Route::post('backup/restore', [BackupController::class, 'restoreBackup'])->name('backup.restore');
});

/*Notificaciones*/
Route::middleware('auth') ->prefix('notificaciones')->name('notificaciones.')->group(function () {
    Route::get('/', [NotificacionController::class, 'index'])->name('index');
    Route::post('/mark-all', [NotificacionController::class, 'markAll'])->name('markAll');
    Route::post('/{id}/mark-one', [NotificacionController::class, 'markOne'])->name('markOne');
    Route::delete('/{id}', [NotificacionController::class, 'destroy'])->name('destroy');
});