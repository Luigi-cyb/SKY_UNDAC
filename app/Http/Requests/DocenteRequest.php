<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocenteRequest extends FormRequest
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
        $docenteId = $this->route('docente') ? $this->route('docente')->id : null;

        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('docentes', 'user_id')->ignore($docenteId),
            ],
            'dni' => [
                'required',
                'string',
                'size:8',
                'regex:/^[0-9]{8}$/',
                Rule::unique('docentes', 'dni')->ignore($docenteId),
            ],
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('docentes', 'email')->ignore($docenteId),
            ],
            'telefono' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'especialidad' => 'required|string|max:200',
            'grado_academico' => 'required|in:Bachiller,Licenciado,Magister,Doctor',
            'cv_url' => 'nullable|file|mimes:pdf|max:5120',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'biografia' => 'nullable|string|max:1000',
            'linkedin' => 'nullable|url|max:200',
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
            'user_id.unique' => 'Este usuario ya está registrado como docente.',
            
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
            
            'especialidad.required' => 'La especialidad es obligatoria.',
            'especialidad.max' => 'La especialidad no debe superar los 200 caracteres.',
            
            'grado_academico.required' => 'El grado académico es obligatorio.',
            'grado_academico.in' => 'Seleccione un grado académico válido.',
            
            'cv_url.file' => 'El CV debe ser un archivo.',
            'cv_url.mimes' => 'El CV debe ser un archivo PDF.',
            'cv_url.max' => 'El CV no debe superar los 5 MB.',
            
            'foto.image' => 'La foto debe ser una imagen.',
            'foto.mimes' => 'La foto debe ser JPG, JPEG o PNG.',
            'foto.max' => 'La foto no debe superar los 2 MB.',
            
            'biografia.max' => 'La biografía no debe superar los 1000 caracteres.',
            
            'linkedin.url' => 'Ingrese una URL válida de LinkedIn.',
            'linkedin.max' => 'La URL no debe superar los 200 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'usuario',
            'dni' => 'DNI',
            'nombres' => 'nombres',
            'apellidos' => 'apellidos',
            'email' => 'correo electrónico',
            'telefono' => 'teléfono',
            'especialidad' => 'especialidad',
            'grado_academico' => 'grado académico',
            'cv_url' => 'curriculum vitae',
            'foto' => 'foto',
            'biografia' => 'biografía',
            'linkedin' => 'LinkedIn',
        ];
    }
}