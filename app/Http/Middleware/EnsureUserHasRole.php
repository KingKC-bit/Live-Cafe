<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * Usage:
     * role:admin
     * role:staff,admin
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        if (! $request->user()) {
            abort(401);
        }

        if (! in_array($request->user()->role, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}