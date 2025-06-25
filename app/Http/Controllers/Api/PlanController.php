<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Traits\ResponseTrait;

class PlanController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:view_plans')->only(['show']);
        $this->middleware('permission:create_plan')->only(['store']);
        $this->middleware('permission:edit_plan')->only(['update']);
        $this->middleware('permission:delete_plan')->only(['destroy']);
    }

    // Public endpoint
    public function index(): JsonResponse
    {
        // Get all features and split by category
        $allFeatures = Feature::all();
        $features = $allFeatures->where('category', null)->values();
        $additionalUsageCharges = $allFeatures->where('category', 'additional_usage_charge')->values();

        // Get all plans with their features
        $plans = Plan::with('features')->get()->map(function ($plan) use ($allFeatures) {
            // Map features by key for this plan
            $planFeatures = [];
            $planAdditionalUsageCharges = [];
            foreach ($allFeatures as $feature) {
                $pivot = $plan->features->firstWhere('id', $feature->id)?->pivot;
                $value = $pivot ? ($feature->type === 'boolean' ? filter_var($pivot->value, FILTER_VALIDATE_BOOLEAN) : $pivot->value) : null;
                $featureData = [
                    'name' => $feature->name,
                    'value' => $value,
                    'type' => $feature->type === 'text' ? 'string' : $feature->type,
                ];
                if ($feature->category === 'additional_usage_charge') {
                    $planAdditionalUsageCharges[$feature->key] = $featureData;
                } else {
                    $planFeatures[$feature->key] = $featureData;
                }
            }
            return [
                'name' => $plan->name,
                'title' => $plan->title,
                'description' => $plan->description,
                'monthlyPrice' => (string) $plan->monthly_price,
                'annualPrice' => (string) $plan->annual_price,
                'annualSavings' => $plan->annual_savings,
                'isPopular' => (bool) $plan->is_popular,
                'features' => $planFeatures,
                'additionalUsageCharges' => $planAdditionalUsageCharges,
            ];
        });

        // Format features and additional usage charges for the top-level arrays
        $featuresArr = $features->map(function ($feature) {
            return [
                'key' => $feature->key,
                'name' => $feature->name,
                'value' => null,
                'type' => $feature->type === 'text' ? 'string' : $feature->type,
            ];
        })->values();
        $additionalUsageChargesArr = $additionalUsageCharges->map(function ($feature) {
            return [
                'key' => $feature->key,
                'name' => $feature->name,
                'value' => null,
                'type' => $feature->type === 'text' ? 'string' : $feature->type,
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully retrieved pricing data',
            'data' => [
                'features' => $featuresArr,
                'additionalUsageCharges' => $additionalUsageChargesArr,
                'plans' => $plans,
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'monthly_price' => 'required|numeric',
            'annual_price' => 'required|numeric',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*.id' => 'required|exists:features,id',
            'features.*.value' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        // Validate feature values match their type
        if ($request->has('features')) {
            foreach ($request->features as $f) {
                $feature = Feature::find($f['id']);
                if ($feature) {
                    if ($feature->type === 'boolean') {
                        $val = $f['value'];
                        if (!in_array($val, [true, false, 'true', 'false', 1, 0, '1', '0'], true)) {
                            return $this->error("Feature '{$feature->name}' must be true or false.", 422);
                        }
                    }
                }
            }
        }
        $plan = Plan::create($validator->validated());
        if ($request->has('features')) {
            $syncData = [];
            foreach ($request->features as $f) {
                $syncData[$f['id']] = ['value' => $f['value'] ?? null];
            }
            $plan->features()->sync($syncData);
        }
        return $this->success($plan->load('features'), 'Plan created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $plan = Plan::with('features')->find($id);
        if (!$plan) {
            return $this->error('Plan not found', 404);
        }
        // Format features to exclude pivot and only show value
        $formatted = [
            'id' => $plan->id,
            'name' => $plan->name,
            'monthly_price' => $plan->monthly_price,
            'annual_price' => $plan->annual_price,
            'description' => $plan->description,
            'created_at' => $plan->created_at,
            'updated_at' => $plan->updated_at,
            'features' => $plan->features->map(function ($feature) {
                $value = $feature->pivot->value;
                if ($feature->type === 'boolean') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                }
                return [
                    'id' => $feature->id,
                    'key' => $feature->key,
                    'name' => $feature->name,
                    'type' => $feature->type,
                    'value' => $value,
                    'created_at' => $feature->created_at,
                    'updated_at' => $feature->updated_at,
                ];
            }),
        ];
        return $this->success($formatted, 'Plan retrieved successfully');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return $this->error('Plan not found', 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string',
            'monthly_price' => 'sometimes|numeric',
            'annual_price' => 'sometimes|numeric',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*.id' => 'required|exists:features,id',
            'features.*.value' => 'nullable',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        // Validate feature values match their type
        if ($request->has('features')) {
            foreach ($request->features as $f) {
                $feature = Feature::find($f['id']);
                if ($feature) {
                    if ($feature->type === 'boolean') {
                        $val = $f['value'];
                        if (!in_array($val, [true, false, 'true', 'false', 1, 0, '1', '0'], true)) {
                            return $this->error("Feature '{$feature->name}' must be true or false.", 422);
                        }
                    }
                }
            }
        }
        $plan->update($validator->validated());
        if ($request->has('features')) {
            $syncData = [];
            foreach ($request->features as $f) {
                $syncData[$f['id']] = ['value' => $f['value'] ?? null];
            }
            $plan->features()->sync($syncData);
        }
        // Format features to exclude pivot and only show value as true/false for boolean
        $formatted = [
            'id' => $plan->id,
            'name' => $plan->name,
            'monthly_price' => $plan->monthly_price,
            'annual_price' => $plan->annual_price,
            'description' => $plan->description,
            'created_at' => $plan->created_at,
            'updated_at' => $plan->updated_at,
            'features' => $plan->features->map(function ($feature) {
                $value = $feature->pivot->value;
                if ($feature->type === 'boolean') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                }
                return [
                    'id' => $feature->id,
                    'key' => $feature->key,
                    'name' => $feature->name,
                    'type' => $feature->type,
                    'value' => $value,
                    'created_at' => $feature->created_at,
                    'updated_at' => $feature->updated_at,
                ];
            }),
        ];
        return $this->success($formatted, 'Plan updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return $this->error('Plan not found', 404);
        }
        $plan->delete();
        return $this->success(null, 'Plan deleted successfully');
    }
} 