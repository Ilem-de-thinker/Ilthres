<?php

namespace App\Support\Money;

use NumberFormatter;

class Money
{
    private static array $currencies = [
        'NGN' => ['symbol' => '₦', 'decimal_places' => 0],
        'USD' => ['symbol' => '$', 'decimal_places' => 2],
        'GBP' => ['symbol' => '£', 'decimal_places' => 2],
        'EUR' => ['symbol' => '€', 'decimal_places' => 2],
        'GHS' => ['symbol' => 'GH₵', 'decimal_places' => 2],
        'ZAR' => ['symbol' => 'R', 'decimal_places' => 2],
        'KES' => ['symbol' => 'KSh', 'decimal_places' => 2],
    ];

    public static function format($amount, ?string $currencyCode = 'NGN'): string
    {
        if ($amount === null || $amount === '') {
            return '';
        }

        $currencyCode = strtoupper($currencyCode ?? 'NGN');

        $currency = self::$currencies[$currencyCode] ?? [
            'symbol' => $currencyCode,
            'decimal_places' => 2,
        ];

        $formatter = new NumberFormatter(
            'en_US',
            NumberFormatter::CURRENCY,
        );

        $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $currency['decimal_places']);
        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $currency['decimal_places']);

        return $formatter->formatCurrency((float) $amount, $currencyCode);
    }
}
