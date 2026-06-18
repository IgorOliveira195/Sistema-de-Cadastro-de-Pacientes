<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesPatient;
use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    use ValidatesPatient;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->preparePatientForValidation();
    }

    public function rules(): array
    {
        return $this->patientRules();
    }

    public function attributes(): array
    {
        return $this->patientAttributes();
    }

    public function messages(): array
    {
        return $this->patientMessages();
    }
}
