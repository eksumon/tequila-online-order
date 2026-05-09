<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()     { return view('pages.home',     $this->meta('Tequila POS', 'Where every dish tells a story.')); }
    public function menu()     { return view('pages.menu',     $this->meta('Menu | TequilaPOS', 'Browse our full menu and place an order.')); }
    public function about()    { return view('pages.about',    $this->meta('About Us | TequilaPOS', 'Our story, our chef, and how to find us.')); }
    public function login()    { return view('pages.login',    $this->meta('Sign In | TequilaPOS', 'Sign in to your TequilaPOS account.')); }
    public function register() { return view('pages.register', $this->meta('Create Account | TequilaPOS', 'Create your TequilaPOS account.')); }
    public function account()  { return view('pages.account',  $this->meta('My Account | TequilaPOS', 'View your profile and order history.')); }
    public function profile()  { return view('pages.profile',  $this->meta('My Profile | TequilaPOS', 'Manage your personal information.')); }
    public function spa()      { return view('pages.home',     $this->meta('Tequila POS', 'Where every dish tells a story.')); }

    protected function meta(string $title, string $description): array
    {
        return ['pageTitle' => $title, 'pageDescription' => $description];
    }
}
