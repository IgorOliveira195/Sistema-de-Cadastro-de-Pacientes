<?php

namespace App\Repositories;

use App\Models\Address;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class AddressRepository
{
    private const SORTABLE_COLUMNS = [
        'id', 'street', 'zip_code', 'neighborhood', 'city', 'state', 'created_at',
    ];

    public function paginate(array $filters = []): LengthAwarePaginator
    {
        $query = $this->buildQuery($filters);

        $this->applySorting($query, $filters);

        return $query->paginate((int) ($filters['per_page'] ?? 15));
    }

    public function findOrFail(int $id): Address
    {
        return Address::withCount('patients')->findOrFail($id);
    }

    public function create(array $data): Address
    {
        return Address::create($data);
    }

    public function update(Address $address, array $data): Address
    {
        $address->update($data);

        return $address->fresh();
    }

    public function delete(Address $address): void
    {
        $address->delete();
    }

    public function hasPatients(Address $address): bool
    {
        return $address->patients()->exists();
    }

    public function count(): int
    {
        return Address::count();
    }

    private function buildQuery(array $filters): Builder
    {
        return Address::query()
            ->withCount('patients')
            ->search($filters['search'] ?? null)
            ->byCity($filters['city'] ?? null)
            ->byState($filters['state'] ?? null)
            ->byZipCode($filters['zip_code'] ?? null);
    }

    private function applySorting(Builder $query, array $filters): void
    {
        $sortBy = $filters['sort_by'] ?? 'id';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        if (! in_array($sortBy, self::SORTABLE_COLUMNS, true)) {
            $sortBy = 'id';
        }

        $query->orderBy($sortBy, $sortDir);
    }
}
