<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street', 255);
            $table->string('zip_code', 8);
            $table->string('neighborhood', 255);
            $table->string('city', 255);
            $table->char('state', 2);
            $table->timestamps();

            $table->index('zip_code');
            $table->index('city');
            $table->index('state');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
