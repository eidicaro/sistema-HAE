<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTipo
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
        public function handle($request, Closure $next, $tipo)
    {
        if (session('tipo') != $tipo) {
            return redirect('/');
        }

        return $next($request);
    }
}
