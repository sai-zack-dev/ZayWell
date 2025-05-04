<?php

namespace App\Helpers;

use App\Models\Currency;

class CurrencyConverter
{
    public static function convert(float $amount, string $fromCode, string $toCode): float
    {
        if ($fromCode === $toCode) {
            return $amount;
        }

        $from = Currency::where('code', $fromCode)->first();
        $to   = Currency::where('code', $toCode)->first();

        if (!$from || !$to || $from->exchange_rate == 0) {
            return $amount; // fallback if invalid
        }

        return ($amount / $from->exchange_rate) * $to->exchange_rate;
    }
}
