@extends('layouts.app')
@section('content')
<section class="mx-auto max-w-3xl px-4 py-16"><h1 class="font-serif text-5xl">Order #{{ $order['id'] ?? $order['order_id'] }}</h1><pre class="mt-8 overflow-auto rounded-2xl border border-white/10 bg-neutral-900/60 p-6 text-sm text-stone-300">{{ json_encode($order, JSON_PRETTY_PRINT) }}</pre></section>
@endsection
