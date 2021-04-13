<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    public function test_it_can_register()
    {
        $this->postJson(route('register'), [
            'name' => 'Alfredo',
            'email' => 'alfredo@gmail.com',
            'password' => 'testtest',
            'password_confirmation' => 'testtest'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Alfredo',
            'email' => 'alfredo@gmail.com',
        ]);
    }

    public function test_it_requires_a_name()
    {
        $this->postJson('api/register',
        )->assertJsonValidationErrors(['name']);
    }

    public function test_it_name_must_be_a_string()
    {
        $this->postJson('api/register',
            $this->userValidData(['name' => 12345])
        )->assertJsonValidationErrors(['name']);
    }

    public function test_it_name_not_be_greater_than_90_characters()
    {
        $this->postJson('api/register',
            $this->userValidData(['name' => \Str::random(101)])
        )->assertJsonValidationErrors(['name']);
    }

    public function test_it_requires_a_email()
    {
        $this->postJson('api/register')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_email_must_be_valid()
    {
        $this->postJson('api/register',
            $this->userValidData(['email' => 'invalid-email'])
        )->assertJsonValidationErrors('email');
    }

    public function test_it_email_not_be_greater_than_60_characters()
    {
        // generate long email
        $longEmail = strtolower(\Str::random(60) . '@gmail.com');

        $this->postJson('api/register',
            $this->userValidData(['email' => $longEmail])
        )->assertJsonValidationErrors(['email']);
    }

    public function test_it_email_must_be_unique()
    {
        $user = User::factory()->create();

        $this->postJson('api/register',
            $this->userValidData(['email' => $user->email])
        )->assertJsonValidationErrors(['email']);
    }

    public function test_it_password_is_required()
    {
        $this->postJson('api/register',
            $this->userValidData(['password' => ''])
        )->assertJsonValidationErrors(['password']);
    }

    public function test_it_password_must_be_string()
    {
        $this->postJson('api/register',
            $this->userValidData([
                'password' => 58383824,
                'password_confirmation' => 58383824
            ])
        )->assertJsonValidationErrors('password');
    }

    public function test_it_password_not_be_less_than_8_characters()
    {
        $this->postJson('api/register',
            $this->userValidData([
                'password' => 'secret',
                'password_confirmation' => 'secret'
            ])
        )->assertJsonValidationErrors('password');
    }

    public function test_it_password_must_be_confirmed()
    {
        $this->postJson('api/register',
            $this->userValidData([
                'password' => 'testtest',
                'password_confirmation' => 'testtest2'
            ])
        )->assertJsonValidationErrors('password');
    }

    /**
     * @param array $overrides
     * @return array
     */
    protected function userValidData($overrides = []): array
    {
        return array_merge([
            'name' => 'Alfredo',
            'email' => 'alfredo@gmail.com',
            'password' => 'testtest',
            'password_confirmation' => 'testtest',
        ], $overrides);
    }
}
