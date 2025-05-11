<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use Inertia\Inertia;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check() && Session::has('cart')) {
            $sessionCart = Session::get('cart');

            foreach ($sessionCart as $productId => $quantity) {
                if (Product::find($productId)) {
                    Cart::updateOrCreate(
                        ['user_id' => Auth::id(), 'product_id' => $productId],
                        ['quantity' => $quantity]
                    );
                }
            }

            Session::forget('cart');
        }

        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get()->map(function ($item) {
            $converted = $item->product->getConvertedCurrencyPrice();
            return [
                'cart_id' => $item->id,
                'product' => $item->product,
                'currency' => $converted->symbol,
                'price' => $converted->price,
                'quantity' => $item->quantity,
            ];
        });

        return Inertia::render('Cart', [
            'cartItems' => $cartItems,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if (Auth::check()) {
            // Logged-in user: Save to DB
            Cart::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'product_id' => $validated['product_id'],
                ],
                [
                    'quantity' => $validated['quantity'],
                ]
            );

            // return redirect()->back()->with('success', 'Product added to your cart!');
            return redirect('cart')->with('success', 'Product added to your cart!');
        } else {
            // Guest: Save in session
            $cart = Session::get('cart', []);
            $cart[$validated['product_id']] = $validated['quantity'];
            Session::put('cart', $cart);

            // Redirect to login with flash message
            return redirect()->route('login')->with('message', 'Product saved in session. Please login to checkout.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id],
            ['quantity' => $request->quantity]
        );

        return back()->with('message', 'Cart updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $cart->delete();

        return back()->with('message', 'Item removed.');
    }

}
