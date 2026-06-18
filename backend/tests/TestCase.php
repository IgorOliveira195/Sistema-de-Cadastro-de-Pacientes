<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected ?User $apiUser = null;

    protected function actingAsApiUser(?User $user = null): static
    {
        $this->apiUser = $user ?? User::factory()->create();
        Sanctum::actingAs($this->apiUser);

        return $this;
    }
}
