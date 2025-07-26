<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class AdminSettingsController extends Controller
{
    /**
     * Request to update profile: just send a verification code to current email.
     */
    public function requestUpdate(Request $request): JsonResponse
    {
        $user = Auth::user();
        $code = random_int(100000, 999999);
        $expiresAt = now()->addMinutes(15);

        $user->email_verification_code = $code;
        $user->email_verification_expires_at = $expiresAt;
        $user->save();

        // Send code to current email
        Mail::raw("Your verification code is: $code", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Profile Update Verification Code');
        });

        return response()->json(['success' => true, 'message' => 'Verification code sent to your email.']);
    }

    /**
     * Confirm update: receive code and update data, validate code, and apply update if code is correct.
     */
    public function confirmUpdate(Request $request): JsonResponse
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8',
            'profile_image' => 'sometimes|file|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        if (
            !$user->email_verification_code ||
            $user->email_verification_code !== $request->code ||
            !$user->email_verification_expires_at ||
            now()->gt($user->email_verification_expires_at)
        ) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired verification code.'], 403);
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $path = $file->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->email_verification_code = null;
        $user->email_verification_expires_at = null;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Profile updated successfully.']);
    }
}
