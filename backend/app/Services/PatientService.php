<?php

namespace App\Services;

use App\Models\Patient;
use App\Repositories\PatientRepository;
use App\Services\Rules\PatientBusinessRules;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class PatientService
{
    public function __construct(private readonly PatientRepository $repository)
    {
    }

    public function list(array $filters = []): LengthAwarePaginator
    {
        return $this->repository->paginate($filters);
    }

    public function find(int $id): Patient
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Patient
    {
        $payload = $this->normalize($data);
        PatientBusinessRules::validateForPersist($payload);

        $patient = $this->repository->create($payload);

        $this->logAction('create', $patient);

        return $patient->load('address');
    }

    public function update(Patient $patient, array $data): Patient
    {
        $payload = $this->normalize($data);
        PatientBusinessRules::validateForPersist($payload, $patient->id);

        $patient = $this->repository->update($patient, $payload);

        $this->logAction('update', $patient);

        return $patient->load('address');
    }

    public function delete(Patient $patient): void
    {
        $this->logAction('delete', $patient);

        $this->repository->delete($patient);
    }

    public function count(): int
    {
        return $this->repository->count();
    }

    private function normalize(array $data): array
    {
        return [
            'address_id' => $data['address_id'],
            'name' => $data['name'],
            'cpf' => preg_replace('/\D/', '', $data['cpf']),
            'cns' => preg_replace('/\D/', '', $data['cns']),
            'birth_date' => $data['birth_date'],
            'gender' => $data['gender'],
            'phone' => isset($data['phone']) && $data['phone'] !== null
                ? preg_replace('/\D/', '', $data['phone'])
                : null,
        ];
    }

    private function logAction(string $action, Patient $patient): void
    {
        Log::channel('daily')->info('Ação em paciente', [
            'action' => $action,
            'entity' => 'patient',
            'patient_id' => $patient->id,
            'cpf' => $patient->cpf,
            'cns' => $patient->cns,
            'address_id' => $patient->address_id,
        ]);
    }
}
