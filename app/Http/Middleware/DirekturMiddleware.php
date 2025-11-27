<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DirekturMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }

        /** @var User $user */
        $user = Auth::user();
        if (!$user->isDirektur()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}