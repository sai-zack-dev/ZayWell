<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Store;
use App\Models\Currency;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $storeIds = Store::pluck('id')->toArray();

        for ($i = 0; $i < 25; $i++) {
            $width = rand(300, 1080);
            $height = $width; // square
            $seed = Str::random(10);

            DB::table('products')->insert([
                'name' => ucwords($faker->words(2, true)),
                'description' => $faker->paragraph,
                'store_id' => $faker->randomElement($storeIds),
                'thumbnail' => "https://picsum.photos/seed/thumb_$seed/{$width}/{$height}",
                'images' => json_encode([
                    "https://picsum.photos/seed/img1_$seed/{$width}/{$height}",
                    "https://picsum.photos/seed/img2_$seed/{$width}/{$height}",
                ]),
                'price' => $faker->randomFloat(2, 5, 500),
                'stock' => $faker->numberBetween(0, 100),
                'currency_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
