<?php

namespace App\Http\Middleware;

use App\Services\CartService;
use App\Services\CustomerAuthService;
use App\Services\TequilaPosClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareGlobals
{
    public function handle(Request $request, Closure $next): Response
    {
        $client = app(TequilaPosClient::class);

        try {
            $settings = $client->websiteSettings();
        } catch (\Throwable) {
            $settings = ['business_name' => 'Tequila Texas'];
        }

        View::share([
            'settings' => $settings,
            'cartItems' => app(CartService::class)->all(),
            'cartCount' => app(CartService::class)->count(),
            'cartSubtotal' => app(CartService::class)->subtotal(),
            'authCustomer' => app(CustomerAuthService::class)->customer(),
            'imageUrl' => fn (?string $path) => $client->imageUrl($path),
        ]);

        return $next($request);
    }
}
