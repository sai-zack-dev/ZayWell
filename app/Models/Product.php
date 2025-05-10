<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\CurrencyConverter;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'store_id',
        'thumbnail',
        'images',
        'price',
        'stock',
        'currency_id'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function getConvertedCurrencyPrice(): object
    {
        $store = auth()->user(); // assumes store panel
        $guest_currency = session('guest_currency');
        $from = $this->currency; // product's original currency
        if ($store?->currency_id === $guest_currency->id) {
            return (object) ['symbol' => $from->symbol, 'price' => number_format($this->price, 2)];
        }
        $to = $store->currency ?? $guest_currency;

        if (!$from || !$to || $from->exchange_rate == 0) {
            return (object) ['symbol' => $from->symbol, 'price' => number_format($this->price, 2)];

        }

        $converted = ($this->price / $from->exchange_rate) * $to->exchange_rate;
        return (object) ['symbol' => $to->symbol, 'price' => number_format($converted, 2)];
    }

}

