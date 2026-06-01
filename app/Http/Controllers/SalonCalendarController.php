<?php

namespace App\Http\Controllers;

use App\Models\OperatorTimeOff;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalonCalendarController extends Controller
{
    public function data(Request $request)
    {
        $date = $request->date; // YYYY-MM-DD

        $start = Carbon::parse($date)->startOfDay();
        $end   = Carbon::parse($date)->endOfDay();

        // آرایشگرهای سالن
        $stylists = User::whereHasRole('operator')->get(['id', 'name']);

        // نوبت‌های همان روز
        $reservations = Reservation::with('costumer', 'services.service')->whereBetween('start_at', [$start, $end])
            ->whereIn('status', ['paid', 'pending'])
            ->get();

        $timeOffs = OperatorTimeOff::whereBetween('start_at', [$start, $end])
            ->get();

        return response()->json([
            'stylists'     => $stylists,
            'events'       => $reservations,
            'time_offs'    => $timeOffs
        ]);
    }
    public function store(Request $request)
    {
        // $request->validate([
        //     'title'      => 'required|string|max:255',
        //     'stylist_id' => 'required|exists:users,id',
        //     'start_at'   => 'required|date',
        //     'end_at'     => 'required|date|after:start_at',
        // ]);

        // $reservation = Reservation::create([
        //     'customer_name' => $request->title,
        //     'operator_id'   => $request->stylist_id,
        //     'start_at'      => $request->start_at,
        //     'end_at'        => $request->end_at,
        //     'status'        => 'pending'
        // ]);

        // return response()->json([
        //     'success' => true,
        //     'event'   => $reservation
        // ]);
    }
}
