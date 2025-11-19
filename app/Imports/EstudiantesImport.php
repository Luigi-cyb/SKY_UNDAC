<?php

namespace App\Imports;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class EstudiantesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        DB::beginTransaction();

        try {
            // Crear usuario
            $user = User::create([
                'name' => trim($row['nombres']) . ' ' . trim($row['apellidos']),
                'email' => trim($row['correo_institucional']),
                'password' => Hash::make($row['dni']),
                'email_verified_at' => now(),
            ]);

            // Asignar rol Estudiante
            $user->assignRole('Estudiante');

            // Crear estudiante
            $estudiante = new Estudiante([
                'user_id' => $user->id,
                'dni' => $row['dni'],
                'codigo_estudiante' => $row['codigo'] ?? null,
                'nombres' => trim($row['nombres']),
                'apellidos' => trim($row['apellidos']),
                'correo_institucional' => trim($row['correo_institucional']),
                'correo_personal' => $row['correo_personal'] ?? null,
                'telefono' => $row['telefono'] ?? null,
                'direccion' => $row['direccion'] ?? null,
                'pertenece_eisc' => isset($row['eisc']) && (strtolower(trim($row['eisc'])) === 'si' || strtolower(trim($row['eisc'])) === 'sí') ? true : false,
                'ciclo_academico' => $row['ciclo'] ?? null,
                'activo' => true,
            ]);

            $estudiante->save();
            DB::commit();
            
            return $estudiante;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error importando estudiante: ' . $e->getMessage());
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'dni' => 'required|digits:8|unique:estudiantes,dni',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'correo_institucional' => 'required|email|unique:users,email',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'dni.required' => 'El DNI es obligatorio',
            'dni.digits' => 'El DNI debe tener 8 dígitos',
            'dni.unique' => 'El DNI ya está registrado',
            'correo_institucional.required' => 'El correo es obligatorio',
            'correo_institucional.unique' => 'El correo ya está registrado',
        ];
    }
}