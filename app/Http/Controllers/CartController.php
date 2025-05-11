<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

            return redirect()->back()->with('success', 'Product added to your cart!');
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
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
