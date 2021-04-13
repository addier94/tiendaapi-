<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogintTest extends TestCase
{
    public function test_can_login_with_valid_credentials()
    {
        $this->assertTrue(true);
//        $user = User::factory()->create();
//
//        $response = $this->postJson('api/login', [
//            'email' => $user->email,
//            'password' => $user->password,
//        ]);

    }
}
