<?php

namespace App\Http\Controllers\Api;

use App\Models\Integration;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\IntegrationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IntegrationController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['indexPublic', 'show']);
        $this->middleware('permission:view_integrations')->only(['indexAdmin']);
        $this->middleware('permission:create_integration')->only(['store']);
        $this->middleware('permission:edit_integration')->only(['update']);
        $this->middleware('permission:delete_integration')->only(['destroy']);
        $this->middleware('permission:toggle_integration')->only(['toggleStatus']);
    }

    public function indexPublic(): JsonResponse
    {

        $integrations = Integration::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return $this->success(
            IntegrationResource::collection($integrations),
            'Integrations retrieved successfully'
        );
    }

    public function indexAdmin(): JsonResponse
    {
        $integrations = Integration::orderBy('display_order')->get();

        return $this->success(
            IntegrationResource::collection($integrations),
            'All integrations retrieved successfully'
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:integrations,name',
            'description' => 'required|string',
            'logo' => 'required|image|max:2048', // 2MB max
            'is_active' => 'sometimes|boolean',
            'display_order' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $validated = $validator->validated();
            $logoPath = $request->file('logo')->store('integrations', 'public');

            $displayOrder = $validated['display_order'] ?? Integration::max('display_order') + 1;

            $integration = Integration::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'],
                'logo_path' => $logoPath,
                'is_active' => $validated['is_active'] ?? true,
                'display_order' => $displayOrder,
            ]);

            return $this->success(
                new IntegrationResource($integration),
                'Integration created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create integration: ' . $e->getMessage(), 500);
        }
    }

    public function show($id): JsonResponse
    {
        $integration = Integration::find($id);

        if (!$integration) {
            return $this->error('Integration not found', 404);
        }

        return $this->success(
            new IntegrationResource($integration),
            'Integration retrieved successfully'
        );
    }

    public function update(Request $request, $id): JsonResponse
    {
        $integration = Integration::find($id);

        if (!$integration) {
            return $this->error('Integration not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:integrations,name,' . $integration->id,
            'description' => 'sometimes|string',
            'logo' => 'sometimes|image|max:2048',
            'is_active' => 'sometimes|boolean',
            'display_order' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $validated = $validator->validated();
            $updateData = [];

            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($integration->logo_path) {
                    Storage::disk('public')->delete($integration->logo_path);
                }
                $updateData['logo_path'] = $request->file('logo')->store('integrations', 'public');
            }

            if (isset($validated['name'])) {
                $updateData['name'] = $validated['name'];
                $updateData['slug'] = Str::slug($validated['name']);
            }

            if (isset($validated['description'])) {
                $updateData['description'] = $validated['description'];
            }

            if (array_key_exists('is_active', $validated)) {
                $updateData['is_active'] = $validated['is_active'];
            }

            if (isset($validated['display_order'])) {
                $updateData['display_order'] = $validated['display_order'];
            }

            $integration->update($updateData);

            return $this->success(
                new IntegrationResource($integration),
                'Integration updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update integration: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $integration = Integration::find($id);

        if (!$integration) {
            return $this->error('Integration not found', 404);
        }

        try {
            if ($integration->logo_path) {
                Storage::disk('public')->delete($integration->logo_path);
            }

            $integration->delete();

            return $this->success(
                null,
                'Integration deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to delete integration: ' . $e->getMessage(), 500);
        }
    }

    public function toggleStatus($id): JsonResponse
    {
        $integration = Integration::find($id);

        if (!$integration) {
            return $this->error('Integration not found', 404);
        }

        try {
            $integration->update(['is_active' => !$integration->is_active]);

            return $this->success(
                new IntegrationResource($integration),
                'Integration status updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to toggle integration status: ' . $e->getMessage(), 500);
        }
    }
}
