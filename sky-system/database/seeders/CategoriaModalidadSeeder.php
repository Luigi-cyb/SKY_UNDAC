<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaCurso;
use App\Models\Modalidad;
use App\Models\MetodoPago;

class CategoriaModalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorías de Cursos
        $categorias = [
            ['nombre' => 'Programación', 'descripcion' => 'Cursos de desarrollo de software', 'activo' => true],
            ['nombre' => 'Bases de Datos', 'descripcion' => 'Cursos de gestión de bases de datos', 'activo' => true],
            ['nombre' => 'Redes', 'descripcion' => 'Cursos de redes y telecomunicaciones', 'activo' => true],
            ['nombre' => 'Diseño Web', 'descripcion' => 'Cursos de diseño y desarrollo web', 'activo' => true],
            ['nombre' => 'Inteligencia Artificial', 'descripcion' => 'Cursos de IA y Machine Learning', 'activo' => true],
            ['nombre' => 'Ciberseguridad', 'descripcion' => 'Cursos de seguridad informática', 'activo' => true],
            ['nombre' => 'Gestión de Proyectos', 'descripcion' => 'Cursos de gestión y metodologías ágiles', 'activo' => true],
        ];

        foreach ($categorias as $categoria) {
            CategoriaCurso::create($categoria);
        }

        // Modalidades
        $modalidades = [
            ['nombre' => 'Presencial', 'descripcion' => 'Clases presenciales en el campus', 'activo' => true],
            ['nombre' => 'Virtual', 'descripcion' => 'Clases en línea', 'activo' => true],
            ['nombre' => 'Híbrido', 'descripcion' => 'Combinación de presencial y virtual', 'activo' => true],
        ];

        foreach ($modalidades as $modalidad) {
            Modalidad::create($modalidad);
        }

        // Métodos de Pago
        $metodosPago = [
            ['nombre' => 'Efectivo', 'descripcion' => 'Pago en efectivo en caja', 'activo' => true],
            ['nombre' => 'Transferencia Bancaria', 'descripcion' => 'Transferencia a cuenta institucional', 'activo' => true],
            ['nombre' => 'Depósito Bancario', 'descripcion' => 'Depósito en ventanilla o agente', 'activo' => true],
            ['nombre' => 'Yape', 'descripcion' => 'Pago mediante Yape', 'activo' => true],
            ['nombre' => 'Plin', 'descripcion' => 'Pago mediante Plin', 'activo' => true],
            ['nombre' => 'Tarjeta de Crédito/Débito', 'descripcion' => 'Pago con tarjeta', 'activo' => true],
        ];

        foreach ($metodosPago as $metodo) {
            MetodoPago::create($metodo);
        }
    }
}