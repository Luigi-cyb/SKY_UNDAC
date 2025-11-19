<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Resetear cachés de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ========================================
        // CREAR PERMISOS POR MÓDULO
        // ========================================

        // 1. GESTIÓN DE CURSOS
        $cursosPermisos = [
            'cursos.ver',
            'cursos.crear',
            'cursos.editar',
            'cursos.eliminar',
            'cursos.publicar',
            'cursos.archivar',
        ];

        // 2. GESTIÓN DE ESTUDIANTES
        $estudiantesPermisos = [
            'estudiantes.ver',
            'estudiantes.crear',
            'estudiantes.editar',
            'estudiantes.eliminar',
            'estudiantes.inscribir',
            'estudiantes.listar',
        ];

        // 3. GESTIÓN DE DOCENTES
        $docentesPermisos = [
            'docentes.ver',
            'docentes.crear',
            'docentes.editar',
            'docentes.eliminar',
            'docentes.asignar',
            'docentes.listar',
        ];

        // 4. INSCRIPCIONES
        $inscripcionesPermisos = [
            'inscripciones.ver',
            'inscripciones.crear',
            'inscripciones.editar',
            'inscripciones.cancelar',
            'inscripciones.confirmar',
            'inscripciones.listar',
        ];

        // 5. ASISTENCIA
        $asistenciaPermisos = [
            'asistencia.ver',
            'asistencia.registrar',
            'asistencia.editar',
            'asistencia.reportes',
        ];

        // 6. EVALUACIONES Y CALIFICACIONES
        $evaluacionesPermisos = [
            'evaluaciones.ver',
            'evaluaciones.crear',
            'evaluaciones.editar',
            'evaluaciones.eliminar',
            'calificaciones.ver',
            'calificaciones.registrar',
            'calificaciones.editar',
        ];

        // 7. CERTIFICADOS
        $certificadosPermisos = [
            'certificados.ver',
            'certificados.generar',
            'certificados.descargar',
            'certificados.validar',
            'certificados.revocar',
        ];

        // 8. PAGOS
        $pagosPermisos = [
            'pagos.ver',
            'pagos.registrar',
            'pagos.confirmar',
            'pagos.rechazar',
            'pagos.reportes',
        ];

        // 9. COMPROBANTES
        $comprobantesPermisos = [
            'comprobantes.ver',
            'comprobantes.generar',
            'comprobantes.anular',
        ];

        // 10. MATERIALES
        $materialesPermisos = [
            'materiales.ver',
            'materiales.subir',
            'materiales.editar',
            'materiales.eliminar',
            'materiales.descargar',
        ];

        // 11. ENCUESTAS
        $encuestasPermisos = [
            'encuestas.ver',
            'encuestas.crear',
            'encuestas.editar',
            'encuestas.eliminar',
            'encuestas.responder',
            'encuestas.resultados',
        ];

        // 12. NOTIFICACIONES
        $notificacionesPermisos = [
            'notificaciones.ver',
            'notificaciones.enviar',
            'notificaciones.crear_plantillas',
        ];

        // 13. REPORTES
        $reportesPermisos = [
            'reportes.academicos',
            'reportes.administrativos',
            'reportes.estadisticas',
            'reportes.exportar',
        ];

        // 14. ADMINISTRACIÓN DEL SISTEMA
        $sistemaPermisos = [
            'sistema.configurar',
            'sistema.usuarios',
            'sistema.roles',
            'sistema.permisos',
            'sistema.logs',
        ];

        // Combinar todos los permisos
        $todosLosPermisos = array_merge(
            $cursosPermisos,
            $estudiantesPermisos,
            $docentesPermisos,
            $inscripcionesPermisos,
            $asistenciaPermisos,
            $evaluacionesPermisos,
            $certificadosPermisos,
            $pagosPermisos,
            $comprobantesPermisos,
            $materialesPermisos,
            $encuestasPermisos,
            $notificacionesPermisos,
            $reportesPermisos,
            $sistemaPermisos
        );

        // Crear todos los permisos (solo si no existen)
        foreach ($todosLosPermisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // ========================================
        // CREAR ROLES Y ASIGNAR PERMISOS
        // ========================================

        // 1. ROL: ADMINISTRADOR (Acceso total)
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $adminRole->syncPermissions(Permission::all());

        // 2. ROL: DOCENTE
        $docenteRole = Role::firstOrCreate(['name' => 'Docente']);
        $docenteRole->syncPermissions([
            // Cursos - Solo ver los asignados
            'cursos.ver',
            
            // Estudiantes - Ver inscritos en sus cursos
            'estudiantes.ver',
            'estudiantes.listar',
            
            // Asistencia - Registrar y ver
            'asistencia.ver',
            'asistencia.registrar',
            'asistencia.editar',
            'asistencia.reportes',
            
            // Evaluaciones - Crear y calificar
            'evaluaciones.ver',
            'evaluaciones.crear',
            'evaluaciones.editar',
            'calificaciones.ver',
            'calificaciones.registrar',
            'calificaciones.editar',
            
            // Materiales - Subir y gestionar
            'materiales.ver',
            'materiales.subir',
            'materiales.editar',
            'materiales.eliminar',
            
            // Encuestas - Ver resultados
            'encuestas.ver',
            'encuestas.resultados',
            
            // Notificaciones - Ver
            'notificaciones.ver',
            
            // Reportes - Solo académicos de sus cursos
            'reportes.academicos',
        ]);

        // 3. ROL: ESTUDIANTE
        $estudianteRole = Role::firstOrCreate(['name' => 'Estudiante']);
        $estudianteRole->syncPermissions([
            // Cursos - Ver catálogo
            'cursos.ver',
            
            // Inscripciones - Gestionar propias
            'inscripciones.ver',
            'inscripciones.crear',
            'inscripciones.cancelar',
            
            // Asistencia - Solo ver propia
            'asistencia.ver',
            
            // Calificaciones - Solo ver propias
            'calificaciones.ver',
            
            // Certificados - Ver y descargar propios
            'certificados.ver',
            'certificados.descargar',
            
            // Pagos - Ver y registrar propios
            'pagos.ver',
            'pagos.registrar',
            
            // Comprobantes - Ver propios
            'comprobantes.ver',
            
            // Materiales - Descargar
            'materiales.ver',
            'materiales.descargar',
            
            // Encuestas - Responder
            'encuestas.ver',
            'encuestas.responder',
            
            // Notificaciones - Ver propias
            'notificaciones.ver',
        ]);

        // 4. ROL: COMITÉ ACADÉMICO
        $comiteRole = Role::firstOrCreate(['name' => 'Comité Académico']);
        $comiteRole->syncPermissions([
            // Cursos - Ver y editar
            'cursos.ver',
            'cursos.editar',
            'cursos.publicar',
            
            // Estudiantes - Ver todos
            'estudiantes.ver',
            'estudiantes.listar',
            
            // Docentes - Ver y asignar
            'docentes.ver',
            'docentes.asignar',
            'docentes.listar',
            
            // Inscripciones - Ver y confirmar
            'inscripciones.ver',
            'inscripciones.confirmar',
            'inscripciones.listar',
            
            // Asistencia - Ver reportes
            'asistencia.ver',
            'asistencia.reportes',
            
            // Evaluaciones - Ver
            'evaluaciones.ver',
            'calificaciones.ver',
            
            // Certificados - Generar y validar
            'certificados.ver',
            'certificados.generar',
            'certificados.validar',
            
            // Pagos - Ver y confirmar
            'pagos.ver',
            'pagos.confirmar',
            'pagos.reportes',
            
            // Encuestas - Crear y ver resultados
            'encuestas.ver',
            'encuestas.crear',
            'encuestas.editar',
            'encuestas.resultados',
            
            // Notificaciones - Enviar
            'notificaciones.ver',
            'notificaciones.enviar',
            
            // Reportes - Todos
            'reportes.academicos',
            'reportes.administrativos',
            'reportes.estadisticas',
            'reportes.exportar',
        ]);

        // ========================================
        // CREAR USUARIO ADMINISTRADOR INICIAL
        // ========================================
        
        $admin = User::firstOrCreate(
            ['email' => 'admin@undac.edu.pe'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        
        $admin->syncRoles(['Administrador']);

        $this->command->info('✅ Roles y permisos creados exitosamente');
        $this->command->info('📧 Usuario admin: admin@undac.edu.pe');
        $this->command->info('🔑 Contraseña: admin123');
    }
}