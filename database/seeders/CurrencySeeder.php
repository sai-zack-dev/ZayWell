<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar',      'symbol' => '$',  'exchange_rate' => 1,     'is_default' => true],
            ['code' => 'MMK', 'name' => 'Myanmar Kyat',    'symbol' => 'Ks', 'exchange_rate' => 2100,  'is_default' => false],
            ['code' => 'THB', 'name' => 'Thai Baht',       'symbol' => '฿',  'exchange_rate' => 35.5,  'is_default' => false],
            ['code' => 'SGD', 'name' => 'Singapore Dollar','symbol' => 'S$', 'exchange_rate' => 1.34,  'is_default' => false],
            ['code' => 'VND', 'name' => 'Vietnamese Dong', 'symbol' => '₫',  'exchange_rate' => 24500, 'is_default' => false],
        ];

        foreach ($currencies as $data) {
            Currency::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}

