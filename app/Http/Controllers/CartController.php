<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Reservation;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = Cart::where('status', 'active')->where('user_id', auth()->id())->first();
        return view('web.cart', compact('cart'));
    }

    public function remove(Reservation $reservation)
    {
        // امنیت: فقط رزرو مربوط به کاربر
        if ($reservation->user_id != auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reservation->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
