<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\Patient;
use App\Repositories\PatientRepository;
use App\Services\PatientService;
use Tests\TestCase;

class PatientServiceTest extends TestCase
{
    public function test_creates_patient_with_normalized_documents(): void
    {
        $address = Address::factory()->create();
        $service = app(PatientService::class);

        $patient = $service->create([
            'address_id' => $address->id,
            'name' => 'Paciente Teste',
            'cpf' => '529.982.247-25',
            'cns' => '123 4567 8901 2345',
            'birth_date' => '1990-05-10',
            'gender' => 'M',
            'phone' => '(11) 98765-4321',
        ]);

        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'cpf' => '52998224725',
            'cns' => '123456789012345',
            'phone' => '11987654321',
        ]);
    }

    public function test_filters_patients_by_gender(): void
    {
        $address = Address::factory()->create();
        Patient::factory()->create(['address_id' => $address->id, 'gender' => 'M']);
        Patient::factory()->create(['address_id' => $address->id, 'gender' => 'F']);

        $paginator = app(PatientRepository::class)->paginate([
            'gender' => 'F',
            'per_page' => 15,
        ]);

        $this->assertCount(1, $paginator->items());
        $this->assertSame('F', $paginator->items()[0]->gender);
    }

    public function test_counts_patients(): void
    {
        Patient::factory()->count(2)->create();

        $this->assertSame(2, app(PatientService::class)->count());
    }
}
