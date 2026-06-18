<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Patient;
use Tests\TestCase;

class PatientApiTest extends TestCase
{
    public function test_lists_patients(): void
    {
        Patient::factory()->count(2)->create();

        $response = $this->actingAsApiUser()->getJson('/api/patients');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_creates_patient_with_valid_cpf(): void
    {
        $address = Address::factory()->create();

        $response = $this->actingAsApiUser()->postJson('/api/patients', [
            'address_id' => $address->id,
            'name' => 'Paciente API',
            'cpf' => '52998224725',
            'cns' => '123456789012345',
            'birth_date' => '1990-01-15',
            'gender' => 'M',
            'phone' => '11987654321',
        ]);

        $response->assertCreated()
            ->assertJsonPath('name', 'Paciente API');

        $this->assertDatabaseHas('patients', [
            'cpf' => '52998224725',
            'name' => 'Paciente API',
        ]);
    }

    public function test_rejects_patient_with_invalid_cpf(): void
    {
        $address = Address::factory()->create();

        $response = $this->actingAsApiUser()->postJson('/api/patients', [
            'address_id' => $address->id,
            'name' => 'Paciente Inválido',
            'cpf' => '12345678901',
            'cns' => '123456789012346',
            'birth_date' => '1990-01-15',
            'gender' => 'F',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function test_rejects_duplicate_cpf(): void
    {
        $address = Address::factory()->create();
        Patient::factory()->create([
            'address_id' => $address->id,
            'cpf' => '52998224725',
            'cns' => '123456789012345',
        ]);

        $response = $this->actingAsApiUser()->postJson('/api/patients', [
            'address_id' => $address->id,
            'name' => 'Outro Paciente',
            'cpf' => '52998224725',
            'cns' => '987654321098765',
            'birth_date' => '1985-06-20',
            'gender' => 'M',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cpf']);
    }

    public function test_updates_patient(): void
    {
        $patient = Patient::factory()->create([
            'cpf' => '52998224725',
            'cns' => '123456789012345',
        ]);

        $response = $this->actingAsApiUser()->putJson("/api/patients/{$patient->id}", [
            'address_id' => $patient->address_id,
            'name' => 'Nome Atualizado',
            'cpf' => '52998224725',
            'cns' => '123456789012345',
            'birth_date' => $patient->birth_date->format('Y-m-d'),
            'gender' => $patient->gender,
        ]);

        $response->assertOk()
            ->assertJsonPath('name', 'Nome Atualizado');
    }

    public function test_deletes_patient(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->actingAsApiUser()->deleteJson("/api/patients/{$patient->id}");

        $response->assertOk()
            ->assertJsonPath('message', 'Paciente excluído com sucesso.');

        $this->assertDatabaseMissing('patients', ['id' => $patient->id]);
    }
}
