<?php

namespace App\Http\Controllers\Api;

use App\Models\HelpArticle;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HelpArticleController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $articles = HelpArticle::all();
        return $this->success($articles, 'Help articles retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:help_articles,title|string|max:255',
            'content' => 'required|string',
            'status' => 'sometimes|in:draft,published',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $article = HelpArticle::create([
            'title' => $request->title,
            'content' => $request->content,
            'slug' => Str::slug($request->title),
            'status' => $request->status ?? 'draft',
        ]);

        return $this->success($article, 'Help article created successfully', 201);
    }

    public function show($idOrSlug)
    {
        $article = HelpArticle::where('id', $idOrSlug)
            ->orWhere('slug', $idOrSlug)
            ->firstOrFail();

        return $this->success($article, 'Help article retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'status' => 'sometimes|in:draft,published',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $article = HelpArticle::findOrFail($id);
        
        $article->update([
            'title' => $request->title ?? $article->title,
            'content' => $request->content ?? $article->content,
            'slug' => $request->title ? Str::slug($request->title) : $article->slug,
            'status' => $request->status ?? $article->status,
        ]);

        return $this->success($article, 'Help article updated successfully');
    }

    public function destroy($id)
    {
        $article = HelpArticle::findOrFail($id);
        $article->delete();

        return $this->success(null, 'Help article deleted successfully');
    }
}