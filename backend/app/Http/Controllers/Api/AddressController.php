<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(private readonly AddressService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->service->list($request->query())
        );
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        $address = $this->service->create($request->validated());

        return response()->json($address, 201);
    }

    public function show(Address $address): JsonResponse
    {
        return response()->json($address->loadCount('patients'));
    }

    public function update(UpdateAddressRequest $request, Address $address): JsonResponse
    {
        $address = $this->service->update($address, $request->validated());

        return response()->json($address);
    }

    public function destroy(Address $address): JsonResponse
    {
        $this->service->delete($address);

        return response()->json([
            'message' => 'Endereço excluído com sucesso.',
        ]);
    }
}
