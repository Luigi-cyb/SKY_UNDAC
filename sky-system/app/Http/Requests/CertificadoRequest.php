<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificadoRequest extends FormRequest
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
            'inscripcion_id' => 'required|exists:inscripciones,id',
            'numero_certificado' => 'nullable|string|max:50|unique:certificados,numero_certificado',
            'codigo_verificacion' => 'nullable|string|max:100|unique:certificados,codigo_verificacion',
            'fecha_emision' => 'required|date',
            'nota_final' => 'required|numeric|min:0|max:20',
            'porcentaje_asistencia' => 'required|numeric|min:0|max:100',
            'firma_digital' => 'nullable|string',
            'observaciones' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'inscripcion_id.required' => 'Debe seleccionar una inscripción.',
            'inscripcion_id.exists' => 'La inscripción seleccionada no existe.',
            
            'numero_certificado.max' => 'El número de certificado no debe superar los 50 caracteres.',
            'numero_certificado.unique' => 'Este número de certificado ya existe.',
            
            'codigo_verificacion.max' => 'El código de verificación no debe superar los 100 caracteres.',
            'codigo_verificacion.unique' => 'Este código de verificación ya existe.',
            
            'fecha_emision.required' => 'La fecha de emisión es obligatoria.',
            'fecha_emision.date' => 'Ingrese una fecha válida.',
            
            'nota_final.required' => 'La nota final es obligatoria.',
            'nota_final.numeric' => 'La nota debe ser un valor numérico.',
            'nota_final.min' => 'La nota mínima es 0.',
            'nota_final.max' => 'La nota máxima es 20.',
            
            'porcentaje_asistencia.required' => 'El porcentaje de asistencia es obligatorio.',
            'porcentaje_asistencia.numeric' => 'El porcentaje debe ser un valor numérico.',
            'porcentaje_asistencia.min' => 'El porcentaje mínimo es 0%.',
            'porcentaje_asistencia.max' => 'El porcentaje máximo es 100%.',
            
            'observaciones.max' => 'Las observaciones no deben superar los 500 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'inscripcion_id' => 'inscripción',
            'numero_certificado' => 'número de certificado',
            'codigo_verificacion' => 'código de verificación',
            'fecha_emision' => 'fecha de emisión',
            'nota_final' => 'nota final',
            'porcentaje_asistencia' => 'porcentaje de asistencia',
            'firma_digital' => 'firma digital',
            'observaciones' => 'observaciones',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Verificar que el estudiante cumpla requisitos de aprobación
            if ($this->filled(['inscripcion_id', 'nota_final', 'porcentaje_asistencia'])) {
                $inscripcion = \App\Models\Inscripcion::with('curso')->find($this->inscripcion_id);
                
                if ($inscripcion && $inscripcion->curso) {
                    $curso = $inscripcion->curso;

                    // Verificar nota mínima
                    if ($this->nota_final < $curso->nota_minima_aprobacion) {
                        $validator->errors()->add(
                            'nota_final',
                            "La nota final debe ser al menos {$curso->nota_minima_aprobacion} para aprobar."
                        );
                    }

                    // Verificar asistencia mínima
                    if ($this->porcentaje_asistencia < $curso->porcentaje_asistencia_minima) {
                        $validator->errors()->add(
                            'porcentaje_asistencia',
                            "La asistencia debe ser al menos {$curso->porcentaje_asistencia_minima}% para aprobar."
                        );
                    }

                    // Verificar que el curso haya finalizado
                    if ($curso->fecha_fin > now()) {
                        $validator->errors()->add(
                            'inscripcion_id',
                            'No se puede generar certificado para un curso que aún no ha finalizado.'
                        );
                    }

                    // Verificar que la inscripción esté confirmada
                    if ($inscripcion->estado !== 'confirmada') {
                        $validator->errors()->add(
                            'inscripcion_id',
                            'Solo se pueden generar certificados para inscripciones confirmadas.'
                        );
                    }

                    // Verificar que no exista ya un certificado
                    $certificadoExistente = \App\Models\Certificado::where('inscripcion_id', $this->inscripcion_id)
                        ->exists();

                    if ($certificadoExistente && !$this->route('certificado')) {
                        $validator->errors()->add(
                            'inscripcion_id',
                            'Ya existe un certificado para esta inscripción.'
                        );
                    }
                }
            }
        });
    }
}