<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\Patient;
use App\Services\AddressService;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AddressServiceTest extends TestCase
{
    public function test_creates_address_with_normalized_data(): void
    {
        $service = app(AddressService::class);

        $address = $service->create([
            'street' => 'Rua Teste',
            'zip_code' => '01310-100',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'sp',
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'zip_code' => '01310100',
            'state' => 'SP',
        ]);
    }

    public function test_cannot_delete_address_with_linked_patients(): void
    {
        $address = Address::factory()->create();
        Patient::factory()->create(['address_id' => $address->id]);

        $this->expectException(ValidationException::class);

        app(AddressService::class)->delete($address);
    }

    public function test_deletes_address_without_patients(): void
    {
        $address = Address::factory()->create();

        app(AddressService::class)->delete($address);

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_counts_addresses(): void
    {
        Address::factory()->count(3)->create();

        $this->assertSame(3, app(AddressService::class)->count());
    }
}
