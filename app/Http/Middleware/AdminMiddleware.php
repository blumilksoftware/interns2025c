<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route("login");
        }

        if (!$user->haveAdminRole()) {
            abort(403, "Unauthorized");
        }

        return $next($request);
    }
}
