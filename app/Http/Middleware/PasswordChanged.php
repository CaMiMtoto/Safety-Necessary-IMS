<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (auth()->check() && !auth()->user()->password_changed_at) {
            return redirect()->route('users.change-password-view')
                ->with('error', 'You need to change your password to continue');
        }
        return $next($request);
    }
}
