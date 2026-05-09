<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $pageTitle ?? ($settings['business_name'] ?? 'TequilaPOS') }}</title>
    <meta name="description" content="{{ $pageDescription ?? 'Order online from your favorite TequilaPOS restaurant.' }}" />
    <meta name="author" content="TequilaPOS" />
    <link rel="icon" href="{{ isset($settings['favicon']) ? $imageUrl($settings['favicon']) : '/favicon.ico' }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $pageTitle ?? ($settings['business_name'] ?? 'TequilaPOS') }}" />
    <meta property="og:description" content="{{ $pageDescription ?? 'Order online from your favorite TequilaPOS restaurant.' }}" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $pageTitle ?? ($settings['business_name'] ?? 'TequilaPOS') }}" />
    <meta name="twitter:description" content="{{ $pageDescription ?? 'Order online from your favorite TequilaPOS restaurant.' }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak]{display:none!important}.gallery-asymmetric{display:grid;grid-template-columns:2fr 1fr 1fr;grid-template-rows:180px 180px;gap:1rem}.gallery-asymmetric>*:first-child{grid-row:span 2}@media(max-width:768px){.gallery-asymmetric{grid-template-columns:1fr;grid-template-rows:none}.gallery-asymmetric>*:first-child{grid-row:auto}}
    </style>
</head>
<body class="bg-neutral-950 text-stone-100 antialiased" x-data="{cartOpen:false}">
    @include('partials.header')
    <main class="pt-16 md:pt-20 min-h-screen">
        @if (session('status'))
            <div class="mx-auto max-w-6xl px-4 pt-4"><div class="rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">{{ session('status') }}</div></div>
        @endif
        @if ($errors->any())
            <div class="mx-auto max-w-6xl px-4 pt-4"><div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">{{ $errors->first() }}</div></div>
        @endif
        @yield('content')
    </main>
    @include('partials.cart-sidebar')
    @include('partials.footer')
</body>
</html>
