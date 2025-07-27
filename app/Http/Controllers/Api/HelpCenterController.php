<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpCategory;
use App\Models\HelpTopic;
use App\Http\Resources\HelpCategoryResource;
use App\Http\Resources\HelpTopicResource;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;

class HelpCenterController extends Controller
{
    use ResponseTrait;

    // List all categories with subcategories and topics (public)
    public function index()
    {
        $categories = HelpCategory::with(['subcategories.topics' => function($q) {
            $q->where('is_active', true)->orderBy('order');
        }])->where('is_active', true)->orderBy('order')->get();
        return $this->success(HelpCategoryResource::collection($categories), 'Help center categories fetched successfully');
    }

    // Show a topic by slug (public)
    public function showTopic($slug)
    {
        $topic = HelpTopic::with('subcategory.category', 'author')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
        if (!$topic) {
            return $this->error('Topic not found', 404);
        }
        return $this->success(new HelpTopicResource($topic), 'Help topic fetched successfully');
    }

    // Search topics (public, optimized)
    public function search(Request $request)
    {
        $q = $request->input('q');
        if (!$q) {
            return $this->error('Query is required', 422);
        }
        // Use FULLTEXT if available, fallback to LIKE
        $topics = HelpTopic::where('is_active', true)
            ->where(function($query) use ($q) {
                $query->where('title', 'like', "%$q%")
                      ->orWhere('content', 'like', "%$q%")
                      ->orWhere('slug', 'like', "%$q%")
                      ;
            })
            ->with('subcategory.category', 'author')
            ->orderBy('order')
            ->limit(20)
            ->get();
        return $this->success(HelpTopicResource::collection($topics), 'Search results fetched successfully');
    }
} 