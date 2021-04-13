<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_it_requires_an_email()
    {
        $this->postJson('api/login')
            ->assertJsonValidationErrors('email');
    }

    public function test_it_requires_a_password()
    {
        $this->postJson('api/login')
            ->assertJsonValidationErrors('password');
    }

    public function test_it_returns_a_validation_error_if_credentials_dont_match()
    {
        $user = User::factory()->create();

        $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'nope'
        ])->assertJsonValidationErrors('email');
    }
}
