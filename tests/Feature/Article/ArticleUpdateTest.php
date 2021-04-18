<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleUpdateTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->postJson('api/article')
            ->assertStatus(401);
    }
    public function test_if_fails_if_article_cant_be_found()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'PATCH', 'api/article/1', [
            'name' => 'anything'
        ])
            ->assertStatus(404);
    }
    public function test_it_updates_the_article()
    {
        $user = User::factory()->create();

        $article = Article::factory()->create([
            'name' => 'Cracker',
            'user_id' => $user->id
        ]);

        $this->jsonAs($user, 'PATCH', "api/article/{$article->id}", [
                'name' => 'Cracker modified'
        ])->assertJsonFragment([
            'name' => 'Cracker modified'
        ]);

        $this->assertDatabaseHas('articles', [
            'name' => 'Cracker modified'
        ]);
    }

    public function test_it_requires_a_name()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'PATCH', "api/article/1")
            ->assertJsonValidationErrors('name');
    }

    public function test_it_requires_name_to_be_an_string()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'PATCH', 'api/article/1', [
            'name' => 1
        ])->assertJsonValidationErrors(['name']);
    }
}
