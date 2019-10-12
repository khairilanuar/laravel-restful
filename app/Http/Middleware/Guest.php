<?php

namespace App\Http\Middleware;

use Closure;
use http\Env\Response;
use Illuminate\Support\Facades\Auth;

class Guest
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not Allowed',
                'data'    => [],
            ], \Illuminate\Http\Response::HTTP_BAD_REQUEST);
        }

        return $next($request);
    }
}
