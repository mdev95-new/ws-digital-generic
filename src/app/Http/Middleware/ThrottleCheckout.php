<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;

class ThrottleCheckout
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->ip();

        $limiter = app(RateLimiter::class);

        if ($limiter->tooManyAttempts("checkout:{$key}", 5)) {
            return response('Too many attempts. Try again in 1 minute.', 429);
        }

        $limiter->hit("checkout:{$key}", 60);

        return $next($request);
    }
}