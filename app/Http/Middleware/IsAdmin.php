<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->hasRole('administrator')) {
           return $next($request);
        }

        return back()->withWarning('Anda tidak mempunyai hak akses ke halaman yang anda tuju');
    }
}
