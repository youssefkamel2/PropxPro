<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpSubcategory;
use App\Http\Resources\HelpSubcategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseTrait;

class HelpSubcategoryController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:view_help_subcategories')->only(['index', 'show']);
        $this->middleware('permission:create_help_subcategory')->only(['store']);
        $this->middleware('permission:edit_help_subcategory')->only(['update']);
        $this->middleware('permission:delete_help_subcategory')->only(['destroy']);
    }

    public function index()
    {
        $subcategories = HelpSubcategory::with('topics')->orderBy('order')->get();
        return $this->success(HelpSubcategoryResource::collection($subcategories), 'Help subcategories fetched successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:help_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $subcategory = HelpSubcategory::create($validator->validated());
        return $this->success(new HelpSubcategoryResource($subcategory), 'Help subcategory created successfully', 201);
    }

    public function show($id)
    {
        $subcategory = HelpSubcategory::with('topics')->findOrFail($id);
        return $this->success(new HelpSubcategoryResource($subcategory), 'Help subcategory fetched successfully');
    }

    public function update(Request $request, $id)
    {
        $subcategory = HelpSubcategory::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:help_categories,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $subcategory->update($validator->validated());
        return $this->success(new HelpSubcategoryResource($subcategory), 'Help subcategory updated successfully');
    }

    public function destroy($id)
    {
        $subcategory = HelpSubcategory::findOrFail($id);
        $subcategory->delete();
        return $this->success(null, 'Subcategory deleted successfully');
    }
}