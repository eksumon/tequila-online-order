<header class="fixed inset-x-0 top-0 z-40 border-b border-white/10 bg-neutral-950/90 backdrop-blur">
    <nav class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 md:h-20" aria-label="Primary navigation">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            @if (! empty($settings['logo']))<img src="{{ $imageUrl($settings['logo']) }}" alt="{{ $settings['business_name'] ?? 'Restaurant' }} logo" class="h-10 w-10 rounded-full object-cover">@endif
            <span class="font-serif text-xl font-semibold">{{ $settings['business_name'] ?? 'TequilaPOS' }}</span>
        </a>
        <div class="hidden items-center gap-8 md:flex">
            <a class="hover:text-amber-300 {{ request()->routeIs('home') ? 'text-amber-300' : '' }}" href="{{ route('home') }}">Home</a>
            <a class="hover:text-amber-300 {{ request()->routeIs('menu') ? 'text-amber-300' : '' }}" href="{{ route('menu') }}">Menu</a>
            <a class="hover:text-amber-300 {{ request()->routeIs('about') ? 'text-amber-300' : '' }}" href="{{ route('about') }}">About</a>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" @click="cartOpen=true" class="relative rounded-full border border-white/15 px-4 py-2 text-sm hover:border-amber-300">Cart<span class="ml-2 rounded-full bg-amber-400 px-2 py-0.5 text-xs text-neutral-950">{{ $cartCount }}</span></button>
            @if ($authCustomer)
                <div class="relative" x-data="{open:false}">
                    <button @click="open=!open" class="rounded-full bg-white/10 px-4 py-2 text-sm">{{ $authCustomer['name'] ?? 'Account' }}</button>
                    <div x-cloak x-show="open" @click.outside="open=false" class="absolute right-0 mt-2 w-48 rounded-xl border border-white/10 bg-neutral-900 p-2 shadow-xl">
                        <a class="block rounded px-3 py-2 text-sm hover:bg-white/10" href="{{ route('account') }}">Dashboard</a>
                        <a class="block rounded px-3 py-2 text-sm hover:bg-white/10" href="{{ route('orders') }}">Orders</a>
                        <a class="block rounded px-3 py-2 text-sm hover:bg-white/10" href="{{ route('profile') }}">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="block w-full rounded px-3 py-2 text-left text-sm hover:bg-white/10">Sign out</button></form>
                    </div>
                </div>
            @else
                <a class="rounded-full bg-amber-400 px-4 py-2 text-sm font-semibold text-neutral-950" href="{{ route('login') }}">Sign in</a>
            @endif
        </div>
    </nav>
</header>
