<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\Currency;
use Illuminate\Support\Facades\Hash;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $usd = Currency::where('code', 'USD')->first();
        $sgd = Currency::where('code', 'SGD')->first();

        Store::updateOrCreate(
            ['email' => 'store@example.com'],
            [
                'name' => 'Test Store',
                'email' => 'store@example.com',
                'password' => Hash::make('password'),
                'avatar' => null,
                'currency_id' => $usd?->id,
            ]
        );

        Store::updateOrCreate(
            ['email' => 'store2@example.com'],
            [
                'name' => 'SGD Store',
                'email' => 'store2@example.com',
                'password' => Hash::make('password'),
                'avatar' => null,
                'currency_id' => $sgd?->id,
            ]
        );
    }
}
