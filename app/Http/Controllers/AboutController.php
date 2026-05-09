<?php

namespace App\Http\Controllers;

use App\Services\TequilaPosClient;

class AboutController extends Controller
{
    public function index(TequilaPosClient $client)
    {
        $settings = $client->websiteSettings();
        $hours = json_decode($settings['opening_hours'] ?? '[]', true) ?: [];

        return view('pages.about', [
            'pageTitle' => 'About | ' . ($settings['business_name'] ?? 'TequilaPOS'),
            'pageDescription' => $settings['banner_desc'] ?? 'Learn more about our restaurant.',
            'hours' => $hours,
        ]);
    }
}
