<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'same-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $contentType = (string) $response->headers->get('Content-Type');
        $contentSecurityPolicy = str_starts_with($contentType, 'text/html')
            ? "default-src 'self'; base-uri 'self'; connect-src 'self'; font-src 'self'; "
                ."form-action 'self'; frame-ancestors 'none'; img-src 'self' data:; "
                ."object-src 'none'; script-src 'self'; style-src 'self' 'unsafe-inline'"
            : "sandbox; default-src 'none'; frame-ancestors 'none'";

        $response->headers->set('Content-Security-Policy', $contentSecurityPolicy);

        if (Auth::check()) {
            $response->headers->set('Cache-Control', 'no-store, private');
        }

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        return $response;
    }
}
