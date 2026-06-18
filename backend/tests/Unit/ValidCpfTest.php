<?php

namespace Tests\Unit;

use App\Rules\ValidCpf;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidCpfTest extends TestCase
{
    public function test_accepts_valid_cpf(): void
    {
        $validator = Validator::make(
            ['cpf' => '52998224725'],
            ['cpf' => [new ValidCpf]]
        );

        $this->assertTrue($validator->passes());
    }

    public function test_rejects_cpf_with_all_equal_digits(): void
    {
        $validator = Validator::make(
            ['cpf' => '11111111111'],
            ['cpf' => [new ValidCpf]]
        );

        $this->assertTrue($validator->fails());
    }

    public function test_rejects_cpf_with_invalid_check_digits(): void
    {
        $validator = Validator::make(
            ['cpf' => '12345678901'],
            ['cpf' => [new ValidCpf]]
        );

        $this->assertTrue($validator->fails());
    }

    public function test_rejects_cpf_with_wrong_length(): void
    {
        $validator = Validator::make(
            ['cpf' => '123456789'],
            ['cpf' => [new ValidCpf]]
        );

        $this->assertTrue($validator->fails());
    }
}
