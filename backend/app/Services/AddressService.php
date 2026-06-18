<?php

namespace App\Services;

use App\Models\Address;
use App\Repositories\AddressRepository;
use App\Services\Rules\AddressBusinessRules;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class AddressService
{
    public function __construct(private readonly AddressRepository $repository)
    {
    }

    public function list(array $filters = []): LengthAwarePaginator
    {
        return $this->repository->paginate($filters);
    }

    public function find(int $id): Address
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Address
    {
        $payload = $this->normalize($data);
        AddressBusinessRules::validateForPersist($payload);

        $address = $this->repository->create($payload);

        $this->logAction('create', $address);

        return $address->loadCount('patients');
    }

    public function update(Address $address, array $data): Address
    {
        $payload = $this->normalize($data);
        AddressBusinessRules::validateForPersist($payload);

        $address = $this->repository->update($address, $payload);

        $this->logAction('update', $address);

        return $address->loadCount('patients');
    }

    public function delete(Address $address): void
    {
        AddressBusinessRules::ensureCanDelete($address);

        $this->logAction('delete', $address);

        $this->repository->delete($address);
    }

    public function count(): int
    {
        return $this->repository->count();
    }

    private function normalize(array $data): array
    {
        return [
            'street' => $data['street'],
            'zip_code' => preg_replace('/\D/', '', $data['zip_code']),
            'neighborhood' => $data['neighborhood'],
            'city' => $data['city'],
            'state' => strtoupper($data['state']),
        ];
    }

    private function logAction(string $action, Address $address): void
    {
        Log::channel('daily')->info('Ação em endereço', [
            'action' => $action,
            'entity' => 'address',
            'address_id' => $address->id,
            'city' => $address->city,
            'state' => $address->state,
            'zip_code' => $address->zip_code,
        ]);
    }
}
