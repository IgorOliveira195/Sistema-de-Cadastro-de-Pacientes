<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Patient;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
    public function test_returns_totals_for_authenticated_user(): void
    {
        $addresses = Address::factory()->count(2)->create();

        Patient::factory()->create(['address_id' => $addresses[0]->id]);
        Patient::factory()->create(['address_id' => $addresses[1]->id]);
        Patient::factory()->create(['address_id' => $addresses[0]->id]);

        $response = $this->actingAsApiUser()->getJson('/api/dashboard');

        $response->assertOk()
            ->assertJson([
                'patients_total' => 3,
                'addresses_total' => 2,
            ]);
    }
}
