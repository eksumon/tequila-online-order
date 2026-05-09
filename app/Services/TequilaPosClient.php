<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TequilaPosClient
{
    protected ?int $restaurantId = null;

    public function restaurantId(?string $host = null): int
    {
        if ($this->restaurantId) {
            return $this->restaurantId;
        }

        $subdomain = $this->subdomain($host ?? request()->getHost());
        $fallback = (int) config('services.tequilapos.default_restaurant_id', 43);

        if (! $subdomain) {
            return $this->restaurantId = $fallback;
        }

        try {
            $value = Cache::remember("tequilapos.restaurant_id.{$subdomain}", 300, function () use ($subdomain) {
                return $this->unwrap($this->baseRequest($this->fallbackRestaurantId())->get("/website-restaurant-id/{$subdomain}")->json());
            });
        } catch (\Throwable) {
            $value = null;
        }

        $id = $this->numericId($value);

        return $this->restaurantId = $id > 0 ? $id : $fallback;
    }

    public function imageUrl(?string $path): string
    {
        if (! $path) {
            return '/placeholder.svg';
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return rtrim(config('services.tequilapos.image_base'), '/') . '/' . ltrim($path, '/');
    }

    public function websiteSettings(): array
    {
        $id = $this->restaurantId();
        return $this->cached("settings.{$id}", fn () => $this->get("/website-settings/{$id}"));
    }

    public function galleryImages(): array
    {
        $id = $this->restaurantId();
        $images = $this->cached("gallery.{$id}", fn () => $this->get("/website-gallery-images/{$id}"));

        return collect($images)->filter(fn ($image) => (string) ($image['status'] ?? '') === '1' && empty($image['deleted_at']))
            ->sortBy('position')->values()->all();
    }

    public function menuSettings(): array
    {
        $id = $this->restaurantId();
        return $this->cached("menu_settings.{$id}", fn () => $this->get("/website-menu-settings/{$id}"));
    }

    public function items(): array
    {
        return $this->cached('items.' . $this->restaurantId(), fn () => collect($this->get('/online/items'))->map(fn ($item) => $this->mapItem($item))->values()->all());
    }

    public function categories(): array
    {
        $categories = $this->cached('categories.' . $this->restaurantId(), fn () => $this->get('/online/categories'));

        if (! empty($categories)) {
            return $categories;
        }

        return collect($this->items())->pluck('category_id')->filter()->unique()->values()
            ->map(fn ($id) => ['id' => $id, 'name' => $this->categoryName((int) $id)])->all();
    }

    public function itemDetails(int $id): array
    {
        return $this->cached("item.{$this->restaurantId()}.{$id}", fn () => $this->get("/online/items/{$id}/details"));
    }

    public function itemModifiers(int $id): array
    {
        $data = $this->cached("modifiers.{$this->restaurantId()}.{$id}", fn () => $this->get("/online/items/{$id}/modifiers"));
        return $this->flattenModifiers($id, $data);
    }

    public function loginCustomer(array $credentials): array
    {
        return $this->post('/online/loginCustomer', $credentials);
    }

    public function registerCustomer(array $payload): array
    {
        return $this->post('/online/registerCustomer', $payload);
    }

    public function customerProfile(string $token): array
    {
        return $this->request($this->restaurantId(), $token)->get('/online/customer/profile')->json('data') ?? [];
    }

    public function updateCustomerProfile(string $token, array $payload): array
    {
        return $this->unwrap($this->request($this->restaurantId(), $token)->asForm()->post('/online/customer/profile', $payload)->json());
    }

    public function createOrder(string $token, array $payload): array
    {
        return $this->post('/online/create-order', $payload, $token);
    }

    public function orders(string $token): array
    {
        return $this->unwrap($this->request($this->restaurantId(), $token)->get('/online/orders')->json()) ?: [];
    }

    protected function get(string $path): array
    {
        try {
            return $this->unwrap($this->request($this->restaurantId())->get($path)->json()) ?: [];
        } catch (\Throwable) {
            return [];
        }
    }

    protected function post(string $path, array $payload, ?string $token = null): array
    {
        return $this->unwrap($this->request($this->restaurantId(), $token)->post($path, $payload)->json()) ?: [];
    }

    protected function cached(string $key, callable $callback): array
    {
        return Cache::remember("tequilapos.{$key}", 300, $callback);
    }

    protected function request(int $restaurantId, ?string $customerToken = null): PendingRequest
    {
        $request = $this->baseRequest($restaurantId);

        return $customerToken ? $request->withHeaders(['X-CUST-AUTH-KEY' => $customerToken]) : $request;
    }

    protected function baseRequest(int $restaurantId): PendingRequest
    {
        return Http::baseUrl(config('services.tequilapos.base_url'))
            ->acceptJson()
            ->timeout(12)
            ->withHeaders([
                'X-AUTH-KEY' => config('services.tequilapos.auth_key'),
                'X-RESTAURANT-ID' => (string) $restaurantId,
            ]);
    }

    protected function unwrap(mixed $payload): mixed
    {
        return is_array($payload) && array_key_exists('data', $payload) ? $payload['data'] : $payload;
    }

    protected function subdomain(string $host): ?string
    {
        $host = strtolower(explode(':', $host)[0]);

        if (in_array($host, ['localhost', '127.0.0.1'], true) || str_ends_with($host, '.lovable.app') || str_ends_with($host, '.lovableproject.com')) {
            return null;
        }

        $parts = explode('.', $host);
        return count($parts) >= 3 ? $parts[0] : null;
    }

    protected function fallbackRestaurantId(): int
    {
        return (int) config('services.tequilapos.default_restaurant_id', 43);
    }

    protected function numericId(mixed $value): int
    {
        if (is_array($value)) {
            $value = $value['restaurant_id'] ?? $value['id'] ?? $value['data'] ?? null;
        }

        return is_numeric($value) ? (int) $value : 0;
    }

    protected function mapItem(array $item): array
    {
        $variants = collect($item['variants'] ?? [])->filter(fn ($variant) => ! isset($variant['status']) || (int) $variant['status'] === 1)
            ->map(fn ($variant) => [
                'id' => $variant['id'] ?? null,
                'item_id' => $item['id'] ?? null,
                'name' => $variant['name'] ?? 'Variant',
                'price' => (float) ($variant['price'] ?? 0),
                'is_variant' => true,
            ])->values()->all();
        $basePrice = (float) ($item['price'] ?? 0);
        $prices = collect($variants)->pluck('price');

        return [
            'id' => $item['id'] ?? null,
            'name' => $item['name'] ?? 'Menu item',
            'category_id' => $item['category_id'] ?? null,
            'menu_type_id' => 1,
            'price' => $basePrice,
            'sold_out' => (int) ($item['is_soldout'] ?? 0) === 1,
            'description' => $item['description'] ?? '',
            'image' => $item['image'] ?? null,
            'variants' => $variants,
            'has_variants' => count($variants) > 0,
            'min_price' => count($variants) ? (float) $prices->min() : $basePrice,
            'max_price' => count($variants) ? (float) $prices->max() : $basePrice,
        ];
    }

    protected function flattenModifiers(int $itemId, array $groups): array
    {
        return collect($groups)->flatMap(function ($group) use ($itemId) {
            $options = $group['modifiers'] ?? $group['items'] ?? $group['options'] ?? [];
            return collect($options)->map(fn ($modifier) => [
                'id' => $modifier['id'] ?? null,
                'item_id' => $itemId,
                'name' => $modifier['name'] ?? 'Add-on',
                'price' => (float) ($modifier['price'] ?? 0),
                'is_variant' => false,
                'group_id' => $group['id'] ?? null,
                'group_name' => $group['name'] ?? $group['group_name'] ?? 'Add-ons',
                'is_multi_select' => (bool) ($group['is_multi_select'] ?? $group['multi_select'] ?? false),
                'is_required' => (bool) ($group['is_required'] ?? false),
                'min_count' => (int) ($group['min_count'] ?? 0),
                'max_count' => (int) ($group['max_count'] ?? 0),
            ]);
        })->values()->all();
    }

    protected function categoryName(int $id): string
    {
        return [1 => 'Appetizers', 2 => 'Soups', 3 => 'Salads', 4 => 'Fajitas', 5 => 'Enchiladas', 6 => 'Tacos', 7 => 'Burritos', 8 => 'Desserts', 9 => 'Drinks'][$id] ?? "Category {$id}";
    }
}
