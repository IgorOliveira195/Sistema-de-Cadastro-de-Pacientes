<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'address_id' => Address::factory(),
            'name' => fake()->name(),
            'cpf' => $this->generateValidCpf(),
            'cns' => fake()->unique()->numerify('###############'),
            'birth_date' => fake()->dateTimeBetween('-80 years', '-1 year')->format('Y-m-d'),
            'gender' => fake()->randomElement(['M', 'F', 'O']),
            'phone' => fake()->optional()->numerify('###########'),
        ];
    }

    private function generateValidCpf(): string
    {
        $digits = [];

        for ($i = 0; $i < 9; $i++) {
            $digits[] = random_int(0, 9);
        }

        $digits[] = $this->calculateCpfDigit($digits, 10);
        $digits[] = $this->calculateCpfDigit($digits, 11);

        return implode('', $digits);
    }

    private function calculateCpfDigit(array $digits, int $factor): int
    {
        $sum = 0;

        foreach ($digits as $index => $digit) {
            $sum += $digit * ($factor - $index);
        }

        $remainder = ($sum * 10) % 11;

        return $remainder === 10 ? 0 : $remainder;
    }
}
