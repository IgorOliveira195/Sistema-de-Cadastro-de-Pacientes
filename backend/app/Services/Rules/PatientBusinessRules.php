<?php

namespace App\Services\Rules;

use App\Models\Patient;
use App\Rules\ValidCpf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PatientBusinessRules
{
    public static function validateForPersist(array $data, ?int $patientId = null): void
    {
        $cpf = preg_replace('/\D/', '', (string) ($data['cpf'] ?? ''));
        $cns = preg_replace('/\D/', '', (string) ($data['cns'] ?? ''));

        $validator = Validator::make([
            'address_id' => $data['address_id'] ?? null,
            'name' => $data['name'] ?? null,
            'cpf' => $cpf,
            'cns' => $cns,
            'birth_date' => $data['birth_date'] ?? null,
            'gender' => $data['gender'] ?? null,
            'phone' => $data['phone'] ?? null,
        ], [
            'address_id' => ['required', 'integer', Rule::exists('addresses', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'cpf' => [
                'required',
                'string',
                'size:11',
                'regex:/^\d{11}$/',
                new ValidCpf,
                Rule::unique('patients', 'cpf')->ignore($patientId),
            ],
            'cns' => [
                'required',
                'string',
                'size:15',
                'regex:/^\d{15}$/',
                Rule::unique('patients', 'cns')->ignore($patientId),
            ],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'gender' => ['required', Rule::in(['M', 'F', 'O'])],
            'phone' => ['nullable', 'string', 'regex:/^\d{10,11}$/'],
        ], [
            'cpf.unique' => 'Este CPF já está cadastrado no sistema. (RN-01)',
            'cns.unique' => 'Este CNS já está cadastrado no sistema. (RN-02)',
            'cns.size' => 'O CNS deve conter exatamente 15 dígitos. (RN-06)',
            'cns.regex' => 'O CNS deve conter apenas números. (RN-06)',
            'birth_date.before_or_equal' => 'A data de nascimento não pode ser no futuro. (RN-08)',
            'gender.in' => 'O sexo deve ser M, F ou O. (RN-09)',
            'address_id.exists' => 'O endereço informado não existe.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
