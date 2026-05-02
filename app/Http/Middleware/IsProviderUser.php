<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsProviderUser
{
  
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user instanceof User) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        if (! $user->is_provider || ! $user->provider) {
            return response()->json(['message' => 'Only providers can access this resource.'], 401);
        }
        return $next($request);
    }
}
