<div x-cloak x-show="cartOpen" class="fixed inset-0 z-50" aria-labelledby="cart-title" role="dialog">
    <div class="absolute inset-0 bg-black/60" @click="cartOpen=false"></div>
    <aside class="absolute right-0 top-0 h-full w-full max-w-md overflow-y-auto border-l border-white/10 bg-neutral-950 p-6 shadow-2xl">
        <div class="flex items-center justify-between"><h2 id="cart-title" class="font-serif text-2xl">Your Cart</h2><button @click="cartOpen=false" class="text-stone-400">Close</button></div>
        <div class="mt-6 space-y-4">
            @forelse ($cartItems as $line)
                <div class="rounded-xl border border-white/10 p-4">
                    <div class="flex justify-between gap-3"><div><h3 class="font-medium">{{ $line['name'] }}</h3>@foreach($line['modifiers'] as $modifier)<p class="text-xs text-stone-400">{{ $modifier['name'] }} +${{ number_format($modifier['price'], 2) }}</p>@endforeach</div><p>${{ number_format(($line['price'] + $line['modifier_cost']) * $line['quantity'], 2) }}</p></div>
                    <div class="mt-3 flex items-center justify-between">
                        <form method="POST" action="{{ route('cart.update', $line['key']) }}" class="flex items-center gap-2">@csrf @method('PATCH')<input name="quantity" type="number" min="0" value="{{ $line['quantity'] }}" class="w-16 rounded bg-neutral-900 px-2 py-1"><button class="text-sm text-amber-300">Update</button></form>
                        <form method="POST" action="{{ route('cart.remove', $line['key']) }}">@csrf @method('DELETE')<button class="text-sm text-red-300">Remove</button></form>
                    </div>
                </div>
            @empty
                <p class="text-stone-400">Your cart is empty.</p>
            @endforelse
        </div>
        @if($cartCount)
            <div class="mt-6 border-t border-white/10 pt-4"><div class="flex justify-between text-lg font-semibold"><span>Subtotal</span><span>${{ number_format($cartSubtotal, 2) }}</span></div>
            @if($authCustomer)<form method="POST" action="{{ route('checkout') }}" class="mt-4 space-y-3">@csrf<input type="hidden" name="order_type" value="pickup"><input type="hidden" name="payment_method" value="cash"><textarea name="customer_note" placeholder="Order notes" class="w-full rounded bg-neutral-900 p-3"></textarea><button class="w-full rounded-full bg-amber-400 py-3 font-semibold text-neutral-950">Place order</button></form>@else<a href="{{ route('login') }}" class="mt-4 block rounded-full bg-amber-400 py-3 text-center font-semibold text-neutral-950">Sign in to checkout</a>@endif</div>
        @endif
    </aside>
</div>
