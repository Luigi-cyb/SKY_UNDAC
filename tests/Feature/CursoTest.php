<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Curso;
use App\Models\CategoriaCurso;
use App\Models\Modalidad;
use App\Models\Docente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class CursoTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $categoria;
    protected $modalidad;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear roles necesarios
        Role::create(['name' => 'Administrador']);
        
        // Crear usuario administrador
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Administrador');
        
        // Crear datos base necesarios
        $this->categoria = CategoriaCurso::create([
            'nombre' => 'Programación',
            'descripcion' => 'Cursos de programación'
        ]);
        
        $this->modalidad = Modalidad::create([
            'nombre' => 'Virtual',
            'descripcion' => 'Clases en línea'
        ]);
    }

    /** @test */
    public function admin_puede_ver_lista_de_cursos()
    {
        $response = $this->actingAs($this->admin)
                        ->get(route('cursos.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cursos.index');
    }

    /** @test */
    public function admin_puede_crear_curso_con_datos_validos()
    {
        $docente = Docente::factory()->create();
        
        $datosCurso = [
            'nombre' => 'Laravel Avanzado',
            'codigo' => 'LAR-2025-001',
            'categoria_id' => $this->categoria->id,
            'modalidad_id' => $this->modalidad->id,
            'docente_id' => $docente->id,
            'duracion_horas' => 40,
            'cupo_minimo' => 10,
            'cupo_maximo' => 30,
            'fecha_inicio' => now()->addDays(30)->format('Y-m-d'),
            'fecha_fin' => now()->addDays(60)->format('Y-m-d'),
            'costo' => 150.00,
            'nota_minima_aprobacion' => 14,
            'asistencia_minima' => 80,
            'descripcion' => 'Curso avanzado de Laravel',
            'estado' => 'borrador'
        ];

        $response = $this->actingAs($this->admin)
                        ->post(route('cursos.store'), $datosCurso);

        $response->assertRedirect(route('cursos.index'));
        $this->assertDatabaseHas('cursos', [
            'codigo' => 'LAR-2025-001',
            'nombre' => 'Laravel Avanzado'
        ]);
    }

    /** @test */
    public function no_se_puede_crear_curso_sin_datos_obligatorios()
    {
        $response = $this->actingAs($this->admin)
                        ->post(route('cursos.store'), []);

        $response->assertSessionHasErrors([
            'nombre',
            'codigo',
            'categoria_id',
            'modalidad_id'
        ]);
    }
}