<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Traits\ResponseTrait;
use App\Http\Resources\FeatureResource;

class FeatureController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['indexPublic', 'show']);
        $this->middleware('permission:view_features')->only(['indexAdmin']);
        $this->middleware('permission:create_feature')->only(['store']);
        $this->middleware('permission:edit_feature')->only(['update']);
        $this->middleware('permission:toggle_feature_status')->only(['toggleStatus']);
        $this->middleware('permission:delete_feature')->only(['destroy']);
    }

    // Public endpoint
    public function indexPublic(): JsonResponse
    {
        $features = Feature::where('is_active', true)->get();

        return $this->success(
            FeatureResource::collection($features),
            'Features retrieved successfully'
        );
    }

    // Admin endpoint
    public function indexAdmin(): JsonResponse
    {
        $features = Feature::all();

        return $this->success(
            FeatureResource::collection($features),
            'All features retrieved successfully'
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|unique:features,key',
            'name' => 'required|string',
            'type' => 'required|in:boolean,text',
            'category' => 'sometimes',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $feature = Feature::create($validator->validated());
        return $this->success(new FeatureResource($feature), 'Feature created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $feature = Feature::find($id);
        if (!$feature) {
            return $this->error('Feature not found', 404);
        }
        return $this->success(new FeatureResource($feature), 'Feature retrieved successfully');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $feature = Feature::find($id);
        if (!$feature) {
            return $this->error('Feature not found', 404);
        }
        $validator = Validator::make($request->all(), [
            'key' => 'sometimes|string|unique:features,key,' . $feature->id,
            'name' => 'sometimes|string',
            'type' => 'sometimes|in:boolean,text',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $feature->update($validator->validated());
        return $this->success(new FeatureResource($feature), 'Feature updated successfully');
    }

    // toggle status
    public function toggleStatus($id): JsonResponse
    {
        $feature = Feature::find($id);
        if (!$feature) {
            return $this->error('Feature not found', 404);
        }

        try {
            $feature->update(['is_active' => !$feature->is_active]);

            return $this->success(new FeatureResource($feature), 'Feature status updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to toggle feature status: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $feature = Feature::find($id);
        if (!$feature) {
            return $this->error('Feature not found', 404);
        }
        $feature->delete();
        return $this->success(null, 'Feature deleted successfully');
    }
}
