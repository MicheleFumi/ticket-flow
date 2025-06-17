<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user) {
            $currentAccessToken = $user->currentAccessToken();

            if (
                $currentAccessToken && $currentAccessToken->expires_at &&
                $currentAccessToken->expires_at->isPast()
            ) {

                // Token scaduto
                $currentAccessToken->delete();

                return response()->json(['msg' => 'Il token è scaduto.'], 401);
            }
        }

        return $next($request);
    }
}
