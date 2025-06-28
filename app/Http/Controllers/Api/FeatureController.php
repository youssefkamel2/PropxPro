<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Traits\ResponseTrait;

class FeatureController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:view_features')->only(['indexAdmin', 'show']);
        $this->middleware('permission:create_feature')->only(['store']);
        $this->middleware('permission:edit_feature')->only(['update']);
        $this->middleware('permission:toggle_feature_status')->only(['toggleStatus']);
        $this->middleware('permission:delete_feature')->only(['destroy']);
    }

    // Admin endpoint
    public function indexAdmin(): JsonResponse
    {
        $features = Feature::all()->map(function ($feature) {
            return [
                'id' => $feature->id,
                'key' => $feature->key,
                'name' => $feature->name,
                'type' => $feature->type,
                'category' => $feature->category,
                'is_active' => (bool) $feature->is_active,
                'created_at' => $feature->created_at,
                'updated_at' => $feature->updated_at,
            ];
        });
        return $this->success($features, 'Features retrieved successfully');
    }

    // Public endpoint
    public function index(): JsonResponse
    {
        $features = Feature::all();
        return $this->success($features, 'Features retrieved successfully');
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
        return $this->success($feature, 'Feature created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $feature = Feature::find($id);
        if (!$feature) {
            return $this->error('Feature not found', 404);
        }
        return $this->success($feature, 'Feature retrieved successfully');
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
        return $this->success($feature, 'Feature updated successfully');
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

            return $this->success($feature, 'Feature status updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to toggle integration status: ' . $e->getMessage(), 500);
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
