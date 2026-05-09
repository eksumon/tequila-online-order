<?php

namespace App\Http\Controllers;

use App\Services\TequilaPosClient;

class HomeController extends Controller
{
    public function index(TequilaPosClient $client)
    {
        $settings = $client->websiteSettings();
        $items = collect($client->items());
        $menuSettings = $client->menuSettings();
        $specialIds = collect($menuSettings['todays_special'] ?? [])->pluck('id')->merge($menuSettings['todays_special'] ?? [])->map(fn ($id) => is_array($id) ? ($id['id'] ?? null) : $id)->filter();
        $featuredIds = collect($menuSettings['featured_menus'] ?? [])->pluck('id')->merge($menuSettings['featured_menus'] ?? [])->map(fn ($id) => is_array($id) ? ($id['id'] ?? null) : $id)->filter();

        return view('pages.home', [
            'pageTitle' => ($settings['business_name'] ?? 'TequilaPOS') . ' | Online Ordering',
            'pageDescription' => $settings['banner_subtitle'] ?? 'Order fresh restaurant favorites online.',
            'gallery' => $client->galleryImages(),
            'todaysSpecials' => $items->whereIn('id', $specialIds)->take(4)->values(),
            'featuredMenus' => $items->whereIn('id', $featuredIds)->take(6)->values(),
            'fallbackItems' => $items->take(6)->values(),
        ]);
    }
}
