<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia; 
use Illuminate\Support\Facades\Session;
 

class ProductManagementController extends Controller
{
    public function index()
    {
        $query = Product::select('id', 'name', 'thumbnail', 'price', 'stock', 'currency_id')
            ->latest()
            ->get();
        $products = $query->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'thumbnail' => $product->thumbnail,
                'price' => $product->getConvertedPriceAttribute(),
                'stock' => $product->stock,
                'currency_id' => Session::get('guest_currency')->symbol,
            ];
        });
        return Inertia::render('Welcome', [
            'products' => $products,
        ]);
    }
}
