<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use App\Traits\LoginAttemptTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ResponseTrait, LoginAttemptTrait;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $email = $request->input('email');

            // Check if user exists
            $user = User::where('email', $email)->first();
            if (!$user) {
                return $this->error('Invalid credentials', 401);
            }

            // Check if user is active
            if ($user->status === 'inactive') {
                return $this->error('Your account has been deactivated. Please contact administrator.', 401);
            }

            // Check for too many login attempts
            if ($this->hasTooManyLoginAttempts($email)) {
                // Deactivate user
                $user->update(['status' => 'inactive']);
                $this->clearLoginAttempts($email);
                return $this->error('Account deactivated due to too many failed login attempts. Please contact administrator.', 401);
            }

            // Attempt login
            if (!$token = auth()->attempt($request->only('email', 'password'))) {
                $this->incrementLoginAttempts($email);
                $remainingAttempts = 3 - $this->getLoginAttempts($email);
                return $this->error("Invalid credentials. {$remainingAttempts} attempts remaining.", 401);
            }

            // Clear login attempts on successful login
            $this->clearLoginAttempts($email);
            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        // Get all permissions including those from roles
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->merge(
            $user->getDirectPermissions()->pluck('name')
        )->unique()->values();

        return $this->success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_image' => $user->profile_image,
                'status' => $user->status,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'roles' => $user->getRoleNames(),
            'permissions' => $permissions
        ], 'User profile retrieved successfully');
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return $this->success(null, 'Successfully logged out');
    }

    protected function respondWithToken(string $token): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        // Get all permissions including those from roles
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->merge(
            $user->getDirectPermissions()->pluck('name')
        )->unique()->values();

        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_image' => $user->profile_image,
                'status' => $user->status,
            ],
            'roles' => $user->getRoleNames(),
            'permissions' => $permissions
        ], 'Authentication successful');
    }
}
