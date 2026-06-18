<?php

namespace App\Http\Requests\Concerns;

use App\Rules\ValidCpf;
use Illuminate\Validation\Rule;

trait ValidatesPatient
{
    protected function preparePatientForValidation(): void
    {
        $merge = [];

        if ($this->has('cpf')) {
            $merge['cpf'] = preg_replace('/\D/', '', (string) $this->input('cpf'));
        }

        if ($this->has('cns')) {
            $merge['cns'] = preg_replace('/\D/', '', (string) $this->input('cns'));
        }

        if ($this->has('phone') && $this->input('phone') !== null) {
            $merge['phone'] = preg_replace('/\D/', '', (string) $this->input('phone'));
        }

        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    protected function patientRules(?int $patientId = null): array
    {
        return [
            'address_id' => ['required', 'integer', 'exists:addresses,id'],
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
        ];
    }

    protected function patientAttributes(): array
    {
        return [
            'address_id' => 'endereço',
            'name' => 'nome',
            'cpf' => 'CPF',
            'cns' => 'CNS',
            'birth_date' => 'data de nascimento',
            'gender' => 'sexo',
            'phone' => 'telefone',
        ];
    }

    protected function patientMessages(): array
    {
        return [
            'address_id.required' => 'O endereço é obrigatório.',
            'address_id.exists' => 'O endereço informado não existe.',
            'name.required' => 'O nome é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.size' => 'O CPF deve conter exatamente 11 dígitos.',
            'cpf.regex' => 'O CPF deve conter apenas números.',
            'cpf.unique' => 'Este CPF já está cadastrado no sistema.',
            'cns.required' => 'O CNS é obrigatório.',
            'cns.size' => 'O CNS deve conter exatamente 15 dígitos.',
            'cns.regex' => 'O CNS deve conter apenas números.',
            'cns.unique' => 'Este CNS já está cadastrado no sistema.',
            'birth_date.required' => 'A data de nascimento é obrigatória.',
            'birth_date.date' => 'A data de nascimento deve ser uma data válida.',
            'birth_date.before_or_equal' => 'A data de nascimento não pode ser no futuro.',
            'gender.required' => 'O sexo é obrigatório.',
            'gender.in' => 'O sexo deve ser M, F ou O.',
            'phone.regex' => 'O telefone deve conter DDD e número (10 ou 11 dígitos).',
        ];
    }
}
