<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class ProductManagementController extends Controller
{
    public function index()
    {
        $query = Product::select('id', 'name', 'thumbnail', 'price', 'stock', 'currency_id')
            ->orderBy('stock', 'asc')
            ->latest()
            ->get();
        $products = $query->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'thumbnail' => $product->thumbnail,
                'currency' => $product->getConvertedCurrencyPrice()->symbol,
                'price' => $product->getConvertedCurrencyPrice()->price,
                'stock' => $product->stock,
            ];
        });
        $sorted_products = $products->sortByDesc('price')->values()->all();
        return Inertia::render('Welcome', [
            'products' => $sorted_products,
        ]);
    }

    public function show(Product $product)
    {
        $product->load('store', 'currency'); // if you have these relationships

        return Inertia::render('Product', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'thumbnail' => $product->thumbnail,
                'images' => $product->images,
                'currency' => $product->getConvertedCurrencyPrice()->symbol,
                'price' => $product->getConvertedCurrencyPrice()->price,
                'stock' => $product->stock,
            ]
        ]);
    }
}
