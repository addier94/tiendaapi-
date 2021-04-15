<?php

namespace Tests\Feature\Article;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleUpdateTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('PATCH', 'api/article/1')
            ->assertStatus(401);
    }

//    public function test_it_fails_if_article_cant_be_found()
//    {
//        $user = User::factory()->create();
//
//        $response = $this->jsonAs($user, 'PATCH', 'api/article/1')
//            ->assertStatus(404);
//    }
}
