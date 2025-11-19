<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Curso;
use App\Models\Inscripcion;

class InscripcionRequest extends FormRequest
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
            'estudiante_id' => 'required|exists:estudiantes,id',
            'fecha_inscripcion' => 'required|date',
            'estado' => 'nullable|in:provisional,confirmada,cancelada,retirada',
            'notas' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'curso_id.required' => 'Debe seleccionar un curso.',
            'curso_id.exists' => 'El curso seleccionado no existe.',
            
            'estudiante_id.required' => 'Debe seleccionar un estudiante.',
            'estudiante_id.exists' => 'El estudiante seleccionado no existe.',
            
            'fecha_inscripcion.required' => 'La fecha de inscripción es obligatoria.',
            'fecha_inscripcion.date' => 'Ingrese una fecha válida.',
            
            'estado.in' => 'El estado seleccionado no es válido.',
            
            'notas.max' => 'Las notas no deben superar los 500 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'curso_id' => 'curso',
            'estudiante_id' => 'estudiante',
            'fecha_inscripcion' => 'fecha de inscripción',
            'estado' => 'estado',
            'notas' => 'notas',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Verificar cupos disponibles
            if ($this->filled(['curso_id', 'estudiante_id'])) {
                $curso = Curso::find($this->curso_id);
                
                if ($curso) {
                    // Verificar que el curso esté en convocatoria
                    if (!in_array($curso->estado, ['convocatoria', 'en_curso'])) {
                        $validator->errors()->add(
                            'curso_id',
                            'El curso no está disponible para inscripciones.'
                        );
                    }

                    // Verificar cupos disponibles (solo para nuevas inscripciones)
                    if (!$this->route('inscripcion')) {
                        $inscritosConfirmados = Inscripcion::where('curso_id', $curso->id)
                            ->where('estado', 'confirmada')
                            ->count();

                        if ($inscritosConfirmados >= $curso->cupo_maximo) {
                            $validator->errors()->add(
                                'curso_id',
                                'El curso ha alcanzado el cupo máximo de estudiantes.'
                            );
                        }
                    }

                    // Verificar que el estudiante no esté ya inscrito
                    if (!$this->route('inscripcion')) {
                        $yaInscrito = Inscripcion::where('curso_id', $this->curso_id)
                            ->where('estudiante_id', $this->estudiante_id)
                            ->whereIn('estado', ['provisional', 'confirmada'])
                            ->exists();

                        if ($yaInscrito) {
                            $validator->errors()->add(
                                'estudiante_id',
                                'El estudiante ya está inscrito en este curso.'
                            );
                        }
                    }

                    // Verificar fechas del curso
                    if ($curso->fecha_inicio < now()) {
                        $validator->errors()->add(
                            'curso_id',
                            'No se puede inscribir en un curso que ya inició.'
                        );
                    }
                }
            }
        });
    }
}