<?php

namespace App\Http\Controllers;

use App\Models\Organ;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialOfferController extends Controller
{
    public function edit()
    {
        $user = User::find(Auth::user()->id);
        if ($user->hasRole('manager')) {
            $services = Service::where('organ_id', auth()->user()->organ_id)->latest()->get();
        } else {
            $services = Service::latest()->get();
        }
        return view('dashboard.special-offers.edit', compact('services'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'services' => 'array',
            'services.*' => 'numeric|min:0|max:100',
        ], [
            'services.*.numeric' => 'مقدار تخفیف باید عدد باشد.',
            'services.*.min' => 'مقدار تخفیف نمی‌تواند کمتر از ۰ باشد.',
            'services.*.max' => 'حداکثر مقدار تخفیف ۱۰۰٪ است.',
        ]);

        // تنظیم تخفیف فقط برای سرویس‌هایی که در فرم انتخاب شده‌اند
        foreach (Service::all() as $service) {
            $discount = $request->input("services.{$service->id}");
            $service->off = $discount ?? 0; // اگر انتخاب نشده بود، صفر کن
            $service->save();
        }

        return redirect()->route('special-offers.edit')->with('success', 'تخفیف‌ها با موفقیت به‌روزرسانی شدند.');
    }
}
