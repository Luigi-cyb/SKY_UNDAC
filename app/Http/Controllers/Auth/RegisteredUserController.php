<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Estudiante;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'dni' => ['required', 'string', 'size:8', 'unique:estudiantes,dni'],
            'fecha_nacimiento' => ['required', 'date', 'before:-15 years'],
            'sexo' => ['required', 'in:M,F'],
            'telefono' => ['required', 'string', 'size:9'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'nombres.required' => 'Los nombres son obligatorios',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'dni.required' => 'El DNI es obligatorio',
            'dni.size' => 'El DNI debe tener 8 dígitos',
            'dni.unique' => 'Este DNI ya está registrado',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.before' => 'Debes tener al menos 15 años',
            'sexo.required' => 'Selecciona tu sexo',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.size' => 'El teléfono debe tener 9 dígitos',
            'email.unique' => 'Este correo ya está registrado',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        DB::beginTransaction();
        
        try {
            // Crear usuario
            $user = User::create([
                'name' => trim($request->nombres . ' ' . $request->apellidos),
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Asignar rol de Estudiante
            $user->assignRole('Estudiante');

            // Generar código de estudiante (Año-DNI)
            $codigoEstudiante = date('Y') . '-' . $request->dni;

            // Crear registro de estudiante
            Estudiante::create([
                'user_id' => $user->id,
                'dni' => $request->dni,
                'codigo_estudiante' => $codigoEstudiante,
                'nombres' => trim($request->nombres),
                'apellidos' => trim($request->apellidos),
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'telefono' => $request->telefono,
                'correo_institucional' => $request->email,
                'pertenece_eisc' => 1,
                'activo' => 1
            ]);

            DB::commit();

            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false))
                ->with('success', '¡Registro exitoso! Bienvenido al Sistema SKYUNDAC');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear la cuenta: ' . $e->getMessage()]);
        }
    }
}