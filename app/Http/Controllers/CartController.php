<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CustomerAuthService;
use App\Services\TequilaPosClient;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, CartService $cart, TequilaPosClient $client)
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'variant_id' => ['nullable', 'integer'],
            'modifiers' => ['nullable', 'array'],
        ]);

        $item = collect($client->items())->firstWhere('id', $data['item_id']) ?: $client->itemDetails($data['item_id']);
        $selected = [];
        if (! empty($data['variant_id'])) {
            $variant = collect($item['variants'] ?? [])->firstWhere('id', (int) $data['variant_id']);
            if ($variant) {
                $item['selected_price'] = $variant['price'];
                $selected[] = $variant;
            }
        }

        $availableModifiers = collect($client->itemModifiers($data['item_id']));
        foreach ($data['modifiers'] ?? [] as $modifierId) {
            if ($modifier = $availableModifiers->firstWhere('id', (int) $modifierId)) {
                $selected[] = $modifier;
            }
        }

        $cart->add($item, $selected, $data['quantity'] ?? 1);

        return back()->with('status', 'Added to cart.');
    }

    public function update(string $key, Request $request, CartService $cart)
    {
        $cart->update($key, $request->integer('quantity'));
        return back()->with('status', 'Cart updated.');
    }

    public function remove(string $key, CartService $cart)
    {
        $cart->remove($key);
        return back()->with('status', 'Item removed.');
    }

    public function clear(CartService $cart)
    {
        $cart->clear();
        return back()->with('status', 'Cart cleared.');
    }

    public function checkout(Request $request, CartService $cart, CustomerAuthService $auth, TequilaPosClient $client)
    {
        $payload = $request->validate([
            'order_type' => ['required', 'string'],
            'customer_note' => ['nullable', 'string'],
            'payment_method' => ['nullable', 'string'],
        ]);
        $payload['items'] = array_values($cart->all());
        $payload['subtotal'] = $cart->subtotal();
        $payload['total'] = $cart->subtotal();

        $order = $client->createOrder($auth->token(), $payload);
        $cart->clear();

        return redirect()->route('order.confirmation', $order['id'] ?? $order['order_id'] ?? 'new');
    }
}
