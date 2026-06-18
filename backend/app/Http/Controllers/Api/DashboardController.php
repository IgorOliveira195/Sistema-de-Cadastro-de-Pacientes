<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AddressService;
use App\Services\PatientService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __invoke(
        PatientService $patientService,
        AddressService $addressService,
    ): JsonResponse {
        return response()->json([
            'patients_total' => $patientService->count(),
            'addresses_total' => $addressService->count(),
        ]);
    }
}
