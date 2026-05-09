<footer class="border-t border-white/10 bg-neutral-950 py-10">
    <div class="mx-auto grid max-w-6xl gap-8 px-4 md:grid-cols-3">
        <div><h2 class="font-serif text-2xl">{{ $settings['business_name'] ?? 'TequilaPOS' }}</h2><p class="mt-3 text-sm text-stone-400">{{ $settings['banner_desc'] ?? 'Fresh food, simple online ordering, and warm hospitality.' }}</p></div>
        <div><h2 class="font-semibold">Contact</h2><p class="mt-3 text-sm text-stone-400">{{ $settings['phone_no'] ?? '' }}</p><p class="text-sm text-stone-400">{{ $settings['email'] ?? '' }}</p><p class="text-sm text-stone-400">{{ $settings['locations'] ?? '' }}</p></div>
        <div><h2 class="font-semibold">Follow</h2><div class="mt-3 flex gap-3 text-sm text-amber-300">@foreach(['facebook','instagram','youtube','whatsapp'] as $social)@if(!empty($settings[$social]))<a href="{{ $settings[$social] }}" rel="noopener" target="_blank">{{ ucfirst($social) }}</a>@endif @endforeach</div></div>
    </div>
</footer>
