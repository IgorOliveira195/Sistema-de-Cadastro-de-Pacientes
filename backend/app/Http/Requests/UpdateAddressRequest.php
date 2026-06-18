<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesAddress;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    use ValidatesAddress;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareAddressForValidation();
    }

    public function rules(): array
    {
        return $this->addressRules();
    }

    public function attributes(): array
    {
        return $this->addressAttributes();
    }

    public function messages(): array
    {
        return $this->addressMessages();
    }
}
