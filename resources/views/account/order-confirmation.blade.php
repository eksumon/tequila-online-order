@extends('layouts.app')
@section('content')
<section class="mx-auto max-w-xl px-4 py-20 text-center"><p class="text-amber-300">Thank you</p><h1 class="mt-2 font-serif text-5xl">Order confirmed</h1><p class="mt-6 text-stone-300">Your order #{{ $orderId }} has been sent to the restaurant.</p><a href="{{ route('menu') }}" class="mt-8 inline-flex rounded-full bg-amber-400 px-8 py-3 font-semibold text-neutral-950">Order more</a></section>
@endsection
