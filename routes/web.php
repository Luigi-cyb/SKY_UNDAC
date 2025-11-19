<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\MaterialCursoController;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SesionCursoController;

// ==========================================
// 🏠 PÁGINA PRINCIPAL
// ==========================================
Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// 🌐 RUTAS PÚBLICAS (Sin autenticación)
// ==========================================
// ==========================================
// 🌐 RUTAS PÚBLICAS (Sin autenticación)
// ==========================================
Route::get('certificados/validar/{numero_serie}', [CertificadoController::class, 'validar'])->name('certificados.validar');
Route::get('validar-certificado', [CertificadoController::class, 'verificarCertificado'])->name('validar.certificado');
Route::post('validar-certificado', [CertificadoController::class, 'validarCertificado'])->name('validar.certificado.post');

// ⭐ NUEVA RUTA: Ver certificado público por código QR
Route::get('certificado/{codigo_qr}', [CertificadoController::class, 'verPublico'])->name('certificado.publico');
// ==========================================
// 🎯 DASHBOARD Y PERFIL - Todos los usuarios autenticados
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Notificaciones propias (todos los roles)
    Route::get('mis-notificaciones', [NotificacionController::class, 'misNotificaciones'])->name('notificaciones.mis-notificaciones');
    Route::post('notificaciones/{notificacion}/marcar-leida', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.marcar-leida');
    Route::post('notificaciones/marcar-todas-leidas', [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.marcar-todas-leidas');

        Route::get('certificados/{certificado}/descargar', [CertificadoController::class, 'descargar'])->name('certificados.descargar');

Route::get('certificados/{certificado}/descargar-pdf', [CertificadoController::class, 'descargarPDF'])->name('certificados.descargar-pdf');

});

// ==========================================
// 👨‍🎓 RUTAS SOLO PARA ESTUDIANTES
// ==========================================
Route::middleware(['auth', 'role:Estudiante'])->prefix('estudiantes')->group(function () {

    // Importación masiva de estudiantes
 Route::get('mis-cursos', [EstudianteController::class, 'misCursos'])->name('estudiantes.mis-cursos');
    Route::get('curso/{curso}/detalle', [EstudianteController::class, 'cursoDetalle'])->name('estudiantes.curso.detalle');
    Route::get('mis-inscripciones', [EstudianteController::class, 'misInscripciones'])->name('estudiantes.mis-inscripciones');
    Route::get('mis-certificados', [EstudianteController::class, 'misCertificados'])->name('estudiantes.mis-certificados');
    
    // ⭐ AGREGAR ESTAS LÍNEAS NUEVAS ⭐
    Route::get('inscripciones/{inscripcion}/pago', [EstudianteController::class, 'mostrarPago'])->name('estudiantes.pago.mostrar');
    Route::post('inscripciones/{inscripcion}/pago', [EstudianteController::class, 'registrarPago'])->name('estudiantes.pago.registrar');
    Route::get('mis-pagos', [EstudianteController::class, 'misPagos'])->name('estudiantes.mis-pagos');
    

    // ... resto de rutas
    // ⭐ RUTAS PARA MATRICULACIÓN
    Route::get('cursos-disponibles', [EstudianteController::class, 'cursosDisponibles'])->name('estudiantes.cursos-disponibles');
    Route::get('inscripcion/{curso}', [EstudianteController::class, 'mostrarInscripcion'])->name('estudiantes.mostrar-inscripcion');
    Route::post('inscripcion/{curso}', [EstudianteController::class, 'inscribirse'])->name('estudiantes.inscribirse');
    
    // ⭐ NUEVA RUTA PARA MARCAR ASISTENCIA
    // ⭐ NUEVA RUTA PARA MARCAR ASISTENCIA
Route::post('sesion/{sesion}/marcar-asistencia', [EstudianteController::class, 'marcarAsistencia'])->name('estudiantes.marcar-asistencia');

// ✅ NUEVO: Rutas para evaluaciones
Route::post('evaluaciones/{evaluacion}/iniciar', [EstudianteController::class, 'iniciarEvaluacion'])->name('estudiantes.evaluacion.iniciar');
Route::get('evaluaciones/intento/{intento}/resolver', [EstudianteController::class, 'resolverEvaluacion'])->name('estudiantes.evaluacion.resolver');
Route::post('evaluaciones/intento/{intento}/guardar-respuesta', [EstudianteController::class, 'guardarRespuesta'])->name('estudiantes.evaluacion.guardar-respuesta');
Route::post('evaluaciones/intento/{intento}/finalizar', [EstudianteController::class, 'finalizarEvaluacion'])->name('estudiantes.evaluacion.finalizar');
Route::get('evaluaciones/intento/{intento}/resultado', [EstudianteController::class, 'verResultado'])->name('estudiantes.evaluacion.resultado');

});

// Otras rutas de estudiantes
// Otras rutas de estudiantes
Route::middleware(['auth', 'role:Estudiante'])->group(function () {
    // Descargar certificados propios
    // Descargar certificados propios

    
    // Descargar materiales del curso
    Route::get('materiales/{material}/descargar', [MaterialCursoController::class, 'descargar'])->name('materiales.descargar');
    
    // Responder encuestas
    Route::get('encuestas/{encuesta}/responder', [EncuestaController::class, 'responder'])->name('encuestas.responder');
    Route::post('encuestas/{encuesta}/responder', [EncuestaController::class, 'guardarRespuesta'])->name('encuestas.guardar-respuesta');
});

// ==========================================
// 👨‍🏫 RUTAS SOLO PARA DOCENTES
// ==========================================
// ==========================================
// 👨‍🏫 RUTAS SOLO PARA DOCENTES
// ==========================================
Route::middleware(['auth', 'role:Docente'])->group(function () {
    
    // Mis cursos como docente
    Route::get('docentes/mis-cursos', [DocenteController::class, 'misCursos'])->name('docentes.mis-cursos');
    
    // ⭐ SESIONES (NUEVO - DOCENTES PUEDEN GESTIONAR)
    Route::get('docente/cursos/{curso}/sesiones', [SesionCursoController::class, 'index'])->name('docente.sesiones.index');
    Route::get('docente/cursos/{curso}/sesiones/create', [SesionCursoController::class, 'create'])->name('docente.sesiones.create');
    Route::post('docente/cursos/{curso}/sesiones', [SesionCursoController::class, 'store'])->name('docente.sesiones.store');
    Route::get('docente/sesiones/{sesion}/edit', [SesionCursoController::class, 'edit'])->name('docente.sesiones.edit');
    Route::put('docente/sesiones/{sesion}', [SesionCursoController::class, 'update'])->name('docente.sesiones.update');
    Route::delete('docente/sesiones/{sesion}', [SesionCursoController::class, 'destroy'])->name('docente.sesiones.destroy');
    
    // ⭐⭐⭐ NUEVAS RUTAS PARA ASISTENCIA POR SESIÓN ⭐⭐⭐
    Route::post('docente/sesiones/{sesion}/iniciar', [SesionCursoController::class, 'iniciarSesion'])
        ->name('docente.sesiones.iniciar');
    Route::post('docente/sesiones/{sesion}/finalizar', [SesionCursoController::class, 'finalizarSesion'])
        ->name('docente.sesiones.finalizar');
    Route::get('docente/sesiones/{sesion}/asistencias', [SesionCursoController::class, 'verAsistencias'])
        ->name('docente.sesiones.asistencias');
    
    // ✅ ASISTENCIAS (ya existentes)
    
    // ... resto de rutas de asistencias ...
    // ✅ ASISTENCIAS (ya existentes)
    Route::get('asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
    Route::get('asistencias/create', [AsistenciaController::class, 'create'])->name('asistencias.create');
    Route::post('asistencias', [AsistenciaController::class, 'store'])->name('asistencias.store');
    Route::get('asistencias/{curso_id}/{numero_sesion}', [AsistenciaController::class, 'show'])->name('asistencias.show');
    Route::get('asistencias/{curso_id}/{numero_sesion}/edit', [AsistenciaController::class, 'edit'])->name('asistencias.edit');
    Route::put('asistencias/{curso_id}/{numero_sesion}', [AsistenciaController::class, 'update'])->name('asistencias.update');
    Route::get('asistencias/reporte', [AsistenciaController::class, 'reporte'])->name('asistencias.reporte');
    
    // 📊 EVALUACIONES (ya existentes + NUEVAS PARA PREGUNTAS)
    Route::get('evaluaciones/peso-disponible', [EvaluacionController::class, 'pesoDisponible'])->name('evaluaciones.peso-disponible');
    Route::get('evaluaciones/estadisticas/{curso_id}', [EvaluacionController::class, 'estadisticasCurso'])->name('evaluaciones.estadisticas-curso');
    Route::get('evaluaciones/{evaluacion}/calificar', [EvaluacionController::class, 'calificar'])->name('evaluaciones.calificar');
    Route::post('evaluaciones/{evaluacion}/guardar-calificaciones', [EvaluacionController::class, 'guardarCalificaciones'])->name('evaluaciones.guardar-calificaciones');
    Route::patch('evaluaciones/{evaluacion}/toggle-status', [EvaluacionController::class, 'toggleStatus'])->name('evaluaciones.toggle-status');
    
    // ⭐ GESTIÓN DE PREGUNTAS (NUEVO - COMPLETO)
    Route::get('evaluaciones/{evaluacion}/preguntas', [EvaluacionController::class, 'gestionarPreguntas'])->name('evaluaciones.preguntas');
    Route::post('evaluaciones/{evaluacion}/preguntas', [EvaluacionController::class, 'guardarPregunta'])->name('evaluaciones.preguntas.store');
    Route::get('preguntas/{pregunta}/editar', [EvaluacionController::class, 'editarPregunta'])->name('preguntas.editar');
    Route::put('preguntas/{pregunta}', [EvaluacionController::class, 'actualizarPregunta'])->name('preguntas.actualizar');
    Route::delete('preguntas/{pregunta}', [EvaluacionController::class, 'eliminarPregunta'])->name('preguntas.destroy');
    Route::get('evaluaciones/{evaluacion}/preview', [EvaluacionController::class, 'preview'])->name('evaluaciones.preview');
    Route::post('evaluaciones/{evaluacion}/reordenar-preguntas', [EvaluacionController::class, 'reordenarPreguntas'])->name('evaluaciones.reordenar');
    
    Route::resource('evaluaciones', EvaluacionController::class)->parameters([
    'evaluaciones' => 'evaluacion'
])->except(['show']);
    
    // 📈 CALIFICACIONES (ya existentes)
    Route::get('calificaciones', [CalificacionController::class, 'index'])->name('calificaciones.index');
    Route::get('calificaciones/create', [CalificacionController::class, 'create'])->name('calificaciones.create');
    Route::post('calificaciones', [CalificacionController::class, 'store'])->name('calificaciones.store');
    Route::get('calificaciones/{evaluacion_id}', [CalificacionController::class, 'show'])->name('calificaciones.show');
    Route::get('calificaciones/{evaluacion_id}/edit', [CalificacionController::class, 'edit'])->name('calificaciones.edit');
    Route::put('calificaciones/{evaluacion_id}', [CalificacionController::class, 'update'])->name('calificaciones.update');
    Route::get('calificaciones/estudiante/{inscripcion_id}/promedio', [CalificacionController::class, 'promedioEstudiante'])->name('calificaciones.promedio-estudiante');
});

// ==========================================
// 👔 RUTAS SOLO PARA ADMINISTRADOR
// ==========================================
// ==========================================
// 👔 RUTAS SOLO PARA ADMINISTRADOR
// ==========================================
Route::middleware(['auth', 'role:Administrador'])->group(function () {
     // ✅ AGREGAR ESTAS DOS RUTAS NUEVAS:
    Route::get('certificados/{certificado}/descargar-sin-firmar', [CertificadoController::class, 'descargarPDFSinFirmar'])
        ->name('certificados.descargar-sin-firmar');
    
    Route::post('certificados/{certificado}/subir-firmado', [CertificadoController::class, 'subirPDFFirmado'])
        ->name('certificados.subir-firmado');
    // 💰 PAGOS (Solo Administrador)
    Route::get('pagos-buscar', [PagoController::class, 'buscar'])->name('pagos.buscar');
    Route::get('pagos-reportes', [PagoController::class, 'reportes'])->name('pagos.reportes');
    Route::get('pagos-conciliacion', [PagoController::class, 'conciliar'])->name('pagos.conciliar');
    Route::post('pagos/{pago}/confirmar', [PagoController::class, 'confirmar'])->name('pagos.confirmar');
    Route::post('pagos/{pago}/rechazar', [PagoController::class, 'rechazar'])->name('pagos.rechazar');
    Route::get('pagos/{pago}/descargar-comprobante', [PagoController::class, 'descargarComprobante'])->name('pagos.descargar-comprobante');
    Route::post('pagos/{pago}/reenviar-comprobante', [PagoController::class, 'reenviarComprobante'])->name('pagos.reenviar-comprobante');
    Route::resource('pagos', PagoController::class);
    
    // 🧾 COMPROBANTES (Solo Administrador)
    Route::get('comprobantes/{comprobante}/pdf', [ComprobanteController::class, 'pdf'])->name('comprobantes.pdf');
    Route::get('comprobantes/{comprobante}/descargar', [ComprobanteController::class, 'descargar'])->name('comprobantes.descargar');
    Route::post('comprobantes/{comprobante}/reenviar', [ComprobanteController::class, 'reenviar'])->name('comprobantes.reenviar');
    Route::resource('comprobantes', ComprobanteController::class);
    
    // ⭐ NUEVAS RUTAS: PAGOS PENDIENTES CON YAPE
    Route::get('admin/pagos-pendientes', [PagoController::class, 'pagosPendientes'])
        ->name('admin.pagos-pendientes');
    Route::post('admin/pagos/{pago}/confirmar-manual', [PagoController::class, 'confirmarManual'])
        ->name('admin.pagos.confirmar-manual');
    Route::post('admin/pagos/{pago}/rechazar-manual', [PagoController::class, 'rechazarManual'])
        ->name('admin.pagos.rechazar-manual');
});
// ==========================================
// 👔 RUTAS PARA ADMINISTRADOR Y COMITÉ ACADÉMICO
// ==========================================
// ==========================================
// 👔 RUTAS PARA ADMINISTRADOR Y COMITÉ ACADÉMICO
// ==========================================
Route::middleware(['auth', 'role:Administrador|Comité Académico|Docente'])->group(function () { 
    Route::post('estudiantes/importar', [EstudianteController::class, 'importar'])->name('estudiantes.importar');
    Route::get('estudiantes/plantilla', [EstudianteController::class, 'descargarPlantilla'])->name('estudiantes.plantilla');
    Route::resource('estudiantes', EstudianteController::class);
    
    // ⭐ SESIONES DE CURSO (NUEVO)
    Route::get('cursos/{curso}/sesiones', [SesionCursoController::class, 'index'])->name('sesiones.index');
    Route::get('cursos/{curso}/sesiones/create', [SesionCursoController::class, 'create'])->name('sesiones.create');
    Route::post('cursos/{curso}/sesiones', [SesionCursoController::class, 'store'])->name('sesiones.store');
    Route::get('sesiones/{sesion}/edit', [SesionCursoController::class, 'edit'])->name('sesiones.edit');
    Route::put('sesiones/{sesion}', [SesionCursoController::class, 'update'])->name('sesiones.update');
    Route::delete('sesiones/{sesion}', [SesionCursoController::class, 'destroy'])->name('sesiones.destroy');
    
    // ⭐⭐⭐ NUEVAS RUTAS PARA ASISTENCIA POR SESIÓN (ADMIN/COMITÉ) ⭐⭐⭐
    Route::post('sesiones/{sesion}/iniciar', [SesionCursoController::class, 'iniciarSesion'])
        ->name('sesiones.iniciar');
    Route::post('sesiones/{sesion}/finalizar', [SesionCursoController::class, 'finalizarSesion'])
        ->name('sesiones.finalizar');
    Route::get('sesiones/{sesion}/asistencias', [SesionCursoController::class, 'verAsistencias'])
        ->name('sesiones.asistencias');
    
 
    // ... resto de rutas ...
    // ⭐ GESTIÓN DE PREGUNTAS PARA EVALUACIONES (ADMINISTRADOR/COMITÉ)
    Route::get('admin/evaluaciones/{evaluacion}/preguntas', [EvaluacionController::class, 'gestionarPreguntas'])->name('admin.evaluaciones.preguntas');
    Route::post('admin/evaluaciones/{evaluacion}/preguntas', [EvaluacionController::class, 'guardarPregunta'])->name('admin.evaluaciones.preguntas.store');
    Route::get('admin/preguntas/{pregunta}/editar', [EvaluacionController::class, 'editarPregunta'])->name('admin.preguntas.editar');
    Route::put('admin/preguntas/{pregunta}', [EvaluacionController::class, 'actualizarPregunta'])->name('admin.preguntas.actualizar');
    Route::delete('admin/preguntas/{pregunta}', [EvaluacionController::class, 'eliminarPregunta'])->name('admin.preguntas.destroy');
    Route::get('admin/evaluaciones/{evaluacion}/preview', [EvaluacionController::class, 'preview'])->name('admin.evaluaciones.preview');
    Route::post('admin/evaluaciones/{evaluacion}/reordenar-preguntas', [EvaluacionController::class, 'reordenarPreguntas'])->name('admin.evaluaciones.reordenar');
    
    // 📚 CURSOS
    Route::post('cursos/{curso}/asignar-docente', [CursoController::class, 'asignarDocente'])->name('cursos.asignar-docente');
    Route::delete('cursos/{curso}/desasignar-docente/{asignacion}', [CursoController::class, 'desasignarDocente'])->name('cursos.desasignar-docente');
    Route::resource('cursos', CursoController::class);
    
    // 🎓 ESTUDIANTES
    Route::resource('estudiantes', EstudianteController::class);
    
    // 👨‍🏫 DOCENTES
    Route::get('docentes/buscar', [DocenteController::class, 'buscar'])->name('docentes.buscar');
    Route::post('docentes/{docente}/reenviar-credenciales', [DocenteController::class, 'reenviarCredenciales'])->name('docentes.reenviar-credenciales');
    Route::patch('docentes/{docente}/toggle-status', [DocenteController::class, 'toggleStatus'])->name('docentes.toggle-status');
    Route::get('docentes/{docente}/carga-academica', [DocenteController::class, 'cargaAcademica'])->name('docentes.carga-academica');
    Route::get('docentes/{docente}/descargar-cv', [DocenteController::class, 'descargarCV'])->name('docentes.descargar-cv');
    Route::resource('docentes', DocenteController::class);
    
    // 📝 INSCRIPCIONES
    Route::post('inscripciones/{inscripcion}/confirmar', [InscripcionController::class, 'confirmar'])->name('inscripciones.confirmar');
    Route::resource('inscripciones', InscripcionController::class)->parameters(['inscripciones' => 'inscripcion']);
    
    // 🎖️ CERTIFICADOS
 // 🎖️ CERTIFICADOS
   // 🎖️ CERTIFICADOS
Route::get('certificados-generar', [CertificadoController::class, 'generar'])->name('certificados.generar');
Route::post('certificados/generar-masivo', [CertificadoController::class, 'generarMasivo'])->name('certificados.generar-masivo');
Route::get('certificados/buscar', [CertificadoController::class, 'buscar'])->name('certificados.buscar');
Route::post('certificados/{certificado}/revocar', [CertificadoController::class, 'revocar'])->name('certificados.revocar');
Route::post('certificados/{certificado}/restaurar', [CertificadoController::class, 'restaurar'])->name('certificados.restaurar');
Route::post('certificados/{certificado}/reenviar', [CertificadoController::class, 'reenviar'])->name('certificados.reenviar');
Route::get('certificados/{certificado}', [CertificadoController::class, 'show'])->name('certificados.show');
Route::delete('certificados/{certificado}', [CertificadoController::class, 'destroy'])->name('certificados.destroy');
Route::resource('certificados', CertificadoController::class);
    
    // 📁 MATERIALES
    Route::get('materiales/curso/{curso_id}', [MaterialCursoController::class, 'porCurso'])->name('materiales.por-curso');
    Route::resource('materiales', MaterialCursoController::class);
    
    // 📋 ENCUESTAS
    Route::get('encuestas/{encuesta}/resultados', [EncuestaController::class, 'resultados'])->name('encuestas.resultados');
    Route::get('encuestas/{encuesta}/exportar', [EncuestaController::class, 'exportar'])->name('encuestas.exportar');
    Route::resource('encuestas', EncuestaController::class);
    
    // 🔔 NOTIFICACIONES
    Route::resource('notificaciones', NotificacionController::class);
    
    // 📊 REPORTES
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/inscripciones', [ReporteController::class, 'inscripciones'])->name('reportes.inscripciones');
    Route::get('reportes/calificaciones', [ReporteController::class, 'calificaciones'])->name('reportes.calificaciones');
    Route::get('reportes/asistencia', [ReporteController::class, 'asistencia'])->name('reportes.asistencia');
    Route::get('reportes/pagos', [ReporteController::class, 'pagos'])->name('reportes.pagos');
    Route::get('reportes/certificados', [ReporteController::class, 'certificados'])->name('reportes.certificados');
    Route::get('reportes/academico/{curso_id}', [ReporteController::class, 'academicoPorCurso'])->name('reportes.academico-curso');
    Route::get('reportes/rendimiento', [ReporteController::class, 'rendimientoAcademico'])->name('reportes.rendimiento');
    Route::get('reportes/carga-docente', [ReporteController::class, 'cargaDocente'])->name('reportes.carga-docente');
    Route::get('reportes/estadisticas', [ReporteController::class, 'estadisticasGenerales'])->name('reportes.estadisticas');
    Route::get('reportes/satisfaccion', [ReporteController::class, 'satisfaccion'])->name('reportes.satisfaccion');
});

require __DIR__.'/auth.php';