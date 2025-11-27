<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EvaluacionRequest extends FormRequest
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
    return [
        'curso_id' => 'required|exists:cursos,id',
        'nombre' => 'required|string|max:100',
        'descripcion' => 'nullable|string',
        'tipo' => 'required|in:parcial,final,trabajo,practica,proyecto',
        'peso_porcentaje' => 'required|integer|min:1|max:100',
        'fecha_evaluacion' => 'nullable|date',
        
        // ✅ NUEVOS CAMPOS
        'fecha_disponible' => 'required|date',
        'fecha_limite' => 'required|date|after:fecha_disponible',
        'duracion_minutos' => 'required|integer|min:5|max:300',
        'numero_intentos_permitidos' => 'required|integer|min:1|max:5',
        'mostrar_respuestas_correctas' => 'nullable|boolean',
        'aleatorizar_preguntas' => 'nullable|boolean',
        
        'nota_maxima' => 'required|numeric|min:1|max:20',
        'nota_minima_aprobacion' => 'nullable|numeric|min:0|max:20',
        'criterios_evaluacion' => 'nullable|string',
    ];
}

public function messages(): array
{
    return [
        'curso_id.required' => 'Debe seleccionar un curso',
        'nombre.required' => 'El nombre de la evaluación es obligatorio',
        'tipo.required' => 'Debe seleccionar un tipo de evaluación',
        'peso_porcentaje.required' => 'El peso porcentual es obligatorio',
        'peso_porcentaje.max' => 'El peso no puede exceder 100%',
        
        // ✅ NUEVOS MENSAJES
        'fecha_disponible.required' => 'La fecha disponible es obligatoria',
        'fecha_limite.required' => 'La fecha límite es obligatoria',
        'fecha_limite.after' => 'La fecha límite debe ser posterior a la fecha disponible',
        'duracion_minutos.required' => 'La duración es obligatoria',
        'duracion_minutos.min' => 'La duración mínima es 5 minutos',
        'duracion_minutos.max' => 'La duración máxima es 300 minutos',
        'numero_intentos_permitidos.required' => 'El número de intentos es obligatorio',
        'numero_intentos_permitidos.min' => 'Debe permitir al menos 1 intento',
        'numero_intentos_permitidos.max' => 'El máximo de intentos es 5',
        
        'nota_maxima.required' => 'La nota máxima es obligatoria',
        'nota_maxima.max' => 'La nota máxima no puede exceder 20',
    ];
}

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'curso_id' => 'curso',
            'nombre' => 'nombre',
            'descripcion' => 'descripción',
            'tipo' => 'tipo de evaluación',
            'fecha_evaluacion' => 'fecha de evaluación',
            'peso_porcentaje' => 'peso porcentual',
            'nota_maxima' => 'nota máxima',
            'duracion_minutos' => 'duración en minutos',
            'instrucciones' => 'instrucciones',
            'archivo_evaluacion' => 'archivo de evaluación',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validar que la suma de pesos no supere 100% por curso
            if ($this->filled(['curso_id', 'peso_porcentaje'])) {
                $evaluacionId = $this->route('evaluacion') ? $this->route('evaluacion')->id : null;
                
                $pesoTotal = \App\Models\Evaluacion::where('curso_id', $this->curso_id)
                    ->when($evaluacionId, function ($query) use ($evaluacionId) {
                        return $query->where('id', '!=', $evaluacionId);
                    })
                    ->sum('peso_porcentaje');

                $nuevoTotal = $pesoTotal + $this->peso_porcentaje;

                if ($nuevoTotal > 100) {
                    $pesoDisponible = 100 - $pesoTotal;
                    $validator->errors()->add(
                        'peso_porcentaje',
                        "La suma de pesos excede el 100%. Peso disponible: {$pesoDisponible}%."
                    );
                }
            }

            // Validar que la fecha de evaluación esté dentro del periodo del curso
            if ($this->filled(['curso_id', 'fecha_evaluacion'])) {
                $curso = \App\Models\Curso::find($this->curso_id);
                
                if ($curso) {
                    $fechaEvaluacion = \Carbon\Carbon::parse($this->fecha_evaluacion);
                    
                    if ($fechaEvaluacion->lt($curso->fecha_inicio)) {
                        $validator->errors()->add(
                            'fecha_evaluacion',
                            'La evaluación no puede ser antes del inicio del curso.'
                        );
                    }

                    if ($fechaEvaluacion->gt($curso->fecha_fin)) {
                        $validator->errors()->add(
                            'fecha_evaluacion',
                            'La evaluación no puede ser después del fin del curso.'
                        );
                    }
                }
            }
        });
    }
}