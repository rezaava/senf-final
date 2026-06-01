<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Cart;
use App\Models\Organ;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $organ = Organ::find($user->organ_id);
        if ($user->hasRole('manager')) {
            $orders = $organ->reservations()->whereIn('status', ['paid', 'done'])->latest()->paginate(10);
        } elseif ($user->hasRole('operator')) {
            $orders = $user->reservations_operator()
                ->whereIn('status', ['paid', 'done'])
                ->where('organ_id', $user->organ_id)
                ->latest()->paginate(10);
        } else {
            $orders = Reservation::whereIn('status', ['paid', 'done'])->latest()->paginate(10);
        }
        return view('dashboard.orders.index', compact('orders'));
    }
    public function show(Reservation $reservation)
    {
        // return $reservation;
        $reservation->load(['costumer', 'services', 'operator']);
        $hasPriceRange = !is_null($reservation->services()->first()->service->price_max);
        return view('dashboard.orders.show', compact('reservation', 'hasPriceRange'));
    }
    public function status(Cart $cart, Request $request)
    {
        $cart->status = $request->status;
        $cart->save();
        return redirect()->back()->with('وضعیت سفارش با موفقیت ویرایش شد');
    }
    public function suggestPrice(Request $request, Appointment $appointment)
    {
        $request->validate([
            'suggested_price' => 'required|numeric|min:0',
        ]);

        $appointment->update([
            'suggested_price' => $request->suggested_price,
            'status' => 6,
        ]);

        return back()->with('success', 'قیمت پیشنهادی ارسال شد.');
    }
    public function finalizePrice(Request $request, Appointment $appointment)
    {
        $request->validate([
            'final_price' => 'required|numeric|min:0',
        ]);
        $service = $appointment->service;
        if ($request->final_price < $service->price or $request->final_price > $service->price_max) {
            return back()->with('fail', 'مبلغ وارد شده با بازه قیمت خدمت همخوانی ندارد.');
        }
        $appointment->update([
            'price' => $request->final_price,
            'status' => 3,
        ]);

        return back()->with('success', 'مبلغ نهایی ثبت شد.');
    }
}
