<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if ($request->user() && in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        return redirect('/'); // Atau arahkan ke halaman yang sesuai
    }
}
