<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('cpf', 11);
            $table->string('cns', 15);
            $table->date('birth_date');
            $table->enum('gender', ['M', 'F', 'O']);
            $table->string('phone', 11)->nullable();
            $table->foreignId('address_id')->constrained('addresses')->restrictOnDelete();
            $table->timestamps();

            $table->unique('cpf');
            $table->unique('cns');
            $table->index('gender');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
