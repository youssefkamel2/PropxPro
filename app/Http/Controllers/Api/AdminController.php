<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware(['auth:api', 'role:superadmin']);
    }

    public function index(): JsonResponse
    {
        $admins = User::role('admin')
            ->with(['roles', 'permissions'])
            ->get()
            ->map(function ($admin) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'status' => $admin->status,
                    'role' => 'admin',
                    'permissions' => $admin->getAllPermissions()->pluck('name'),
                    'created_at' => $admin->created_at
                ];
            });

        return $this->success($admins, 'Admins retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Password::defaults()],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name']
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $validated = $validator->validated();

            $admin = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => 'active'
            ]);

            $admin->assignRole('admin');

            if (isset($validated['permissions'])) {
                $admin->givePermissionTo($validated['permissions']);
            }

            return $this->success([
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'status' => 'active',
                'role' => 'admin',
                'permissions' => $admin->getAllPermissions()->pluck('name'),
                'created_at' => $admin->created_at
            ], 'Admin created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create admin: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $admin = User::find($id);
        
        if (!$admin) {
            return $this->error('Admin not found', 404);
        }

        if (!$admin->hasRole('admin')) {
            return $this->error('User is not an admin', 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $admin->id,
            'password' => ['sometimes', Password::defaults()],
            'status' => 'sometimes|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $validated = $validator->validated();
            $updateData = [];

            if (isset($validated['name'])) {
                $updateData['name'] = $validated['name'];
            }

            if (isset($validated['email'])) {
                $updateData['email'] = $validated['email'];
            }

            if (isset($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            if (isset($validated['status'])) {
                $updateData['status'] = $validated['status'];
            }

            $admin->update($updateData);

            return $this->success([
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'status' => $admin->status,
                'role' => 'admin',
                'permissions' => $admin->getAllPermissions()->pluck('name'),
                'created_at' => $admin->created_at
            ], 'Admin updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update admin: ' . $e->getMessage(), 500);
        }
    }

    public function toggleStatus($id): JsonResponse
    {
        $admin = User::find($id);
        
        if (!$admin) {
            return $this->error('Admin not found', 404);
        }

        if (!$admin->hasRole('admin')) {
            return $this->error('User is not an admin', 403);
        }

        try {
            $admin->status = $admin->status === 'active' ? 'inactive' : 'active';
            $admin->save();

            return $this->success([
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'status' => $admin->status,
                'role' => 'admin',
                'permissions' => $admin->getAllPermissions()->pluck('name'),
                'created_at' => $admin->created_at
            ], 'Admin status updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to toggle admin status: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $admin = User::find($id);
        
        if (!$admin) {
            return $this->error('Admin not found', 404);
        }

        if (!$admin->hasRole('admin')) {
            return $this->error('User is not an admin', 403);
        }

        try {
            $admin->delete();
            return $this->success(null, 'Admin deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete admin: ' . $e->getMessage(), 500);
        }
    }

    public function updatePermissions(Request $request, $id): JsonResponse
    {
        $admin = User::find($id);
        
        if (!$admin) {
            return $this->error('Admin not found', 404);
        }

        if (!$admin->hasRole('admin')) {
            return $this->error('User is not an admin', 403);
        }

        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $validated = $validator->validated();
            $admin->syncPermissions($validated['permissions']);

            return $this->success([
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'status' => $admin->status,
                'role' => 'admin',
                'permissions' => $admin->getAllPermissions()->pluck('name'),
                'created_at' => $admin->created_at
            ], 'Admin permissions updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update permissions: ' . $e->getMessage(), 500);
        }
    }

    public function listAvailablePermissions(): JsonResponse
    {
        try {
            $permissions = Permission::all()->map(function ($permission) {
                return [
                    'name' => $permission->name,
                    'description' => $permission->description
                ];
            });

            return $this->success($permissions, 'Available permissions retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve permissions: ' . $e->getMessage(), 500);
        }
    }
}