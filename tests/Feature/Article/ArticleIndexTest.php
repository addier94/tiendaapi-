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

        Article::factory()->times(10)->create([
            'user_id'=>$user->id
        ]);

        $this->jsonAs($user, 'GET', 'api/article')
            ->assertJsonCount(10);
    }
}
