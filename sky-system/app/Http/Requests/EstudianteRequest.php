<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EstudianteRequest extends FormRequest
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
        $estudianteId = $this->route('estudiante') ? $this->route('estudiante')->id : null;

        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('estudiantes', 'user_id')->ignore($estudianteId),
            ],
            'codigo_universitario' => [
                'required',
                'string',
                'max:20',
                Rule::unique('estudiantes', 'codigo_universitario')->ignore($estudianteId),
            ],
            'dni' => [
                'required',
                'string',
                'size:8',
                'regex:/^[0-9]{8}$/',
                Rule::unique('estudiantes', 'dni')->ignore($estudianteId),
            ],
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('estudiantes', 'email')->ignore($estudianteId),
            ],
            'telefono' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'direccion' => 'nullable|string|max:200',
            'fecha_nacimiento' => 'required|date|before:-16 years',
            'genero' => 'required|in:M,F,Otro',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'escuela_profesional' => 'nullable|string|max:100',
            'ciclo_actual' => 'nullable|integer|min:1|max:12',
            'activo' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Debe seleccionar un usuario.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'user_id.unique' => 'Este usuario ya está registrado como estudiante.',
            
            'codigo_universitario.required' => 'El código universitario es obligatorio.',
            'codigo_universitario.unique' => 'Este código universitario ya está registrado.',
            'codigo_universitario.max' => 'El código no debe superar los 20 caracteres.',
            
            'dni.required' => 'El DNI es obligatorio.',
            'dni.size' => 'El DNI debe tener 8 dígitos.',
            'dni.regex' => 'El DNI solo debe contener números.',
            'dni.unique' => 'Este DNI ya está registrado.',
            
            'nombres.required' => 'Los nombres son obligatorios.',
            'nombres.max' => 'Los nombres no deben superar los 100 caracteres.',
            
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.max' => 'Los apellidos no deben superar los 100 caracteres.',
            
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'email.max' => 'El correo no debe superar los 100 caracteres.',
            
            'telefono.regex' => 'El formato del teléfono no es válido.',
            'telefono.max' => 'El teléfono no debe superar los 20 caracteres.',
            
            'direccion.max' => 'La dirección no debe superar los 200 caracteres.',
            
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'Ingrese una fecha válida.',
            'fecha_nacimiento.before' => 'Debe ser mayor de 16 años.',
            
            'genero.required' => 'El género es obligatorio.',
            'genero.in' => 'Seleccione un género válido.',
            
            'foto.image' => 'La foto debe ser una imagen.',
            'foto.mimes' => 'La foto debe ser JPG, JPEG o PNG.',
            'foto.max' => 'La foto no debe superar los 2 MB.',
            
            'escuela_profesional.max' => 'La escuela profesional no debe superar los 100 caracteres.',
            
            'ciclo_actual.integer' => 'El ciclo debe ser un número entero.',
            'ciclo_actual.min' => 'El ciclo mínimo es 1.',
            'ciclo_actual.max' => 'El ciclo máximo es 12.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'usuario',
            'codigo_universitario' => 'código universitario',
            'dni' => 'DNI',
            'nombres' => 'nombres',
            'apellidos' => 'apellidos',
            'email' => 'correo electrónico',
            'telefono' => 'teléfono',
            'direccion' => 'dirección',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'genero' => 'género',
            'foto' => 'foto',
            'escuela_profesional' => 'escuela profesional',
            'ciclo_actual' => 'ciclo actual',
        ];
    }
}