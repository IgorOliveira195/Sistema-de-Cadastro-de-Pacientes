<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            [
                'address_id' => 1,
                'name' => 'Maria da Silva',
                'cpf' => '12345678901',
                'cns' => '123456789012345',
                'birth_date' => '1985-03-15',
                'gender' => 'F',
                'phone' => '11987654321',
            ],
            [
                'address_id' => 1,
                'name' => 'Pedro Henrique Souza',
                'cpf' => '23456789012',
                'cns' => '234567890123456',
                'birth_date' => '1992-08-20',
                'gender' => 'M',
                'phone' => '11912345678',
            ],
            [
                'address_id' => 2,
                'name' => 'João Carlos Oliveira',
                'cpf' => '98765432100',
                'cns' => '987654321098765',
                'birth_date' => '1972-07-22',
                'gender' => 'M',
            ],
            [
                'address_id' => 2,
                'name' => 'Fernanda Lima Costa',
                'cpf' => '87654321098',
                'cns' => '876543210987654',
                'birth_date' => '1988-12-05',
                'gender' => 'F',
                'phone' => '31999887766',
            ],
            [
                'address_id' => 3,
                'name' => 'Ana Paula Rodrigues',
                'cpf' => '45678912300',
                'cns' => '456789123004567',
                'birth_date' => '1990-11-08',
                'gender' => 'F',
            ],
            [
                'address_id' => 3,
                'name' => 'Ricardo Almeida',
                'cpf' => '56789123045',
                'cns' => '567891230456789',
                'birth_date' => '1975-04-18',
                'gender' => 'M',
                'phone' => '41988776655',
            ],
            [
                'address_id' => 4,
                'name' => 'Carlos E. Mendes',
                'cpf' => '32165498700',
                'cns' => '321654987003216',
                'birth_date' => '1968-01-30',
                'gender' => 'M',
            ],
            [
                'address_id' => 4,
                'name' => 'Juliana Martins',
                'cpf' => '65498732100',
                'cns' => '654987321006549',
                'birth_date' => '1995-06-25',
                'gender' => 'F',
                'phone' => '21976543210',
            ],
            [
                'address_id' => 5,
                'name' => 'Lucas Ferreira',
                'cpf' => '78912345600',
                'cns' => '789123456007891',
                'birth_date' => '2000-02-14',
                'gender' => 'M',
            ],
            [
                'address_id' => 5,
                'name' => 'Beatriz Santos',
                'cpf' => '89123456078',
                'cns' => '891234560788912',
                'birth_date' => '1983-09-10',
                'gender' => 'F',
                'phone' => '81955443322',
            ],
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }
    }
}
