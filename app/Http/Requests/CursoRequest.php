<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CursoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $cursoId = $this->route('curso') ? $this->route('curso')->id : null;

        return [
            'codigo' => [
                'required',
                'string',
                'max:20',
                Rule::unique('cursos', 'codigo')->ignore($cursoId),
            ],
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:2000',
            'categoria_id' => 'required|exists:categorias_cursos,id',
            'modalidad_id' => 'required|exists:modalidades,id',
            'duracion_horas' => 'required|integer|min:1|max:500',
            'cupo_minimo' => 'required|integer|min:1|max:100',
            'cupo_maximo' => 'required|integer|min:1|max:200',
            'costo' => 'required|numeric|min:0|max:10000',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'nota_minima_aprobacion' => 'required|numeric|min:0|max:20',
            'porcentaje_asistencia_minima' => 'required|integer|min:0|max:100',
            'silabo' => 'nullable|file|mimes:pdf|max:5120',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'objetivos' => 'nullable|string|max:2000',
            'requisitos' => 'nullable|string|max:1000',
            'estado' => 'nullable|in:borrador,convocatoria,en_curso,finalizado,cancelado,archivado',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'codigo.required' => 'El código del curso es obligatorio.',
            'codigo.unique' => 'Este código ya está registrado.',
            'codigo.max' => 'El código no debe superar los 20 caracteres.',
            
            'nombre.required' => 'El nombre del curso es obligatorio.',
            'nombre.max' => 'El nombre no debe superar los 200 caracteres.',
            
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            
            'modalidad_id.required' => 'Debe seleccionar una modalidad.',
            'modalidad_id.exists' => 'La modalidad seleccionada no es válida.',
            
            'duracion_horas.required' => 'La duración en horas es obligatoria.',
            'duracion_horas.integer' => 'La duración debe ser un número entero.',
            'duracion_horas.min' => 'La duración mínima es 1 hora.',
            'duracion_horas.max' => 'La duración máxima es 500 horas.',
            
            'cupo_minimo.required' => 'El cupo mínimo es obligatorio.',
            'cupo_minimo.min' => 'El cupo mínimo debe ser al menos 1.',
            'cupo_minimo.max' => 'El cupo mínimo no puede superar 100.',
            
            'cupo_maximo.required' => 'El cupo máximo es obligatorio.',
            'cupo_maximo.min' => 'El cupo máximo debe ser al menos 1.',
            'cupo_maximo.max' => 'El cupo máximo no puede superar 200.',
            
            'costo.required' => 'El costo del curso es obligatorio.',
            'costo.numeric' => 'El costo debe ser un valor numérico.',
            'costo.min' => 'El costo no puede ser negativo.',
            'costo.max' => 'El costo máximo es S/ 10,000.',
            
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior.',
            
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            
            'nota_minima_aprobacion.required' => 'La nota mínima de aprobación es obligatoria.',
            'nota_minima_aprobacion.min' => 'La nota mínima no puede ser negativa.',
            'nota_minima_aprobacion.max' => 'La nota máxima es 20.',
            
            'porcentaje_asistencia_minima.required' => 'El porcentaje de asistencia mínima es obligatorio.',
            'porcentaje_asistencia_minima.min' => 'El porcentaje mínimo es 0%.',
            'porcentaje_asistencia_minima.max' => 'El porcentaje máximo es 100%.',
            
            'silabo.file' => 'El sílabo debe ser un archivo.',
            'silabo.mimes' => 'El sílabo debe ser un archivo PDF.',
            'silabo.max' => 'El sílabo no debe superar los 5 MB.',
            
            'imagen.image' => 'La imagen debe ser un archivo de imagen.',
            'imagen.mimes' => 'La imagen debe ser JPG, JPEG o PNG.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
            
            'estado.in' => 'El estado seleccionado no es válido.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'codigo' => 'código',
            'nombre' => 'nombre',
            'categoria_id' => 'categoría',
            'modalidad_id' => 'modalidad',
            'duracion_horas' => 'duración en horas',
            'cupo_minimo' => 'cupo mínimo',
            'cupo_maximo' => 'cupo máximo',
            'costo' => 'costo',
            'fecha_inicio' => 'fecha de inicio',
            'fecha_fin' => 'fecha de fin',
            'nota_minima_aprobacion' => 'nota mínima de aprobación',
            'porcentaje_asistencia_minima' => 'porcentaje de asistencia mínima',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validar que cupo_maximo sea mayor que cupo_minimo
            if ($this->filled(['cupo_minimo', 'cupo_maximo'])) {
                if ($this->cupo_maximo < $this->cupo_minimo) {
                    $validator->errors()->add(
                        'cupo_maximo',
                        'El cupo máximo debe ser mayor o igual al cupo mínimo.'
                    );
                }
            }

            // Validar que la fecha de fin sea al menos 1 día después del inicio
            // Validar que la fecha de fin sea al menos 1 día después del inicio
if ($this->filled(['fecha_inicio', 'fecha_fin'])) {
    $inicio = \Carbon\Carbon::parse($this->fecha_inicio);
    $fin = \Carbon\Carbon::parse($this->fecha_fin);
    
    // ✅ CORRECCIÓN: Verificar que fin sea DESPUÉS de inicio
    if ($fin->lte($inicio)) {
        $validator->errors()->add(
            'fecha_fin',
            'La fecha de fin debe ser posterior a la fecha de inicio.'
        );
    }
}
        });
    }
}