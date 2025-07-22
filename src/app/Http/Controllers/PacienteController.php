<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $pacientes = Paciente::latest()->get();

        return Inertia::render('Pacientes', [
            'pacientes' => $pacientes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'cpf' => 'required|string|size:11|unique:pacientes,cpf', // CPF deve ter exatamente 11 dígitos
            'role' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'motherName' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:pacientes,email',
        ]);

        // Ajustar o nome do campo para corresponder à migration
        $validated['mother_name'] = $validated['motherName'];
        unset($validated['motherName']);

        Paciente::create($validated);

        return to_route('pacientes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paciente $paciente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paciente $paciente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();

        return to_route('pacientes.index');
    }
}
