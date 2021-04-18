<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\CreateArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Http\Resources\Article\ArticleResource;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = auth()->user()->articles()->get();
        return ArticleResource::collection($articles);
    }

    public function store(CreateArticleRequest $request)
    {
        $article = Article::where('id', $request->id)
            ->where(function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->first();

        if($article === null) {
            $article = auth()->user()->articles()->make(['name' => $request->name]);
        }

        $article->save();
        return new ArticleResource($article);
    }

    public function update(UpdateArticleRequest $request, $id)
    {
        $article = Article::findOrfail($id);

        $article->name = $request->name;
        $article->save();
//        return new ArticleResource($article);
        return ArticleResource::make($article);
    }
}
