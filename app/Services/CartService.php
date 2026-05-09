<?php

namespace App\Services;

class CartService
{
    public function all(): array
    {
        return session('cart.items', []);
    }

    public function add(array $item, array $modifiers = [], int $quantity = 1): void
    {
        $key = $this->key((int) $item['id'], collect($modifiers)->pluck('id')->filter()->all());
        $items = $this->all();
        $modifierCost = collect($modifiers)->sum(fn ($modifier) => (float) ($modifier['price'] ?? 0));

        if (isset($items[$key])) {
            $items[$key]['quantity'] += $quantity;
        } else {
            $items[$key] = [
                'key' => $key,
                'item_id' => $item['id'],
                'name' => $item['name'],
                'price' => (float) ($item['selected_price'] ?? $item['price'] ?? $item['min_price'] ?? 0),
                'image' => $item['image'] ?? null,
                'quantity' => $quantity,
                'modifiers' => $modifiers,
                'modifier_cost' => $modifierCost,
            ];
        }

        session(['cart.items' => $items]);
    }

    public function update(string $key, int $quantity): void
    {
        $items = $this->all();

        if ($quantity <= 0) {
            unset($items[$key]);
        } elseif (isset($items[$key])) {
            $items[$key]['quantity'] = $quantity;
        }

        session(['cart.items' => $items]);
    }

    public function remove(string $key): void
    {
        $items = $this->all();
        unset($items[$key]);
        session(['cart.items' => $items]);
    }

    public function clear(): void
    {
        session()->forget('cart.items');
    }

    public function count(): int
    {
        return collect($this->all())->sum('quantity');
    }

    public function subtotal(): float
    {
        return collect($this->all())->sum(fn ($line) => ((float) $line['price'] + (float) $line['modifier_cost']) * (int) $line['quantity']);
    }

    protected function key(int $itemId, array $modifierIds): string
    {
        sort($modifierIds);
        return $itemId . ':' . implode(',', $modifierIds);
    }
}
