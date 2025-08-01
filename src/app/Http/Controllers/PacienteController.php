<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use App\Models\Paciente;
use App\Services\PacienteService;
use Inertia\Inertia;
use Inertia\Response;

final class PacienteController extends Controller
{
    public function __construct(
        private PacienteService $pacienteService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $pacientes = Paciente::latest()->get();

        // Formata os dados usando o service
        $formattedPacientes = $pacientes->map(function (Paciente $paciente) {
            return $this->pacienteService->formatForResponse($paciente);
        });

        return Inertia::render('Pacientes', [
            'pacientes' => $formattedPacientes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Pacientes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePacienteRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = $request->validated();

            $this->pacienteService->create($data);

            return to_route('pacientes.index')
                ->with('success', 'Paciente criado com sucesso!');
        } catch (\InvalidArgumentException $e) {
            return back()
                ->withErrors(['cpf' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente): Response
    {
        $formattedPaciente = $this->pacienteService->formatForResponse($paciente);

        return Inertia::render('Pacientes/Show', [
            'paciente' => $formattedPaciente,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paciente $paciente): Response
    {
        return Inertia::render('Pacientes/Edit', [
            'paciente' => $paciente,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePacienteRequest $request, Paciente $paciente): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = $request->validated();

            $this->pacienteService->update($paciente->id, $data);

            return to_route('pacientes.index')
                ->with('success', 'Paciente atualizado com sucesso!');
        } catch (\InvalidArgumentException $e) {
            return back()
                ->withErrors(['cpf' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente): \Illuminate\Http\RedirectResponse
    {
        $paciente->delete();

        return to_route('pacientes.index');
    }
}
