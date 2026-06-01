<?php

namespace App\Http\Controllers;

use App\Models\OperatorTimeOff;
use App\Models\OperatorWorkingHour;
use App\Models\Organ;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('dashboard.calender.operator');
    }
    public function data($operatorId, Request $request)
    {
        $start = Carbon::create($request->start)->subDays(2); // 2025-02-01
        $end   = Carbon::create($request->end)->addDays(2);   // 2025-02-07

        $workHours = OperatorWorkingHour::where('user_id', $operatorId)->get();

        $reservations = Reservation::with('costumer', 'services.service')->where('operator_id', $operatorId)
            ->whereBetween('start_at', [$start, $end])
            ->whereIn('status', ['paid', 'pending'])
            ->get();

        $timeOffs = OperatorTimeOff::where('user_id', $operatorId)
            ->whereBetween('start_at', [$start, $end])
            ->get();

        return response()->json([
            'work_hours' => $workHours,
            'reservations' => $reservations,
            'time_offs' => $timeOffs,
        ]);
    }
    public function time_off(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'reason' => 'nullable|string|max:255',
        ]);

        $timeOff = OperatorTimeOff::create([
            'user_id' => $request->user_id,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'reason' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'data' => $timeOff
        ]);
    }

    public function organ_index(Organ $organ)
    {
        return view('dashboard.calender.organ');
    }
}
