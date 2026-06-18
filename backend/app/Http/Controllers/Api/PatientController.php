<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct(private readonly PatientService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->service->list($request->query())
        );
    }

    public function store(StorePatientRequest $request): JsonResponse
    {
        $patient = $this->service->create($request->validated());

        return response()->json($patient, 201);
    }

    public function show(Patient $patient): JsonResponse
    {
        return response()->json($patient->load('address'));
    }

    public function update(UpdatePatientRequest $request, Patient $patient): JsonResponse
    {
        $patient = $this->service->update($patient, $request->validated());

        return response()->json($patient);
    }

    public function destroy(Patient $patient): JsonResponse
    {
        $this->service->delete($patient);

        return response()->json([
            'message' => 'Paciente excluído com sucesso.',
        ]);
    }
}
