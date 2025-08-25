<?php

namespace Tests\Unit\Http\Requests\Auth;

use App\Http\Requests\Auth\UpdateProfileRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateProfileRequestTest extends TestCase
{
    private function validate(array $data)
    {
        $request = new UpdateProfileRequest();

        return Validator::make($data, $request->rules());
    }

    /** @test */
    public function it_passes_with_valid_name_and_phone_number()
    {
        $validator = $this->validate([
            'name' => 'John Doe',
            'phone_number' => '+841234567890',
        ]);

        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function it_fails_when_name_is_too_long()
    {
        $validator = $this->validate([
            'name' => str_repeat('a', 65),
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_when_phone_number_has_invalid_format()
    {
        $validator = $this->validate([
            'phone_number' => 'abc12345',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone_number', $validator->errors()->toArray());
    }

    /** @test */
    public function it_allows_partial_updates()
    {
        $validator = $this->validate([
            'name' => 'Valid Only',
        ]);

        $this->assertFalse($validator->fails());

        $validator = $this->validate([
            'phone_number' => '+1234567890',
        ]);

        $this->assertFalse($validator->fails());
    }
}
