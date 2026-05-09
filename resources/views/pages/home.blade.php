@extends('layouts.app')
@section('content')
<section id="hero" class="relative min-h-[620px] overflow-hidden">
    @if(!empty($settings['banner_img']))<img src="{{ $imageUrl($settings['banner_img']) }}" alt="{{ $settings['business_name'] ?? 'Restaurant' }}" class="absolute inset-0 h-full w-full object-cover opacity-45">@endif
    <div class="absolute inset-0 bg-gradient-to-b from-neutral-950/40 via-neutral-950/70 to-neutral-950"></div>
    <div class="relative mx-auto flex min-h-[620px] max-w-6xl items-center px-4">
        <div class="max-w-2xl"><p class="mb-4 text-sm uppercase tracking-[0.35em] text-amber-300">Online ordering</p><h1 class="font-serif text-5xl font-bold md:text-7xl">{{ $settings['banner_title'] ?? ($settings['business_name'] ?? 'Tequila Texas') }}</h1><p class="mt-6 text-lg text-stone-300">{{ $settings['banner_subtitle'] ?? 'Where every dish tells a story.' }}</p><a href="{{ route('menu') }}" class="mt-8 inline-flex rounded-full bg-amber-400 px-8 py-3 font-semibold text-neutral-950">Order now</a></div>
    </div>
</section>
@php($displayItems = $todaysSpecials->isNotEmpty() ? $todaysSpecials : $fallbackItems)
<section id="todays-specials" class="mx-auto max-w-6xl px-4 py-20"><div class="mb-8 flex items-end justify-between"><div><p class="text-sm uppercase tracking-[0.25em] text-amber-300">Chef picks</p><h2 class="mt-2 font-serif text-4xl">Today's Specials</h2></div><a href="{{ route('menu') }}" class="text-amber-300">View menu</a></div><div class="grid gap-6 md:grid-cols-3">@foreach($displayItems as $item)@include('partials.menu-card',['item'=>$item])@endforeach</div></section>
@if($featuredMenus->isNotEmpty())<section id="featured-menus" class="bg-neutral-900/50 py-20"><div class="mx-auto max-w-6xl px-4"><h2 class="font-serif text-4xl">Featured Menus</h2><div class="mt-8 grid gap-6 md:grid-cols-3">@foreach($featuredMenus as $item)@include('partials.menu-card',['item'=>$item])@endforeach</div></div></section>@endif
@if(count($gallery))<section id="gallery" class="mx-auto max-w-6xl px-4 py-20"><p class="text-sm uppercase tracking-[0.25em] text-amber-300">Gallery</p><h2 class="mt-2 font-serif text-4xl">A taste of our table</h2><div class="gallery-asymmetric mt-8">@foreach(array_slice($gallery,0,5) as $image)<img src="{{ $imageUrl($image['image'] ?? $image['path'] ?? '') }}" alt="Restaurant gallery image" class="h-full w-full rounded-2xl object-cover">@endforeach</div></section>@endif
@endsection
