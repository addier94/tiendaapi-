<?php

namespace Tests\Feature\Article;

use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleStoreTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->postJson('api/article')
            ->assertStatus(401);
    }

    public function test_it_can_create_an_article()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/article', [
           'name' => 'cookies'
        ]);

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'name' => 'cookies'
        ]);
    }

    public function test_it_requires_a_name()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/article')
            ->assertJsonValidationErrors('name');
    }

    public function test_it_requires_name_to_be_an_string()
    {
        $user = User::factory()->create();
        $this->jsonAs($user, 'POST', 'api/article', [
            'name' => 1
        ])->assertJsonValidationErrors(['name']);
    }
}
