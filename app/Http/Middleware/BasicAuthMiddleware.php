<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $username = env('BASIC_AUTH_USER', 'user');
        $password = env('BASIC_AUTH_PASSWORD', 'pass');

        $authUser = $request->getUser();
        $authPassword = $request->getPassword();

        if ($authUser !== $username || $authPassword !== $password) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

