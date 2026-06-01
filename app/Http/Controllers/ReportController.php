<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Organ;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // نمایش لیست سالن‌ها و درآمدها
    public function index()
    {
        $organs = Organ::withSum('appointments as total_income', 'price')
            ->withSum('appointments as app_profit', 'app_share')
            ->get();

        return view('dashboard.reports.index', compact('organs'));
    }

    // نمایش گزارش دقیق یک سالن
    public function show(Request $request, Organ $organ)
    {
        $query = Appointment::with('user', 'operator', 'service')
            ->where('organ_id', $organ->id);

        // فیلتر بازه زمانی
        if ($request->filled('from_date')) {
            $query->where('day', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('day', '<=', $request->to_date);
        }

        // فیلتر اپراتور
        if ($request->filled('operator_id')) {
            $query->where('operator_id', $request->operator_id);
        }

        // فیلتر خدمت
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        $appointments = $query->orderBy('day', 'desc')->paginate(15);

        // برای فرم فیلتر نیاز داریم
        $operators = $organ->operator()->whereHasRole('operator')->get(); // آرایشگرهای فعال سالن
        $services = $organ->services;   // خدمات فعال سالن

        return view('dashboard.reports.show', compact('appointments', 'organ', 'operators', 'services'));
    }
}
