<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redis;
use App\Models\Account;


class PasswordThrottleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $accountId = $request->route('id');
        $seconds = 3600;
        $messages = 'You have already received an email with your password in the last hour. Please check your email.';
        $accountId = $accountId ?: $request->account_id;
        $key = 'password-resend-' . $accountId;
        if (RateLimiter::tooManyAttempts($key, env('MAX_ATTEMPTS_PER_HOUR', 1))) { // 1 attempt per 60 minutes
           return response()->json([
                'message' => $messages
            ], 429); // 429 Too Many Requests
        }
        RateLimiter::hit($key, $seconds); // Register a hit to the rate limiter for 60 minutes
        return $next($request);
    }
}
