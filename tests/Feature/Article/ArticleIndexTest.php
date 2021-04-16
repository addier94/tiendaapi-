<?php

namespace Tests\Feature\Article;

use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleIndexTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('GET', 'api/article')
            ->assertStatus(401);
    }

    public function test_it_get_all_articles_own_user()
    {
        $user = User::factory()->create();
        $articles = Article::factory()->times(5)->create(['user_id' => $user->id ]);

        $response = $this->jsonAs($user, 'GET', 'api/article');

        $articles->each(function ($article) use ($response) {
            $response->assertJsonFragment([
               'name' => $article->name
            ]);
        });
    }
}
