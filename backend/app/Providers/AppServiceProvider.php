<?php

namespace App\Providers;

use App\Repositories\AddressRepository;
use App\Repositories\PatientRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AddressRepository::class);
        $this->app->singleton(PatientRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
