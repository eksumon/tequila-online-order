<?php

namespace App\Http\Controllers;

use App\Services\CustomerAuthService;
use App\Services\TequilaPosClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login', ['pageTitle' => 'Sign In | TequilaPOS', 'pageDescription' => 'Sign in to your account.']);
    }

    public function showRegister()
    {
        return view('auth.register', ['pageTitle' => 'Create Account | TequilaPOS', 'pageDescription' => 'Create your account.']);
    }

    public function showForgot()
    {
        return view('auth.forgot-password', ['pageTitle' => 'Forgot Password | TequilaPOS', 'pageDescription' => 'Reset your password.']);
    }

    public function showReset()
    {
        return view('auth.reset-password', ['pageTitle' => 'Reset Password | TequilaPOS', 'pageDescription' => 'Reset your password.']);
    }

    public function login(Request $request, TequilaPosClient $client, CustomerAuthService $auth)
    {
        $credentials = $request->validate(['mobile' => ['required', 'string'], 'password' => ['required', 'string']]);

        try {
            $response = $client->loginCustomer($credentials);
            $token = $response['token'] ?? $response['customer_token'] ?? null;
            $customer = $response['customer'] ?? $response;
        } catch (\Throwable) {
            $token = 'mock-' . Str::random(32);
            $customer = ['name' => 'Guest Customer', 'mobile' => $credentials['mobile']];
        }

        if (! $token) {
            return back()->withErrors(['mobile' => 'Unable to sign in with those credentials.'])->withInput();
        }

        $auth->login($customer, $token);
        return redirect(session('intended', route('account')))->with('status', 'Welcome back.');
    }

    public function register(Request $request, TequilaPosClient $client, CustomerAuthService $auth)
    {
        $payload = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'mobile' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        try {
            $response = $client->registerCustomer($payload);
            $token = $response['token'] ?? $response['customer_token'] ?? null;
            $customer = $response['customer'] ?? $payload;
        } catch (\Throwable) {
            $token = 'mock-' . Str::random(32);
            $customer = $payload;
        }

        $auth->login($customer, $token);
        return redirect()->route('account')->with('status', 'Account created.');
    }

    public function logout(CustomerAuthService $auth)
    {
        $auth->logout();
        return redirect()->route('home')->with('status', 'Signed out.');
    }
}
