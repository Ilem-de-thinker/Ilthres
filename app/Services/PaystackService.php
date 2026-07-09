<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PaystackService
{
    protected string $secretKey;

    protected string $baseUrl = 'https://api.paystack.co';

    public function __construct()
    {
        $this->secretKey = config('paystack.secret_key');
    }

    protected function request(string $method, string $path, array $data = []): array
    {
        $response = Http::withToken($this->secretKey)
            ->$method("{$this->baseUrl}/{$path}", $data);

        if (! $response->successful()) {
            throw new RuntimeException('Paystack API error: '.$response->body());
        }

        $body = $response->json();

        if (! ($body['status'] ?? false)) {
            throw new RuntimeException('Paystack request failed: '.($body['message'] ?? 'Unknown error'));
        }

        return $body['data'];
    }

    public function initializeTransaction(string $email, int $amount, string $callbackUrl, array $metadata = []): array
    {
        return $this->request('post', 'transaction/initialize', [
            'email' => $email,
            'amount' => $amount,
            'callback_url' => $callbackUrl,
            'metadata' => $metadata,
        ]);
    }

    public function verifyTransaction(string $reference): array
    {
        return $this->request('get', "transaction/verify/{$reference}");
    }
}
