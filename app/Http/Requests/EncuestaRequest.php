<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EncuestaRequest extends FormRequest
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
        $encuestaId = $this->route('encuesta') ? $this->route('encuesta')->id : null;
        $tieneRespuestas = false;

        if ($encuestaId) {
            $tieneRespuestas = \App\Models\Encuesta::find($encuestaId)
                ->respuestas()
                ->count() > 0;
        }

        $rules = [
            'curso_id' => 'required|exists:cursos,id',
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:1000',
            'tipo' => 'required|in:satisfaccion,evaluacion_docente,evaluacion_curso,general',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'activa' => 'nullable|boolean',
            'obligatoria' => 'nullable|boolean',
            'mostrar_resultados' => 'nullable|boolean',
        ];

        // Solo validar preguntas si no tiene respuestas (al crear o editar sin respuestas)
        if (!$tieneRespuestas) {
            $rules['preguntas'] = 'required|json';
            $rules['anonima'] = 'required|boolean';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'curso_id.required' => 'Debe seleccionar un curso.',
            'curso_id.exists' => 'El curso seleccionado no existe.',
            
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no debe superar los 200 caracteres.',
            
            'descripcion.max' => 'La descripción no debe superar los 1000 caracteres.',
            
            'tipo.required' => 'Debe seleccionar un tipo de encuesta.',
            'tipo.in' => 'El tipo de encuesta no es válido.',
            
            'preguntas.required' => 'Debe agregar al menos una pregunta.',
            'preguntas.json' => 'El formato de preguntas no es válido.',
            
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'Ingrese una fecha válida.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior.',
            
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'Ingrese una fecha válida.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            
            'anonima.required' => 'Debe especificar si la encuesta es anónima.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'curso_id' => 'curso',
            'titulo' => 'título',
            'descripcion' => 'descripción',
            'tipo' => 'tipo de encuesta',
            'preguntas' => 'preguntas',
            'fecha_inicio' => 'fecha de inicio',
            'fecha_fin' => 'fecha de fin',
            'anonima' => 'encuesta anónima',
            'activa' => 'estado activo',
            'obligatoria' => 'obligatoria',
            'mostrar_resultados' => 'mostrar resultados',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validar estructura de preguntas
            if ($this->filled('preguntas')) {
                $preguntas = json_decode($this->preguntas, true);
                
                if (!is_array($preguntas) || empty($preguntas)) {
                    $validator->errors()->add(
                        'preguntas',
                        'Debe agregar al menos una pregunta válida.'
                    );
                } else {
                    foreach ($preguntas as $index => $pregunta) {
                        $numero = $index + 1;
                        
                        // Validar texto de pregunta
                        if (!isset($pregunta['texto']) || empty($pregunta['texto'])) {
                            $validator->errors()->add(
                                'preguntas',
                                "La pregunta #{$numero} debe tener un texto."
                            );
                        }

                        // Validar tipo de pregunta
                        if (!isset($pregunta['tipo']) || !in_array($pregunta['tipo'], ['texto', 'escala', 'opcion_multiple'])) {
                            $validator->errors()->add(
                                'preguntas',
                                "La pregunta #{$numero} tiene un tipo no válido."
                            );
                        }

                        // Validar escalas
                        if (isset($pregunta['tipo']) && $pregunta['tipo'] === 'escala') {
                            if (!isset($pregunta['escala_min']) || !isset($pregunta['escala_max'])) {
                                $validator->errors()->add(
                                    'preguntas',
                                    "La pregunta #{$numero} (escala) debe tener valores mínimo y máximo."
                                );
                            }
                        }

                        // Validar opciones múltiples
                        if (isset($pregunta['tipo']) && $pregunta['tipo'] === 'opcion_multiple') {
                            if (!isset($pregunta['opciones']) || !is_array($pregunta['opciones']) || empty($pregunta['opciones'])) {
                                $validator->errors()->add(
                                    'preguntas',
                                    "La pregunta #{$numero} (opción múltiple) debe tener al menos una opción."
                                );
                            }
                        }
                    }
                }
            }

            // Validar que las fechas estén dentro del periodo del curso
            if ($this->filled(['curso_id', 'fecha_inicio', 'fecha_fin'])) {
                $curso = \App\Models\Curso::find($this->curso_id);
                
                if ($curso) {
                    $inicio = \Carbon\Carbon::parse($this->fecha_inicio);
                    $fin = \Carbon\Carbon::parse($this->fecha_fin);

                    if ($inicio->lt($curso->fecha_inicio)) {
                        $validator->errors()->add(
                            'fecha_inicio',
                            'La encuesta no puede iniciar antes del inicio del curso.'
                        );
                    }

                    // Permitir que la encuesta continúe hasta 30 días después del curso
                    if ($fin->gt($curso->fecha_fin->addDays(30))) {
                        $validator->errors()->add(
                            'fecha_fin',
                            'La encuesta no debe extenderse más de 30 días después del fin del curso.'
                        );
                    }
                }
            }

            // Validar duración mínima de la encuesta
            if ($this->filled(['fecha_inicio', 'fecha_fin'])) {
                $inicio = \Carbon\Carbon::parse($this->fecha_inicio);
                $fin = \Carbon\Carbon::parse($this->fecha_fin);
                
                if ($fin->diffInDays($inicio) < 1) {
                    $validator->errors()->add(
                        'fecha_fin',
                        'La encuesta debe estar disponible al menos 1 día.'
                    );
                }
            }
        });
    }
}