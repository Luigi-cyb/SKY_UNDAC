<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PagoRequest extends FormRequest
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
        $rules = [
            'inscripcion_id' => [
                'required',
                'exists:inscripciones,id',
                // Validar que la inscripción no tenga ya un pago confirmado
                Rule::unique('pagos', 'inscripcion_id')
                    ->where(function ($query) {
                        return $query->where('estado', 'confirmado');
                    })
                    ->ignore($this->pago)
            ],
            'metodo_pago_id' => 'required|exists:metodos_pago,id',
            'monto' => 'required|numeric|min:0|max:9999.99',
            'fecha_pago' => 'required|date|before_or_equal:today',
            'numero_operacion' => 'nullable|string|max:50|unique:pagos,numero_operacion,' . ($this->pago->id ?? 'NULL'),
            'descripcion' => 'nullable|string|max:500',
        ];

        // Si es actualización, no requerir inscripcion_id
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            unset($rules['inscripcion_id']);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'inscripcion_id.required' => 'Debe seleccionar una inscripción.',
            'inscripcion_id.exists' => 'La inscripción seleccionada no existe.',
            'inscripcion_id.unique' => 'Esta inscripción ya tiene un pago confirmado.',
            
            'metodo_pago_id.required' => 'Debe seleccionar un método de pago.',
            'metodo_pago_id.exists' => 'El método de pago seleccionado no es válido.',
            
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un valor numérico.',
            'monto.min' => 'El monto debe ser mayor a 0.',
            'monto.max' => 'El monto no puede exceder S/. 9,999.99.',
            
            'fecha_pago.required' => 'La fecha de pago es obligatoria.',
            'fecha_pago.date' => 'La fecha de pago no es válida.',
            'fecha_pago.before_or_equal' => 'La fecha de pago no puede ser futura.',
            
            'numero_operacion.unique' => 'Este número de operación ya está registrado.',
            'numero_operacion.max' => 'El número de operación no puede exceder 50 caracteres.',
            
            'descripcion.max' => 'La descripción no puede exceder 500 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'inscripcion_id' => 'inscripción',
            'metodo_pago_id' => 'método de pago',
            'monto' => 'monto',
            'fecha_pago' => 'fecha de pago',
            'numero_operacion' => 'número de operación',
            'descripcion' => 'descripción',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Limpiar y formatear datos antes de validar
        if ($this->has('monto')) {
            $this->merge([
                'monto' => floatval(str_replace(',', '', $this->monto)),
            ]);
        }

        if ($this->has('numero_operacion')) {
            $this->merge([
                'numero_operacion' => strtoupper(trim($this->numero_operacion)),
            ]);
        }
    }
}