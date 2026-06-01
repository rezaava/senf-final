<?php

namespace App\Http\Controllers;

use App\Models\OperatorWorkingHour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkHourController extends Controller
{
    public function index(User $user = null)
    {
        if ($user == null) {
            $user = Auth::user();
        }
        $workhpurs = OperatorWorkingHour::where('user_id', $user->id)->get();
        // return $workhpurs    ;
        return view('dashboard.workHour.index', compact('workhpurs', 'user'));
    }
    public function store(Request $request)
    {
        $userId = $request->user ?? auth()->id();

        foreach ($request->working_hours as $day => $data) {

            // اگر فعال نیست → حذف کن
            if (!isset($data['active'])) {
                OperatorWorkingHour::where('user_id', $userId)
                    ->where('day_of_week', $day)
                    ->delete();
                continue;
            }

            // اگر فعال هست → ذخیره یا آپدیت کن
            OperatorWorkingHour::updateOrCreate(
                [
                    'user_id' => $userId,
                    'day_of_week' => $day,
                ],
                [
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                ]
            );
        }

        return back()->with('success', 'ساعات کاری ذخیره شد');
    }
}
