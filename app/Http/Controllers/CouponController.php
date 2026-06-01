<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);
        if ($user->hasRole('manager')) {
            $coupons = Coupon::where('organ_id', auth()->user()->organ_id)->latest()->paginate(10);
        } else {
            $coupons = Coupon::whereNull('organ_id')->latest()->paginate(10);
        }
        return view('dashboard.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('dashboard.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|integer|min:1',
            'usage_limit' => 'required|integer|min:1',
        ]);

        $validated['organ_id'] = auth()->user()->organ_id ?? null;

        Coupon::create($validated);

        return redirect()->route('coupons.index')->with('success', 'کد تخفیف ایجاد شد.');
    }

    public function edit(Coupon $coupon)
    {
        return view('dashboard.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|integer|min:1',
            'usage_limit' => 'required|integer|min:1',
        ]);

        $coupon->update($validated);

        return redirect()->route('coupons.index')->with('success', 'کد تخفیف ویرایش شد.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'کد تخفیف حذف شد.');
    }
}
