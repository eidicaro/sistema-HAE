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
    public function handle(Request $request, Closure $next, string ...$tipos): Response
    {
        abort_unless(
            $request->user() && in_array($request->user()->role, $tipos, true),
            403
        );

        return $next($request);
    }
}
