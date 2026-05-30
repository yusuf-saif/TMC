<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user() ?: $request->user();

        if (! $user) {
            abort(403);
        }

        if (! ($user->is_admin && ($user->email_verified_at !== null) && ($user->status === 'approved'))) {
            abort(403);
        }

        return $next($request);
    }
}
