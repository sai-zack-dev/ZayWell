<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\CurrencyConverter;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'store_id', 'thumbnail', 'images', 'price', 'stock', 'currency_id'
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

    public function getConvertedPriceAttribute(): string
    {
        $store = auth()->user(); // assumes store panel

        $from = $this->currency; // product's original currency
        $to = $store->currency ?? $from; // fallback to same if null

        if (!$from || !$to || $from->exchange_rate == 0) {
            return number_format($this->price, 2);
        }

        $converted = ($this->price / $from->exchange_rate) * $to->exchange_rate;
        return $to->symbol . ' ' . number_format($converted, 2);
    }

}

