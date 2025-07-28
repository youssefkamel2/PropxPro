<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpCategory;
use App\Models\HelpTopic;
use App\Http\Resources\HelpCategoryResource;
use App\Http\Resources\HelpTopicResource;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\HelpSubcategory;
use App\Http\Resources\HelpSubcategoryResource;

class HelpCenterController extends Controller
{
    use ResponseTrait;

    // List all categories with subcategories and topics (public)
    public function index()
    {
        $categories = HelpCategory::with([
            'subcategories.topics' => function ($q) {
                $q->where('is_active', true)->orderBy('order');
            }
        ])->where('is_active', true)->orderBy('order')->get();
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
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%$q%")
                    ->orWhere('content', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%")
                ;
            })
            ->with('subcategory.category', 'author')
            ->orderBy('order')
            ->limit(20)
            ->get()
            ->map(function ($topic) {
                return [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'slug' => $topic->slug,
                    'content' => $topic->content,
                    'order' => $topic->order,
                    'is_active' => $topic->is_active,
                    'created_at' => $topic->created_at,
                    'updated_at' => $topic->updated_at,
                    'subcategory' => [
                        'id' => $topic->subcategory->id,
                        'name' => $topic->subcategory->name,
                    ],
                    'category' => [
                        'id' => $topic->subcategory->category->id,
                        'name' => $topic->subcategory->category->name,
                    ],
                    'author' => $topic->author ? [
                        'id' => $topic->author->id,
                        'name' => $topic->author->name,
                    ] : null,
                ];
            });
        return $this->success($topics, 'Search results fetched successfully');
    }

    // Get category by ID (public)
    public function getCategory($id)
    {
        $category = HelpCategory::with([
            'subcategories.topics' => function ($q) {
                $q->where('is_active', true)->orderBy('order');
            }
        ])->where('is_active', true)->find($id);

        if (!$category) {
            return $this->error('Category not found', 404);
        }

        return $this->success(new HelpCategoryResource($category), 'Category fetched successfully');
    }

    // Get subcategory by ID (public)
    public function getSubcategory($id)
    {
        $subcategory = HelpSubcategory::with([
            'topics' => function ($q) {
                $q->where('is_active', true)->orderBy('order');
            },
            'category'
        ])->where('is_active', true)->find($id);

        if (!$subcategory) {
            return $this->error('Subcategory not found', 404);
        }

        return $this->success(new HelpSubcategoryResource($subcategory), 'Subcategory fetched successfully');
    }
}