<?php

return [
    'public_key' => env('PAYSTACK_PUBLIC_KEY'),
    'secret_key' => env('PAYSTACK_SECRET_KEY'),
    'base_url' => env('PAYSTACK_BASE_URL', 'https://api.paystack.co'),
    'timeout' => env('PAYSTACK_TIMEOUT', 30),
    'callback_url' => env('PAYSTACK_CALLBACK_URL', 'http://localhost:8000/onboarding/paystack/callback'),
];
