<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    public function test_it_fails_if_not_authenticated()
    {
        $this->postJson('api/article')
            ->assertStatus(401);
    }
}
