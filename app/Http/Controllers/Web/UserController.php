<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use App\Services\OperatorSalonService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected OperatorSalonService $salons) {}

    public function profile()
    {
        $cities = City::where('parent', null)->get();

        // سالن‌هایی که کاربر در آن‌ها عضو فعال است + سالن انتخاب‌شده‌ی فعلی
        $salons = $this->salons->presentFor(auth()->user());
        $currentSalon = collect($salons)->firstWhere('is_current', true);

        // دکمه‌ی «تغییر سالن» فقط وقتی معنا دارد که کاربر الان داخل یک سالن باشد
        // و بیش از یک سالن هم در دسترسش باشد.
        $canSwitchSalon = $currentSalon && count($salons) > 1;

        return view('web.profile', compact('cities', 'salons', 'currentSalon', 'canSwitchSalon'));
    }

    /**
     * تغییر سالن فعال از داخل صفحه‌ی پروفایل.
     *
     * برخلاف انتخاب سالن هنگام ورود، اینجا نقش انتخابی کاربر دست‌نخورده
     * می‌ماند و فقط سالن فعال (users.organ_id) عوض می‌شود.
     */
    public function switchSalon(Request $request)
    {
        $request->validate([
            'salon_id' => ['required', 'integer'],
        ]);

        $user = auth()->user();

        if (! $this->salons->canAccess($user, $request->salon_id)) {
            return response()->json([
                'errors' => ['salon_id' => ['شما به این سالن دسترسی ندارید']],
            ], 403);
        }

        $organ = $this->salons->select($user, $request->salon_id);

        return response()->json([
            'success' => true,
            'salon' => ['id' => $organ->id, 'name' => $organ->name],
            'message' => 'سالن فعال شما به «'.$organ->name.'» تغییر کرد',
        ]);
    }

    public function getCitiesByProvince($provinceId)
    {
        $cities = City::where('parent', $provinceId)->get();

        return response()->json(['data' => $cities]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|regex:/^09\d{9}$/',
            'birthDate' => 'nullable',
        ], [
            'name.required' => 'لطفا نام خود را وارد کنید.',
            'mobile.required' => 'شماره موبایل خود را وارد کنید.',
            'mobile.regex' => 'شماره موبایل باید 11 رقمی باید و با 09 شروع شود.',
        ]);
        $user = User::find(auth()->id());
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->birthDate = $request->birthDate;
        $user->city = $request->city;
        $user->city2 = $request->city2;
        $user->save();

        return redirect()->back()->with('success', 'پروفایل با موفقیت تکمیل شد.');
    }
}
