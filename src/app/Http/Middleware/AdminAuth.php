<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $adminKey = config('app.admin_key');

        if (!$adminKey) {
            abort(503, 'Admin not configured. Set ADMIN_KEY in .env');
        }

        $provided = $request->header('X-Admin-Key')
            ?? $request->query('key')
            ?? $request->input('admin_key');

        if (!$provided || !hash_equals($adminKey, $provided)) {
            abort(401, 'Unauthorized.');
        }

        return $next($request);
    }
}