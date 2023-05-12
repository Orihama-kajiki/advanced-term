<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::user() || !Auth::user()->hasAnyRole($roles)) {
            abort(403);
        }

        return $next($request);
    }
}
