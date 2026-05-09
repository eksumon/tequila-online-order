<?php

namespace App\Services;

class CustomerAuthService
{
    public function login(array $customer, string $token): void
    {
        session(['customer' => $customer, 'customer_token' => $token]);
    }

    public function logout(): void
    {
        session()->forget(['customer', 'customer_token']);
    }

    public function customer(): ?array
    {
        return session('customer');
    }

    public function token(): ?string
    {
        return session('customer_token');
    }

    public function check(): bool
    {
        return filled($this->token());
    }
}
