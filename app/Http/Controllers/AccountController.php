<?php

namespace App\Http\Controllers;

use App\Services\CustomerAuthService;
use App\Services\TequilaPosClient;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(CustomerAuthService $auth)
    {
        return view('account.index', ['pageTitle' => 'My Account | TequilaPOS', 'pageDescription' => 'View your account.', 'customer' => $auth->customer()]);
    }

    public function edit(CustomerAuthService $auth)
    {
        return view('account.profile', ['pageTitle' => 'My Profile | TequilaPOS', 'pageDescription' => 'Manage your profile.', 'customer' => $auth->customer()]);
    }

    public function update(Request $request, CustomerAuthService $auth, TequilaPosClient $client)
    {
        $payload = $request->validate(['name' => ['required', 'string', 'max:100'], 'email' => ['nullable', 'email']]);
        $customer = array_merge($auth->customer() ?? [], $payload);

        try {
            $client->updateCustomerProfile($auth->token(), $payload);
        } catch (\Throwable) {
            // Keep session profile in sync when the remote API is unavailable.
        }

        $auth->login($customer, $auth->token());
        return back()->with('status', 'Profile updated.');
    }

    public function orders(CustomerAuthService $auth, TequilaPosClient $client)
    {
        try {
            $orders = $client->orders($auth->token());
        } catch (\Throwable) {
            $orders = [];
        }

        return view('account.orders', ['pageTitle' => 'Orders | TequilaPOS', 'pageDescription' => 'View order history.', 'orders' => $orders]);
    }

    public function orderShow(string $id, CustomerAuthService $auth, TequilaPosClient $client)
    {
        try {
            $order = collect($client->orders($auth->token()))->first(fn ($order) => (string) ($order['id'] ?? $order['order_id'] ?? '') === $id);
        } catch (\Throwable) {
            $order = null;
        }

        abort_unless($order, 404);
        return view('account.order-show', ['pageTitle' => "Order #{$id} | TequilaPOS", 'pageDescription' => 'View order details.', 'order' => $order]);
    }

    public function confirmation(string $id)
    {
        return view('account.order-confirmation', ['pageTitle' => 'Order Confirmed | TequilaPOS', 'pageDescription' => 'Your order has been placed.', 'orderId' => $id]);
    }
}
