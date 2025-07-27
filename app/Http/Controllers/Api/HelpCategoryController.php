<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpCategory;
use App\Http\Resources\HelpCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseTrait;

class HelpCategoryController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:view_help_categories')->only(['index', 'show']);
        $this->middleware('permission:create_help_category')->only(['store']);
        $this->middleware('permission:edit_help_category')->only(['update']);
        $this->middleware('permission:delete_help_category')->only(['destroy']);
    }

    public function index()
    {
        $categories = HelpCategory::with('subcategories')->orderBy('order')->get();
        return $this->success(HelpCategoryResource::collection($categories), 'Help categories fetched successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $category = HelpCategory::create($validator->validated());
        return $this->success(new HelpCategoryResource($category), 'Help category created successfully', 201);
    }

    public function show($id)
    {
        $category = HelpCategory::with('subcategories')->findOrFail($id);
        return $this->success(new HelpCategoryResource($category), 'Help category fetched successfully');
    }

    public function update(Request $request, $id)
    {
        $category = HelpCategory::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $category->update($validator->validated());
        return $this->success(new HelpCategoryResource($category), 'Help category updated successfully');
    }

    public function destroy($id)
    {
        $category = HelpCategory::findOrFail($id);
        $category->delete();
        return $this->success(null, 'Category deleted successfully');
    }
}