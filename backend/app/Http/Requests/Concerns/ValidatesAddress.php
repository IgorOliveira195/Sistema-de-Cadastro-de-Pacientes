<?php

namespace App\Http\Requests\Concerns;

trait ValidatesAddress
{
    protected function prepareAddressForValidation(): void
    {
        if ($this->has('zip_code')) {
            $this->merge([
                'zip_code' => preg_replace('/\D/', '', (string) $this->input('zip_code')),
            ]);
        }

        if ($this->has('state')) {
            $this->merge([
                'state' => strtoupper((string) $this->input('state')),
            ]);
        }
    }

    protected function addressRules(): array
    {
        return [
            'street' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'size:8', 'regex:/^\d{8}$/'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'size:2', 'regex:/^[A-Z]{2}$/'],
        ];
    }

    protected function addressAttributes(): array
    {
        return [
            'street' => 'logradouro',
            'zip_code' => 'CEP',
            'neighborhood' => 'bairro',
            'city' => 'cidade',
            'state' => 'estado',
        ];
    }

    protected function addressMessages(): array
    {
        return [
            'street.required' => 'O logradouro é obrigatório.',
            'zip_code.required' => 'O CEP é obrigatório.',
            'zip_code.size' => 'O CEP deve conter exatamente 8 dígitos.',
            'zip_code.regex' => 'O CEP deve conter apenas números.',
            'neighborhood.required' => 'O bairro é obrigatório.',
            'city.required' => 'A cidade é obrigatória.',
            'state.required' => 'O estado é obrigatório.',
            'state.size' => 'O estado deve conter 2 letras (UF).',
            'state.regex' => 'O estado deve ser uma UF válida com 2 letras maiúsculas.',
        ];
    }
}
