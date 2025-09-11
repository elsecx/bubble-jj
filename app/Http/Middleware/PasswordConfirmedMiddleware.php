<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordConfirmedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $confirmedAt = $request->session()->get('password_confirmed_at');

        if (!$confirmedAt || $confirmedAt->diffInMinutes(now()) > 5) {
            return redirect()->route('user.dashboard')->withErrors([
                'password' => 'Harap konfirmasi password terlebih dahulu.'
            ]);
        }

        return $next($request);
    }
}
