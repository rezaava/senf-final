<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Organ;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

use function PHPUnit\Framework\isNull;

class AppointmentController extends Controller
{
    public function index(Service $service)
    {
        $appointments = $service->appointments()->with('user')->orderBy('day')->orderBy('start_time')->get();
        $now = Carbon::now(); // زمان فعلی
        foreach ($appointments as $key => $item) {
            $appoint = Appointment::find($item->id);
            if ($appoint->day < $now->toDateString()) {
                // dd($appoint);
                if ($appoint->customer) {
                    $appoint->status = 3;
                } else {
                    $appoint->status = 4;
                }
            } elseif ($appoint->day == $now->toDateString() and $appoint->end_time <= $now->format('H:i')) {
                if ($appoint->customer) {
                    $appoint->status = 3;
                } else {
                    $appoint->status = 4;
                }
            }
            $appoint->save();
        }
        return view('dashboard.service.appointments', compact('appointments', 'service'));
    }
    public function store(StoreAppointmentRequest $request)
    {
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $time = str_replace($persianNumbers, $englishNumbers, $request->day);
        // $jalaliDate = Jalalian::fromFormat('Y-m-d', $time);
        // $gregorianDate = $jalaliDate->toCarbon()->format('Y-m-d');
        Appointment::create([
            'organ_id' => auth()->user()->organ_id,
            'user_id' => $request->user_id,
            'service_id' => $request->service_id,
            'day' => $time,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'free_customer' => $request->customer_name,
        ]);
        return redirect()->back()->with('success', 'نوبت با موفقیت ثبت شد.');
    }
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        if ($appointment) {
            $appointment->delete();
        }
        return redirect()->back()->with('success', 'نوبت با موفقیت حذف شد.');
    }
    public function appointments(Request $request)
    {
        $organ = Organ::find(auth()->user()->organ_id);
        if ($organ->users()->count() < 1) {
            return redirect()->back()->with('fail','سالن شما هنوز اپراتوری ندارد.');
        }
        $users = $organ->users()->whereHasRole('operator')->get(); // فرض بر اینه role برای اپراتور داری
        $operators = $organ->users()->whereHasRole('operator')->pluck('users.id'); // فرض بر اینه role برای اپراتور داری

        $query_for_change = Appointment::with(['user', 'service', 'reservedBy'])->whereIn('user_id', $operators);
        if ($request->has('user_id') && $request->user_id != 'all') {
            $query_for_change->where('user_id', $request->user_id);
        }
        $query = Appointment::with(['user', 'service', 'reservedBy'])->whereIn('user_id', $operators);
        if ($request->has('user_id') && $request->user_id != 'all') {
            $query->where('user_id', $request->user_id);
        }
        $appointments_to_change = $query_for_change->whereIn('status', [0, 1])->get();
        $now = Carbon::now(); // زمان فعلی
        foreach ($appointments_to_change as $key => $item) {
            $appoint = Appointment::find($item->id);
            if ($appoint->day < $now->toDateString()) {
                // dd($appoint);
                if (!$appoint->customer and !$appoint->free_customer) {
                    $appoint->status = 4;
                } else {
                    $appoint->status = 3;
                }
            } elseif ($appoint->day == $now->toDateString() and $appoint->end_time <= $now->format('H:i')) {
                if (!$appoint->customer and !$appoint->free_customer) {
                    $appoint->status = 4;
                } else {
                    $appoint->status = 3;
                }
                // return $appoint;
            }
            $appoint->save();
        }

        $appointments = $query->get();
        $events = $appointments->map(function ($item) {
            $start = Carbon::parse($item->day . ' ' . $item->start_time)->toDateTimeString();
            $end = Carbon::parse($item->day . ' ' . $item->end_time)->toDateTimeString();

            // انتخاب رنگ بر اساس وضعیت
            $color = match ($item->status) {
                0 => '#FCF259', // waiting (زرد)
                1 => '#0065F8', // reserve (آبی)
                2 => '#FF3F33', // cancel (قرمز)
                4 => '#FF3F33', // cancel (قرمز)
                3 => '#00FF9C', // done (سبز)
                default => '#6c757d', // default (خاکستری)
            };
            $title = match ($item->status) {

                0 => 'نوبت بدون مشتری', // waiting (زرد)
                1 => $item->CustomerName . ': روزو شده توسط', // reserve (آبی)
                2 => $item->CustomerName . ': کنسل شده توسط', // cancel (قرمز)
                4 => 'نوبت منقضی شده', // cancel (قرمز)
                3 => 'نوبت انجام شده', // done (سبز)
                default => 'نامعلوم', // default (خاکستری)
            };

            return [
                'title' => $title,
                'operator' => $item->user->name,
                'customer' => $item->CustomerName ?? '',
                'start' => $start,
                'end' => $end,
                'color' => $color,
                'status' => $item->status,
                'id_code' => $item->id,
            ];
        });
        return view('dashboard.appointments.create', compact('events', 'users', 'appointments'));
    }
    public function create(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $services = $user->services ?? []; // اگر اپراتور خدمات مرتبط داره

        return view('admin.appointments.create', [
            'user' => $user,
            'date' => $request->date,
            'start' => $request->start,
            'end' => $request->end,
            'services' => $services,
        ]);
    }
    public function update_status(Request $request)
    {
        $appointment = Appointment::findOrFail($request->id);
        $appointment->status = $request->status;
        $appointment->free_customer = $request->customer_name;
        $appointment->save();
        // return $appointment;
        return back()->with('success', 'وضعیت نوبت با موفقیت تغییر کرد.');
    }
    public function show()
    {
        $organ = Organ::find(auth()->user()->organ_id);
        $users = $organ->users()->whereHasRole('operator')->get(); // فرض بر اینه role برای اپراتور داری
        $operators = $organ->users()->whereHasRole('operator')->pluck('users.id'); // فرض بر اینه role برای اپراتور داری

        $query_for_change = Appointment::with(['user', 'service', 'reservedBy'])->whereIn('user_id', $operators);

        $query_for_change->where('user_id', auth()->id());

        $query = Appointment::with(['user', 'service', 'reservedBy'])->whereIn('user_id', $operators);
        $query->where('user_id', auth()->id());
        $appointments_to_change = $query_for_change->whereIn('status', [0, 1])->get();
        $now = Carbon::now(); // زمان فعلی
        foreach ($appointments_to_change as $key => $item) {
            $appoint = Appointment::find($item->id);
            if ($appoint->day < $now->toDateString()) {
                // dd($appoint);
                if (!$appoint->customer and !$appoint->free_customer) {
                    $appoint->status = 4;
                } else {
                    $appoint->status = 3;
                }
            } elseif ($appoint->day == $now->toDateString() and $appoint->end_time <= $now->format('H:i')) {
                if (!$appoint->customer and !$appoint->free_customer) {
                    $appoint->status = 4;
                } else {
                    $appoint->status = 3;
                }
                // return $appoint;
            }
            $appoint->save();
        }

        $appointments = $query->get();
        $events = $appointments->map(function ($item) {
            $start = Carbon::parse($item->day . ' ' . $item->start_time)->toDateTimeString();
            $end = Carbon::parse($item->day . ' ' . $item->end_time)->toDateTimeString();

            // انتخاب رنگ بر اساس وضعیت
            $color = match ($item->status) {
                0 => '#FCF259', // waiting (زرد)
                1 => '#0065F8', // reserve (آبی)
                2 => '#FF3F33', // cancel (قرمز)
                4 => '#FF3F33', // cancel (قرمز)
                3 => '#00FF9C', // done (سبز)
                default => '#6c757d', // default (خاکستری)
            };
            $title = match ($item->status) {

                0 => 'نوبت بدون مشتری', // waiting (زرد)
                1 => $item->CustomerName . ': روزو شده توسط', // reserve (آبی)
                2 => $item->CustomerName . ': کنسل شده توسط', // cancel (قرمز)
                4 => 'نوبت منقضی شده', // cancel (قرمز)
                3 => 'نوبت انجام شده', // done (سبز)
                default => 'نامعلوم', // default (خاکستری)
            };

            return [
                'title' => $title,
                'operator' => $item->user->name,
                'customer' => $item->CustomerName ?? '',
                'start' => $start,
                'end' => $end,
                'color' => $color,
                'status' => $item->status,
                'id_code' => $item->id,
            ];
        });
        return view('dashboard.appointments.show', compact('events',));
    }
}
