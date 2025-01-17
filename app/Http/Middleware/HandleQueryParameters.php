<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class HandleQueryParameters
{
    public function handle(Request $request, Closure $next, string $domain): Response
    {
        if (! $domain) {
            return $next($request);
        }

        $sessionKey = "{$domain}_query_parameters";

        if ($request->route()->getActionMethod() === 'index') {
            if ($request->query()) {
                Session::put($sessionKey, $request->query());
            } else {
                Session::forget($sessionKey);
            }
        }

        if ($request->route()->getActionMethod() === 'show') {
            $request->query->add(Session::get($sessionKey, []));
        }

        return $next($request);
    }
}
