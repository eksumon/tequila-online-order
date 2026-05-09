<?php

namespace App\Http\Controllers;

use App\Services\TequilaPosClient;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request, TequilaPosClient $client)
    {
        $categories = collect($client->categories());
        $items = collect($client->items());
        $activeCategory = $request->integer('category') ?: ($categories->first()['id'] ?? null);

        if ($activeCategory) {
            $items = $items->where('category_id', $activeCategory);
        }

        return view('pages.menu', [
            'pageTitle' => 'Menu | ' . ($client->websiteSettings()['business_name'] ?? 'TequilaPOS'),
            'pageDescription' => 'Browse our menu and place an online order.',
            'categories' => $categories,
            'items' => $items->values(),
            'activeCategory' => $activeCategory,
        ]);
    }
}
