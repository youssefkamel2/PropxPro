<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait LoginAttemptTrait
{
    protected function incrementLoginAttempts($email): void
    {
        $attempts = Cache::get("login_attempts_$email", 0);
        Cache::put("login_attempts_$email", $attempts + 1, now()->addMinutes(30));
    }

    protected function getLoginAttempts($email): int
    {
        return Cache::get("login_attempts_$email", 0);
    }

    protected function clearLoginAttempts($email): void
    {
        Cache::forget("login_attempts_$email");
    }

    protected function hasTooManyLoginAttempts($email): bool
    {
        return $this->getLoginAttempts($email) >= 3;
    }
} 