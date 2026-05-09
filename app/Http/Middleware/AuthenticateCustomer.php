<?php

namespace App\Http\Middleware;

use App\Services\CustomerAuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app(CustomerAuthService::class)->check()) {
            return redirect()->route('login')->with('intended', $request->fullUrl());
        }

        return $next($request);
    }
}
