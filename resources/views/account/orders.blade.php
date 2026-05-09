@extends('layouts.app')
@section('content')
<section class="mx-auto max-w-4xl px-4 py-16"><h1 class="font-serif text-5xl">Orders</h1><div class="mt-8 space-y-4">@forelse($orders as $order)@php($id=$order['id'] ?? $order['order_id'] ?? '')<a href="{{ route('orders.show',$id) }}" class="block rounded-2xl border border-white/10 bg-neutral-900/60 p-5 hover:border-amber-300"><div class="flex justify-between"><h2 class="font-semibold">Order #{{ $id }}</h2><span>${{ number_format($order['total'] ?? $order['grand_total'] ?? 0, 2) }}</span></div><p class="mt-2 text-sm text-stone-400">{{ $order['status'] ?? 'Received' }}</p></a>@empty<p class="text-stone-400">No orders found.</p>@endforelse</div></section>
@endsection
