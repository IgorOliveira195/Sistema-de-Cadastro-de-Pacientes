<?php

namespace App\Services\Rules;

use App\Models\Address;
use App\Rules\ValidCpf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AddressBusinessRules
{
    public static function validateForPersist(array $data): void
    {
        $validator = Validator::make($data, [
            'street' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'size:8', 'regex:/^\d{8}$/'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'size:2', 'regex:/^[A-Z]{2}$/'],
        ], [
            'zip_code.size' => 'O CEP deve conter exatamente 8 dígitos. (RN-07)',
            'zip_code.regex' => 'O CEP deve conter apenas números. (RN-07)',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public static function ensureCanDelete(Address $address): void
    {
        if ($address->patients()->exists()) {
            throw ValidationException::withMessages([
                'address' => ['Não é possível excluir um endereço com pacientes vinculados. (RN-03)'],
            ]);
        }
    }
}
