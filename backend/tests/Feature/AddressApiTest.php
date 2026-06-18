<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Patient;
use Tests\TestCase;

class AddressApiTest extends TestCase
{
    public function test_lists_addresses_with_pagination(): void
    {
        Address::factory()->count(3)->create();

        $response = $this->actingAsApiUser()
            ->getJson('/api/addresses?per_page=2');

        $response->assertOk()
            ->assertJsonStructure([
                'data',
                'current_page',
                'last_page',
                'per_page',
                'total',
            ])
            ->assertJsonCount(2, 'data');
    }

    public function test_filters_addresses_by_state(): void
    {
        Address::factory()->create(['state' => 'SP', 'city' => 'São Paulo']);
        Address::factory()->create(['state' => 'RJ', 'city' => 'Rio de Janeiro']);

        $response = $this->actingAsApiUser()
            ->getJson('/api/addresses?state=SP');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.state', 'SP');
    }

    public function test_creates_address(): void
    {
        $response = $this->actingAsApiUser()->postJson('/api/addresses', [
            'street' => 'Rua Nova',
            'zip_code' => '01310100',
            'neighborhood' => 'Bela Vista',
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $response->assertCreated()
            ->assertJsonPath('street', 'Rua Nova');

        $this->assertDatabaseHas('addresses', [
            'street' => 'Rua Nova',
            'zip_code' => '01310100',
        ]);
    }

    public function test_rejects_invalid_address_payload(): void
    {
        $response = $this->actingAsApiUser()->postJson('/api/addresses', [
            'street' => '',
            'zip_code' => '123',
            'neighborhood' => '',
            'city' => '',
            'state' => 'SPA',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function test_updates_address(): void
    {
        $address = Address::factory()->create();

        $response = $this->actingAsApiUser()->putJson("/api/addresses/{$address->id}", [
            'street' => 'Rua Atualizada',
            'zip_code' => $address->zip_code,
            'neighborhood' => $address->neighborhood,
            'city' => $address->city,
            'state' => $address->state,
        ]);

        $response->assertOk()
            ->assertJsonPath('street', 'Rua Atualizada');
    }

    public function test_deletes_address_without_patients(): void
    {
        $address = Address::factory()->create();

        $response = $this->actingAsApiUser()->deleteJson("/api/addresses/{$address->id}");

        $response->assertOk()
            ->assertJsonPath('message', 'Endereço excluído com sucesso.');

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_cannot_delete_address_with_patients(): void
    {
        $address = Address::factory()->create();
        Patient::factory()->create(['address_id' => $address->id]);

        $response = $this->actingAsApiUser()->deleteJson("/api/addresses/{$address->id}");

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['address']);
    }
}
