<?php

namespace App\Repositories;

use App\Models\Patient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PatientRepository
{
    private const SORTABLE_COLUMNS = [
        'id', 'name', 'cpf', 'cns', 'birth_date', 'gender', 'created_at',
    ];

    public function paginate(array $filters = []): LengthAwarePaginator
    {
        $query = $this->buildQuery($filters);

        $this->applySorting($query, $filters);

        return $query->paginate((int) ($filters['per_page'] ?? 15));
    }

    public function findOrFail(int $id): Patient
    {
        return Patient::with('address')->findOrFail($id);
    }

    public function create(array $data): Patient
    {
        return Patient::create($data);
    }

    public function update(Patient $patient, array $data): Patient
    {
        $patient->update($data);

        return $patient->fresh();
    }

    public function delete(Patient $patient): void
    {
        $patient->delete();
    }

    public function count(): int
    {
        return Patient::count();
    }

    private function buildQuery(array $filters): Builder
    {
        return Patient::query()
            ->with('address')
            ->search($filters['search'] ?? null)
            ->byName($filters['name'] ?? null)
            ->byCpf($filters['cpf'] ?? null)
            ->byCns($filters['cns'] ?? null)
            ->byGender($filters['gender'] ?? null)
            ->byAddress(isset($filters['address_id']) ? (int) $filters['address_id'] : null);
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
